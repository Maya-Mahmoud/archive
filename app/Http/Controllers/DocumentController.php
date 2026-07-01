<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:documents.create', only: ['create', 'store']),
            new Middleware('permission:documents.edit', only: ['edit', 'update']),
            new Middleware('permission:documents.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $category = $request->input('category');

        $documents = Document::query()
            ->with('creator')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Document::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('documents.index', compact('documents', 'categories', 'search', 'category'));
    }

    public function create(): View
    {
        return view('documents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDocument($request);

        $document = Document::create([
            ...$validated,
            'status' => 'active',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document created successfully.');
    }

    public function show(Request $request, Document $document): View|JsonResponse
    {
        $document->load(['creator', 'editor']);

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $document->id,
                'title' => $document->title,
                'document_number' => $document->document_number,
                'category' => $document->category,
                'document_date' => $document->document_date?->format('Y-m-d'),
                'status' => $document->status,
                'description' => $document->description,
                'creator' => $document->creator?->name,
                'editor' => $document->editor?->name,
                'created_at' => $document->created_at?->format('Y-m-d H:i'),
                'updated_at' => $document->updated_at?->format('Y-m-d H:i'),
            ]);
        }

        return view('documents.show', compact('document'));
    }

    public function edit(Document $document): View
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $this->validateDocument($request, $document->id);

        $document->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted.');
    }

    private function validateDocument(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'document_number' => ['nullable', 'string', 'max:255', 'unique:documents,document_number'.($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
        ], [], [
            'title' => 'title',
            'document_number' => 'document number',
            'description' => 'description',
            'category' => 'category',
            'document_date' => 'document date',
        ]);
    }
}
