<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stypes extends Model
{
    use HasFactory;
    protected $table = 'stypes';
    protected $primaryKey = 'id';

    public function types()
    {
        return $this->belongsTo(Types::class);
    }

    public function type()
    {
        return $this->belongsTo(Types::class, 'types_id');
    }
}
