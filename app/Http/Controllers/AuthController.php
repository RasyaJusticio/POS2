<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function me()
    {
        return response()->json(['user' => auth()->user()]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['status' => false, 'message' => 'Email atau Password salah!'], 401);
        }

        return response()->json([
            'status' => true,
            'user' => auth()->user(),
            'token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $token = auth()->login($user);

        return response()->json([
            'message' => 'Pengguna berhasil terdaftar.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    
    public function reset(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Temukan pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika pengguna tidak ditemukan, lemparkan pengecualian
        if (!$user) {
            throw ValidationException::withMessages(['email' => ['Email tidak ditemukan.']]);
        }

        // Ganti password
        $user->password = Hash::make($request->password);
        $user->save();

        // Kirim respons sukses
        return response()->json(['message' => 'Password berhasil direset.']);
    }
}