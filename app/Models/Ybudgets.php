<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ybudgets extends Model
{
    protected $fillable = [
        'ysummary_id',
        'btypes_id', // Add this line
        'bstypes_id', // Add this line
        'amount',
        'created_by',
    ];

    public function ysummary()
    {
        return $this->belongsTo(Ysummary::class);
    }


    public function ybudgetType()
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
