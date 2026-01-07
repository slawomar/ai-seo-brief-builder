<?php

namespace App\Jobs;

use App\Models\Brief;
use App\Services\BriefGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class GenerateBriefJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $briefId;

    public $tries = 3;
    public $backoff = [10, 60, 300];

    public function __construct(int $briefId)
    {
        $this->briefId = $briefId;
    }

    public function handle(BriefGenerator $generator): void
    {
        // Row-level lock = basic idempotency if job is dispatched twice
        DB::transaction(function () {
            $brief = Brief::whereKey($this->briefId)->lockForUpdate()->firstOrFail();

            if ($brief->status === 'done') {
                return;
            }

            $brief->update([
                'status' => 'running',
                'progress' => 5,
                'error_message' => null,
            ]);
        });

        try {
            $brief = Brief::findOrFail($this->briefId);

            // demo progress steps
            $this->updateProgress($brief, 15);
            $project = $brief->project()->with('keywords')->firstOrFail();

            $this->updateProgress($brief, 40);
            $result = $generator->generate($project);

            $this->updateProgress($brief, 85);

            $brief->update([
                'result_json' => $result,
                'status' => 'done',
                'progress' => 100,
                'generated_at' => now(),
            ]);
        } catch (Throwable $e) {
            Brief::whereKey($this->briefId)->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e; // let queue handle retry/backoff
        }
    }

    private function updateProgress(Brief $brief, int $progress): void
    {
        $brief->update(['progress' => $progress]);
    }
}
