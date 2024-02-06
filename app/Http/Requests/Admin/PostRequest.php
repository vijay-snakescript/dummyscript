<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Translations\PostTranslations;

class PostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $locale = app()->getLocale();

        return [
            "{$locale}.title" => 'required|max:150',
            "{$locale}.slug" => ['required', 'max:150', 'unique:post_translations,slug,' . $this->id . ',post_id'],
            "{$locale}.contents" => 'required',
            "{$locale}.excerpt" => 'max:500',
            "{$locale}.meta_title" => 'nullable|max:150',
            "{$locale}.meta_description" => 'nullable|max:500',
            "{$locale}.og_title" => 'nullable|max:150',
            "{$locale}.og_description" => 'nullable|max:500',
            "featured_image" => 'nullable|image|dimensions:min_width=200,min_height=200',
        ];
    }
}
