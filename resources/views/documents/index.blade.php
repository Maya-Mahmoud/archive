<x-app-layout>
    <style>[x-cloak]{display:none!important}</style>

    <div class="py-10" x-data="documentsPage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="page-head">
                <h2 class="page-title">Documents</h2>
                @if (Auth::user()->hasPermission('documents.create'))
                    <button type="button" @click="openCreate()" class="app-btn">+ Add Document</button>
                @endif
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('documents.index') }}" class="flex flex-wrap gap-3 mb-6">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search documents..."
                       class="flex-1 min-w-64 border-gray-300 rounded-md shadow-sm">
                <select name="category" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="app-btn">Search</button>
                @if ($search || $category)
                    <a href="{{ route('documents.index') }}" class="app-btn-outline">Reset</a>
                @endif
            </form>

            <div class="content-card table-responsive">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Number</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Created by</th>
                            <th class="px-6 py-3">Files</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">
                                    <button type="button" @click="openView({{ $document->id }})" class="text-purple-600 hover:underline">
                                        {{ $document->title }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $document->document_number ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $document->category ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $document->document_date?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $document->creator?->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-gray-600">
                                        📎 {{ $document->files_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="openView({{ $document->id }})" class="text-gray-600 hover:underline">View</button>
                                        @if (Auth::user()->hasPermission('documents.edit'))
                                            <button type="button" @click="openEdit({{ $document->id }})" class="text-purple-600 hover:underline">Edit</button>
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
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">No documents found.</td>
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

                    @if (Auth::user()->hasPermission('files.upload'))
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Attach Files</label>
                            <input type="file" x-ref="formFiles" multiple class="file-input w-full text-sm text-gray-600">
                            <p class="text-xs text-gray-400 mt-1">PDF, images, Word or Excel — up to {{ \App\Models\Setting::get('max_file_size_mb', 20) }}MB each.</p>
                            <template x-if="fileError"><p class="text-red-500 text-sm mt-1" x-text="fileError"></p></template>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="submitting" class="app-btn">
                            <span x-text="submitting ? 'Saving...' : 'Save'"></span>
                        </button>
                        <button type="button" @click="showForm = false" class="app-btn-outline">Cancel</button>
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

                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-700">Attached Files</h4>
                            @if (Auth::user()->hasPermission('files.upload'))
                                <label class="app-btn" style="cursor:pointer">
                                    <span x-text="uploading ? 'Uploading...' : '+ Upload'"></span>
                                    <input type="file" class="hidden" multiple @change="uploadFiles($event)" :disabled="uploading">
                                </label>
                            @endif
                        </div>

                        <template x-if="uploadError">
                            <p class="text-red-500 text-sm mb-2" x-text="uploadError"></p>
                        </template>

                        <ul class="divide-y divide-gray-100">
                            <template x-for="file in (view.files || [])" :key="file.id">
                                <li class="flex items-center justify-between py-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span>📄</span>
                                        <span class="truncate" x-text="file.name"></span>
                                        <span class="text-gray-400 text-sm" x-text="'(' + file.size + ')'"></span>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        @if (Auth::user()->hasPermission('files.download'))
                                            <a :href="`${base}/files/${file.id}/download`" class="text-blue-600 hover:underline text-sm">Download</a>
                                        @endif
                                        @if (Auth::user()->hasPermission('files.delete'))
                                            <button type="button" @click="deleteFile(file.id)" class="text-red-600 hover:underline text-sm">Delete</button>
                                        @endif
                                    </div>
                                </li>
                            </template>
                            <template x-if="!view.files || view.files.length === 0">
                                <li class="py-2 text-gray-400 text-sm">No files attached yet.</li>
                            </template>
                        </ul>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        @if (Auth::user()->hasPermission('documents.edit'))
                            <button type="button" @click="editFromView()" class="app-btn">Edit</button>
                        @endif
                        <button type="button" @click="showView = false" class="app-btn-outline">Close</button>
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
                uploading: false,
                uploadError: '',
                errors: {},
                form: { id: null, title: '', document_number: '', category: '', document_date: '', description: '' },
                view: {},

                base: @json(url('documents')),
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                get fileError() {
                    const key = Object.keys(this.errors).find(k => k.startsWith('files'));
                    return key ? this.errors[key][0] : '';
                },

                clearFileInput() {
                    if (this.$refs.formFiles) this.$refs.formFiles.value = '';
                },

                resetForm() {
                    this.errors = {};
                    this.form = { id: null, title: '', document_number: '', category: '', document_date: '', description: '' };
                    this.clearFileInput();
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
                    this.uploadError = '';
                    this.view = await this.fetchDocument(id);
                    this.showView = true;
                },

                async uploadFiles(event) {
                    const files = event.target.files;
                    if (!files || files.length === 0) return;

                    this.uploading = true;
                    this.uploadError = '';

                    const body = new FormData();
                    body.append('_token', this.csrf);
                    for (const file of files) {
                        body.append('files[]', file);
                    }

                    try {
                        const res = await fetch(`${this.base}/${this.view.id}/files`, {
                            method: 'POST',
                            body,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });

                        if (res.status === 422) {
                            const data = await res.json();
                            this.uploadError = Object.values(data.errors ?? {}).flat()[0] ?? 'Invalid file.';
                        } else if (!res.ok) {
                            this.uploadError = 'Upload failed. Please try again.';
                        } else {
                            this.view = await this.fetchDocument(this.view.id);
                        }
                    } catch (e) {
                        this.uploadError = 'Network error. Please try again.';
                    }

                    event.target.value = '';
                    this.uploading = false;
                },

                async deleteFile(fileId) {
                    if (!confirm('Are you sure you want to delete this file?')) return;

                    const body = new FormData();
                    body.append('_token', this.csrf);
                    body.append('_method', 'DELETE');

                    try {
                        const res = await fetch(`${this.base}/files/${fileId}`, {
                            method: 'POST',
                            body,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (res.ok) {
                            this.view = await this.fetchDocument(this.view.id);
                        } else {
                            alert('Could not delete the file.');
                        }
                    } catch (e) {
                        alert('Network error. Please try again.');
                    }
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

                    const fi = this.$refs.formFiles;
                    if (fi && fi.files) {
                        for (const file of fi.files) body.append('files[]', file);
                    }

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