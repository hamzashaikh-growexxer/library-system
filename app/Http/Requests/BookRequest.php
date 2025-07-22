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
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            'name' => 'required|string|unique:books,name,' . $bookId,
            'status' => ['required', Rule::in(['Available', 'Booked'])],
            'location' => 'required|string',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Book name is required.',
            'name.string' => 'Book name must be a string.',
            'name.unique' => 'Book name already exists.',
            'status.required' => 'Book status is required.',
            'status.in' => 'Status must be either Available or Booked.',
            'location.required' => 'Location is required.',
            'location.string' => 'Location must be a string.',
            'author_ids.required' => 'At least one author is required.',
            'author_ids.array' => 'Author IDs must be an array.',
            'author_ids.*.exists' => 'Selected author is invalid.',
            'category_ids.required' => 'At least one category is required.',
            'category_ids.array' => 'Category IDs must be an array.',
            'category_ids.*.exists' => 'Selected category is invalid.',
        ];
    }
}
