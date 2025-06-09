<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Projects extends Controller
{
    public function showProjects()
    {
        $id = Auth::id();
        $projects = DB::table('projects')->where('user_id', $id)->get();
        $userProjects = $projects->toArray();
        return view('projects', [
            'projects' => $userProjects,
        ]);
    }

    public function deleteProject(Request $request)
    {
        $projectId = $request->all('projectId')['projectId'] ?? null;

        if (!$projectId) {
            return response()->json(['error' => 'Project ID is required'], 400);
        }

        $project = DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        DB::table('projects')
            ->where('id', $projectId)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['status' => 'success'], 200);
    }

    public function createProject($projectName)
    {
        if (!$projectName) {
            return response()->json(['error' => 'Project name is required'], 400);
        }

        $projectId = DB::table('projects')->insertGetId([
            'name' => $projectName,
            'user_id' => Auth::id(),
            'create_date' => now(),
        ]);

        $success = $projectId ? true : false;

        return response()->json(['success' => $success, 'project_id' => $projectId], 201);
    }

    public function lastProject()
    {
        $id = Auth::id();
        $project = DB::table('projects')
            ->join('process_control', 'projects.id', '=', 'process_control.project_id')
            ->where('projects.user_id', $id)
            ->where('process_control.done', 1)
            ->orderBy('create_date', 'desc')
            ->orderBy('projects.id', 'desc')
            ->first();
            
        return view('lastProject', [
            'project' => $project,
        ]);
    }
}
