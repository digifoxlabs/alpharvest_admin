@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">My Profile</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update your password and profile picture.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[360px_minmax(0,1fr)]">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="text-center">
                <div class="mx-auto h-32 w-32 overflow-hidden rounded-full border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                    <img id="avatarPreview" src="{{ $user->profile_photo_url ?: asset('images/admin/src/images/user/owner.jpg') }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800 dark:text-white/90">{{ $user->name }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            </div>

            <div class="mt-6 rounded-xl bg-gray-50 p-4 dark:bg-gray-900/50">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Photo preview</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Choose an image, crop it, and save to update the avatar shown in the admin header.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Profile Picture</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new profile picture and crop it before saving.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Choose Image</label>
                    <input id="profilePhotoInput" type="file" name="profile_photo" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <input type="hidden" name="cropped_profile_photo" id="croppedProfilePhoto">
                    @error('profile_photo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('cropped_profile_photo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="cropSection" class="hidden space-y-4 rounded-2xl border border-dashed border-gray-300 p-4 dark:border-gray-700">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_180px]">
                        <div class="overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-900">
                            <img id="cropImage" alt="Crop preview" class="max-h-[420px] w-full object-contain">
                        </div>
                        <div>
                            <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Cropped Preview</p>
                            <div class="mx-auto h-36 w-36 overflow-hidden rounded-full border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800">
                                <div id="cropPreview" class="h-full w-full overflow-hidden rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Change Password</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave these fields blank if you do not want to change your password.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                        <input type="password" name="current_password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @error('current_password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div></div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.getElementById('profilePhotoInput');
    const cropSection = document.getElementById('cropSection');
    const cropImage = document.getElementById('cropImage');
    const cropPreview = document.getElementById('cropPreview');
    const avatarPreview = document.getElementById('avatarPreview');
    const hiddenInput = document.getElementById('croppedProfilePhoto');
    const form = document.getElementById('profileForm');
    let cropper = null;

    function updatePreview() {
        if (!cropper) {
            return;
        }

        const canvas = cropper.getCroppedCanvas({
            width: 320,
            height: 320,
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
        previewImage.alt = 'Cropped profile preview';
        previewImage.className = 'h-full w-full object-cover';
        cropPreview.appendChild(previewImage);
        avatarPreview.src = dataUrl;
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
                preview: '#cropPreview',
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
