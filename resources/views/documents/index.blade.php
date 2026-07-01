<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Documents</h2>
        </div>
    </x-slot>

    <style>[x-cloak]{display:none!important}</style>

    <div class="py-12" x-data="documentsPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
                <form method="GET" action="{{ route('documents.index') }}" class="flex flex-wrap gap-3 flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search documents..."
                           class="flex-1 min-w-64 border-gray-300 rounded-md shadow-sm">
                    <select name="category" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Search</button>
                    @if ($search || $category)
                        <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                    @endif
                </form>
                @if (Auth::user()->hasPermission('documents.create'))
                    <button type="button" @click="openCreate()"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 whitespace-nowrap">
                        + Add Document
                    </button>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Number</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Created by</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">
                                    <button type="button" @click="openView({{ $document->id }})" class="text-blue-600 hover:underline">
                                        {{ $document->title }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $document->document_number ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $document->category ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $document->document_date?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $document->creator?->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="openView({{ $document->id }})" class="text-gray-600 hover:underline">View</button>
                                        @if (Auth::user()->hasPermission('documents.edit'))
                                            <button type="button" @click="openEdit({{ $document->id }})" class="text-blue-600 hover:underline">Edit</button>
                                        @endif
                                        @if (Auth::user()->hasPermission('documents.delete'))
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">No documents found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        </div>

        <div x-cloak x-show="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition.opacity>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showForm = false"></div>

            <div class="relative bg-white w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto"
                 x-transition>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold" x-text="mode === 'create' ? 'Add New Document' : 'Edit Document'"></h3>
                    <button type="button" @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <form @submit.prevent="submit()" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.title" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Document title">
                            <template x-if="errors.title"><p class="text-red-500 text-sm mt-1" x-text="errors.title[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Number</label>
                            <input type="text" x-model="form.document_number" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. DOC-2026-001">
                            <template x-if="errors.document_number"><p class="text-red-500 text-sm mt-1" x-text="errors.document_number[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" x-model="form.category" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. Contracts">
                            <template x-if="errors.category"><p class="text-red-500 text-sm mt-1" x-text="errors.category[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Date</label>
                            <input type="date" x-model="form.document_date" class="w-full border-gray-300 rounded-md shadow-sm">
                            <template x-if="errors.document_date"><p class="text-red-500 text-sm mt-1" x-text="errors.document_date[0]"></p></template>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="form.description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Notes or description"></textarea>
                        <template x-if="errors.description"><p class="text-red-500 text-sm mt-1" x-text="errors.description[0]"></p></template>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="submitting"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 disabled:opacity-50">
                            <span x-text="submitting ? 'Saving...' : 'Save'"></span>
                        </button>
                        <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showView" class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition.opacity>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showView = false"></div>

            <div class="relative bg-white w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto"
                 x-transition>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold" x-text="view.title"></h3>
                    <button type="button" @click="showView = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <div class="p-6">
                    <dl class="divide-y divide-gray-100">
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Document Number</dt>
                            <dd class="flex-1" x-text="view.document_number || '—'"></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Category</dt>
                            <dd class="flex-1" x-text="view.category || '—'"></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Document Date</dt>
                            <dd class="flex-1" x-text="view.document_date || '—'"></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Status</dt>
                            <dd class="flex-1" x-text="view.status"></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Description</dt>
                            <dd class="flex-1 whitespace-pre-line" x-text="view.description || '—'"></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Created by</dt>
                            <dd class="flex-1"><span x-text="view.creator || '—'"></span> · <span x-text="view.created_at"></span></dd>
                        </div>
                        <div class="py-3 flex">
                            <dt class="w-48 text-gray-500">Last updated by</dt>
                            <dd class="flex-1"><span x-text="view.editor || '—'"></span> · <span x-text="view.updated_at"></span></dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex items-center gap-3">
                        @if (Auth::user()->hasPermission('documents.edit'))
                            <button type="button" @click="editFromView()" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Edit</button>
                        @endif
                        <button type="button" @click="showView = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('documentsPage', () => ({
                showForm: false,
                showView: false,
                mode: 'create',
                submitting: false,
                errors: {},
                form: { id: null, title: '', document_number: '', category: '', document_date: '', description: '' },
                view: {},

                base: @json(url('documents')),
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                resetForm() {
                    this.errors = {};
                    this.form = { id: null, title: '', document_number: '', category: '', document_date: '', description: '' };
                },

                openCreate() {
                    this.mode = 'create';
                    this.resetForm();
                    this.showForm = true;
                },

                async fetchDocument(id) {
                    const res = await fetch(`${this.base}/${id}`, { headers: { 'Accept': 'application/json' } });
                    return res.json();
                },

                async openEdit(id) {
                    this.mode = 'edit';
                    this.resetForm();
                    const d = await this.fetchDocument(id);
                    this.form = {
                        id: d.id,
                        title: d.title ?? '',
                        document_number: d.document_number ?? '',
                        category: d.category ?? '',
                        document_date: d.document_date ?? '',
                        description: d.description ?? '',
                    };
                    this.showForm = true;
                },

                async openView(id) {
                    this.view = await this.fetchDocument(id);
                    this.showView = true;
                },

                editFromView() {
                    this.showView = false;
                    this.openEdit(this.view.id);
                },

                async submit() {
                    this.submitting = true;
                    this.errors = {};

                    const url = this.mode === 'create' ? this.base : `${this.base}/${this.form.id}`;
                    const body = new FormData();
                    body.append('_token', this.csrf);
                    if (this.mode === 'edit') body.append('_method', 'PUT');
                    body.append('title', this.form.title ?? '');
                    body.append('document_number', this.form.document_number ?? '');
                    body.append('category', this.form.category ?? '');
                    body.append('document_date', this.form.document_date ?? '');
                    body.append('description', this.form.description ?? '');

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            body,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });

                        if (res.status === 422) {
                            const data = await res.json();
                            this.errors = data.errors ?? {};
                            this.submitting = false;
                            return;
                        }

                        if (!res.ok) {
                            alert('Something went wrong. Please try again.');
                            this.submitting = false;
                            return;
                        }

                        window.location.href = this.base;
                    } catch (e) {
                        alert('Network error. Please try again.');
                        this.submitting = false;
                    }
                },
            }));
        });
    </script>
</x-app-layout>
