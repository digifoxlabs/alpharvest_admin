<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if ($method !== 'POST') @method($method) @endif
        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label><select name="product_category_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"><option value="">Select Category</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('product_category_id', $product?->product_category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select>@error('product_category_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label><input type="text" name="name" value="{{ old('name', $product?->name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">@error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label><input type="text" name="slug" value="{{ old('slug', $product?->slug) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label><input type="text" name="sku" value="{{ old('sku', $product?->sku) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">@error('sku')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Retailer ID</label><input type="text" name="meta_retailer_id" value="{{ old('meta_retailer_id', $product?->meta_retailer_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>
        <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label><textarea name="description" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('description', $product?->description) }}</textarea></div>
        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label><input type="text" name="color" value="{{ old('color', $product?->color) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Size</label><input type="text" name="size" value="{{ old('size', $product?->size) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Weight</label><input type="number" step="0.01" name="shipping_weight" value="{{ old('shipping_weight', $product?->shipping_weight) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label><input type="number" step="0.01" name="price" value="{{ old('price', $product?->price) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sale Price</label><input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Inventory</label><input type="number" name="inventory_quantity" value="{{ old('inventory_quantity', $product?->inventory_quantity ?? 0) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></div>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $product ? 'Replace Product Image' : 'Product Image' }}</label>
            <input id="productImageInput" type="file" name="product_image" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <input type="hidden" name="cropped_product_image" id="croppedProductImage">
            @error('product_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            @error('cropped_product_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid gap-5 lg:grid-cols-[220px_minmax(0,1fr)]">
            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Product Image Preview</p>
                <div class="h-40 w-40 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                    <img id="productImagePreview" src="{{ old('cropped_product_image') ?: ($product?->image_url ?: asset('images/admin/src/images/user/owner.jpg')) }}" alt="{{ $product?->name ?: 'Product image preview' }}" class="h-full w-full object-cover">
                </div>
                @if ($product?->image_url)
                    <label class="mt-4 inline-flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="remove_product_image" value="1" @checked(old('remove_product_image'))>
                        Remove current product image
                    </label>
                @endif
            </div>
            <div id="productCropSection" class="hidden rounded-2xl border border-dashed border-gray-300 p-4 dark:border-gray-700">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_180px]">
                    <div class="overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-900">
                        <img id="productCropImage" alt="Crop product image" class="max-h-[420px] w-full object-contain">
                    </div>
                    <div>
                        <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Cropped Preview</p>
                        <div class="h-36 w-36 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                            <div id="productCropPreview" class="h-full w-full overflow-hidden rounded-xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured ?? false))> Featured product</label>
        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))> Active product</label>
        <div class="flex items-center gap-3"><button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Product</button><a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a></div>
    </form>
</div>

@push('scripts')
<script>
(() => {
    const input = document.getElementById('productImageInput');
    const cropSection = document.getElementById('productCropSection');
    const cropImage = document.getElementById('productCropImage');
    const cropPreview = document.getElementById('productCropPreview');
    const imagePreview = document.getElementById('productImagePreview');
    const hiddenInput = document.getElementById('croppedProductImage');
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
        previewImage.alt = 'Cropped product image preview';
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
                preview: '#productCropPreview',
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
