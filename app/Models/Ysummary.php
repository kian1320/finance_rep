<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ysummary extends Model
{
    protected $table = 'ysummary';



    protected $fillable = [

        'year',
        'type',
        'totalstr',
        'aftexpenses',
        'beginbal',
        'created_by',
    ];




    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function expenses()
    {
        return $this->hasMany(Expenses::class);
    }
}
