<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bstypes extends Model
{
    use HasFactory;

    public function btypes()
    {
        return $this->belongsTo(Btypes::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function budgets()
    {
        return $this->hasMany(Ybudgets::class, 'bstypes_id');
    }
}
