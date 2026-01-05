<?php

namespace App\Builders;

use App\Models\Pengukuran;
use Illuminate\Database\Eloquent\Builder;

class LaporanPengukuranBuilder
{
    protected Builder $query;

    public function __construct()
    {
        // query dasar
        $this->query = Pengukuran::query()->with('balita');
    }

    /**
     * Set rentang tanggal laporan
     */
    public function betweenTanggal(string $mulai, string $selesai): self
    {
        $this->query->whereBetween('created_at', [$mulai, $selesai]);
        return $this;
    }

    /**
     * Urutkan berdasarkan tanggal
     */
    public function orderByTanggal(string $arah = 'asc'): self
    {
        $this->query->orderBy('created_at', $arah);
        return $this;
    }

    /**
     * Eksekusi query dan ambil hasil
     */
    public function get()
    {
        return $this->query->get();
    }
}