<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                Rule::unique('books', 'name')->ignore($this->id),
            ],
            'status' => ['required', Rule::in(['Available', 'Booked'])],
            'location' => 'required|string',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];
    }
}
