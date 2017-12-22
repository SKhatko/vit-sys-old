<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class SectionRequest extends Request
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
        $rules = [];

        if (!$this->isMethod('delete')) {
            $rules = [
                "name" => "required",
                "image" => "mimes:jpg,JPG,png,gif"
            ];
        }

        return $rules;
    }

}