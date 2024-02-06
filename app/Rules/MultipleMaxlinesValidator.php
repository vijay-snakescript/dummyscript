<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MultipleMaxlinesValidator implements Rule
{
    protected $max;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max = 5)
    {
        $this->max = $max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Validator::make(
            [
                "{$attribute}" => explode(PHP_EOL, $value)
            ],
            [
                "{$attribute}" => 'required|array|max:' . $this->max
            ]
        )->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Max {$this->max} :attribute allowed.";
    }
}
