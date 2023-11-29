<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{

    protected $table = 'summary';



    protected $fillable = [
        'month',
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

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    // Summary.php

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

}
