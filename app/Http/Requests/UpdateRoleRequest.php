<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->check())
            return true;
        return false;
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
                'required',
                'string',
            ],
            'encryption' => [
                'required',
                'string',
                'unique:roles',
            ],
        ];
    }

    public function getDTO()
    {
        return new Role_DTO(
            $this->input('name'), 
            $this->input('description'), 
            $this->input('encryption'),
        );
    }
}
