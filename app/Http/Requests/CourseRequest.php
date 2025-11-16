<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (session()->has('step1') && session()->has('step2') && session()->has('step3') && session()->has('step4') && session()->has('step5') && session()->has('step6')) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // curso
            'course_id' => ['required', 'exists:courses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'O campo curso é obrigatório.',
            'course_id.exists' => 'O curso selecionado não existe.',
        ];
    }
}
