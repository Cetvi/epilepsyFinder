<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoreInfo extends Controller
{
    public function showMoreInfo(Request $request)
    {
        $userId = Auth::id();
        $projectId = $request->all('project_id')['project_id'] ?? null;
        $extraData = '_'. $projectId . '_' . $userId;
        if (!$request->user()) {
            return redirect()->route('login');
        }
        $path = public_path('images/resultImages/main_label' . $extraData . '.png');
        
        if (!file_exists($path)) {
            return redirect()->route('dashboard')->with('error', 'The project does not have more info yet.');
        }

        return view('moreInfo', ["projectId" => $projectId, "userId" => $userId]);

    }
}
