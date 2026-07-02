<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 rounded-2xl bg-gradient-to-l from-gray-900 to-gray-700 p-6 text-white shadow-sm">
                <p class="text-gray-300 text-sm">Welcome back</p>
                <div class="flex items-center gap-3 mt-1">
                    <h3 class="text-2xl font-bold">{{ Auth::user()->name }}</h3>
                    @if (Auth::user()->role)
                        <span class="px-2.5 py-1 text-xs rounded-full bg-white/15 text-white">{{ Auth::user()->role->display_name }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                @isset($stats['documents'])
                    <x-stat-card icon="document" label="Documents" :value="$stats['documents']" color="blue" />
                @endisset
                @isset($stats['files'])
                    <x-stat-card icon="paperclip" label="Files" :value="$stats['files']" color="teal" />
                @endisset
                @isset($stats['storage'])
                    <x-stat-card icon="database" label="Storage Used" :value="$stats['storage']" color="amber" />
                @endisset
                @isset($stats['my_documents'])
                    <x-stat-card icon="folder" label="My Documents" :value="$stats['my_documents']" color="purple" />
                @endisset
                @isset($stats['users'])
                    <x-stat-card icon="users" label="Users" :value="$stats['users']" color="rose" />
                @endisset
                @isset($stats['roles'])
                    <x-stat-card icon="shield" label="Roles" :value="$stats['roles']" color="green" />
                @endisset
            </div>

            @php
                $canCreateDoc = Auth::user()->hasPermission('documents.create');
                $canManageUsers = Auth::user()->hasPermission('users.manage');
                $canManageRoles = Auth::user()->hasPermission('roles.manage');
                $canViewDocs = Auth::user()->hasPermission('documents.view');
                $maxCat = $categoryBreakdown->max('total') ?: 1;
            @endphp

            @if ($canCreateDoc || $canManageUsers || $canManageRoles || $canViewDocs)
                <div class="flex flex-wrap gap-3 mb-8">
                    @if ($canCreateDoc)
                        <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 text-sm shadow-sm">+ Add Document</a>
                    @elseif ($canViewDocs)
                        <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 text-sm shadow-sm">Browse Documents</a>
                    @endif
                    @if ($canManageUsers)
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Manage Users</a>
                    @endif
                    @if ($canManageRoles)
                        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Manage Roles</a>
                    @endif
                </div>
            @endif

            @if ($canViewDocs)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-700">Recent Documents</div>
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Title</th>
                                    <th class="px-6 py-3 font-medium">Category</th>
                                    <th class="px-6 py-3 font-medium">Created by</th>
                                    <th class="px-6 py-3 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($recentDocuments as $document)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium">
                                            <a href="{{ route('documents.index') }}" class="text-blue-600 hover:underline">{{ $document->title }}</a>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $document->category ?? '—' }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $document->creator?->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $document->created_at?->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">No documents yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-6">
                        <div class="font-semibold text-gray-700 mb-4">Documents by Category</div>
                        @forelse ($categoryBreakdown as $cat)
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700">{{ $cat->name }}</span>
                                    <span class="text-gray-500">{{ $cat->total }}</span>
                                </div>
                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ round(($cat->total / $maxCat) * 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm">No data yet.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
