<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Politique de mot de passe (nouvelle règle de sécurité) : au moins 8 caractères,
        // une majuscule, une minuscule, un chiffre et un caractère spécial.
        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers()->symbols();
        });
    }
}
