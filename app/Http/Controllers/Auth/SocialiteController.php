<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        $this->ensureProviderIsSupported($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $this->ensureProviderIsSupported($provider);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::query()
            ->where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$user && $socialUser->getEmail()) {
            $user = User::query()->where('email', $socialUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'Customer',
                'email' => $socialUser->getEmail() ?: Str::uuid()->toString() . '@example.local',
                'password' => Str::password(32),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'role' => 'customer',
                'is_admin' => false,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        } else {
            $user->forceFill([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ])->save();
        }

        $role = Role::firstOrCreate(['name' => 'Customer']);
        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }

        Auth::login($user, true);

        return redirect()->route('home');
    }

    private function ensureProviderIsSupported(string $provider): void
    {
        abort_unless(in_array($provider, ['google', 'facebook'], true), 404);
    }
}
