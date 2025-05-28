<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LockController extends Controller
{
    public function check(): JsonResponse
    {
        $lockPath = storage_path('app/processing.lock');
        $isLocked = file_exists($lockPath);
        return response()->json(['locked' => $isLocked]);
    }
}
