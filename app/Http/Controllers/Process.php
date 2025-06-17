<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Mail\ProcessFinishedMail;
use App\Mail\ProcessStartedMail;
use App\Http\Controllers\UploadNiftyController;

class Process extends Controller
{

    public function dbInsert($projectId, $userId)
    {
        DB::table('process_control')->insert([
            'user_id' => $userId,
            'project_id' => $projectId,
            'done' => 0
        ]);
        return true;
    }
    public function processFinished(Request $request)
    {
        $projectId = $request->all('')['project_id'];
        $userId = $request->all('')['user_id'];

        DB::table('process_control')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->update(['done' => 1]);

        $user = User::find($userId);

        if ($user) {
            Mail::to($user->email)->send(new ProcessFinishedMail($user->name));
        }
        self::continueQueue();
    }

    public function continueQueue(){
        $project = DB::table('process_control')
            ->where('done', 0)
            ->orderBy('id', 'asc')
            ->first();

        if ($project) {
            $projectId = $project->project_id;
            $userId = $project->user_id;
            $extraInfo = '_' . $projectId . '_' . $userId;

            $folder = storage_path('app/private/nii_files');
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            $nameFile0 = 'patient_001_0000.nii.gz';
            $nameFile1 = 'patient_001_0001.nii.gz';

            $file1 = $folder . DIRECTORY_SEPARATOR . $nameFile0;
            $file2 = $folder . DIRECTORY_SEPARATOR . $nameFile1;

            $queueFolder = $folder . DIRECTORY_SEPARATOR . 'queueImages';
            $fileQueue0 = $queueFolder . DIRECTORY_SEPARATOR . 'patient_001_0000'.$extraInfo.'.nii.gz';
            $fileQueue1 = $queueFolder . DIRECTORY_SEPARATOR . 'patient_001_0001'.$extraInfo.'.nii.gz';

            if (file_exists($file1)) {
                unlink($file1);
            }
            if (file_exists($file2)) {
                unlink($file2);
            }
            if (file_exists($fileQueue0)) {
                rename($fileQueue0, $file1);
            }
            if (file_exists($fileQueue1)) {
                rename($fileQueue1, $file2);
            }
            sleep(2); 
            $this->processStarted($userId, $projectId);
        }
    }

    public function processStarted($userId, $projectId){
        $user = User::find($userId);
        if (!$user) {
            Log::warning('User not found', ['userId' => $userId]);
            return;
        }

        $userEmail = $user->email;
        $userName = $user->name;

        $projectName = DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', $userId)
            ->value('name');

        if ($userEmail && $projectName) {
            exec('taskkill /F /IM python.exe > NUL 2>&1');
            $runFastSurfer = new UploadNiftyController();
            $runFastSurfer->runFastSurfer($projectId, $userId);
            Mail::to($userEmail)->send(new ProcessStartedMail($userName, $projectName));
        } else {
            Log::warning('No se pudo enviar el correo: datos incompletos', [
                'userEmail' => $userEmail,
                'projectName' => $projectName
            ]);
        }
    }

}
