<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Document: {{ $document->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('documents.update', $document) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('documents.partials.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
