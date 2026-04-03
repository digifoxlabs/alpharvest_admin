<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" value="{{ old('name', $store?->name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $store?->slug) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Support Phone</label><input type="text" name="support_phone" value="{{ old('support_phone', $store?->support_phone) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label><input type="email" name="contact_email" value="{{ old('contact_email', $store?->contact_email) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Phone</label><input type="text" name="contact_phone" value="{{ old('contact_phone', $store?->contact_phone) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label><textarea name="description" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('description', $store?->description) }}</textarea></div>

        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label><input type="text" name="currency" value="{{ old('currency', $store?->currency ?? 'INR') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number ID</label><input type="text" name="whatsapp_phone_number_id" value="{{ old('whatsapp_phone_number_id', $store?->whatsapp_phone_number_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Business Account ID</label><input type="text" name="whatsapp_business_account_id" value="{{ old('whatsapp_business_account_id', $store?->whatsapp_business_account_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Catalog ID</label><input type="text" name="meta_catalog_id" value="{{ old('meta_catalog_id', $store?->meta_catalog_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Brand Name</label><input type="text" name="whatsapp_brand_name" value="{{ old('whatsapp_brand_name', $store?->whatsapp_brand_name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Access Token</label><textarea name="meta_access_token" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('meta_access_token', $store?->meta_access_token) }}</textarea></div>

        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Welcome Text</label><textarea name="whatsapp_welcome_text" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('whatsapp_welcome_text', $store?->whatsapp_welcome_text) }}</textarea></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Store Intro</label><textarea name="whatsapp_store_intro" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('whatsapp_store_intro', $store?->whatsapp_store_intro) }}</textarea></div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Text</label><textarea name="whatsapp_contact_text" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('whatsapp_contact_text', $store?->whatsapp_contact_text) }}</textarea></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Store Image Path</label><input type="text" name="whatsapp_store_image_path" value="{{ old('whatsapp_store_image_path', $store?->whatsapp_store_image_path) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>

        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Settings JSON</label><textarea name="settings" rows="5" class="w-full rounded-xl border border-gray-200 px-4 py-3 font-mono text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('settings', isset($store) && $store?->settings ? json_encode($store->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>@error('settings')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>

        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $store?->is_active ?? true))> Active store</label>

        <div class="flex items-center gap-3"><button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Store</button><a href="{{ route('admin.stores.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a></div>
    </form>
</div>
