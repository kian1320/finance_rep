<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{

    protected $table = 'budgets';

    protected $fillable = [
        'summary_id',
        'btypes_id', // Add this line
        'bstypes_id', // Add this line
        'others',
        'amount',
        'created_by',
        'month',
        'year',
    ];


    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }


    public function budgetType()
    {
        return $this->belongsTo(Btypes::class, 'btypes_id');
    }


    // Budget.php

    public function btype()
    {
        return $this->belongsTo(Btypes::class, 'btypes_id');
    }

    public function bstype()
    {
        return $this->belongsTo(Bstypes::class, 'bstypes_id');
    }

    public function stype()
    {
        return $this->belongsTo(Stypes::class, 'bstypes_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
