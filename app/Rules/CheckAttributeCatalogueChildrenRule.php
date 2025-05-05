<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\AttributeCatalogue;

class CheckAttributeCatalogueChildrenRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $id;
    public function __construct($id) {
        $this->id = $id;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $flag = AttributeCatalogue::isNodeCheck($this->id);
        if($flag == false) {
            $fail('Không thể xoá do vẫn con danh mục con');
        }   
    }
}
