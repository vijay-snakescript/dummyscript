<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            "{$locale}.name" => 'required|max:150',
            "{$locale}.description" => 'nullable|max:500',
            "monthly_price" => 'required|min:1',
            "yearly_price" => 'required|min:1',
        ];
    }
}
