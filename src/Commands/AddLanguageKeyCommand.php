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

        if (empty($key) || empty($value)) {
            $this->error('Both key and value are required.');
            return 1;
        }

        if (!preg_match('/^[a-zA-Z0-9_.]+$/', $key)) {
            $this->error('The key can only contain alphanumeric characters, dots, and underscores.');
            return 1;
        }

        $supportedLanguages = config('artisan-language.languages');
        if (!array_key_exists($lang, $supportedLanguages)) {
            $this->error("The language '{$lang}' is not supported.");
            return 1;
        }

        $this->processLanguageKey($key, $value, $lang);

        return 0;
    }

    protected function processLanguageKey($key, $value, $lang)
    {
        try {
            $langFile = base_path("lang/{$lang}.json");

            if (!File::exists($langFile)) {
                File::put($langFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info("Created new language file '{$lang}.json'");
            }

            $translations = json_decode(File::get($langFile), true);

            if (isset($translations[$key])) {
                $this->warn("The key '{$key}' already exists. It will be overwritten.");
            }

            $translations[$key] = $value;

            File::put($langFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->info("Added key '{$key}' with value '{$value}' to '{$lang}.json'");
        } catch (\Exception $e) {
            $this->error("Failed to update the language file: {$e->getMessage()}");
        }
    }


}
