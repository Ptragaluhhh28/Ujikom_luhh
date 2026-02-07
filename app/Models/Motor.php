<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TarifRental;

class Motor extends Model
{
    protected $table = 'motor';

    protected $fillable = [
        'pemilik_id',
        'merk',
        'tipe_cc',
        'no_plat',
        'status',
        'photo',
        'dokumen_kepemilikan',
        'surat_lainnya',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function tarif()
    {
        return $this->hasOne(TarifRental::class, 'motor_id');
    }
}
