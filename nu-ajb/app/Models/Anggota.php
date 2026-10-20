<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    protected $fillable = [
    'user_id',
    'nomor_anggota',
    'nama_anggota',
    'alamat',
    'tanggal_lahir',
    'jenis_kelamin',
    'no_hp',
    'foto',
    'status_verifikasi',
    'catatan_verifikasi',
];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}