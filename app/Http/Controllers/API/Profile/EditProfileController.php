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
    public function edit(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'status' => session('status')
        ], 200);
    }

    public function updateInfo(Request $request)
    {
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

        $user = $request->user();
        $user->fill($fields);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile information updated successfully',
            'data' => $user
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $fields = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', 'min:3']
        ]);

        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $user = $request->user();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ], 200);
    }
}
