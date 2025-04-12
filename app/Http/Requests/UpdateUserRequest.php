<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Enums\UserType;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                'unique:users,email,' . ($this->route('record') ?? $this->user),
            ],
            'password' => ['sometimes', Password::defaults()],
            'type' => ['sometimes', 'in:' . implode(',', array_column(UserType::cases(), 'value'))],

        ];
    }
}
