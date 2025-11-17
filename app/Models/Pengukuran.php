<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    use HasFactory;

    protected $table = 'pengukuran';
    protected $primaryKey = 'id_ukur';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_balita',
        'id_user',
        'tanggal_ukur',
        'umur_bulan',
        'bb_kg',
        'tb_cm',
        'lila_cm',
        'status_stunting',
    ];

    protected $casts = [
        'status_stunting' => 'boolean',
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'id_balita', 'id_balita');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function hasilPrediksi()
    {
        return $this->hasOne(HasilPrediksi::class, 'id_ukur', 'id_ukur');
    }
}
