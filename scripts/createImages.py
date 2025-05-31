from nilearn import plotting
import nibabel as nib
import numpy as np
import scipy.ndimage
import os
import re
import matplotlib.pyplot as plt
from matplotlib.colors import ListedColormap
from convertMriToVtk import convert_mri_mask_to_json
from moreInfo import create_info_json

def detect_latest_number(base_dir):
    registered_dirs = [d for d in os.listdir(base_dir) if re.match(r'registered_\d+', d)]
    if not registered_dirs:
        raise FileNotFoundError("No se encontró ningún directorio 'registered_XXX'.")
    registered_dirs.sort()
    latest = registered_dirs[-1]
    number = int(re.findall(r'\d+', latest)[0])
    return number

def create_images(flair_path, t1_path, mask_path, output_folder, type, userId, projectId):
    os.makedirs(output_folder, exist_ok=True)

    flair_img = nib.load(flair_path)
    t1_img = nib.load(t1_path)
    mask_img = nib.load(mask_path)

    mask_data = mask_img.get_fdata()
    center_of_mass_voxel = scipy.ndimage.center_of_mass(mask_data)
    voxel_coords = np.array([*center_of_mass_voxel, 1])
    affine = flair_img.affine
    world_coords = affine @ voxel_coords
    cut_coords = world_coords[:3]

    fig_flair = plotting.plot_roi(mask_img, flair_img, cmap='autumn', cut_coords=cut_coords,
                                  display_mode='ortho', draw_cross=True, title="FLAIR with mask")
    fig_flair.savefig(os.path.join(output_folder, f"flair_mask_overlay_{type}_{projectId}_{userId}.png"))
    fig_flair.close()

    fig_t1 = plotting.plot_roi(mask_img, t1_img, cmap='autumn', cut_coords=cut_coords,
                               display_mode='ortho', draw_cross=True, title="T1 with mask")
    fig_t1.savefig(os.path.join(output_folder, f"t1_mask_overlay_{type}_{projectId}_{userId}.png"))
    fig_t1.close()

    print("Imágenes guardadas en:", output_folder)

def create_segmentation_image(anat_path, seg_path, lut_path, output_path, title="Segmentation Overlay", userId=None, projectId=None):
    def read_freesurfer_lut(lut_path):
        lut = {}
        with open(lut_path, 'r') as f:
            for line in f:
                if line.strip() == '' or line.startswith('#'):
                    continue
                parts = re.split(r'\s+', line.strip())
                if len(parts) >= 6:
                    label_id = int(parts[0])
                    r, g, b = map(int, parts[2:5])
                    lut[label_id] = (r / 255, g / 255, b / 255)
        return lut

    def get_colormap_for_seg(seg_data, lut):
        lut[0] = (0.9, 0.9, 0.9)  # background color
        max_label = int(np.max(seg_data)) + 1
        colors = np.zeros((max_label, 3))
        for label, color in lut.items():
            if label < max_label:
                colors[label] = color
        return ListedColormap(colors)

    anat_img = nib.load(anat_path)
    seg_nii = nib.load(seg_path)
    seg_data = seg_nii.get_fdata().astype(int)
    affine = anat_img.affine

    # Centro de masa para cortes informativos
    center_of_mass_voxel = scipy.ndimage.center_of_mass(seg_data)
    if np.isnan(center_of_mass_voxel).any():
        center_of_mass_voxel = np.array(seg_data.shape) // 2  # fallback
    cut_coords = nib.affines.apply_affine(affine, center_of_mass_voxel)

    # LUT y colormap
    lut = read_freesurfer_lut(lut_path)
    cmap = get_colormap_for_seg(seg_data, lut)

    seg_img = nib.Nifti1Image(seg_data, affine)

    display = plotting.plot_anat(anat_img, title=title,
                                 display_mode='ortho', cut_coords=cut_coords,
                                 annotate=False)
    display.add_overlay(seg_img, cmap=cmap, alpha=0.5)
    display.savefig(output_path)
    display.close()

    print("Imagen de segmentación guardada en:", output_path)

def main(userId, projectId):
    processed_base = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\temporalFiles'
    output_folder = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\images\resultImages'
    fast_surfer_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles'
    
    number = detect_latest_number(processed_base)

    flair_path = os.path.join(processed_base, f'registered_{number}', f'skull_{number}', 'flair', f'flair_{number}_skull_stripped_lia.nii.gz')
    t1_path = os.path.join(processed_base, f'registered_{number}', f'skull_{number}', 't1', f't1_{number}_skull_stripped_lia.nii.gz')
    mask_path = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\inference\patient_001.nii.gz"

    create_images(flair_path, t1_path, mask_path, output_folder, type='skull_stripped')

    temporal_flair_path = os.path.join(processed_base, f'registered_{number}', 'flair', f'flair_{number}_lia.nii.gz')
    temporal_t1_path = os.path.join(fast_surfer_path, 'orig.nii')

    create_images(temporal_flair_path, temporal_t1_path, mask_path, output_folder, type='with_skull', userId=userId)

    segmentation_path = os.path.join(fast_surfer_path, 'aparc.DKTatlas+aseg.deep.nii')
    lut_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\textFiles\FreeSurferColorLUT.txt'
    seg_output_path = os.path.join(output_folder, f"segmentation_overlay_{projectId}_{userId}.png")

    create_segmentation_image(temporal_flair_path, segmentation_path, lut_path, seg_output_path, title="Segmentation Overlay", userId=userId, projectId=projectId)

    vkt_output_path = fr'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\json\volume_data_{projectId}_{userId}.json'
    convert_mri_mask_to_json(t1_path, segmentation_path, vkt_output_path)

    anat_img_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles\orig.nii'
    output_img_dir = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\images\resultImages"
    create_info_json(mask_path, segmentation_path, anat_img_path,  fr'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\json\extraInfo_{projectId}_{userId}.json', output_img_dir, f'_{projectId}_{userId}')
    #aqui tengo que crear las imagenes de las labes y zonas interesantes

if __name__ == "__main__":
    main()
