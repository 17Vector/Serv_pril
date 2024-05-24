<?php

namespace App\Http\Requests;

use App\DTO\Auth_DTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'regex:/^[A-Z][a-zA-Z]{6,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль пользователя',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Имя пользователя должно содержать только буквы латинского алфавита и начинаться с большой буквы.',
            'password.regex' => 'Пароль должен отвечать требованиям по минимальной длине, содержанию цифр, символов верхнего и нижнего регистров.',
        ];
    }

    public function getDTO()
    {
        return new Auth_DTO(
            $this->input('username'), 
            $this->input('password'));
    }
}
