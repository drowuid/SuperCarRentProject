<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    $user->fill($request->validated());

    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $user->profile_photo_path = $path;
    }

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadPhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|max:2048',
    ]);

    $user = $request->user();

    // Store the image
    $path = $request->file('profile_photo')->store('profile_photos', 'public');

    // Delete old photo if exists
    if ($user->profile_photo && Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
        Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
    }

    // Update user profile
    $user->profile_photo = basename($path);
    $user->save();

    return redirect()->route('profile.edit')->with('status', 'profile-updated');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $request->user()->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('status', 'password-updated');
}

}
