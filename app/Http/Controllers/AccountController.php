<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController
{
    // Displays the user settings page
    public function showUserSettings()
    {
        return Inertia::render('UserSettings');
    }

    // Updates the user's password
    public function updatePassword(Request $request)
    {
        // Store the user in a variable
        $user = User::find(Auth::id());

        // Check if the current password is correct
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->route('account')->withErrors(['currentPassword' => 'Current password is incorrect']);
        }

        // Check if the new password is the same as the current password
        if ($request->currentPassword === $request->password) {
            return redirect()->route('account')->withErrors(['password' => 'New password cannot be the same as the current password']);
        }

        // Validate the request
        $request->validate([
            'currentPassword' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);
        
        // Update the user's password
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('account');
    }

    // Updates the user's username
    public function updateUsername(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
        ]);

        // Store the user in a variable
        $user = User::find(Auth::id());
        
        // Update the user's username
        $user->username = $request->username;
        $user->save();

        return redirect()->route('account');
    }
}
