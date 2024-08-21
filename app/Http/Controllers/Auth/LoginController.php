<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {

        $userControl = [
            'email'  =>  $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($userControl)) {

            return response()->json(Auth::user(),'200');
        } else {
            return response()->json('Giriş Başarısız', 401);
        }
    }
    public function logout(): JsonResponse
    {
        Session::flush();
        return response()->json('Çıkış İşlemi Başarılı');
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'g-recaptcha-response' => 'required',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $responseBody = json_decode($response->body());

        if (!$responseBody->success) {
            response()->json(['message' => 'Doğrulama başarısız',], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Kayıt Başarılı. Giriş Yapabilirsiniz', 'user' => $user], 201);
    }
}
