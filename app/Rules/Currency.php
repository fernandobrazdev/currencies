<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Currency implements ValidationRule
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            if ($this->type == 'code_list' || $this->type == 'number_list')
                $fail('The value must be an array');

            if (!$this->itemIsValid($value))
                $fail('The selected value is invalid');
        } else {
            if ($this->type == 'code' || $this->type == 'number')
                $fail('The value cannot be an array');

            foreach ($value as $i => $item) {
                if (!$this->itemIsValid($item))
                    $fail('The select value (' . $i + 1 . ')  is invalid');
            }
        }
    }

    public function itemIsValid($item)
    {
        if (strlen($item) != 3)
            return false;

        if ($this->type == 'number' || $this->type == 'number_list') {
            if (!is_numeric($item)) {
                return false;
            }
        } else {
            if (preg_match('~[0-9]+~', $item)) {
                return false;
            }
        }

        return true;
    }
}
