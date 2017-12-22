<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReservationRequest extends Request
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
                "last_name" => "required",
                "persons_num" => "required|numeric",
                "time" => "required",
                "date" => "required",
                "offer_file_upload" => "mimes:pdf,docx,doc,odt,rtf,txt,jpeg,jpg,png,bmp",
                "user" => "required",
                "email" => "email",
            ];
        }

        return $rules;
    }

}
