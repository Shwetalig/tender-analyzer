<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use App\Services\TenderSummaryService;

class GenerateTenderSummaries extends Command
{
    protected $signature = 'tenders:generate-summaries';
    protected $description = 'Generate summaries for tenders without summaries';

    public function handle()
    {
        $service = new TenderSummaryService();

        $tenders = Tender::whereNull('summary')->whereNotNull('text_content')->get();

        if ($tenders->isEmpty()) {
            $this->info("No tenders found without summary.");
            return;
        }

        $this->info("Found {$tenders->count()} tenders to summarize...");

        foreach ($tenders as $tender) {
            $summary = $service->generate($tender->text_content);
            $tender->summary = $summary;
            $tender->save();

            $this->line("✔️ {$tender->original_name} summarized.");
        }

        $this->info("Done!");
    }
}
