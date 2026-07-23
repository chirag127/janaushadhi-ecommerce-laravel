<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    private const PROVIDERS = ['google', 'github', 'microsoft', 'facebook'];

    public function redirect(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            abort(404);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => ucfirst($provider).' sign-in is not available right now.']);
        }
    }

    public function callback(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            abort(404);
        }

        try {
            $oauthUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Could not sign you in with '.ucfirst($provider).'. Please try again.']);
        }

        $email = $oauthUser->getEmail();
        if (empty($email)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your '.ucfirst($provider).' account has no email address to link.']);
        }

        $providerId = $oauthUser->getId();

        // 1. Exact identity match: same provider + provider_id -> always safe.
        $user = User::where('provider', $provider)->where('provider_id', $providerId)->first();

        // 2. Email match: only auto-link to a pre-existing account when the
        //    provider asserts the email is VERIFIED. Otherwise an attacker could
        //    set a victim's email as their unverified provider email and hijack
        //    the victim's password account. Google always verifies; GitHub/MS/
        //    Facebook may not.
        if (! $user) {
            $existing = User::where('email', $email)->first();
            if ($existing) {
                if (! $this->providerEmailVerified($provider, $oauthUser)) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'An account with this email already exists. Please sign in with your password, or use a provider that verifies your email.',
                    ]);
                }
                $existing->fill(array_filter([
                    'provider' => $existing->provider ?: $provider,
                    'provider_id' => $existing->provider_id ?: $providerId,
                    'avatar' => $existing->avatar ?: $oauthUser->getAvatar(),
                ]));
                $existing->save();
                $user = $existing;
            }
        }

        // 3. No match -> create a fresh customer account.
        if (! $user) {
            $user = User::create([
                'name' => $oauthUser->getName() ?: $email,
                'email' => $email,
                'email_verified_at' => now(),
                'role' => User::ROLE_CUSTOMER,
                'provider' => $provider,
                'provider_id' => $providerId,
                'avatar' => $oauthUser->getAvatar(),
                'password' => null,
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        $target = $user->isAdmin() ? route('admin.dashboard') : route('dashboard');

        return redirect()->intended($target);
    }

    /**
     * Whether the provider asserts the OAuth email is verified. Google always
     * verifies. Others expose a flag on the raw payload; default to false
     * (safe) when unknown.
     */
    private function providerEmailVerified(string $provider, $oauthUser): bool
    {
        if ($provider === 'google') {
            return true;
        }
        $raw = (array) ($oauthUser->user ?? []);

        return (bool) ($raw['email_verified'] ?? $raw['verified'] ?? false);
    }
}
