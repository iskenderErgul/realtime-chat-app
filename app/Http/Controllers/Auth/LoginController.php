<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $recaptcha;
    public function __construct(RecaptchaService $recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }
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


//        $recaptchaResponse = $request->input('g-recaptcha-response');
//        if (!$this->recaptcha->verify($recaptchaResponse)) {
//            return response()->json([
//                'message' => 'reCAPTCHA doğrulaması başarısız.'
//            ], 422);
//        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Kayıt Başarılı. Giriş Yapabilirsiniz', 'user' => $user], 201);
    }
}
