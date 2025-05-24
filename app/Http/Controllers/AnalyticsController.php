<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tender;

class AnalyticsController extends Controller
{
    public function index()
    {
        $tagsCount = Tender::all()
            ->flatMap(fn($t) => $t->tags ?? [])
            ->countBy()
            ->sortDesc();

        $dailyUploads = Tender::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $duplicateCount = Tender::where('is_duplicate', true)->count();

        return view('analytics.index', compact('tagsCount', 'dailyUploads', 'duplicateCount'));
    }
}

