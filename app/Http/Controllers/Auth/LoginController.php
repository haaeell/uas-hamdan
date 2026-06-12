<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = '/redirect-after-login';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username(): string
    {
        return 'login';
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau NISN wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
    }

    protected function attemptLogin(Request $request): bool
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';
        $user = User::where($field, $login)->first();

        if (!$user) {
            return false;
        }

        if (!$user->is_active) {
            if ($user->role === 'owner' && !$user->approved_at) {
                throw ValidationException::withMessages([
                    'login' => 'Akun owner Anda masih menunggu persetujuan admin.',
                ]);
            }

            throw ValidationException::withMessages([
                'login' => 'Akun Anda belum aktif. Silakan hubungi admin.',
            ]);
        }

        $password = (string) $request->input('password');

        if ($user->role === 'siswa' && session('exam_owner_id') && (int) $user->owner_id !== (int) session('exam_owner_id')) {
            return false;
        }

        $storedPassword = (string) $user->password;

        if (!Hash::check($password, $storedPassword) && !hash_equals($storedPassword, $password)) {
            return false;
        }

        $this->guard()->login($user, $request->boolean('remember'));

        return true;
    }
}
