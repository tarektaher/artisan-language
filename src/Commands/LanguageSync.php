<?php

namespace Tarektaher\ArtisanLanguage\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LanguageSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:sync
                            {locale : The locale to synchronize}
                            {--nomissing : Skip missing strings}
                            {--nofurther : Skip further strings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize differences for the given locale';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $locale = $this->argument('locale');

        // Locale strings
        $localeStrings = $this->getLocaleStrings($locale);

        // Synchronize if locale strings exist
        if ($localeStrings) {
            $strings = $this->getBaseStrings();

            // Missing strings
            if (!$this->option('nomissing')) {
                $missing = $strings->diffKeys($localeStrings);
                if ($missing->isNotEmpty()) {
                    $merged = $missing->merge($localeStrings)->toArray();
                    ksort($merged, SORT_NATURAL | SORT_FLAG_CASE);
                    $this->updateFile(collect($merged), $locale);
                    $localeStrings = $this->getLocaleStrings($locale);
                    $this->info('File successfully synchronized for missing strings.');
                } else {
                    $this->info('No missing strings for this locale.');
                }
            }

            // Further strings
            if (!$this->option('nofurther')) {
                $further = $localeStrings->diffKeys($strings);
                if ($further->isNotEmpty()) {
                    $result = $localeStrings->except($further->keys()->all());
                    $this->updateFile($result, $locale);
                    $this->info('File successfully synchronized for further strings.');
                } else {
                    $this->info('No further strings for this locale.');
                }
            }
        }
    }

    protected function getLocaleStrings($locale)
    {
        $langFile = base_path("lang/{$locale}.json");

        if (!File::exists($langFile)) {
            $this->error("Language file '{$locale}.json' does not exist.");
            return null;
        }

        $content = json_decode(File::get($langFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in language file for '{$locale}'.");
            return null;
        }

        return collect($content);
    }

    protected function getBaseStrings()
    {
        $langFile = base_path("lang/en.json");

        if (!File::exists($langFile)) {
            $this->error("Base language file 'en.json' does not exist.");
            return collect();
        }

        $content = json_decode(File::get($langFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in base language file.");
            return collect();
        }

        return collect($content);
    }

    protected function updateFile($strings, $locale)
    {
        $langFile = base_path("lang/{$locale}.json");

        try {
            File::put($langFile, json_encode($strings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $this->error("Failed to update the language file for '{$locale}': {$e->getMessage()}");
        }
    }
}
