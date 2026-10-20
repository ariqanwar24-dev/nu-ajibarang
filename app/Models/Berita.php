<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'penulis',
        'status',
        'isi',
        'gambar',
        'lokasi_nama',
        'lokasi_alamat',
        'lokasi_desa',
        'latitude',
        'longitude',
        'lokasi_keterangan',
        'acara_mulai',
        'acara_selesai',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'acara_mulai' => 'datetime',
            'acara_selesai' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gunakan slug pada URL publik dan panel admin.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Tetap dukung tautan lama berbasis ID jika slug belum ditemukan.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $berita = $this->newQuery()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();

        if (! $berita && $field === null && is_numeric($value)) {
            $berita = $this->newQuery()->find($value);
        }

        return $berita;
    }

    /**
     * Berita yang boleh tampil di halaman publik.
     */
    public function scopeTerbit(Builder $query): Builder
    {
        return $query->where('status', 'terbit');
    }

    public function sudahTerbit(): bool
    {
        return $this->status === 'terbit';
    }

    public function memilikiLokasi(): bool
    {
        return filled($this->lokasi_nama)
            || filled($this->lokasi_alamat)
            || ($this->latitude !== null && $this->longitude !== null);
    }

    public function memilikiKoordinat(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
