<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use Smalot\PdfParser\Parser;

class ExtractPdfText extends Command
{
    protected $signature = 'tenders:extract-pdf-text';
    protected $description = 'Extract text from PDF files where text_content is null';

    public function handle()
    {
        $tenders = Tender::whereNull('text_content')->get();
        $this->info("Found {$tenders->count()} tenders to process...");

        foreach ($tenders as $tender) {
            $path = storage_path('app/private/' . $tender->file_path);

            if (!file_exists($path)) {
                $this->warn("❌ File not found: {$path}");
                continue;
            }

            $mime = mime_content_type($path);
            if ($mime !== 'application/pdf') {
                $this->warn("⏭️ Skipping non-PDF file: {$tender->original_name}");
                continue;
            }

            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();

                $tender->text_content = $text;
                $tender->save();
                $this->info("✅ Extracted: {$tender->original_name}");
            } catch (\Exception $e) {
                $this->error("❌ Failed: {$tender->original_name} - " . $e->getMessage());
            }
        }

        $this->info('🎉 Done extracting all PDFs!');
    }
}
