<?php

namespace Modules\Community\App\Http\Requests;

use App\Services\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CommunityFilterRequest extends FormRequest
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
        return [
            //membersCommonity(relasi nya apa).filters(bebas diganti apa).byFacultyId(apa yang mau difilter)
            'membersCommonity.filters.byFacultyId' => 'nullable'
            // 'orderByFaculty' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'membersCommonity.filters.byFacultyId' => 'Faculty Id'
            // 'orderByFaculty' => 'True or False',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error_validation($validator)
        );
    }
}
