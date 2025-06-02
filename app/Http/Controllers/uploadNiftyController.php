<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Projects;
use App\Http\Controllers\Process as Nprocess;

class UploadNiftyController extends Controller
{
    public function uploadNifty(Request $request){

        $projectName = $request->all('name')['name'];

        $project = new Projects();
        $newProject = json_decode($project->createProject($projectName)->content(), true);

        if(!$newProject['success']){
            return response()->json(['error' => 'Error creating project'], 500);
        }else{
            $projectId = $newProject['project_id'];
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

        $lockPath = storage_path('app/processing.lock');
        $process = new Nprocess();
        $userId = Auth::id();
        $process->dbInsert($projectId, $userId);
        if (file_exists($lockPath)) {
            self::insertQueueFiles($projectId, $userId, $orderFiles, $request);
            return response()->json(['status' => 'busy']);
        }
        
        if($orderFiles[0] == $fileName0){
            $path0 = $request->file('file0')->storeAs('nii_files', 'patient_001_0000.nii.gz');
            $path1 = $request->file('file1')->storeAs('nii_files', 'patient_001_0001.nii.gz');
        }else{
            $path0 = $request->file('file1')->storeAs('nii_files', 'patient_001_0000.nii.gz');
            $path1 = $request->file('file0')->storeAs('nii_files', 'patient_001_0001.nii.gz');
        }

        $userId = Auth::id();
        return self::runFastSurfer($projectId, $userId);
    }

    public function runFastSurfer($projectId, $userId){

        $scriptPath = base_path('scripts/runFastSurfer.py');
        $logPath = storage_path('logs/fastsurfer_laravel_output.log');


        $cmd = "start /B python \"$scriptPath\" $userId $projectId > \"$logPath\" 2>&1";


        pclose(popen($cmd, "r"));
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

    public function insertQueueFiles($projectId, $userId, $orderFiles, $request)
    {
        $extraInfo = '_' . $projectId . '_' . $userId;
        $fileName0 = $request->file('file0')->getClientOriginalName();
        if($orderFiles[0] == $fileName0){
            $path0 = $request->file('file0')->storeAs('nii_files\queueImages', 'patient_001_0000'.$extraInfo.'.nii.gz');
            $path1 = $request->file('file1')->storeAs('nii_files\queueImages', 'patient_001_0001'.$extraInfo.'.nii.gz');
        }else{
            $path0 = $request->file('file1')->storeAs('nii_files\queueImages', 'patient_001_0000'.$extraInfo.'.nii.gz');
            $path1 = $request->file('file0')->storeAs('nii_files\queueImages', 'patient_001_0001'.$extraInfo.'.nii.gz');
        }
    }
}
