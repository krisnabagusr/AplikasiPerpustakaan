<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
    ];

    /**
     * Relasi One-to-Many: Satu anggota dapat memiliki banyak riwayat transaksi peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
