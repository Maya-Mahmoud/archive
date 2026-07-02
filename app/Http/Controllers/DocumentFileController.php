<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentFileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:files.upload', only: ['store']),
            new Middleware('permission:files.download', only: ['download']),
            new Middleware('permission:files.delete', only: ['destroy']),
        ];
    }

    public function store(Request $request, Document $document): JsonResponse|RedirectResponse
    {
        $maxKb = ((int) Setting::get('max_file_size_mb', 20)) * 1024;

        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', "max:{$maxKb}", 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx'],
        ], [], [
            'files' => 'files',
            'files.*' => 'file',
        ]);

        foreach ($request->file('files') as $uploaded) {
            DocumentFile::storeUploaded($document, $uploaded);
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Files uploaded successfully.');
    }

    public function download(DocumentFile $file): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($file->file_path), 404);

        return Storage::disk('local')->download($file->file_path, $file->original_name);
    }

    public function destroy(Request $request, DocumentFile $file): JsonResponse|RedirectResponse
    {
        Storage::disk('local')->delete($file->file_path);
        $file->delete();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'File deleted.');
    }
}
