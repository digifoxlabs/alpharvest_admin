<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $store ? 'Replace Store Image' : 'Store Image' }}</label>
                <input id="storeImageInput" type="file" name="whatsapp_store_image" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <input type="hidden" name="cropped_whatsapp_store_image" id="croppedStoreImage">
                @error('whatsapp_store_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('cropped_whatsapp_store_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Zones</label>
            <textarea name="delivery_zones_text" rows="4" placeholder="700001 | Kolkata&#10;700002 | Howrah" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('delivery_zones_text', $deliveryZonesText ?? '') }}</textarea>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">One area per line using <code>pincode | city</code>.</p>
            @error('delivery_zones_text')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Undeliverable Message</label>
            <textarea name="undeliverable_message" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('undeliverable_message', $undeliverableMessage ?? '') }}</textarea>
            @error('undeliverable_message')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        @php($orderNumbering = array_merge(['prefix' => strtoupper($store?->slug ?? 'ORD'), 'start_from' => 1, 'digits' => 5, 'type' => 'sequential'], (array) data_get($store?->settings, 'order_numbering', [])))
        <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Order Numbering</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure how `StoreEngineService` generates the next order number for this store.</p>

            <div class="mt-4 grid gap-5 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Prefix</label>
                    <input type="text" name="order_number_prefix" value="{{ old('order_number_prefix', $orderNumbering['prefix']) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @error('order_number_prefix')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Start From</label>
                    <input type="number" min="1" name="order_number_start_from" value="{{ old('order_number_start_from', $orderNumbering['start_from']) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @error('order_number_start_from')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Digits</label>
                    <input type="number" min="1" max="10" name="order_number_digits" value="{{ old('order_number_digits', $orderNumbering['digits']) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @error('order_number_digits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Numbering Type</label>
                    <select name="order_number_type" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="sequential" @selected(old('order_number_type', $orderNumbering['type']) === 'sequential')>Sequential</option>
                        <option value="random" @selected(old('order_number_type', $orderNumbering['type']) === 'random')>Random</option>
                    </select>
                    @error('order_number_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-[220px_minmax(0,1fr)]">
            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Store Image Preview</p>
                <div class="h-40 w-40 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                    <img id="storeImagePreview" src="{{ old('cropped_whatsapp_store_image') ?: ($store?->whatsapp_store_image_url ?: asset('images/admin/src/images/user/owner.jpg')) }}" alt="{{ $store?->name ?: 'Store image preview' }}" class="h-full w-full object-cover">
                </div>
                @if ($store?->whatsapp_store_image_url)
                    <label class="mt-4 inline-flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="remove_whatsapp_store_image" value="1" @checked(old('remove_whatsapp_store_image'))>
                        Remove current store image
                    </label>
                @endif
            </div>

            <div id="storeCropSection" class="hidden rounded-2xl border border-dashed border-gray-300 p-4 dark:border-gray-700">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_180px]">
                    <div class="overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-900">
                        <img id="storeCropImage" alt="Crop store image" class="max-h-[420px] w-full object-contain">
                    </div>
                    <div>
                        <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Cropped Preview</p>
                        <div class="h-36 w-36 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                            <div id="storeCropPreview" class="h-full w-full overflow-hidden rounded-xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $store?->is_active ?? true))> Active store</label>

        <div class="flex items-center gap-3"><button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Store</button><a href="{{ route('admin.stores.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a></div>
    </form>
</div>

@push('scripts')
<script>
(() => {
    const input = document.getElementById('storeImageInput');
    const cropSection = document.getElementById('storeCropSection');
    const cropImage = document.getElementById('storeCropImage');
    const cropPreview = document.getElementById('storeCropPreview');
    const imagePreview = document.getElementById('storeImagePreview');
    const hiddenInput = document.getElementById('croppedStoreImage');
    const form = input?.form;
    let cropper = null;

    function updatePreview() {
        if (!cropper) {
            return;
        }

        const canvas = cropper.getCroppedCanvas({
            width: 600,
            height: 600,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            return;
        }

        const dataUrl = canvas.toDataURL('image/png');
        hiddenInput.value = dataUrl;
        cropPreview.innerHTML = '';

        const previewImage = document.createElement('img');
        previewImage.src = dataUrl;
        previewImage.alt = 'Cropped store image preview';
        previewImage.className = 'h-full w-full object-cover';
        cropPreview.appendChild(previewImage);
        imagePreview.src = dataUrl;
    }

    input?.addEventListener('change', (event) => {
        const [file] = event.target.files || [];

        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = (loadEvent) => {
            cropSection.classList.remove('hidden');
            cropImage.src = loadEvent.target?.result;

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                preview: '#storeCropPreview',
                autoCropArea: 1,
                responsive: true,
                cropend: updatePreview,
                ready: updatePreview,
                zoom: updatePreview,
                crop: updatePreview,
            });
        };

        reader.readAsDataURL(file);
    });

    form?.addEventListener('submit', () => {
        if (cropper) {
            updatePreview();
        }
    });
})();
</script>
@endpush
