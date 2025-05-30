# convert_mri_mask_to_json.py
import nibabel as nib
import numpy as np
import json

def convert_mri_mask_to_json(mri_path, mask_path, output_path):
    mri_img = nib.load(mri_path)
    mask_img = nib.load(mask_path)

    mri_data = mri_img.get_fdata().astype(np.float32)
    mask_data = mask_img.get_fdata().astype(np.uint16)

    shape = mri_data.shape
    if mask_data.shape != shape:
        raise ValueError("MRI y máscara deben tener las mismas dimensiones.")

    # Normalización global: usando percentiles para evitar outliers
    vmin, vmax = np.percentile(mri_data, [1, 99])
    range_val = vmax - vmin if vmax > vmin else 1.0

    normalized_mri = np.clip((mri_data - vmin) / range_val, 0, 1) * 1000

    mri_flat = normalized_mri.flatten().tolist()
    mask_flat = mask_data.flatten().tolist()

    output = {
        "dimensions": shape,
        "mri": mri_flat,
        "mask": mask_flat,
    }

    with open(output_path, 'w') as f:
        json.dump(output, f)

    print(f"Guardado en {output_path}")



if __name__ == "__main__":
    '''import sys
    if len(sys.argv) != 4:
        print("Uso: python convert_mri_mask_to_json.py <mri_path> <mask_path> <output_path>")
        sys.exit(1)

    mri_path = sys.argv[1]
    mask_path = sys.argv[2]
    output_path = sys.argv[3]'''

    mri_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\temporalFiles\registered_1\skull_1\t1\t1_1_skull_stripped_lia.nii.gz'
    mask_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles\aparc.DKTatlas+aseg.deep.nii'
    output_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\json\volume_data.json'

    convert_mri_mask_to_json(mri_path, mask_path, output_path)
