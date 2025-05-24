<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tender;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tagFilter = $request->input('tag');

        $query = Tender::query();

        if ($tagFilter) {
            $query->whereJsonContains('tags', $tagFilter);
        }

        $tenders = $query->latest()->get();

        $allTags = Tender::select('tags')->get()
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('dashboard', [
    'tenders' => $tenders,
    'allTags' => $allTags,
]);

    }
}

