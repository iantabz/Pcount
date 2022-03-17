<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLocationRequest extends FormRequest
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
        // dd(request()->all());
        if (!request()->location_id) {
            return [
                'company' => ['required'],
                'business_unit' => ['required'],
                // 'department' => ['required'],
                // 'section' => ['required'],
                'selectedEmp' => ['required'],
                'selectedAudit' => ['required'],
                'rack_desc' => ['required']
            ];
        }

        return [
            'company' => ['required'],
            'business_unit' => ['required'],
            // 'department' => ['required'],
            // 'section' => ['required'],
            'selectedEmp' => ['required'],
            'selectedAudit' => ['required'],
            'rack_desc' => ['required']
        ];
    }
}
