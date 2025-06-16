# epilepsyFinder

**Web platform for automatic detection of epileptic lesions in MRI using deep learning and neuroanatomical segmentation.**

---

## Overview

**epilepsyFinder** is a web application built with Laravel (PHP) and JQuery that integrates Python scripts to perform automatic analysis on brain MRI scans (FLAIR and T1). The system uses **FastSurfer** for brain segmentation and anatomical parcellation, and applies **nnUNet v2** for lesion inference.

Designed for both clinical professionals and casual users, it provides rich visual output, including 2D segmentation views and interactive 3D brain models.

---

## Key Features

-  User registration and login system
-  Project creation with just two MRI scans (FLAIR & T1)
-  Automatic anatomical segmentation with FastSurfer
-  Deep learning-based lesion detection using nnUNet v2
-  Output visualizations (2D images and 3D models)
-  Interactive tutorial with sample data
-  Persistent project storage and access

---

##  Technologies Used

### Backend
- Laravel (PHP 8.1+)
- Python 3.9+

### Frontend
- JQuery
- Bootstrap

### Python Libraries
- `nibabel`, `nilearn`, `SimpleITK`, `ants`, `antspynet`
- `matplotlib`, `numpy`, `scipy`, `pandas`
- `opencv-python`, `ipywidgets`
- `intensity-normalization`
- `requests`, `json`, `pathlib`, `re`, etc.

---

## Getting Started

### Requirements

- PHP ≥ 8.1
- Composer
- Python ≥ 3.9
- Installed and configured FastSurfer & nnUNet v2
- GPU recommended for inference speed

### Installation

1. Clone the repository:
```bash
git clone https://github.com/Cetvi/epilepsyFinder.git
cd epilepsyFinder
```
2. Install Laravel dependencies
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```
3. Install Python dependencies
```bash
cd python_scripts
pip install -r requirements.txt
```
4. Install FastSurfer (also it's license) and nnU-Net v2
5. Configure .env with the database configuration and mail configuration.
6. Change the proper paths to FastSurfer and nnUNet scripts. Look config.py, runFastSurfer.py and anaconda.bat
7. Start server
```bash
composer run dev
```

#### Structure

epilepsyFinder/
│
├── app/                        # Laravel MVC application
│   ├── Http/Controllers/
│   ├── Models/
│   └── Views/
│
├── scripts/            # All segmentation/inference logic
│   ├── config.py              # Path configuration
│   ├── runFastSurfer.py       # Main runner
│   ├── runInference/          # Run inference
│   └── etc.
|
├── storage/app/private/nii_files/  # Input MRI files (.nii.gz)
│
├── fileFolder/image-<id>/mri/      # FastSurfer segmentation output
├── inference/   # nnUNet v2 inference output
│
├── public/images/           # Visualization assets
├── public/jsons/            # File's JSONs
└── resources/js/            # JQuery, Bootstrap-based frontend