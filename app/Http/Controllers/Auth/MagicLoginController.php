<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MagicLoginController extends Controller
{
    public function __invoke(Request $request, string $token): RedirectResponse
    {
        $hashedToken = hash('sha256', $token);

        $user = User::where('role', 'owner')
            ->where('is_active', true)
            ->whereNotNull('approved_at')
            ->where('login_magic_token_hash', $hashedToken)
            ->where('login_magic_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Tautan login sudah kedaluwarsa atau tidak valid.',
                ]);
        }

        $user->forceFill([
            'login_magic_token_hash' => null,
            'login_magic_token_expires_at' => null,
        ])->save();

        Auth::logout();
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('redirect.after.login');
    }

    public static function makeTokenFor(User $user, int $minutes = 15): array
    {
        $token = Str::random(64);

        $user->forceFill([
            'login_magic_token_hash' => hash('sha256', $token),
            'login_magic_token_expires_at' => now()->addMinutes($minutes),
        ])->save();

        return [
            'token' => $token,
            'expires_at' => $user->login_magic_token_expires_at,
        ];
    }
}
