<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $request->user()->hasAnyRole(['admin', 'manager'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'You do not have access to the admin panel.'])
                ->onlyInput('email');
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function profile(Request $request): View
    {
        return view('admin.auth.profile', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'profile_photo' => ['nullable', 'image', 'max:4096'],
            'cropped_profile_photo' => ['nullable', 'string'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (! empty($validated['cropped_profile_photo'])) {
            $this->replaceProfilePhotoFromBase64($user, $validated['cropped_profile_photo']);
        } elseif ($request->hasFile('profile_photo')) {
            $this->deleteExistingProfilePhoto($user);

            $directory = public_path('uploads/users/profile');
            File::ensureDirectoryExists($directory);

            $extension = $request->file('profile_photo')->getClientOriginalExtension() ?: 'jpg';
            $filename = (string) Str::uuid() . '.' . strtolower($extension);

            $request->file('profile_photo')->move($directory, $filename);

            $user->profile_photo_path = 'uploads/users/profile/' . $filename;
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function profilePhoto(User $user)
    {
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            return response()->file(Storage::disk('public')->path($user->profile_photo_path));
        }

        return response()->file(public_path('images/admin/src/images/user/owner.jpg'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function replaceProfilePhotoFromBase64($user, string $payload): void
    {
        if (! preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $payload, $matches)) {
            return;
        }

        $data = substr($payload, strpos($payload, ',') + 1);
        $binary = base64_decode($data, true);

        if ($binary === false) {
            return;
        }

        $this->deleteExistingProfilePhoto($user);

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $directory = public_path('uploads/users/profile');
        File::ensureDirectoryExists($directory);

        $filename = (string) Str::uuid() . '.' . $extension;
        $path = 'uploads/users/profile/' . $filename;

        file_put_contents($directory . DIRECTORY_SEPARATOR . $filename, $binary);
        $user->profile_photo_path = $path;
    }

    protected function deleteExistingProfilePhoto($user): void
    {
        if (! $user->profile_photo_path) {
            return;
        }

        $publicFile = public_path($user->profile_photo_path);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        }

        if (Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
    }
}
