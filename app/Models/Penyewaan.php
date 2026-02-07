<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    protected $table = 'penyewaan';

    protected $fillable = [
        'penyewa_id',
        'motor_id',
        'tanggal_mulai',
        'tanggal_selesali',
        'tipe_durasi',
        'harga',
        'status',
    ];

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'penyewaan_id');
    }
    //
}
