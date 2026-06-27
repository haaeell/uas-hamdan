<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentLoginController extends Controller
{
    public function show(string $token)
    {
        $owner = User::where('role', 'owner')
            ->where('is_active', true)
            ->where('exam_token', $token)
            ->firstOrFail();

        session(['exam_owner_id' => $owner->id]);

        return view('auth.student-login', compact('owner', 'token'));
    }

    public function login(Request $request, string $token)
    {
        $request->validate([
            'nisn'     => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nisn.required'     => 'NISN wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $owner = User::where('role', 'owner')
            ->where('is_active', true)
            ->where('exam_token', $token)
            ->firstOrFail();

        session(['exam_owner_id' => $owner->id]);

        $student = User::where('nisn', $request->nisn)
            ->where('role', 'siswa')
            ->where('owner_id', $owner->id)
            ->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'nisn' => 'NISN tidak ditemukan.',
            ]);
        }

        if (!$student->is_active) {
            throw ValidationException::withMessages([
                'nisn' => 'Akun Anda belum aktif. Hubungi admin.',
            ]);
        }

        $stored = (string) $student->password;
        $input  = (string) $request->password;

        $valid = \Illuminate\Support\Facades\Hash::check($input, $stored)
            || hash_equals($stored, $input);

        if (!$valid) {
            throw ValidationException::withMessages([
                'password' => 'Password salah.',
            ]);
        }

        auth()->login($student);

        return redirect()->intended(route('redirect.after.login'));
    }
}
