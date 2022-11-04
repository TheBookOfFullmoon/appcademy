<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'name' => 'string|required',
            'birthday' => 'date|required',
            'birth_place' => 'string|required',
            'address' => 'string|required',
            'gender' => 'string|required',
            'phone' => 'string|required',
            'major_id' => 'integer|required',
        ];
    }
}
