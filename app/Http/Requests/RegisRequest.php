<?php

namespace App\Http\Requests;

use App\Services\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'password' => 'required',
            "region" => 'required',
            "eula_accepted" => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'phone' => 'Telephone Number',
            'email' => 'Email',
            'password' => 'Password',
            'region' => 'Region',
            'eula_accepted' => 'Eula Accepted',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error_validation($validator)
        );
    }
}
