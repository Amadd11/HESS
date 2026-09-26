<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Demographic extends Model
{
    use HasFactory;

    /**
     * Cache key untuk menyimpan opsi demografi terkelompok.
     */
    public const CACHE_KEY = 'hess_demographic_options';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'parent_name',
        'order',
        'is_active',
    ];

    /**
     * Bersihkan cache secara otomatis saat data opsi diubah.
     */
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Peta hierarki resmi Satuan Kerja di bawah masing-masing Direktorat RSUP Dr. Sardjito.
     *
     * @var array<string, array<int, string>>
     */
    public const DIRECTORATE_UNITS = [
        'Direktorat Medik dan Keperawatan' => [
            'Departemen Anestesi',
            'Departemen Bedah',
            'Departemen Ibu dan Anak',
            'Departemen Kardiovaskular',
            'Departemen Kulit dan Estetika',
            'Departemen Medik',
            'Departemen Neurologi',
            'Departemen Oftalmologi',
            'Departemen Onkologi',
            'Departemen Ortopedi',
            'Departemen Penunjang',
            'Departemen Radiologi',
            'Departemen THT',
            'Departemen Uro-Negrologi',
            'Instalasi Farmasi',
            'Instalasi Gawat Darurat',
            'Instalasi Ginjal Terpadu',
            'Instalasi Jantung, Otak, dan Pembuluh Darah Terpadu',
            'Instalasi Kamar Bedah dan Anestesi',
            'Instalasi Kanker Terpadu, Kedokteran Nuklir dan Teranostik Molekuler',
            'Instalasi Kedokteran Forensik dan Medikolegal',
            'Instalasi Kesehatan Ibu dan Anak Terpadu',
            'Instalasi Laboratorium Terpadu',
            'Instalasi Radiologi',
            'Instalasi Rawat Inap Dewasa',
            'Instalasi Rawat Jalan Reguler',
            'Instalasi Rawat Khusus',
            'Instalasi Rekam Medik dan Informasi Kesehatan',
            'Tim Kerja Pelayanan Keperawatan',
            'Tim Kerja Pelayanan Medik',
            'Tim Kerja Pelayanan Penunjang',
            'Unit Pengelola Darah',
            'UPB Royal Sardjito',
        ],
        'Direktorat SDM, Pendidikan, dan Penelitian' => [
            'Instalasi Inovasi, Sister Hospital, dan Kerjasama Luar Negeri',
            'Instalasi Pendidikan dan Pelatihan',
            'Instalasi Penelitian',
            'Tim Kerja Organisasi dan SDM',
            'Tim Kerja Pendidikan dan Penelitian',
            'Unit Fungsional Pendidikan',
        ],
        'Direktorat Perencanaan dan Pengembangan Strategi Layanan' => [
            'Tim Kerja Perencanaan dan Evaluasi Program',
            'Tim Kerja Pengembangan Strategi Layanan',
            'Tim Kerja Perencanaan Anggaran',
        ],
        'Direktorat Keuangan dan BMN' => [
            'Tim Kerja Pelaksanaan Keuangan',
            'Tim Kerja Akuntasi dan Barang Milik Negara (BMN)',
            'Instalasi Penjaminan dan Casemix',
        ],
        'Direktorat Layanan Operasional' => [
            'Tim Kerja Hukum dan Humas',
            'Tim Kerja Operasional dan Ketatausahaan',
            'Instalasi Gizi',
            'Instalasi Kesehatan Lingkungan dan K3RS',
            'Instalasi Pemeliharaan Sarana dan Prasarana RS',
            'Instalasi Promosi Kesehatan RS',
            'Instalasi Sistem Informasi Manajemen RS',
            'Instalasi Sterilisasi Sentral dan Binatu',
        ],
        'Non Direktorat / Fungsional' => [
            'Komite Etik dan Hukum',
            'Komite Koordinasi Pendidikan',
            'Komite Medik',
            'Komite Mutu Rumah Sakit',
            'Komite Pencegahan dan Pengendalian Infeksi (PPI) dan PPRA',
            'Komite Tenaga Kesehatan',
            'Satuan Pemeriksaan Intern',
            'Unit Layanan Pengadaan',
            'UPF Yankestrad Tawangmangu',
            'UPB RS Kardiologi Emirates Indonesia',
        ],
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope data aktif terurut.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope berdasarkan tipe demografi.
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Ambil seluruh opsi demografi aktif yang dikelompokkan berdasarkan tipe.
     *
     * @return array{professions: array<int, string>, directorates: array<int, string>, units: array<int, string>, directorate_units: array<string, array<int, string>>, statuses: array<int, string>, tenures: array<int, string>}
     */
    public static function getGroupedOptions(): array
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            $all = static::active()->get()->groupBy('type');

            $directorates = $all->get('directorate', collect())->pluck('name')->toArray();
            if (empty($directorates)) {
                $directorates = array_keys(static::DIRECTORATE_UNITS);
            }

            $units = $all->get('unit', collect())->pluck('name')->toArray();
            if (empty($units)) {
                $units = array_merge(...array_values(static::DIRECTORATE_UNITS));
            }

            // Susun mapping direktorat => units berdasarkan database atau master konstan
            $directorateUnits = [];
            $unitModels = $all->get('unit', collect());
            $hasParentNames = $unitModels->whereNotNull('parent_name')->isNotEmpty();

            if ($hasParentNames) {
                foreach ($directorates as $dir) {
                    $matched = $unitModels->where('parent_name', $dir)->pluck('name')->toArray();
                    if (! empty($matched)) {
                        $directorateUnits[$dir] = $matched;
                    } elseif (isset(static::DIRECTORATE_UNITS[$dir])) {
                        $directorateUnits[$dir] = static::DIRECTORATE_UNITS[$dir];
                    }
                }
            } else {
                $directorateUnits = static::DIRECTORATE_UNITS;
            }

            return [
                'professions' => $all->get('profession', collect())->pluck('name')->toArray() ?: [
                    'Dokter / Medis',
                    'Penunjang medis',
                    'Perawat dan bidan',
                    'Non medis',
                ],
                'directorates' => $directorates,
                'units' => $units,
                'directorate_units' => $directorateUnits,
                'statuses' => $all->get('status', collect())->pluck('name')->toArray() ?: [
                    'PNS',
                    'PPPK',
                    'BLU (Non PNS Tetap, Non PNS Kontrak, Mitra)',
                ],
                'tenures' => $all->get('tenure', collect())->pluck('name')->toArray() ?: [
                    '< 1 tahun',
                    '1–3 tahun',
                    '4–5 tahun',
                    '6–10 tahun',
                    '> 10 tahun',
                ],
                'ages' => $all->get('age', collect())->pluck('name')->toArray() ?: [
                    '< 25 tahun',
                    '25–35 tahun',
                    '36–45 tahun',
                    '46–55 tahun',
                    '> 55 tahun',
                ],
                'genders' => $all->get('gender', collect())->pluck('name')->toArray() ?: [
                    'Laki-laki',
                    'Perempuan',
                ],
                'educations' => $all->get('education', collect())->pluck('name')->toArray() ?: [
                    'SMA / SMK / Sederajat',
                    'Diploma (D3 / D4)',
                    'Sarjana (S1)',
                    'Profesi (Dokter / Ners / Apoteker / dll.)',
                    'Magister (S2) / Spesialis',
                    'Doktor (S3) / Subspesialis',
                ],
                'incomes' => $all->get('income', collect())->pluck('name')->toArray() ?: [
                    '< Rp 3.000.000',
                    'Rp 3.000.000 – Rp 5.000.000',
                    'Rp 5.000.001 – Rp 10.000.000',
                    'Rp 10.000.001 – Rp 15.000.000',
                    '> Rp 15.000.000',
                ],
            ];
        });
    }
}
