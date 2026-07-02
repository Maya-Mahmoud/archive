<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">General Settings</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name <span class="text-red-500">*</span></label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $settings['organization_name']) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max File Size (MB) <span class="text-red-500">*</span></label>
                            <input type="number" name="max_file_size_mb" value="{{ old('max_file_size_mb', $settings['max_file_size_mb']) }}"
                                   min="1" max="1024" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Items Per Page <span class="text-red-500">*</span></label>
                            <input type="number" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page']) }}"
                                   min="5" max="100" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Optional">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
