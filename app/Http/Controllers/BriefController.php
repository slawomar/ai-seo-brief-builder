<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateBriefJob;
use App\Models\Brief;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BriefController extends Controller
{
    public function show(Brief $brief)
    {
        abort_unless($brief->user_id === auth()->id(), 403);

        $brief->load('project:id,name');

        return Inertia::render('Briefs/Show', [
            'brief' => $brief,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required','integer','exists:projects,id'],
            'title' => ['required','string','max:255'],
        ]);

        $project = Project::findOrFail($data['project_id']);
        abort_unless($project->user_id === auth()->id(), 403);

        $brief = Brief::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'status' => 'queued',
            'progress' => 0,
        ]);

        GenerateBriefJob::dispatch($brief->id);

        return redirect()->route('briefs.show', $brief);
    }

    public function status(Brief $brief)
    {
        abort_unless($brief->user_id === auth()->id(), 403);

        return response()->json([
            'id' => $brief->id,
            'status' => $brief->status,
            'progress' => $brief->progress,
            'generated_at' => optional($brief->generated_at)->toISOString(),
            'error_message' => $brief->error_message,
            'result_json' => $brief->result_json,
        ]);
    }
}
