<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('user_id', auth()->id())
            ->withCount(['keywords','briefs'])
            ->latest()
            ->get(['id','name','description','created_at']);

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:2000'],
        ]);

        $project = Project::create([
            'user_id' => auth()->id(),
            ...$data,
        ]);

        return redirect()->route('projects.show', $project);
    }

    public function show(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);

        $project->load([
            'keywords:id,project_id,phrase,locale,intent,volume,difficulty,created_at',
            'briefs:id,project_id,user_id,title,status,progress,generated_at,created_at',
        ]);

        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }
}
