<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    use HasFactory;


    protected $table = 'types';



    protected $fillable = [
        'name',
        'created_by',

    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stypes()
    {
        return $this->hasMany(Stypes::class, 'types_id');
    }
}
