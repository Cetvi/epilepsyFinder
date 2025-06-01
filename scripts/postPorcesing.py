import os
from helpers import *
import ants
import SimpleITK as sitk
import glob
import numpy as np
import matplotlib.pyplot as plt
import matplotlib.mlab as mlab
from antspynet.utilities import brain_extraction
import nibabel as nib
import cv2 as cv
import pandas as pd
import shutil
from intensity_normalization.typing import Modality,TissueType
from intensity_normalization.normalize.zscore import ZScoreNormalize
from intensity_normalization.normalize.whitestripe import WhiteStripeNormalize
from intensity_normalization.normalize.fcm import FCMNormalize
from intensity_normalization.normalize.kde import KDENormalize
from nilearn import plotting
import scipy.ndimage as ndi
from datetime import datetime
import re
from nibabel.orientations import aff2axcodes, axcodes2ornt, ornt_transform, apply_orientation, inv_ornt_aff
import gc
import gzip
from importMap import importMap

LABELS_MAP = importMap()

def parse_txt(filepath, threshold=0.8):
    with open(filepath, "r", encoding="utf-8") as file:
        lines = file.readlines()

    label_percentages = []
    for line in lines:
        line = line.strip()
        parts = line.split(":")
        label_info = parts[0].split(" ")
        label_id = label_info[1]
        num = float(parts[2].strip().replace("%", ""))
        porcentaje = num / 100.0
        label_percentages.append((label_id, porcentaje))

    
    label_percentages.sort(key=lambda x: x[1], reverse=True)
    acumulado = 0.0
    etiquetas_validas = {}

    for label_id, porcentaje in label_percentages:
        if acumulado >= threshold:
            break
        etiquetas_validas[label_id] = porcentaje
        acumulado += porcentaje

    print(f"Se conservarán {len(etiquetas_validas)} etiquetas que cubren el {acumulado * 100:.2f}% del volumen total.")
    return etiquetas_validas



def niiTransformation(folder, original_file, aseg_file, ):
    #file_array = [original_file, aseg_file, ]
    file_array = [original_file, aseg_file]
    output_dir = os.path.join(folder, "niiFiles")
    os.makedirs(output_dir, exist_ok=True)

    for file in file_array:
        print(file)
        data = nib.load(file, mmap=True)
        img_nifti = nib.Nifti1Image(data.dataobj, data.affine, header=nib.Nifti1Header())
        file_dir = os.path.join(output_dir, os.path.basename(file).replace(".mgz", ".nii"))
        nib.save(img_nifti, file_dir)
        print(f"Transformed {os.path.basename(file)} to Nifti format and saved in {output_dir}")

        del data, img_nifti
        gc.collect()



def register(folder, output_dir, original_file, flair_file, number):
    file_array = [flair_file]
    output_dir = os.path.join(output_dir, f"registered_{number}")
    os.makedirs(output_dir, exist_ok=True)
    flair_folder = os.path.join(output_dir, "flair")
    os.makedirs(flair_folder, exist_ok=True)

    template = original_file
    print("Template file_name is: ", os.path.basename(template))
    template_img_ants = ants.image_read(template, reorient="IAL")

    for file in file_array:

        filename = os.path.basename(file)
        raw_img_ants = ants.image_read(flair_file, reorient="IAL")
        
        process_filename = os.path.join(flair_folder, f"flair_{number}.nii")
        process_output = os.path.join(flair_folder, f"flair_{number}_lia.nii")

        transformation = ants.registration(fixed=template_img_ants, moving=raw_img_ants, 
                                    type_of_transform="SyN")
        
        registered_img_ants = transformation["warpedmovout"]

        print(f"Applied registered transformation to {filename}") 
        
        registered_img_ants.to_file(process_filename)
        changeToLia(process_filename, process_output)
        print(f"Successfully registered and saved {filename} in {output_dir}")



    
