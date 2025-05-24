<?php

namespace App\Services;

class TagSuggesterService
{
    public function getTagMap(): array
    {
        return [
            'Construction' => ['bridge', 'road', 'highway', 'cement', 'contractor'],
            'Furniture'    => ['chair', 'table', 'office', 'sofa', 'cabinet'],
            'Electrical'   => ['wiring', 'transformer', 'voltage', 'electricity'],
            'IT Services'  => ['software', 'hardware', 'server', 'network'],
            'Consultancy'  => ['consultant', 'strategy', 'proposal', 'advisory'],
        ];
    }

    public function suggestTags(string $text): array
    {
        $tags = [];
        $map = $this->getTagMap();

        foreach ($map as $tag => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains(strtolower($text), strtolower($keyword))) {
                    $tags[] = $tag;
                    break; // avoid duplicate tag
                }
            }
        }

        return array_unique($tags);
    }
}
