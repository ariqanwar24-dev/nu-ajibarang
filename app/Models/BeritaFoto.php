<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaFoto extends Model
{
    protected $table = 'berita_fotos';

    protected $fillable = [
        'berita_id',
        'path',
        'original_name',
        'mime_type',
        'size',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'urutan' => 'integer',
        ];
    }

    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class);
    }
}
