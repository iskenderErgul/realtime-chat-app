<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class SendMessagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov|max:51200', // Dosya türleri ve boyut sınırı (örnek)

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $message = $this->input('message');
            $file = $this->file('file');

            // Eğer hem mesaj hem dosya yoksa doğrulama hatası oluştur
            if (empty($message) && !$file) {
                $validator->errors()->add('message', 'Bir mesaj veya dosya göndermek zorundasınız.');
            }
        });
    }
}
