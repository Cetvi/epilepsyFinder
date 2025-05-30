import subprocess
import time
import os
import sys
from postPorcesing import main as postProcessing
from runInference import run_batch_script as inference
from createImages import main as createImages

def runFastSurfer(folder, file, output):
    cmd = [
        "docker", "run", "--gpus", "all",
        "-v", rf"{folder}:/data",
        "-v", rf"{output}:/output",
        "-v", "C:\\Users\\javie\\Desktop\\TFG\\FastSurfer\\FastSurfer:/fs_license",
        "--rm", "--user", "1000:1000", "deepmi/fastsurfer:latest",
        "--fs_license", "/fs_license/license.txt",
        "--seg_only",
        "--no_surfreg",
        "--no_cereb",
        "--no_hypothal",
        "--no_surfreg",
        "--t1", f"/data/{file}",
        "--sid", "image-1", "--sd", "/output"
    ]

    try:
        result = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, err = result.communicate()  

        print("Salida estándar:\n", out.decode(errors='ignore'))
        print("Salida de error:\n", err.decode(errors='ignore'))

    except subprocess.CalledProcessError as e:
        print("Error en la ejecución:", e)
    finally:
        
        postProcessing()
        

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Error: se esperaba un argumento userId")
        sys.exit(1)

    userId = sys.argv[1]
    projectId = sys.argv[2]
    
    lock_path = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'processing.lock')
    with open(lock_path, 'w') as f:
        f.write('processing')

    outputFolder = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'fileFolder'))
    folder = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'private', 'nii_files'))
    file = 'patient_001_0001.nii.gz'

    print("Iniciando procesamientoc de:", file)
    startTime = time.time()
    runFastSurfer(folder=folder, file=file, output=outputFolder)
    endTime = time.time()
    inference()
    createImages(userId, projectId)
    lock_path = os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'processing.lock')
    if os.path.exists(lock_path):
        os.remove(lock_path)
    print("Tiempo de ejecución:", endTime - startTime)
