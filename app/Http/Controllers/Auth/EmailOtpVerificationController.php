<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OwnerOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmailOtpVerificationController extends Controller
{
    public function show(Request $request): View
    {
        return view('auth.email-otp', [
            'email' => $request->session()->get('pending_owner_email', $request->query('email')),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.size' => 'Kode OTP harus 6 digit.',
        ]);

        $owner = User::where('role', 'owner')
            ->where('email', $data['email'])
            ->whereNull('email_verified_at')
            ->whereNull('approved_at')
            ->first();

        if (!$owner) {
            return back()->withErrors([
                'email' => 'Akun owner tidak ditemukan atau sudah diproses.',
            ])->withInput();
        }

        if (!$owner->email_otp_expires_at || $owner->email_otp_expires_at->isPast()) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode.',
            ])->withInput();
        }

        if (!hash_equals((string) $owner->email_otp_code_hash, hash('sha256', trim($data['otp_code'])))) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP tidak sesuai.',
            ])->withInput();
        }

        $owner->forceFill([
            'email_verified_at' => now(),
            'email_otp_code_hash' => null,
            'email_otp_expires_at' => null,
        ])->save();

        $request->session()->forget('pending_owner_email');

        return redirect()
            ->route('login')
            ->with('success', 'Email berhasil diverifikasi. Pengajuan Anda sekarang menunggu persetujuan admin.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $owner = User::where('role', 'owner')
            ->where('email', $data['email'])
            ->whereNull('email_verified_at')
            ->whereNull('approved_at')
            ->first();

        if (!$owner) {
            return back()->withErrors([
                'email' => 'Akun owner tidak ditemukan atau sudah diverifikasi.',
            ])->withInput();
        }

        $otpCode = (string) random_int(100000, 999999);
        $owner->forceFill([
            'email_otp_code_hash' => hash('sha256', $otpCode),
            'email_otp_expires_at' => now()->addMinutes(15),
        ])->save();

        Mail::to($owner->email)->send(new OwnerOtpMail(
            owner: $owner,
            otpCode: $otpCode,
            verifyUrl: route('auth.email-otp.form', ['email' => $owner->email]),
        ));

        return back()->with('success', 'Kode OTP baru sudah dikirim ke email Anda.');
    }

    public static function issueFor(User $owner): string
    {
        $otpCode = (string) random_int(100000, 999999);

        $owner->forceFill([
            'email_otp_code_hash' => hash('sha256', $otpCode),
            'email_otp_expires_at' => now()->addMinutes(15),
        ])->save();

        Mail::to($owner->email)->send(new OwnerOtpMail(
            owner: $owner,
            otpCode: $otpCode,
            verifyUrl: route('auth.email-otp.form', ['email' => $owner->email]),
        ));

        return $otpCode;
    }
}
