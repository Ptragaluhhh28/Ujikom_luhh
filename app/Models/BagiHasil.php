<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagiHasil extends Model
{

    protected $fillable = [
        'penyewaan_id',
        'bagi_hasil_pemilik',
        'bagi_hasil_admin',
        'settled_at',
        'tanggal',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }
}
