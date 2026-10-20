<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $fillable = ['nama', 'jumlah_anggota', 'jumlah_kegiatan', 'jumlah_ranting'];
}
