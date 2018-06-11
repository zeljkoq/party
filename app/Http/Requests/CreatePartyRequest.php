<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartyRequest extends FormRequest
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
        return [
            'name' => 'required|min:4|max:20',
            'date' => 'required',
            'duration' => 'required|integer',
            'capacity' => 'required|integer',
            'description' => 'nullable|min:10',
            'cover_photo' => 'file',
        ];
    }
}
