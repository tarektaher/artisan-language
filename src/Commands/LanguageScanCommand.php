<?php

namespace Tarektaher\ArtisanLanguage\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LanguageScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lg:scan
                            {--lang= : The target language (default: configured default language)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan configured paths for language keys and synchronize missing keys with the JSON language file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get configuration
        $lang = $this->option('lang') ?? config('artisan-language.default_language');
        $scanPaths = config('artisan-language.scan_paths', []);
        $scanPattern = config('artisan-language.scan_pattern', '/(@lang|__)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/U');
        $langPath = config('artisan-language.lang_path', base_path('lang'));
        $langFile = "{$langPath}/{$lang}.json";

        if (!File::exists($langFile)) {
            $this->error("Language file '{$lang}.json' does not exist.");
            return 1;
        }

        // Get existing translations
        $translations = json_decode(File::get($langFile), true) ?? [];

        // Extract language keys from configured paths
        $extractedKeys = $this->extractKeysFromPaths($scanPaths, $scanPattern);

        // Find missing keys
        $missingKeys = $extractedKeys->diffKeys($translations);

        if ($missingKeys->isEmpty()) {
            $this->info("No missing keys found.");
            return 0;
        }

        // Update the language file
        $newTranslations = $translations + $missingKeys->mapWithKeys(function ($value, $key) {
                return [$key => '']; // Add empty values for missing keys
            })->toArray();

        // Save updated language file
        File::put($langFile, json_encode($newTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Language file '{$lang}.json' has been updated with missing keys.");
        return 0;
    }

    /**
     * Extract translation keys from configured paths.
     *
     * @param array $paths
     * @param string $pattern
     * @return \Illuminate\Support\Collection
     */
    protected function extractKeysFromPaths($paths, $pattern)
    {
        $keys = collect();

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                $this->warn("Path '{$path}' does not exist and will be skipped.");
                continue;
            }

            $files = collect(File::allFiles($path))->filter(function ($file) {
                // Filter for supported extensions: .php, .js, .vue
                return in_array($file->getExtension(), ['php', 'js', 'vue']);
            });

            foreach ($files as $file) {
                $content = File::get($file);

                // Match translation functions based on pattern
                preg_match_all($pattern, $content, $matches);

                if (!empty($matches[3])) {
                    foreach ($matches[3] as $key) {
                        $keys->put($key, '');
                    }
                }
            }
        }

        return $keys;
    }
}
