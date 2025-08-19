<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        /**
         * Accept email OR username in the single "email" field sent by the SPA.
         */
        Fortify::authenticateUsing(function (Request $request) {
            $identifier = $request->input('email'); // can be email OR username
            $password   = $request->input('password');

            $user = User::query()
                        ->where('email', $identifier)
                        ->orWhere('username', $identifier)
                        ->first();

            if ($user && Hash::check($password, $user->password)) {
                return $user;
            }

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        });

        /**
         * Rate limit the login.
         */
        RateLimiter::for('login', function (Request $request) {
            // We throttle by the raw identifier (email or username) + IP.
            $loginKey = Str::transliterate(Str::lower((string) $request->input('email')));
            return Limit::perMinute(5)->by($loginKey.'|'.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /**
         * SPA notes:
         * - Don’t register Fortify views in a Vue SPA.
         * - On success, Fortify returns 204 for JSON/SPA requests; Axios code should treat 204 as success and redirect to /dashboard.
         */
    }
}
