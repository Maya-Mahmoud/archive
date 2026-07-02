<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </div>
    </x-slot>

    <style>[x-cloak]{display:none!important}</style>

    <div class="py-12" x-data="usersPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="flex justify-end mb-6">
                <button type="button" @click="openCreate()"
                        class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
                    + Add User
                </button>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->role?->display_name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="openEdit({{ $user->id }})" class="text-blue-600 hover:underline">Edit</button>
                                        @if ($user->id !== Auth::id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No users yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

        <div x-cloak x-show="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showForm = false"></div>

            <div class="relative bg-white w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto" x-transition>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold" x-text="mode === 'create' ? 'Add New User' : 'Edit User'"></h3>
                    <button type="button" @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <form @submit.prevent="submit()" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Full name">
                            <template x-if="errors.name"><p class="text-red-500 text-sm mt-1" x-text="errors.name[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" x-model="form.email" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="name@example.com">
                            <template x-if="errors.email"><p class="text-red-500 text-sm mt-1" x-text="errors.email[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500" x-show="mode === 'create'">*</span>
                                <span class="text-gray-400 text-xs" x-show="mode === 'edit'">(leave blank to keep current)</span>
                            </label>
                            <input type="password" x-model="form.password" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="••••••••">
                            <template x-if="errors.password"><p class="text-red-500 text-sm mt-1" x-text="errors.password[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select x-model="form.role_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">— No role —</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.role_id"><p class="text-red-500 text-sm mt-1" x-text="errors.role_id[0]"></p></template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" x-model="form.phone" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Optional">
                            <template x-if="errors.phone"><p class="text-red-500 text-sm mt-1" x-text="errors.phone[0]"></p></template>
                        </div>
                        <div class="flex items-center mt-6">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" x-model="form.is_active" class="rounded">
                                <span class="text-sm font-medium text-gray-700">Active account</span>
                            </label>
                        </div>
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
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('usersPage', () => ({
                showForm: false,
                mode: 'create',
                submitting: false,
                errors: {},
                form: { id: null, name: '', email: '', password: '', role_id: '', phone: '', is_active: true },

                base: @json(url('users')),
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                resetForm() {
                    this.errors = {};
                    this.form = { id: null, name: '', email: '', password: '', role_id: '', phone: '', is_active: true };
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
                    this.form = {
                        id: d.id,
                        name: d.name ?? '',
                        email: d.email ?? '',
                        password: '',
                        role_id: d.role_id ?? '',
                        phone: d.phone ?? '',
                        is_active: !!d.is_active,
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
                    body.append('email', this.form.email ?? '');
                    if (this.form.password) body.append('password', this.form.password);
                    body.append('role_id', this.form.role_id ?? '');
                    body.append('phone', this.form.phone ?? '');
                    body.append('is_active', this.form.is_active ? 1 : 0);

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
