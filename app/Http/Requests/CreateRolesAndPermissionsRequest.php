<?php

namespace App\Http\Requests;

use App\DTO\RolesAndPermissionsDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateRolesAndPermissionsRequest extends FormRequest
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
            'role_id' => [
                'integer',
            ],
            'permissions_id' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            'role_id.inreger' => 'Идентификатор роли должен быть целым числом.',
            'permissions_id.inreger' => 'Идентификатор разрешения должен быть целым числом.',
        ];
    }

    public function getDTO()
    {
        return new RolesAndPermissionsDTO(
            $this->input('role_id'), 
            $this->input('permission_id'), 
        );
    }
}
