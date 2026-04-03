<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Permission Name</label>
            <input type="text" name="name" value="{{ old('name', $permission?->name) }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 focus:border-brand-500 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                placeholder="Example: create users">
            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Save Permission
            </button>
            <a href="{{ route('admin.permissions.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a>
        </div>
    </form>
</div>
