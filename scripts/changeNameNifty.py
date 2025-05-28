import os
import nibabel as nib

def change_name_nifty(input_path, output_path):
    """
    Change the name of a NIfTI file and save it to a new location.

    Parameters:
    input_path (str): Path to the input NIfTI file.
    output_path (str): Path to save the renamed NIfTI file.
    """
    # Load the NIfTI file
    img = nib.load(input_path)

    # Save the NIfTI file with a new name
    nib.save(img, output_path)
    print(f"File saved as: {output_path}")


if __name__ == "__main__":
    # Example usage
    input_path = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'private', 'nii_files')
    input_path = os.path.abspath(input_path)
    output_path = os.path.join(os.path.dirname(__file__), '..', 'resultImages', 'new_name.nii.gz')
    output_path = os.path.abspath(output_path)


    os.makedirs(os.path.dirname(output_path), exist_ok=True)

    for file in os.listdir(input_path):
        file_path = os.path.join(input_path, file)
        change_name_nifty(file_path, output_path)