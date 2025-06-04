<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class VolumenController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->all() ?? null;
        $projectName = DB::table("projects")
            ->where("id", $data["project_id"])
            ->value("name");
        $path = storage_path('app/textFiles/FreeSurferColorLut.txt');
        $colorLut = $this->readFreesurferLut($path);
        return view('showInference', ['colorLut' => json_encode($colorLut), 'data' => $data, 'projectName' => $projectName]);
    }

    private function readFreesurferLut($path, array $allowedIds = null)
    {
        $lut = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || Str::startsWith($line, '#')) {
                continue;
            }
            $parts = preg_split('/\s+/', $line);
            if (count($parts) >= 6) {
                $labelId = intval($parts[0]);

                // Si se pasó una lista de IDs permitidos, filtrar
                if ($allowedIds && !in_array($labelId, $allowedIds)) {
                    continue;
                }

                $r = intval($parts[2]) / 255;
                $g = intval($parts[3]) / 255;
                $b = intval($parts[4]) / 255;
                $lut[$labelId] = [$r, $g, $b];
            }
        }
        return $lut;
    }


    public function freeSurferColours()
    {
        $path = storage_path('app/textFiles/FreeSurferColorLut.txt');
        $idsPath = storage_path('app/textFiles/label_ids.json');
        $namesPath = storage_path('app/textFiles/label_names.json');

        $validIds = json_decode(file_get_contents($idsPath), true);
        $labelNames = json_decode(file_get_contents($namesPath), true); // id => name

        $filteredLut = $this->readFreesurferLut($path, $validIds);

        // Formateamos para la vista
        $colours = [];
        foreach ($filteredLut as $id => $rgb) {
            $colours[] = [
                'id' => $id,
                'name' => $labelNames[$id] ?? 'Unknown',
                'rgb' => $rgb,
                'css' => 'rgb(' . implode(',', array_map(fn($c) => round($c * 255), $rgb)) . ')'
            ];
        }
        foreach ($colours as &$entry) {
            if (preg_match('/rgb\((\d+),\s*(\d+),\s*(\d+)\)/', $entry['css'], $matches)) {
                $r = intval($matches[1]);
                $g = intval($matches[2]);
                $b = intval($matches[3]);
                $entry['hex'] = sprintf("#%02x%02x%02x", $r, $g, $b);
            } else {
                $entry['hex'] = null; // o algún valor por defecto
            }
        }

        return view('showColours', ['colours' => $colours]);
    }

}
