<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank'; // Set the table name if it's different from the model name

    protected $fillable = [
        'date',
        'bname',
        'accnum',
        'amount',
    ];
}
