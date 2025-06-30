<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Delivery;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('profile_photo');

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->fill($data);
        $user->save();

        // Update sender_name in user's deliveries if name changed
        if ($user->wasChanged('name')) {
            Delivery::where('user_id', $user->id)
                ->update(['sender_name' => $user->name]);
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = $request->password;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Kata sandi berhasil diubah!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Delete profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dihapus!');
    }
}
