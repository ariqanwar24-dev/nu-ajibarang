<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $fillable = ['nama', 'jumlah_anggota', 'jumlah_kegiatan', 'jumlah_ranting'];

    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }
}
