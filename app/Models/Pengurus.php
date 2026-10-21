<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Pengurus extends Model
{
    public const KATEGORI = [
        'mustasyar' => [
            'judul' => 'Dewan Mustasyar',
            'keterangan' => 'Dewan Pertimbangan',
            'filter' => 'Mustasyar',
        ],
        'syuriyah' => [
            'judul' => 'Pengurus Syuriyah',
            'keterangan' => 'Pimpinan Keagamaan',
            'filter' => 'Syuriyah',
        ],
        'tanfidziyah' => [
            'judul' => 'Pengurus Tanfidziyah',
            'keterangan' => 'Pelaksana Organisasi',
            'filter' => 'Tanfidziyah',
        ],
        'banom' => [
            'judul' => 'Badan Otonom (Banom NU)',
            'keterangan' => 'Badan Otonom Nahdlatul Ulama',
            'filter' => 'Banom NU',
        ],
    ];

    protected $fillable = [
        'nama',
        'jabatan',
        'tingkatan',
        'foto',
        'urutan',
        'is_demo',
        'seed_key',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'is_demo' => 'boolean',
        ];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    public function kategoriKey(): string
    {
        $tingkatan = Str::lower(trim((string) $this->tingkatan));

        return array_key_exists($tingkatan, self::KATEGORI) ? $tingkatan : 'banom';
    }

    public function kategoriLabel(): string
    {
        return self::KATEGORI[$this->kategoriKey()]['judul'];
    }

    public function banomName(): ?string
    {
        if ($this->kategoriKey() !== 'banom') {
            return null;
        }

        $tingkatan = Str::lower(trim((string) $this->tingkatan));

        if ($tingkatan === '' || $tingkatan === 'banom') {
            return null;
        }

        return [
            'gp ansor' => 'GP Ansor',
            'muslimat nu' => 'Muslimat NU',
            'fatayat nu' => 'Fatayat NU',
            'ipnu' => 'IPNU',
            'ippnu' => 'IPPNU',
            'pagar nusa' => 'Pagar Nusa',
            'isnu' => 'ISNU',
            'pergunu' => 'Pergunu',
        ][$tingkatan] ?? Str::headline($this->tingkatan);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (blank($this->foto) || str_contains($this->foto, '..')) {
            return null;
        }

        if (! Storage::disk('public')->exists($this->foto)) {
            return null;
        }

        return asset('storage/'.ltrim(str_replace('\\', '/', $this->foto), '/'));
    }

    public function hasFoto(): bool
    {
        return $this->foto_url !== null;
    }
}
