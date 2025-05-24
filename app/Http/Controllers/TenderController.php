<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TenderMatched;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use Smalot\PdfParser\Parser;
use App\Models\Tag;


class TenderController extends Controller
{

    public function upload(Request $request)
    {
        if (!$request->hasFile('document')) {
            return redirect()->back()->with('error', 'No file selected');
        }

        $file = $request->file('document');
        $mime = $file->getMimeType();
        $path = $file->store('tenders');
        $textContent = null;

        // Extract text content based on file type
        try {
            if ($mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                $phpWord = IOFactory::load($file->getPathname(), 'Word2007');
                $text = '';

                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        // Extract text from Paragraph elements
                        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach ($element->getElements() as $subElement) {
                                if ($subElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text .= $subElement->getText() . "\n";
                                }
                            }
                        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }

                $textContent = $text;
            } elseif ($mime === 'application/pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile(storage_path('app/' . $path));
                $textContent = $pdf->getText();
            }
        } catch (\Exception $e) {
            Log::error("File parsing error: " . $e->getMessage());
        }

        // Save tender record
        $tender = Tender::create([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'text_content' => $textContent,
        ]);

        // Example static tags
        $tagNames = ['software', 'construction', 'furniture', 'maintenance', 'hardware', 'consulting'];
        $matchedTags = Tag::whereIn('name', $tagNames)->get();

        if ($matchedTags->isNotEmpty()) {
            // Attach tags to tender
            $tagIds = $matchedTags->pluck('id')->toArray();
            $tender->tags()->attach($tagIds);

            // Find users who have any of these tags
            $matchingUsers = User::whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })->get();

            foreach ($matchingUsers as $user) {
                $user->notify(new \App\Notifications\TenderMatched($tender));
            }
        }

        return redirect()->back()->with('success', 'File uploaded, tags processed, and notifications sent!');
    }



    public function index(Request $request)
    {
        $tagFilter = $request->input('tag');
        $query = Tender::query();

        if ($tagFilter) {
            $query->whereJsonContains('tags', $tagFilter);
        }

        $tenders = $query->latest()->paginate(10);

        $allTags = Tender::select('tags')->get()
            ->pluck('tags')->flatten()->unique()->sort()->values();

        return view('tenders.index', compact('tenders', 'allTags', 'tagFilter'));
    }

    public function download(Tender $tender)
    {
        $path = storage_path("app/private/{$tender->file_path}");
        if (!file_exists($path)) abort(404, 'File not found.');
        return response()->download($path, $tender->original_name);
    }

    public function editTags($id)
    {
        $tender = Tender::findOrFail($id);
        $currentTags = is_array($tender->tags) ? $tender->tags : [];
        $allTags = ['construction', 'furniture', 'maintenance', 'software', 'hardware', 'consulting'];
        return view('tenders.edit-tags', compact('tender', 'currentTags', 'allTags'));
    }

    public function updateTags(Request $request, Tender $tender)
    {
        $validated = $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'string'
        ]);

        $tender->tags = $validated['tags'] ?? [];
        $tender->save();

        return redirect()->route('tenders.index')->with('success', 'Tags updated!');
    }

    public function exportCsv()
    {
        $tenders = Tender::all();
        $filename = 'tenders_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Title', 'Description', 'Created At'];

        $callback = function () use ($tenders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tenders as $tender) {
                fputcsv($file, [
                    $tender->id,
                    $tender->title,
                    $tender->description,
                    $tender->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
