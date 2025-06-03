<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Дозволяємо запит
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:200|unique:blog_posts', // title має бути унікальним
            'slug' => 'max:200|unique:blog_posts', // slug також має бути унікальним
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Введіть заголовок статті',
            'title.unique' => 'Стаття з таким заголовком вже існує', // Додано повідомлення для unique
            'slug.max' => 'Максимальна довжина псевдоніма [:max]',
            'slug.unique' => 'Стаття з таким псевдонімом вже існує', // Додано повідомлення для unique
            'content_raw.min' => 'Мінімальна довжина статті [:min] символів',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'Заголовок статті',
            'slug' => 'Псевдонім',
            'content_raw' => 'Текст статті',
            'category_id' => 'Категорія',
        ];
    }
}
