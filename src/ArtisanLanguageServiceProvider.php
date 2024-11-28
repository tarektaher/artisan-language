<?php

namespace YourVendorName\ArtisanLanguage;

use Illuminate\Support\ServiceProvider;

class ArtisanLanguageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Register your commands here
                Commands\AddLanguageKeyCommand::class,
                //Commands\RemoveLanguageKeyCommand::class,
                // Add more commands as needed
            ]);
        }
    }

    public function register()
    {
        // Bind services or repositories if necessary
    }
}
