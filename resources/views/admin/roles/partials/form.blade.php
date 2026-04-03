@php
    $selectedPermissions = old('permissions', $role?->permissions->pluck('name')->all() ?? []);
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role?->name) }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 focus:border-brand-500 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Permissions</label>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($permissions as $permission)
                    <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, $selectedPermissions, true))>
                        <span>{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('permissions')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            @error('permissions.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Save Role
            </button>
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a>
        </div>
    </form>
</div>
