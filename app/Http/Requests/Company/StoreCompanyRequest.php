<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckCompanyEmail;
use App\Rules\CheckUniquePhone;

class StoreCompanyRequest extends FormRequest
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
            'company_email' => ['required', 'string', 'email', new CheckCompanyEmail()],
            'company_name' => ['required', 'string'],
            'company_website' => ['required', 'string', 'url'],
            'company_phone' => ['required', 'numeric', 'min:10', new CheckUniquePhone()],
            'company_address' => ['required', 'string'],
            'company_password' => ['required', 'string', 'required_with:company_password_confirmation', 'same:company_password_confirmation'],
            'company_password_confirmation' => ['required', 'string'],
            // 'company_photo' => ['required', 'string'],
        ];
    }
}
