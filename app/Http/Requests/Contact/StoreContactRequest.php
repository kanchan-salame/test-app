<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
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
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                Rule::unique('contacts')->ignore($this->id)
            ],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => [
                'sometimes',
                'required',
                'numeric',
                'min:10',
                Rule::unique('contacts')->ignore($this->id)
            ],
            'address' => ['required', 'string'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];
    }
}