def skullStripping(folder, original_file, output_dir, number):

    flair_file = os.path.join(output_dir, f"registered_{number}", "flair" ,f"flair_{number}_lia.nii")

    array_skull_stripping = [flair_file, original_file]

    output_skull_stripping = os.path.join(output_dir, f"registered_{number}", f"skull_{number}")
    
    for nii_file_path in array_skull_stripping:

        filename = os.path.basename(nii_file_path)

        if "flair" in filename:
            modality = "flair"
            output_skull_stripping_path = os.path.join(output_skull_stripping, "flair")
            os.makedirs(output_skull_stripping_path, exist_ok=True)
            name = f"flair_{number}_skull_stripped.nii"

        else:
            modality = "t1"
            output_skull_stripping_path = os.path.join(output_skull_stripping, "t1")
            os.makedirs(output_skull_stripping_path, exist_ok=True)
            name = f"t1_{number}_skull_stripped.nii"


        output_dir = os.path.join(output_skull_stripping_path, name)

        print(f"Skull-stripping {filename} file...")

        reg_img_ants = ants.image_read(nii_file_path, reorient="IAL")

        probability_brain_mask = brain_extraction(reg_img_ants, modality=modality)
        
        brain_mask = ants.get_mask(probability_brain_mask, low_thresh=0.5)

        masked_img_ants = ants.mask_image(reg_img_ants, brain_mask)
        masked_img_ants.to_file(output_dir)

        liaFile = output_dir.replace(".nii", "_lia.nii")
        changeToLia(output_dir, liaFile)
    
        print(f"Skull-stripped {filename} file saved in {output_dir}")
        




def changeToLia(input_path, output_path):
 

    img = nib.load(input_path, mmap=True)
    orientation_original = aff2axcodes(img.affine)
    print(f"Orientación original: {orientation_original}")

    # Convertir a RAS primero
    img_ras = nib.as_closest_canonical(img)

    # Transformar de RAS a LIA
    target_orientation = ('L', 'I', 'A')
    current_orientation = aff2axcodes(img_ras.affine)
    transform = ornt_transform(axcodes2ornt(current_orientation), axcodes2ornt(target_orientation))

    # Aplicar la transformación
    data_lia = apply_orientation(img_ras.dataobj, transform) #si no va cambiar dataobj por get_fdata()
    affine_lia = img_ras.affine @ inv_ornt_aff(transform, img_ras.shape)

    # Crear nueva imagen con orientación LIA
    img_lia = nib.Nifti1Image(data_lia, affine_lia, header=img_ras.header)
    nib.save(img_lia, output_path)


def compress_file(input_path, output_path):
    with open(input_path, 'rb') as f_in:
        with gzip.open(output_path, 'wb') as f_out:
            shutil.copyfileobj(f_in, f_out)

def compress_directory(root_path):
    for foldername, subfolders, filenames in os.walk(root_path):
        for filename in filenames:
            input_file_path = os.path.join(foldername, filename)
            output_file_path = input_file_path + '.gz'
            
            if os.path.exists(input_file_path.replace('.nii.gz', 'lia.nii.gz')) and not input_file_path.endswith('lia.nii.gz'):
                # Comprimir el archivo .nii.gz si existe el archivo .nii.lia.gz
                print(input_file_path)

                

            # Comprimir solo si el archivo no está ya comprimido
            if not input_file_path.endswith('.gz'):
                print(f"Comprimiendo: {input_file_path} -> {output_file_path}")
                compress_file(input_file_path, output_file_path)
                os.remove(input_file_path)  # Eliminar el archivo original



