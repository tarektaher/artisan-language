<?php

namespace Tarektaher\ArtisanLanguage\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveLanguageKeyCommand extends Command
{
    protected $signature = 'lang:remove {key} {--lang=}';
    protected $description = 'Remove a language key from a language file';

    public function handle()
    {
        $key = $this->argument('key');
        $lang = $this->option('lang') ?? config('artisan-language.default_language'); // Use default language if not specified

        $langFile = base_path("lang/{$lang}.json");

        if (!File::exists($langFile)) {
            $this->error("Language file '{$lang}.json' does not exist.");
            return 1;
        }

        $translations = json_decode(File::get($langFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("The language file '{$lang}.json' is not a valid JSON file.");
            return 1;
        }

        if (!isset($translations[$key])) {
            $this->error("The key '{$key}' does not exist in '{$lang}.json'.");
            return 1;
        }

        unset($translations[$key]);

        File::put($langFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info("Removed key '{$key}' from '{$lang}.json'.");

        return 0;
    }
}
