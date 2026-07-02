<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="dash-welcome">Welcome,<br>{{ Auth::user()->name }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                @isset($stats['documents'])
                    <x-stat-card icon="document" label="Documents" :value="$stats['documents']" color="purple" />
                @endisset
                @isset($stats['files'])
                    <x-stat-card icon="paperclip" label="Files" :value="$stats['files']" color="purple" />
                @endisset
                @isset($stats['storage'])
                    <x-stat-card  icon="database" label="Storage Used" :value="$stats['storage']" color="purple" />
                @endisset
                @isset($stats['my_documents'])
                    <x-stat-card  icon="folder" label="My Documents" :value="$stats['my_documents']" color="purple" />
                @endisset
                @isset($stats['users'])
                    <x-stat-card icon="users" label="Users" :value="$stats['users']" color="purple" />
                @endisset
                @isset($stats['roles'])
                    <x-stat-card icon="shield" label="Roles" :value="$stats['roles']" color="purple" />
                @endisset
            </div>

            @php
                $canCreateDoc = Auth::user()->hasPermission('documents.create');
                $canManageUsers = Auth::user()->hasPermission('users.manage');
                $canManageRoles = Auth::user()->hasPermission('roles.manage');
                $canViewDocs = Auth::user()->hasPermission('documents.view');

                $total = $categoryBreakdown->sum('total') ?: 1;
                $sliceColors = ['#7c3aed', '#a855f7', '#c084fc', '#8b5cf6', '#6d28d9', '#d8b4fe'];
                $acc = 0;
                $stops = [];
                foreach ($categoryBreakdown as $i => $cat) {
                    $start = round($acc / $total * 100, 2);
                    $acc += $cat->total;
                    $end = round($acc / $total * 100, 2);
                    $color = $sliceColors[$i % count($sliceColors)];
                    $stops[] = "{$color} {$start}% {$end}%";
                }
                $pieGradient = count($stops) ? 'conic-gradient(' . implode(', ', $stops) . ')' : '#e2e8f0';
            @endphp

            @if ($canCreateDoc || $canManageUsers || $canManageRoles || $canViewDocs)
                <div class="flex flex-wrap gap-3 mb-8">
                    @if ($canCreateDoc)
                        <a href="{{ route('documents.index') }}" class="app-btn">+ Add Document</a>
                    @elseif ($canViewDocs)
                        <a href="{{ route('documents.index') }}" class="app-btn">Browse Documents</a>
                    @endif
                    @if ($canManageUsers)
                        <a href="{{ route('users.index') }}" class="app-btn-outline">Manage Users</a>
                    @endif
                    @if ($canManageRoles)
                        <a href="{{ route('roles.index') }}" class="app-btn-outline">Manage Roles</a>
                    @endif
                </div>
            @endif

            @if ($canViewDocs)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm rounded-xl table-responsive">
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
                                            <a href="{{ route('documents.index') }}" class="text-purple-600 hover:underline">{{ $document->title }}</a>
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
                        @if ($categoryBreakdown->count())
                            <div class="pie" style="background: {{ $pieGradient }};"></div>
                            <div class="pie-legend">
                                @foreach ($categoryBreakdown as $i => $cat)
                                    <div class="pie-legend__item">
                                        <span class="pie-legend__dot" style="background: {{ $sliceColors[$i % count($sliceColors)] }};"></span>
                                        <span>{{ $cat->name }}</span>
                                        <span class="pie-legend__count">{{ $cat->total }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">No data yet.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
