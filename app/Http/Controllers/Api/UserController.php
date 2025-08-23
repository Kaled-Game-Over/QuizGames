<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // إسناد دور teacher لمستخدم عبر الإيميل
    public function assignTeacherRole(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->assignRole('teacher');
        $user->role = 'teacher';  // update the column
        $user->save();

        return response()->json([
            'message' => 'Teacher role assigned successfully!',
            'user' => $user
        ]);
    }
} 