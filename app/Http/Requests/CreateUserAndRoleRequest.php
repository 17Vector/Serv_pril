<?php

namespace App\Http\Requests;

use App\DTO\UsersAndRolesDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserAndRoleRequest extends FormRequest
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
            'user_id' => [
                'integer',
            ],
            'role_id' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.inreger' => 'Идентификатор пользователя должен быть целым числом.',
            'role_id.inreger' => 'Идентификатор роли должен быть целым числом.',
        ];
    }

    public function getDTO()
    {
        return new UsersAndRolesDTO(
            $this->input('user_id'), 
            $this->input('role_id'), 
        );
    }
}
