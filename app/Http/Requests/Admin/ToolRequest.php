<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Translations\ToolTranslations;

class ToolRequest extends FormRequest
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
            "{$locale}.name" => ['required', 'max:150', 'unique:tool_translations,name,' . $this->id . ',tool_id'],
            "slug" => ['required', 'max:150', 'unique:tools,slug,' . $this->id . ',id'],
            "category" => 'required',
            "{$locale}.description" => 'nullable|max:500',
            "{$locale}.content" => 'required',
            "{$locale}.meta_title" => 'nullable|max:150',
            "{$locale}.meta_description" => 'nullable|max:190',
            "{$locale}.og_title" => 'nullable|max:150',
            "{$locale}.og_description" => 'nullable|max:190',
        ];
    }
}
