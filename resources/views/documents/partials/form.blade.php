@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $document->title ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Document title">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
        <input type="text" name="document_number" value="{{ old('document_number', $document->document_number ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. DOC-2026-001">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <input type="text" name="category" value="{{ old('category', $document->category ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. Contracts">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Document Date</label>
        <input type="date" name="document_date"
               value="{{ old('document_date', isset($document->document_date) ? $document->document_date->format('Y-m-d') : '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm">
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm"
              placeholder="Notes or description">{{ old('description', $document->description ?? '') }}</textarea>
</div>

<div class="flex items-center gap-3">
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Save</button>
    <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
</div>
