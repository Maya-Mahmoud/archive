<x-app-layout>
    <style>[x-cloak]{display:none!important}</style>

    <div class="py-10" x-data="rolesPage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="page-head">
                <h2 class="page-title">Roles &amp; Permissions</h2>
                <button type="button" @click="openCreate()" class="app-btn">+ Add Role</button>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="content-card table-responsive">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3">Role Name</th>
                            <th class="px-6 py-3">Identifier</th>
                            <th class="px-6 py-3">Permissions</th>
                            <th class="px-6 py-3">Users</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">{{ $role->display_name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $role->name }}</td>
                                <td class="px-6 py-4">
                                    {{ in_array('*', $role->permissions ?? []) ? 'All' : count($role->permissions ?? []) }}
                                </td>
                                <td class="px-6 py-4">{{ $role->users_count }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="openEdit({{ $role->id }})" class="text-purple-600 hover:underline">Edit</button>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No roles yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-cloak x-show="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showForm = false"></div>

            <div class="relative bg-white w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto" x-transition>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold" x-text="mode === 'create' ? 'Add New Role' : 'Edit Role'"></h3>
                    <button type="button" @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <form @submit.prevent="submit()" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.display_name" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. Archivist">
                            <template x-if="errors.display_name"><p class="text-red-500 text-sm mt-1" x-text="errors.display_name[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Identifier <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="editor">
                            <template x-if="errors.name"><p class="text-red-500 text-sm mt-1" x-text="errors.name[0]"></p></template>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="form.description" rows="2" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Short description of the role"></textarea>
                        <template x-if="errors.description"><p class="text-red-500 text-sm mt-1" x-text="errors.description[0]"></p></template>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>

                        <label class="flex items-center gap-2 mb-4 p-3 bg-gray-50 rounded-md">
                            <input type="checkbox" x-model="form.all" class="rounded">
                            <span class="font-medium">Full access (everything)</span>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" :class="form.all ? 'opacity-50 pointer-events-none' : ''">
                            @foreach ($groups as $group)
                                <div class="border border-gray-200 rounded-md p-4">
                                    <h4 class="font-semibold text-gray-700 mb-2">{{ $group['label'] }}</h4>
                                    @foreach ($group['permissions'] as $key => $label)
                                        <label class="flex items-center gap-2 mb-1 text-sm">
                                            <input type="checkbox" value="{{ $key }}" x-model="form.permissions" class="rounded">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="submitting" class="app-btn">
                            <span x-text="submitting ? 'Saving...' : 'Save'"></span>
                        </button>
                        <button type="button" @click="showForm = false" class="app-btn-outline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rolesPage', () => ({
                showForm: false,
                mode: 'create',
                submitting: false,
                errors: {},
                form: { id: null, name: '', display_name: '', description: '', permissions: [], all: false },

                base: @json(url('roles')),
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                resetForm() {
                    this.errors = {};
                    this.form = { id: null, name: '', display_name: '', description: '', permissions: [], all: false };
                },

                openCreate() {
                    this.mode = 'create';
                    this.resetForm();
                    this.showForm = true;
                },

                async openEdit(id) {
                    this.mode = 'edit';
                    this.resetForm();
                    const res = await fetch(`${this.base}/${id}/edit`, { headers: { 'Accept': 'application/json' } });
                    const d = await res.json();
                    const perms = d.permissions ?? [];
                    this.form = {
                        id: d.id,
                        name: d.name ?? '',
                        display_name: d.display_name ?? '',
                        description: d.description ?? '',
                        permissions: perms.filter(p => p !== '*'),
                        all: perms.includes('*'),
                    };
                    this.showForm = true;
                },

                async submit() {
                    this.submitting = true;
                    this.errors = {};

                    const url = this.mode === 'create' ? this.base : `${this.base}/${this.form.id}`;
                    const body = new FormData();
                    body.append('_token', this.csrf);
                    if (this.mode === 'edit') body.append('_method', 'PUT');
                    body.append('name', this.form.name ?? '');
                    body.append('display_name', this.form.display_name ?? '');
                    body.append('description', this.form.description ?? '');

                    const perms = this.form.all ? ['*'] : this.form.permissions;
                    perms.forEach(p => body.append('permissions[]', p));

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