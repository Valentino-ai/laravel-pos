<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditProfileController extends Controller
{
    // Show the edit profile page
    public function edit(Request $request)
    {
        // Return the user data and session status for the edit form
        return response()->json([
            'user' => $request->user(),
            'status' => session('status')
        ], 200);
    }

    // Update user profile information
    public function updateInfo(Request $request)
    {
        // Validate input fields for updating profile info
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($request->user()->id)
            ]
        ]);

        // Fill user model with validated data
        $user = $request->user();
        $user->fill($fields);

        // Set email_verified_at to null if the email is changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save changes to user profile
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile information updated successfully',
            'data' => $user
        ], 200);
    }

    // Update user password
    public function updatePassword(Request $request)
    {
        // Validate password fields
        $fields = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', 'min:3']
        ]);

        // Update user's password
        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ], 200);
    }

    // Delete user account
    public function destroy(Request $request)
    {
        // Validate password confirmation for account deletion
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $user = $request->user();

        // Delete the user account
        $user->delete();

        // Invalidate and regenerate session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ], 200);
    }
}
