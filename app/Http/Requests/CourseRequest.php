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
            'name' => 'required|max:50',
            'description' => 'required',
            'duration' => 'required|numeric|min:1',
            'info' => 'required',
            'vacancies' => 'required|numeric|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'description.required' => 'O campo descrição é obrigatório.',
            // 'description.max' => 'O campo descrição deve ter no máximo :max caracteres.',
            'duration.required' => 'O campo duração é obrigatório.',
            'duration.numeric' => 'O campo duração deve ser numérico.',
            'duration.min' => 'O campo duração deve ser no mínimo :min.',
            'info.required' => 'O campo informações é obrigatório.',
            // 'info.max' => 'O campo informações deve ter no máximo :max caracteres.',
            'vacancies.required' => 'O campo vagas é obrigatório.',
            'vacancies.numeric' => 'O campo vagas deve ser numérico.',
            'vacancies.min' => 'O campo vagas deve ter no mínimo :min vaga.',
        ];
    }
}
