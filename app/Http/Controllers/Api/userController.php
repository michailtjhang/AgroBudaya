<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function register(Request $request)
    {
        // Validasi data yang dikirim
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:15|unique:users|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            // Membuat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'user_id'     => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'created_date' => $user->created_at->format('Y-m-d H:i:s'),
                ]
            ], 201);
        } catch (QueryException $e) {
            // Jika terjadi error saat menyimpan ke database
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to register user, please try again later.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validasi data yang dikirim
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:15|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan atau password tidak sesuai
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'email or password doesn’t match.'
            ], 401);
        }

        try {
            // Mengatur waktu kedaluwarsa token akses
            $accessToken = $user->createToken('access_token', [], now()->addMinutes(60))->plainTextToken;
            // Mengatur waktu kedaluwarsa token penyegaran
            $refreshToken = $user->createToken('refresh_token', [], now()->addDays(7))->plainTextToken;

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'access_token' => $accessToken,
                    'access_token_expires_at' => now()->addSeconds(120)->toDateTimeString(),
                    'refresh_token' => $refreshToken,
                    'refresh_token_expires_at' => now()->addDays(7)->toDateTimeString(),
                ]
            ], 200);
        } catch (\Exception $e) {
            // Jika terjadi error saat pembuatan token
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to generate tokens, please try again later.'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Mendapatkan pengguna yang sedang login
        $user = $request->user();

        if ($user) {
            try {
                // Menghapus semua token yang terkait dengan pengguna saat ini
                $user->tokens()->delete();

                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'User successfully logged out.'
                ], 200);
            } catch (\Exception $e) {
                // Menangani kesalahan
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Failed to logout, please try again later.'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No user is currently authenticated.'
            ], 401);
        }
    }
}
