<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Btypes extends Model
{
    use HasFactory;


    protected $table = 'btypes';



    protected $fillable = [
        'name',
        'created_by',

    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function budget()
    {
        return $this->hasOne(Budget::class, 'btypes_id');
    }

    public function bsubtypes()
    {
        return $this->hasMany(Bstypes::class, 'btypes_id');
    }

    // Btypes.php (Btypes model)

    public function bstypes()
    {
        return $this->hasMany(Bstypes::class, 'btypes_id');
    }

    public function type()
    {
        return $this->belongsTo(Types::class, 'type_id');
    }

    public function budgets()
    {
        return $this->hasMany(Ybudgets::class, 'btypes_id');
    }
}
