<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lexpenses extends Model
{
    use HasFactory;
    protected $table = 'lexpenses';

    protected $fillable = [
        'date_issued',
        'voucher',
        'check',
        'encashment',
        'description',
        'type_id',
        'stype_id',
        'amount',
        'others',

    ];

    public function type()
    {
        return $this->belongsTo(Types::class);
    }

    public function stype()
    {
        return $this->belongsTo(Stypes::class);
    }
}
