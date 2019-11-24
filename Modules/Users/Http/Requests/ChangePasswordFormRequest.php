<?php
namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordFormRequest extends FormRequest
{

    public function rules()
    {
        $rules = [
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        ];

        return $rules;
    }

    public function authorize()
    {
        return true;
    }
}
