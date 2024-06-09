<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController
{
    public function showUserSettings()
    {
        return Inertia::render('UserSettings');
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::id());

        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->route('account')->withErrors(['currentPassword' => 'Current password is incorrect']);
        }

        if ($request->currentPassword === $request->password) {
            return redirect()->route('account')->withErrors(['password' => 'New password cannot be the same as the current password']);
        }

        $request->validate([
            'currentPassword' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);
        
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('account');
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
        ]);

        $user = User::find(Auth::id());
        $user->username = $request->username;
        $user->save();

        return redirect()->route('account');
    }
}
