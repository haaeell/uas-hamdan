<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static ?Collection $cachedSettings = null;

    public static function definitions(): array
    {
        return [
            'app_name' => [
                'label' => 'Nama Aplikasi',
                'group' => 'general',
                'type' => 'text',
                'default' => 'Sistem Pemilihan Jurusan',
                'rules' => ['required', 'string', 'max:150'],
                'help' => 'Ditampilkan pada halaman admin dan login.',
            ],
            'school_name' => [
                'label' => 'Nama Sekolah',
                'group' => 'general',
                'type' => 'text',
                'default' => 'Sekolah Menengah Atas',
                'rules' => ['required', 'string', 'max:150'],
                'help' => 'Identitas sekolah untuk branding aplikasi.',
            ],
            'support_contact' => [
                'label' => 'Kontak Bantuan',
                'group' => 'general',
                'type' => 'text',
                'default' => 'Hubungi admin sekolah',
                'rules' => ['nullable', 'string', 'max:150'],
                'help' => 'Nomor WA, email, atau nama petugas yang bisa dihubungi.',
            ],
            'logo_path' => [
                'label' => 'Logo Aplikasi',
                'group' => 'general',
                'type' => 'file',
                'default' => null,
                'rules' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
                'help' => 'Dipakai pada halaman login, landing, panel admin, dan PDF. Maksimal 2MB.',
            ],
            'login_help_text' => [
                'label' => 'Teks Bantuan Login',
                'group' => 'general',
                'type' => 'textarea',
                'default' => 'Gunakan email admin atau NISN siswa untuk melanjutkan.',
                'rules' => ['nullable', 'string', 'max:255'],
                'help' => 'Muncul di halaman login sebagai petunjuk singkat.',
            ],
            'academic_duration_minutes' => [
                'label' => 'Durasi Tes Akademik',
                'group' => 'cbt',
                'type' => 'number',
                'default' => '60',
                'rules' => ['required', 'integer', 'min:1', 'max:300'],
                'suffix' => 'menit',
                'help' => 'Waktu hitung mundur otomatis untuk tes akademik.',
            ],
            'psychology_duration_minutes' => [
                'label' => 'Durasi Tes Psikologi',
                'group' => 'cbt',
                'type' => 'number',
                'default' => '45',
                'rules' => ['required', 'integer', 'min:1', 'max:300'],
                'suffix' => 'menit',
                'help' => 'Waktu hitung mundur otomatis untuk tes psikologi.',
            ],
            'cbt_auto_submit_violation_limit' => [
                'label' => 'Batas Pelanggaran Auto Submit',
                'group' => 'cbt',
                'type' => 'number',
                'default' => '3',
                'rules' => ['required', 'integer', 'min:1', 'max:20'],
                'suffix' => 'pelanggaran',
                'help' => 'Jika batas ini tercapai, ujian akan dikirim otomatis.',
            ],
            'cbt_force_fullscreen' => [
                'label' => 'Paksa Fullscreen',
                'group' => 'cbt',
                'type' => 'checkbox',
                'default' => '1',
                'rules' => ['nullable', 'boolean'],
                'help' => 'Aktifkan agar siswa otomatis diminta masuk mode layar penuh.',
            ],
            'cbt_warning_message' => [
                'label' => 'Pesan Peringatan CBT',
                'group' => 'cbt',
                'type' => 'textarea',
                'default' => 'Aktivitas mencurigakan terdeteksi dan dicatat.',
                'rules' => ['required', 'string', 'max:255'],
                'help' => 'Muncul setiap kali sistem mendeteksi pelanggaran.',
            ],
            'student_help_text' => [
                'label' => 'Catatan Bantuan Siswa',
                'group' => 'student',
                'type' => 'textarea',
                'default' => 'Pastikan koneksi stabil, gunakan perangkat pribadi, dan hubungi admin jika ada kendala.',
                'rules' => ['nullable', 'string', 'max:255'],
                'help' => 'Bisa dipakai sebagai catatan umum untuk operasional siswa.',
            ],
        ];
    }

    public static function defaults(): array
    {
        return collect(static::definitions())
            ->mapWithKeys(fn(array $definition, string $key) => [$key => $definition['default'] ?? null])
            ->all();
    }

    public static function groupedDefinitions(): array
    {
        return collect(static::definitions())->groupBy('group', true)->all();
    }

    public static function allKeyed(): Collection
    {
        if (static::$cachedSettings === null) {
            static::$cachedSettings = static::query()->pluck('value', 'key');
        }

        return static::$cachedSettings;
    }

    public static function getSetting(string $key, mixed $default = null): mixed
    {
        $fallback = static::defaults()[$key] ?? $default;

        return static::allKeyed()->get($key, $fallback);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return (int) static::getSetting($key, $default);
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        return filter_var(static::getSetting($key, $default), FILTER_VALIDATE_BOOL);
    }

    public static function setMany(array $values): void
    {
        $definitions = static::definitions();
        $currentValues = static::allKeyed();

        foreach ($definitions as $key => $definition) {
            $value = array_key_exists($key, $values)
                ? $values[$key]
                : (($definition['type'] ?? 'text') === 'checkbox'
                    ? '0'
                    : $currentValues->get($key, $definition['default'] ?? null));

            static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                    'group' => $definition['group'] ?? 'general',
                ]
            );
        }

        static::$cachedSettings = null;
    }

    public static function logoUrl(): string
    {
        $logoPath = static::getSetting('logo_path');

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }

        return asset('images/logo.png');
    }

    public static function logoAbsolutePath(): ?string
    {
        $logoPath = static::getSetting('logo_path');

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return Storage::disk('public')->path($logoPath);
        }

        $defaultPath = public_path('images/logo.png');

        return is_file($defaultPath) ? $defaultPath : null;
    }

    public static function logoDataUri(): ?string
    {
        $path = static::logoAbsolutePath();

        if (!$path || !is_file($path)) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeType = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
    }
}
