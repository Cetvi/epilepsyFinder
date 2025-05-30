import nibabel as nib
import numpy as np
import json
from importMap import importMap

MAP = importMap()

def create_info_json(lesion_mask, fastSurferSeg, output_json):
    data_mask = nib.load(lesion_mask).get_fdata()
    data_seg = nib.load(fastSurferSeg).get_fdata()

    lesion_mask_bool = data_mask > 0

    lesion_labels = data_seg[lesion_mask_bool].astype(np.int32)

    labels, counts = np.unique(lesion_labels, return_counts=True)

    total_lesion_voxels = np.sum(counts)

    lesion_distribution = {
        int(label): round((count / total_lesion_voxels) * 100, 2)
        for label, count in zip(labels, counts)
    }

    print("Lesion distribution:", lesion_distribution)
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
    print("Region info:", region_info)

    with open(output_json, 'w') as f:
        json.dump(region_info, f, indent=4)

if __name__ == "__main__":
    mask_path = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\inference\patient_001.nii.gz"
    fastSurferSeg_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\image-1\mri\niiFiles\aparc.DKTatlas+aseg.deep.nii'
    output_json_path = r'C:\Users\javie\Desktop\TFG\app\epilepsyFinder\public\json\extraInfo_2_1.json'

    create_info_json(mask_path, fastSurferSeg_path, output_json_path)
