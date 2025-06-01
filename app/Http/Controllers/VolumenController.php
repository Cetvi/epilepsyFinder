<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VolumenController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->all() ?? null;
        $path = storage_path('app/textFiles/FreeSurferColorLut.txt');
        $colorLut = $this->readFreesurferLut($path);
        return view('showInference', ['colorLut' => json_encode($colorLut), 'data' => $data]);
    }

    private function readFreesurferLut($path)
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
                $r = intval($parts[2]) / 255;
                $g = intval($parts[3]) / 255;
                $b = intval($parts[4]) / 255;
                $lut[$labelId] = [$r, $g, $b];
            }
        }
        return $lut;
    }
}
