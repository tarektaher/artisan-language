<?php

namespace Tarektaher\ArtisanLanguage\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddLanguageKeyCommand extends Command
{
    protected $signature = 'lang:add {key} {value} {--lang=en}';
    protected $description = 'Add a new language key to a language file';

    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');
        $lang = $this->option('lang');

        $langFile = resource_path("lang/{$lang}.json");

        if (!File::exists($langFile)) {
            File::put($langFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $translations = json_decode(File::get($langFile), true);
        $translations[$key] = $value;

        File::put($langFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Added key '{$key}' with value '{$value}' to '{$lang}.json'");
    }
}
