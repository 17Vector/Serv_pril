<?php

namespace App\Http\Requests;

use App\DTO\RoleDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
                'unique:roles',
            ],
            'description' => [
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Название роли должно быть строкой.',
            'name.unique' => 'Роль с таким названием уже существует.',
        ];
    }

    public function getDTO()
    {
        return new RoleDTO(
            $this->input('name'), 
            $this->input('description')
        );
    }
}
