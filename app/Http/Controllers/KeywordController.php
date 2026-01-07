<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Project;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
     public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required','integer','exists:projects,id'],
            'phrase' => ['required','string','max:255'],
            'locale' => ['nullable','string','max:10'],
            'intent' => ['nullable','string','max:50'],
            'volume' => ['nullable','integer','min:0'],
            'difficulty' => ['nullable','integer','min:0','max:100'],
        ]);

        $project = Project::findOrFail($data['project_id']);
        abort_unless($project->user_id === auth()->id(), 403);

        Keyword::create([
            'project_id' => $project->id,
            'phrase' => $data['phrase'],
            'locale' => $data['locale'] ?? 'en',
            'intent' => $data['intent'] ?? null,
            'volume' => $data['volume'] ?? null,
            'difficulty' => $data['difficulty'] ?? null,
        ]);

        return redirect()->route('projects.show', $project);
    }
}
