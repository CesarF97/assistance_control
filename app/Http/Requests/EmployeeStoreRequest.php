<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'ci' => 'required|unique:employees,ci',
            'rif' => 'required|unique:employees,rif',
            'email' => 'required|unique:employees,email',
            'last_name' => 'required', 
            'phone' => 'required'
        ];

        if($this->method() === 'PUT'){
            $rules['ci'] = "required|unique:employees,ci,{$this->employee->id}";
            $rules['rif'] = "required|unique:employees,rif,{$this->employee->id}";
            $rules['email'] = "required|unique:employees,email,{$this->employee->id}";
        }

        return $rules;
    }
}
