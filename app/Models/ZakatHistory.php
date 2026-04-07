<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatHistory extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_zakat',
        'nominal',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
