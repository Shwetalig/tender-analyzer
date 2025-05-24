<?php

namespace App\Services;

class TenderSummaryService
{
    public function generate(string $text): string
    {
        // Remove extra whitespace
        $cleaned = preg_replace('/\s+/', ' ', strip_tags($text));

        // Split into sentences
        $sentences = preg_split('/(?<=[.?!])\s+/', $cleaned, -1, PREG_SPLIT_NO_EMPTY);

        // Return first 2 sentences or ~300 characters
        $summary = implode(' ', array_slice($sentences, 0, 2));

        return strlen($summary) > 10 ? $summary : substr($cleaned, 0, 300) . '...';
    }
}
