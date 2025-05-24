<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use PhpOffice\PhpWord\IOFactory;

class ExtractDocxText extends Command
{
    protected $signature = 'tenders:extract-docx-text';
    protected $description = 'Extract text from DOCX files where text_content is null';

    public function handle()
    {
        $tenders = Tender::whereNull('text_content')->get();
        $this->info("Found {$tenders->count()} tenders to process...");

        foreach ($tenders as $tender) {
            $path = storage_path('app/private/' . $tender->file_path);
            if (!file_exists($path)) {
                $this->warn("File not found: {$path}");
                continue;
            }

            $mime = mime_content_type($path);
if (!str_ends_with(strtolower($tender->original_name), '.docx')) {
    $this->warn("Skipping non-DOCX file: {$tender->original_name}");
    continue;
}



            try {
                $phpWord = IOFactory::load($path, 'Word2007');
                $text = '';

                foreach ($phpWord->getSections() as $section) {
                    $elements = $section->getElements();
                    foreach ($elements as $element) {
                        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach ($element->getElements() as $child) {
                                if (method_exists($child, 'getText')) {
                                    $text .= $child->getText() . ' ';
                                }
                            }
                            $text .= "\n";
                        }

                        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }

                $tender->text_content = $text;
                $tender->save();
                $this->info("Extracted: {$tender->original_name}");
            } catch (\Exception $e) {
                $this->error("Failed to extract {$tender->original_name}: " . $e->getMessage());
            }
        }

        $this->info('Done!');
    }
}
