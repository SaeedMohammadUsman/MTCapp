<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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


    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        // Log the start of the method

        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            return redirect()->route('profile.edit')->with('error', 'User  not found');
        }

        // Validate the input
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:3',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.edit')->with('error', 'The current password is incorrect.');
        }

        if ($request->password !== $request->password_confirmation) {
            return redirect()->route('profile.edit')->with('error', 'The password confirmation does not match.');
        }
        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();


        return redirect()->route('profile.edit')->with('success', 'Your password has been updated successfully.');
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
}
