<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateSongRequest
 *
 * @package App\Http\Requests
 */
class CreateSongRequest extends FormRequest
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
            'name'     => 'required|min:4|max:20',
            'author'   => 'required|min:5|max:20',
            'link'     => 'required|min:5|max:100',
            'duration' => 'required|between:0,99.99',
        ];
    }
}
