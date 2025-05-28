@echo off
REM Activar el entorno de Conda
call C:\Users\javie\anaconda3\Scripts\activate.bat nnunet_clean_env

REM Establecer variables de entorno necesarias
set nnUNet_raw=C:/Users/javie/Desktop/TFG/dataset/nnUNet_raw
set nnUNet_preprocessed=C:/Users/javie/Desktop/TFG/dataset/nnUNet_preprocessed
set nnUNet_results=C:/Users/javie/Desktop/TFG/dataset/nnUNet_results
set KMP_DUPLICATE_LIB_OK=TRUE

REM Ejecutar el comando de inferencia
nnUNetv2_predict ^
 -i "C:\Users\javie\Desktop\TFG\app\epilepsyFinder\fileFolder\processedFiles" ^
 -o "C:\Users\javie\Desktop\TFG\app\epilepsyFinder\inference" ^
 -d 501 ^
 -c 3d_fullres ^
 -f 3 ^
 -chk checkpoint_best.pth

