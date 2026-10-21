<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'desa_id',
        'nomor_anggota',
        'nama_anggota',
        'alamat',
        'rt',
        'rw',
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

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status_verifikasi) {
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    public function statusDescription(): string
    {
        return match ($this->status_verifikasi) {
            self::STATUS_APPROVED => 'Data anggota sudah disetujui.',
            self::STATUS_REJECTED => 'Pengajuan anggota belum disetujui.',
            default => 'Pengajuan sedang diperiksa oleh admin.',
        };
    }

    /**
     * Nomor anggota resmi yang stabil dan unik.
     *
     * Nomor dibuat dari primary key sehingga tidak pernah acak. Jika nomor
     * bentrok dengan data lama, akhiran berurutan dipakai dan nilai tetap
     * disimpan agar konsisten pada kunjungan berikutnya.
     */
    public function pastikanNomorAnggota(): string
    {
        if (filled($this->nomor_anggota)) {
            return $this->nomor_anggota;
        }

        $nomorDasar = sprintf('NU-AJB-%06d', $this->getKey());
        $nomor = $nomorDasar;
        $urutan = 1;

        while (static::query()
            ->where('nomor_anggota', $nomor)
            ->where('id', '!=', $this->getKey())
            ->exists()) {
            $urutan++;
            $nomor = $nomorDasar.'-'.$urutan;
        }

        $this->forceFill(['nomor_anggota' => $nomor])->save();

        return $nomor;
    }

    /**
     * Tampilkan nama seperlunya untuk pencarian publik.
     */
    public function namaTersamarkan(): string
    {
        $kata = preg_split('/\s+/u', trim((string) $this->nama_anggota)) ?: [];

        return collect($kata)
            ->filter()
            ->map(function (string $kata): string {
                $panjang = mb_strlen($kata);

                if ($panjang <= 2) {
                    return mb_substr($kata, 0, 1).'*';
                }

                return mb_substr($kata, 0, 1).str_repeat('*', min(5, $panjang - 1));
            })
            ->implode(' ');
    }
}
