<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;

    protected $table = 'balita';
    protected $primaryKey = 'id_balita';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_ibu',
    ];

    public function pengukurans()
    {
        return $this->hasMany(Pengukuran::class, 'id_balita', 'id_balita');
    }
}
