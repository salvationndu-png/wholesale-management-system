<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\CustomLoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Bind Fortify’s LoginRequest to our CustomLoginRequest
        $this->app->bind(
            \Laravel\Fortify\Http\Requests\LoginRequest::class,
            CustomLoginRequest::class
        );

        // ✅ Make Fortify think the login field is "login"
        Fortify::username(fn () => 'login');

        // ✅ Allow multiple users to log in with only password
        Fortify::authenticateUsing(function (Request $request) {
            if (! $request->filled('password')) {
                return null;
            }

            foreach (\App\Models\User::all() as $user) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->status == 0) {
                        return null;
                    }
                    return $user;
                }
            }

            return null;
        });

        // Default Fortify hooks
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // ✅ Rate limit login attempts (per IP)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::lower($request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
