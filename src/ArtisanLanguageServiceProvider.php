<?php

namespace Tarektaher\ArtisanLanguage;

use Illuminate\Support\ServiceProvider;

class ArtisanLanguageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\LanguageScanCommand::class,
                Commands\LanguageSync::class,
                Commands\AddLanguageKeyCommand::class,
                Commands\RemoveLanguageKeyCommand::class,
            ]);
        }

        // Publier le fichier de configuration
        $this->publishes([
            __DIR__ . '/../config/artisan-language.php' => config_path('artisan-language.php'),
        ], 'artisan-language-config');

        // Publier les fichiers de traduction si nécessaire
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/artisan-language'),
        ], 'artisan-language-translations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/artisan-language.php',
            'artisan-language'
        );
    }
}
