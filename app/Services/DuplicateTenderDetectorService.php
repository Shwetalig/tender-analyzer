<?php

namespace App\Services;

use App\Models\Tender;

class DuplicateTenderDetectorService
{
    /**
     * Check similarity between tenders and flag duplicates.
     */
    public function detectDuplicates()
    {
        $tenders = Tender::whereNotNull('text_content')->get();

        foreach ($tenders as $base) {
            foreach ($tenders as $compare) {
                if ($base->id === $compare->id || $compare->is_duplicate) {
                    continue;
                }

                similar_text($base->text_content, $compare->text_content, $percent);

                if ($percent > 90) { // You can adjust this threshold
                    $compare->is_duplicate = true;
                    $compare->duplicate_of_id = $base->id;
                    $compare->save();
                }
            }
        }
    }
}
