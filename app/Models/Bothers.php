<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bothers extends Model
{
    protected $fillable = [
        'summary_id',
        'budget_id',
        'btypes_id',
        'bstypes_id',
        'created_by',
        'others',
    ];


    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function btype()
    {
        return $this->belongsTo(Btypes::class, 'btypes_id');
    }

    public function bstype()
    {
        return $this->belongsTo(Bstypes::class, 'bstypes_id');
    }
}
