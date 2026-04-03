<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        @if ($method !== 'POST') @method($method) @endif
        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label><input type="text" name="name" value="{{ old('name', $category?->name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">@error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label><input type="text" name="slug" value="{{ old('slug', $category?->slug) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">@error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <label class="mt-8 flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true))> Active category</label>
        </div>
        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label><textarea name="description" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('description', $category?->description) }}</textarea></div>
        <div class="flex items-center gap-3"><button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Category</button><a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a></div>
    </form>
</div>
