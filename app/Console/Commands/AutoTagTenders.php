<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use App\Services\TagSuggesterService;

class AutoTagTenders extends Command
{
    protected $signature = 'tenders:auto-tag';
    protected $description = 'Auto-tag tenders using extracted text content';

    public function handle()
    {
        $tagger = new TagSuggesterService();

        $tenders = Tender::whereNull('tags')->orWhere('tags', '[]')->get();

        if ($tenders->isEmpty()) {
            $this->info('No tenders found that need tagging.');
            return;
        }

        foreach ($tenders as $tender) {
            $text = $tender->text_content;
            if (!$text) {
                $this->warn("Tender ID {$tender->id} has no text content. Skipping.");
                continue;
            }

            $tags = $tagger->suggestTags($text);
            $tender->tags = $tags;
            $tender->save();

            $this->info("Tagged Tender ID {$tender->id}: " . implode(', ', $tags));
        }

        $this->info('Done tagging!');
    }
}
