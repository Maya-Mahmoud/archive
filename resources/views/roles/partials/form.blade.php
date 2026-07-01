@php
    $selected = old('permissions', isset($role) ? ($role->permissions ?? []) : []);
    $isAll = in_array('*', $selected);
@endphp

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
        <label class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
        <input type="text" name="display_name" value="{{ old('display_name', $role->display_name ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. Archivist">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Identifier <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm" placeholder="editor">
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="2" class="w-full border-gray-300 rounded-md shadow-sm"
              placeholder="Short description of the role">{{ old('description', $role->description ?? '') }}</textarea>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>

    <label class="flex items-center gap-2 mb-4 p-3 bg-gray-50 rounded-md">
        <input type="checkbox" name="permissions[]" value="*" @checked($isAll) class="rounded">
        <span class="font-medium">Full access (everything)</span>
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($groups as $group)
            <div class="border border-gray-200 rounded-md p-4">
                <h4 class="font-semibold text-gray-700 mb-2">{{ $group['label'] }}</h4>
                @foreach ($group['permissions'] as $key => $label)
                    <label class="flex items-center gap-2 mb-1 text-sm">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}"
                               @checked(in_array($key, $selected)) class="rounded">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div class="flex items-center gap-3">
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Save</button>
    <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
</div>
