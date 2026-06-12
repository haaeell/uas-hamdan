@component('mail::message')
# Akun Anda Disetujui

Halo {{ $owner->name }},

Akun owner Anda sudah disetujui oleh admin.

@component('mail::panel')
Email: {{ $owner->email }}  
Status: Aktif
@endcomponent

Silakan login menggunakan email dan password yang sudah Anda daftarkan.

@component('mail::button', ['url' => route('login')])
Masuk ke Sistem
@endcomponent

Terima kasih,  
{{ config('app.name') }}
@endcomponent
