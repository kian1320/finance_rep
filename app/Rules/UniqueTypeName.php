<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Types;

class UniqueTypeName implements Rule
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function passes($attribute, $value)
    {
        // Check for uniqueness across all records, not just those created by the current user
        return !Types::where('name', $value)
            ->where('created_by', '<>', $this->userId)
            ->exists();
    }

    public function message()
    {
        return 'The :attribute already exists in the database.';
    }
}
