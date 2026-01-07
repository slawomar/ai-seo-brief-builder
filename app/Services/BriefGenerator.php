<?php

namespace App\Services;

use App\Models\Project;

class BriefGenerator
{
    /**
     * Demo generator (no external AI required).
     * Replace internals later with OpenAI / other provider.
     */
    public function generate(Project $project): array
    {
        $keywords = $project->keywords()->orderBy('volume', 'desc')->get(['phrase','intent','volume','difficulty','locale']);

        $primary = $keywords->first()?->phrase ?? $project->name;
        $secondary = $keywords->skip(1)->take(6)->pluck('phrase')->values()->all();

        $titleIdeas = [
            "Complete guide to {$primary}",
            "{$primary}: best practices and checklist",
            "How to get results with {$primary} (step-by-step)",
        ];

        $outline = [
            "Introduction: what {$primary} is and why it matters",
            "Search intent and audience needs",
            "Core concepts and terminology",
            "Implementation checklist",
            "Common mistakes and how to avoid them",
            "FAQ",
            "Summary + next steps",
        ];

        $faq = array_map(fn ($k) => "What is {$k} and how does it work?", array_slice($secondary, 0, 4));
        if (count($faq) < 4) $faq[] = "How long does it take to see results?";

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
            ],
            'primary_keyword' => $primary,
            'secondary_keywords' => $secondary,
            'title_ideas' => $titleIdeas,
            'meta' => [
                'title' => "{$primary} — practical guide",
                'description' => "A practical brief for {$primary}: intent, structure, FAQs and checklist.",
            ],
            'outline' => $outline,
            'faq' => $faq,
            'notes' => [
                'This is a demo generator. Swap BriefGenerator internals with an AI provider when ready.',
            ],
        ];
    }
}
