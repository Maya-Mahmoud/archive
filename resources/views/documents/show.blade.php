<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $document->title }}</h2>
            <div class="flex items-center gap-3">
                @if (Auth::user()->hasPermission('documents.edit'))
                    <a href="{{ route('documents.edit', $document) }}"
                       class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">Edit</a>
                @endif
                <a href="{{ route('documents.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="divide-y divide-gray-100">
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Document Number</dt>
                        <dd class="flex-1">{{ $document->document_number ?? '—' }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Category</dt>
                        <dd class="flex-1">{{ $document->category ?? '—' }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Document Date</dt>
                        <dd class="flex-1">{{ $document->document_date?->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Status</dt>
                        <dd class="flex-1">{{ ucfirst($document->status) }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Description</dt>
                        <dd class="flex-1 whitespace-pre-line">{{ $document->description ?? '—' }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Created by</dt>
                        <dd class="flex-1">{{ $document->creator?->name ?? '—' }} · {{ $document->created_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-48 text-gray-500">Last updated by</dt>
                        <dd class="flex-1">{{ $document->editor?->name ?? '—' }} · {{ $document->updated_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
