<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Berita extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_REJECTED = 'rejected';

    public const MAX_GALERI = 3;

    public const MAX_GALERI_PER_UPLOAD = 3;

    public const MAX_GALERI_SIZE_MB = 8;

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'penulis',
        'status',
        'review_note',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'published_at',
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

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'acara_mulai' => 'datetime',
            'acara_selesai' => 'datetime',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function galeri(): HasMany
    {
        return $this->hasMany(BeritaFoto::class)
            ->orderBy('urutan')
            ->orderBy('id');
    }

    /**
     * URL gambar sampul yang benar-benar tersedia di disk publik.
     */
    public function gambarUrl(): ?string
    {
        if (! filled($this->gambar) || ! Storage::disk('public')->exists($this->gambar)) {
            return null;
        }

        return asset('storage/'.$this->gambar);
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
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Alias lama agar pemanggilan yang sudah ada tetap aman.
     */
    public function scopeTerbit(Builder $query): Builder
    {
        return $query->published();
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Alias lama untuk tampilan publik.
     */
    public function sudahTerbit(): bool
    {
        return $this->isPublished();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Review',
            self::STATUS_PUBLISHED => 'Terbit',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Draft',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Menunggu Review',
            self::STATUS_PUBLISHED => 'Terbit',
            self::STATUS_REJECTED => 'Ditolak',
        ];
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

    /**
     * Ringkasan lokasi untuk kartu hasil pencarian.
     */
    public function lokasiRingkas(): ?string
    {
        $bagian = array_filter([
            trim((string) $this->lokasi_nama),
            trim((string) $this->lokasi_desa),
        ], fn (string $nilai): bool => $nilai !== '');

        if ($bagian !== []) {
            return implode(' · ', $bagian);
        }

        if (filled($this->lokasi_alamat)) {
            return Str::limit(trim((string) $this->lokasi_alamat), 80);
        }

        return null;
    }

    /**
     * Tautan Google Maps hanya dibuat dari koordinat atau alamat yang benar-benar
     * tersimpan. Tidak ada lokasi perkiraan atau titik palsu.
     */
    public function lokasiMapsUrl(): ?string
    {
        if ($this->memilikiKoordinat()) {
            $lat = number_format((float) $this->latitude, 7, '.', '');
            $lng = number_format((float) $this->longitude, 7, '.', '');

            return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($lat.','.$lng);
        }

        $alamat = trim((string) $this->lokasi_alamat);

        if (mb_strlen($alamat) < 5) {
            return null;
        }

        $tujuan = [$alamat];
        $desa = trim((string) $this->lokasi_desa);

        if ($desa !== '' && ! Str::contains(Str::lower($alamat), Str::lower($desa))) {
            $tujuan[] = $desa;
        }

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(implode(', ', $tujuan));
    }
}
