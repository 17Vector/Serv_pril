<?php

namespace App\Http\Requests;

use App\DTO\PermissionsDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'unique:permissions',
            ],
            'description' => [
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Название разрешения обязательно для заполнения.',
            'name.string' => 'Название разрешения должно быть строкой.',
            'name.unique' => 'Разрешение с таким названием уже существует.',
        ];
    }

    public function getDTO()
    {
        return new PermissionsDTO(
            $this->input('name'), 
            $this->input('description'), 
        );
    }
}
