<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactBelongsToCompany implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if(Contact::where([
            [$attribute, '=', $value],
            ['user_id', '=', Auth::user()->id]
        ])->first()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry! Contact not belongs to company.';
    }
}
