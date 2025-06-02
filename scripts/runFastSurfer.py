import subprocess
import time
import os
import sys
import shutil
from postPorcesing import main as postProcessing
from runInference import run_batch_script as inference
from createImages import main as createImages
from processDone import notifyProcessFinished

# Ruta absoluta para el log
LOG_PATH = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'storage', 'logs', 'fastsurfer_debug.log'))

def log(message):
    """Escribe mensaje con timestamp en el log."""
    with open(LOG_PATH, 'a') as f:
        f.write(f"[{time.strftime('%Y-%m-%d %H:%M:%S')}] {message}\n")

def runFastSurfer(folder, file, output):
    log(f"Borrando contenido de la carpeta de salida: {output}")
    deleteFileFolder(output)
    log(f"Iniciando runFastSurfer con archivo: {file}")
    cmd = [
        "docker", "run", "--gpus", "all",
        "-v", rf"{folder}:/data",
        "-v", rf"{output}:/output",
        "-v", "C:\\Users\\javie\\Desktop\\TFG\\app\\epilepsyFinder\\textFiles:/fs_license",
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

        result = subprocess.run(cmd, capture_output=True, text=True, timeout=1800)
        
        print("Salida estándar:\n", result.stdout)
        print("Salida de error:\n", result.stderr)

        log("Ejecutando postProcessing...")
        postProcessing()
        log("postProcessing completado.")

        if result.returncode != 0:
            log(f"Error en docker run: código {result.returncode}")
            log(f"stderr: {result.stderr}")

    except subprocess.TimeoutExpired:
        log("El proceso docker run excedió el tiempo límite y fue terminado.")
    except Exception as e:
        log(f"Error en runFastSurfer: {e}")

def deleteFileFolder(folder):
    log(f"Intentando eliminar contenido de: {folder}")
    for filename in os.listdir(folder):
        file_path = os.path.join(folder, filename)
        try:
            if os.path.isfile(file_path) or os.path.islink(file_path):
                os.unlink(file_path)
            elif os.path.isdir(file_path):
                shutil.rmtree(file_path)
            log(f"Eliminado: {file_path}")
        except Exception as e:
            log(f"Error eliminando {file_path}: {e}")

if __name__ == "__main__":
    log("======== NUEVA EJECUCIÓN ========")

    if len(sys.argv) < 3:
        log("Error: faltan argumentos. Esperado: userId y projectId")
        sys.exit(1)

    userId = sys.argv[1]
    projectId = sys.argv[2]
    log(f"Argumentos recibidos: userId={userId}, projectId={projectId}")

    try:
        lock_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'processing.lock'))
        with open(lock_path, 'w') as f:
            f.write('processing')
        log("Lock file creado.")

        outputFolder = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'fileFolder'))
        folder = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'private', 'nii_files'))
        file = 'patient_001_0001.nii.gz'

        log(f"Ruta de entrada: {folder}")
        log(f"Ruta de salida: {outputFolder}")

        startTime = time.time()

        runFastSurfer(folder=folder, file=file, output=outputFolder)

        log("Ejecutando inference()...")
        inference()
        log("inference completado.")

        log("Ejecutando createImages()...")
        createImages(userId, projectId)
        log("createImages completado.")

        notifyProcessFinished(userId, projectId)
        log("notifyProcessFinished ejecutado.")

        if os.path.exists(lock_path):
            os.remove(lock_path)
            log("Lock file eliminado.")

        endTime = time.time()
        log(f"Tiempo total de ejecución: {endTime - startTime:.2f} segundos")
        log("Procesamiento COMPLETADO correctamente.\n")

    except Exception as e:
        log(f"ERROR FATAL: {e}")
