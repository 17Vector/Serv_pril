<?php

namespace App\Http\Requests;

use App\DTO\RegistDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
                'unique:users',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            ],
            'c_password' => [
                'required',
                'string',
                'same:password',
            ],
            'birthday' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email пользователя',
            'password' => 'Пароль пользователя',
            'c_password' => 'Подтверждение пароля',
            'birthday' => 'Дата рождения',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Имя пользователя должно содержать только буквы латинского алфавита и начинаться с большой буквы.',
            'username.unique' => 'Это имя пользователя уже занято.',
            'email.email' => 'Пожалуйста, укажите корректный email адрес.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
            'password.regex' => 'Пароль должен отвечать требованиям по минимальной длине, содержанию цифр, символов верхнего и нижнего регистров.',
            'c_password.same' => 'Подтверждение пароля не совпадает с паролем.',
            'birthday.date_format' => 'Дата рождения должна быть в формате ГГГГ-ММ-ДД.',
        ];
    }

    public function getDTO()
    {
        return new RegistDTO(
            $this->input('username'), 
            $this->input('email'), 
            $this->input('password'),
            $this->input('birthday'),
        );
    }
}
