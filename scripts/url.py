import os
import re
from pathlib import Path

# Define la ruta base
BASE_PATH = r"C:\Users\javie\Desktop\TFG\app\epilepsyFinder"
BASE_PATH_ESCAPED = re.escape(BASE_PATH)

# Directorio raíz donde buscar
PROJECT_ROOT = BASE_PATH

# Patrón para encontrar strings de ruta hardcodeadas
pattern = re.compile(r"(r?[\"'])" + BASE_PATH_ESCAPED + r"(\\[^\"']+)([\"'])")

def convert_to_base_url_expression(full_path: str) -> str:
    # Elimina el prefijo común
    relative_path = full_path.replace(BASE_PATH, "").lstrip("\\")
    parts = relative_path.split("\\")
    # Construye expresión tipo: BASE_URL / "subdir" / "file"
    path_expr = "BASE_URL"
    for part in parts:
        path_expr += f" / \"{part}\""
    return path_expr

def process_file(file_path: Path):
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()

    matches = list(pattern.finditer(content))
    if not matches:
        return

    modified = content
    for match in matches:
        full_match = match.group(0)
        path_suffix = match.group(2)
        original_path = BASE_PATH + path_suffix
        new_path_expr = convert_to_base_url_expression(original_path)
        modified = modified.replace(full_match, new_path_expr)

    # Agrega importación si no existe
    if "from config import BASE_URL" not in modified:
        modified = 'from config import BASE_URL\n\n' + modified

    with open(file_path, "w", encoding="utf-8") as f:
        f.write(modified)
    print(f"Modificado: {file_path}")

def main():
    for dirpath, _, filenames in os.walk(PROJECT_ROOT):
        for filename in filenames:
            if filename.endswith(".py"):
                process_file(Path(dirpath) / filename)

if __name__ == "__main__":
    main()
