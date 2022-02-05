<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEaeRegisterRequest extends FormRequest
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
            'name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'email' => 'required|email|max:30|unique:users',
            'password' => 'required|min:6',
            'provincia' => 'required|max:100',
            'localidad' => 'required|max:100',
            'direccion' => 'required|max:100',
            'servicios_generales' => 'required|max:100',
            'servicios_especificos' => 'required|max:100',
         
        
        ];
    }
}
