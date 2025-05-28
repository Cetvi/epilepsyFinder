import subprocess

def run_batch_script():
    batch_path = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder\scripts\anaconda.bat"
    
    result = subprocess.run(batch_path, shell=True)
    
    if result.returncode == 0:
        print(" Script ejecutado con éxito.")
    else:
        print(f" Error al ejecutar el script. Código de salida: {result.returncode}")

if __name__ == "__main__":
    run_batch_script()
