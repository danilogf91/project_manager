<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Project;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function users()
    {
        $user = auth()->user();
        return view('users')->with('user', $user);
    }

    public function projects()
    {
        $user = auth()->user();
        return view('projects')->with('user', $user);
    }

    public function projectsData($id = null)
    {
        $project = Project::find($id);

        if (!$project) {
            abort(404);
        }
        return view('projects-dashboard')->with('project', $project);
    }

    public function data($id = null)
    {
        if ($id) {
            $project = Project::find($id);

            if (!$project) {
                abort(404);
            }

            $name = $project->name;
        } else {
            $latestProject = Project::latest('id')->first();

            if (!$latestProject) {
                abort(404);
            }

            $name = $latestProject->name;
            $id = $latestProject->id;
        }

        $user = auth()->user();

        return view('data', compact(['name', 'id', 'user']));
    }
}
