<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Rule;

class CategoryParent implements Rule
{
    private Category $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */

    public function passes($attribute, $value): bool
    {
        return $this->category->validParent($value);
    }

    /**
     * @return \Illuminate\Foundation\Application|array|string|Translator|Application|null
     */
    public function message(): \Illuminate\Foundation\Application|array|string|Translator|Application|null
    {
        return trans('validation.custom.parent_id.invalid');
    }
}
