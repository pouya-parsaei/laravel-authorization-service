<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string','max:255',Rule::unique('roles','name')->ignore($this->route('role')->id)],
            'persian_name' => ['required', 'string','max:255',Rule::unique('roles','persian_name')->ignore($this->route('role')->id)],
            'permissions' => ['array'],
            'permissions.*' => ['string',Rule::exists('permissions','name')],
        ];
    }
}
