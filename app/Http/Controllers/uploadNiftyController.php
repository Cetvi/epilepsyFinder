<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class uploadNiftyController extends Controller
{
    public function uploadNifty(Request $request){
 
        if($request->fileCount != 2){
            return response()->json(['error' => 'There are needed 2 files'], 422);
        }

        $lockPath = storage_path('app/processing.lock');
        
        if (file_exists($lockPath)) {
            return response()->json(['status' => 'busy']);
        }

        $request->validate([
            'file0' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value->getClientOriginalName(), '.nii.gz')) {
                        $fail('El archivo debe tener la extensión .nii.gz');
                    }
                },
            ],
            'file1' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value->getClientOriginalName(), '.nii.gz')) {
                        $fail('The file must have extension .nii.gz');
                    }
                },
            ],
        ]);

        $fileName0 = $request->file('file0')->getClientOriginalName();
        $fileName1 = $request->file('file1')->getClientOriginalName();

        $orderFiles = self::returnOrderFiles($fileName0, $fileName1);
        
        if($orderFiles[0] == $fileName0){
            $path0 = $request->file('file0')->storeAs('nii_files', 'patient_001_0000.nii.gz');
            $path1 = $request->file('file1')->storeAs('nii_files', 'patient_001_0001.nii.gz');
        }else{
            $path0 = $request->file('file1')->storeAs('nii_files', 'patient_001_0000.nii.gz');
            $path1 = $request->file('file0')->storeAs('nii_files', 'patient_001_0001.nii.gz');
        }
        
        return self::runFastSurfer();
    }

    public function runFastSurfer(){

        $scriptPath = base_path('scripts/runFastSurfer.py');
        $userId = Auth::id();
        pclose(popen("start /B python $scriptPath $userId", "r"));

        return response()->json(['status' => 'success']);
        
    }

    public function returnOrderFiles($fileName0, $fileName1){
        $orderFiles = [];
        $explode0 = explode("_", $fileName0);
        $explode1 = explode("_", $fileName1);

        $type0 = explode('.',($explode0[count($explode0) - 1]))[0];
        $type1 = explode('.',($explode1[count($explode1) - 1]))[0];

        if($type0 == "0000" && $type1 == "0001"){
            $orderFiles['0'] = $fileName0;
            $orderFiles['1'] = $fileName1;
        }else if($type0 == "0001" && $type1 == "0000"){
            $orderFiles['0'] = $fileName1;
            $orderFiles['1'] = $fileName0;
        }else{
            return response()->json(['error' => 'The files are not in the correct order'], 422);
        }

        return $orderFiles;
    }
}
