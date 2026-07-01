<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentFile extends Model
{
    protected $fillable = [
        'document_id',
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'extension',
        'size',
        'version',
        'is_current',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'size' => 'integer',
            'version' => 'integer',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public static function storeUploaded(Document $document, UploadedFile $uploaded): self
    {
        $extension = $uploaded->getClientOriginalExtension();
        $storedName = (string) Str::uuid().'.'.$extension;
        $path = $uploaded->storeAs("documents/{$document->id}", $storedName, 'local');

        return $document->files()->create([
            'original_name' => $uploaded->getClientOriginalName(),
            'file_name' => $storedName,
            'file_path' => $path,
            'mime_type' => $uploaded->getClientMimeType(),
            'extension' => $extension,
            'size' => $uploaded->getSize(),
            'version' => 1,
            'is_current' => true,
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
