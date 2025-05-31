import nibabel as nib
import numpy as np
import json
import os
from importMap import importMap
from nilearn import plotting
import matplotlib.pyplot as plt
from matplotlib.colors import ListedColormap

MAP = importMap()

import numpy as np
import nibabel as nib
import os
import matplotlib.pyplot as plt
from matplotlib.colors import ListedColormap
from nilearn import plotting

def plot_labels(anat_img_path, seg_img_path, labels, output_dir, name):
    anat_img = nib.load(anat_img_path)
    seg_img = nib.load(seg_img_path)
    seg_data = seg_img.get_fdata()

    if not os.path.exists(output_dir):
        os.makedirs(output_dir)

    combined_mask = np.zeros(seg_data.shape)

    for idx, label in enumerate(labels, start=1):
        combined_mask[seg_data == label] = idx

    combined_mask_img = nib.Nifti1Image(combined_mask, seg_img.affine)

    red_color = (1, 0, 0, 0.7)
    color_list = [red_color] * (len(labels) + 1)
    colors = ListedColormap(color_list)

    mask_indices = np.argwhere(np.isin(seg_data, labels))
    centroid = mask_indices.mean(axis=0)
    centroid_mm = nib.affines.apply_affine(seg_img.affine, centroid)
    cut_coords = (centroid_mm[0], centroid_mm[1], centroid_mm[2])

    print(f"Cortes inteligentes en mm para {name}: {cut_coords}")

    fig = plotting.plot_roi(
        roi_img=combined_mask_img,
        bg_img=anat_img,
        cut_coords=cut_coords,
        display_mode='ortho',
        cmap=colors,
        alpha=0.7
    )

    output_path = os.path.join(output_dir, f"{name}.png")
    fig.savefig(output_path, dpi=300)
    fig.close()
    print(f"Guardada imagen: {output_path}")



def create_info_json(lesion_mask, fastSurferSeg, anat_img, output_json, output_img_dir, extra_info):
    data_mask = nib.load(lesion_mask).get_fdata()
    data_seg = nib.load(fastSurferSeg).get_fdata()

    lesion_mask_bool = data_mask > 0
    lesion_labels = data_seg[lesion_mask_bool].astype(np.int32)

    labels, counts = np.unique(lesion_labels, return_counts=True)

    if len(labels) == 0:
        print("No se encontraron etiquetas dentro de la lesión.")
        return

    total_lesion_voxels = np.sum(counts)

    lesion_distribution = {
        int(label): round((count / total_lesion_voxels) * 100, 2)
        for label, count in zip(labels, counts)
    }

    region_info = {
        str(label): {
            "name": MAP[label][0],
            "zone": MAP[label][1],
            "tissue": MAP[label][2],
            "side": MAP[label][3],
            "percentage": round(count / total_lesion_voxels, 4)
        }
        for label, count in zip(labels, counts)
    }

    region_info = dict(sorted(
        region_info.items(),
        key=lambda item: item[1]["percentage"],
        reverse=True
    ))

    with open(output_json, 'w') as f:
        json.dump(region_info, f, indent=4)

    print("Lesion distribution:", lesion_distribution)
    print("Region info:", region_info)

    main_label = int(next(iter(region_info)))
    main_info = MAP[main_label]

    plot_labels(anat_img, fastSurferSeg, [main_label], output_img_dir, f"main_label{extra_info}")

    same_zone_labels = [k for k, v in MAP.items() if v[1] == main_info[1]]
    plot_labels(anat_img, fastSurferSeg, same_zone_labels, output_img_dir, f"same_zone{extra_info}")

    same_tissue_labels = [k for k, v in MAP.items() if v[2] == main_info[2]]
    plot_labels(anat_img, fastSurferSeg, same_tissue_labels, output_img_dir, f"same_tissue{extra_info}")

    same_side_labels = [k for k, v in MAP.items() if v[3] == main_info[3]]
    plot_labels(anat_img, fastSurferSeg, same_side_labels, output_img_dir, f"same_side{extra_info}")


if __name__ == "__main__":
    mask_path = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\inference\patient_001.nii.gz"
    fastSurferSeg_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles\aparc.DKTatlas+aseg.deep.nii'
    anat_img_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles\orig.nii'
    output_json_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\json\extraInfo_2_1.json'
    output_img_dir = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\images\resultImages"

    create_info_json(mask_path, fastSurferSeg_path, anat_img_path, output_json_path, output_img_dir, '_2_1')
