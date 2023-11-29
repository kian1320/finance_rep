<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Btypes;

class UniqueBtypeName implements Rule
{
    private $userId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $userId
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        // Check for uniqueness across all records, not just those created by the current user
        return !Btypes::where('name', $value)
            ->where('created_by', $this->userId)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
