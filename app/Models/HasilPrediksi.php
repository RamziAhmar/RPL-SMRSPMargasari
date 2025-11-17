<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPrediksi extends Model
{
    use HasFactory;

    protected $table = 'hasil_prediksi';
    protected $primaryKey = 'id_pred';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_ukur',
        'label_pred',
        'prob_pred',
    ];

    protected $casts = [
        'label_pred' => 'boolean',
        'prob_pred'  => 'float',
    ];

    public function pengukuran()
    {
        return $this->belongsTo(Pengukuran::class, 'id_ukur', 'id_ukur');
    }
}

