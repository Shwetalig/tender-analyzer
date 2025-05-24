<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DuplicateTenderDetectorService;

class DetectTenderDuplicates extends Command
{
    protected $signature = 'tenders:detect-duplicates';
    protected $description = 'Detect duplicate tenders by comparing their content';

    public function handle()
    {
        $this->info("Scanning tenders for duplicates...");

        $service = new DuplicateTenderDetectorService();
        $service->detectDuplicates();

        $this->info("Duplicate detection complete.");
    }
}
