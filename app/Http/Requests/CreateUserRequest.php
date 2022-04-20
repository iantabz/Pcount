<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if (!request()->id) {
            return [
                'name' => ['required'],
                'username' => ['required', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'regex:/[0-9]/'],
                'confirm_password' => ['required', 'same:password', 'string', 'min:8', 'regex:/[0-9]/'],
                // 'company' => ['required'],
                // 'business_unit' => ['required'],
                // 'department' => ['required'],
                // 'section' => ['required'],
                'usertype_id' => ['required']
            ];
        }

        return  [
            'name' => ['required'],
            'username' => ['required'],
            // 'company' => ['required'],
            // 'business_unit' => ['required'],
            // 'department' => ['required'],
            // 'section' => ['required'],
            'usertype_id' => ['required']
        ];
    }
}
