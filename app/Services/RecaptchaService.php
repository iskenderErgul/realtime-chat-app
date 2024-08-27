<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = config('services.captcha.secret');
    }

    public function verify($recaptchaResponse)
    {

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $this->secret,
            'response' => $recaptchaResponse,
        ]);

        $responseData = $response->json();


        return $responseData['success'];
    }
}