def filterRegions(text_dir, output_dir, file_dir, number):
    os.makedirs(output_dir, exist_ok=True)
    flair_file = os.path.join(file_dir, f"skull_{number}" ,'flair', f"flair_{number}_skull_stripped_lia.nii.gz")
    t1_file = os.path.join(file_dir, f"skull_{number}" ,'t1', f"t1_{number}_skull_stripped_lia.nii.gz")
    flair_data = nib.load(flair_file).get_fdata()
    t1_data = nib.load(t1_file).get_fdata()
    index_str = f"{number:03d}"

    data_text = parse_txt(text_dir, threshold=0.9)
    flair_output = os.path.join(output_dir, f"patient_{index_str}_0000.nii.gz")
    t1_output = os.path.join(output_dir, f"patient_{index_str}_0001.nii.gz")

    seg_path = os.path.join(os.path.dirname(__file__), '..', 'fileFolder', f"image-{number}", "mri", "niiFiles", "aparc.DKTatlas+aseg.deep.nii")

    seg_data = nib.load(seg_path).get_fdata()
    valid_labels = set(map(int, data_text.keys()))
    mask = np.isin(seg_data, list(valid_labels))

    filtered_data_t1 = np.where(mask, t1_data, 0)
    filtered_data_flair = np.where(mask, flair_data, 0)

    print(f"Filtrando regiones en {flair_file} y {t1_file} basadas en etiquetas válidas.")
    print(f"Etiquetas válidas: {valid_labels}")
    print(f"Guardando imágenes filtradas en {flair_output} y {t1_output}")


    save_filtered_image(flair_file, flair_output, filtered_data_flair)
    save_filtered_image(t1_file, t1_output, filtered_data_t1)


def save_filtered_image(original_path, output_path, filtered_data):
    original_img = nib.load(original_path)
    affine = original_img.affine
    header = original_img.header
    filtered_img = nib.Nifti1Image(filtered_data, affine, header)
    nib.save(filtered_img, output_path)
    print(f"Guardada imagen filtrada en {output_path}")


def preprocessFiles(folder, original_file, aseg_file, flair_file, output_dir, number, text_dir, final_dir):


    niiTransformation(folder, original_file, aseg_file)

    register(folder, output_dir, original_file, flair_file, number)

    skullStripping(folder, original_file, output_dir, number)

    compress_directory(os.path.join(output_dir, f"registered_{number}"))

    filterRegions(text_dir, final_dir, os.path.join(output_dir, f"registered_{number}"), number)

    #deleteTemporaryFiles(os.path.join(output_dir, f"registered_{number}"))
    print("Preprocessing done")


def deleteTemporaryFiles(folder):
    for root, dirs, files in os.walk(folder):
        for file in files:
            file_path = os.path.join(root, file)
            if file.endswith(".nii.gz") or file.endswith(".nii"):
                os.remove(file_path)
                print(f"Deleted {file_path}")

        if not os.listdir(root):
            os.rmdir(root)
            print(f"Deleted empty directory {root}")


    if not os.listdir(folder):
        os.rmdir(folder)
        print(f"Deleted empty directory {folder}")

def main():

    fastSurferPath = os.path.join(os.path.dirname(__file__), '..', 'fileFolder', 'image-1', 'mri')
    flair_dir = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'private', 'nii_files')
    output_dir = os.path.join(os.path.dirname(__file__), '..', 'temporalFiles')
    BASE_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..'))  # Gets you to the root APP level
    txt_dir = os.path.join(BASE_DIR, 'epilepsyFinder', 'textFiles', 'resultados_segmentacion_seg_WithCC_global_75.txt')
    final_dir = os.path.join(os.path.dirname(__file__), '..', 'fileFolder', 'processedFiles')

    number = 1
    original_file = os.path.join(fastSurferPath, "orig.mgz")
    aseg_file = os.path.join(fastSurferPath, "aparc.DKTatlas+aseg.deep.mgz")
    flair_file = os.path.join(flair_dir, 'patient_001_0000.nii.gz')

    preprocessFiles(fastSurferPath, original_file, aseg_file, flair_file, output_dir, number, txt_dir, final_dir)


if __name__ == "__main__":
    main()
    print("Post-processing completed successfully.")