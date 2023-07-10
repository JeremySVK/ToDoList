<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        return [
            'title' => 'required|max:50',
            'description' => 'required',
            'tags' => 'required|min:1',
        ];
    }

    /**
     * messages
     *
     * @return array
     */
    public function messages() : array
    {
        return [
            'required' => 'The :attribute is required',
            'title.max' => 'Max length is 50 characters',
            'tags.required' => 'Task must have at least one tag',
        ];
    }

    // public function attributes()
    // {
    //     return [
    //         'title' => 'Task title',
    //         'description' => 'Task description'
    //     ];
    // }
}
