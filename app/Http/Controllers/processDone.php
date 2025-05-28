<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class processDone extends Controller
{
    public function processDone(Request $request)
    {
        $userId = Auth::id();

        
    }
}
