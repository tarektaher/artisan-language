
# Artisan Language

[![Latest Version](https://img.shields.io/github/v/release/tarektaher/artisan-language?label=latest%20version)](https://github.com/tarektaher/artisan-language/releases)
[![License](https://img.shields.io/github/license/tarektaher/artisan-language)](https://github.com/tarektaher/artisan-language/blob/main/LICENSE)
[![Download](https://img.shields.io/badge/Download-Artisan%20Language-blue)](https://github.com/tarektaher/artisan-language/archive/refs/tags/v1.0.0.zip)

**Artisan Language** is a Laravel package designed to streamline the management of translation keys in your application. It provides powerful Artisan commands to synchronize, scan, and manage translation keys for Blade templates, PHP files, and JavaScript files.

---

## **Features**
- Synchronize translation keys across multiple locales.
- Automatically update JSON language files with missing keys.
- Remove unused keys from JSON language files.
- Scan Blade, PHP, and JavaScript files for translation keys.
- Fully customizable through the configuration file.

---

## **Installation**

Install the package via Composer:

```bash
composer require tarektaher/artisan-language
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=artisan-language-config
```

This will create the configuration file at `config/artisan-language.php`.

---

## **Configuration**

The configuration file allows you to define paths, patterns, and settings for the package.

**Default Configuration:**
```php
return [
    'default_language' => 'en', // Default language for synchronization
    'languages' => [ // Supported languages
        'en' => 'English',
        'fr' => 'French',
    ],
    'scan_paths' => [ // Paths to scan for translation keys
        app_path(),
        resource_path('views'),
        resource_path('assets/js'),
    ],
    'scan_pattern' => '/(@lang|__|\$t|\$tc)\s*(\(\s*[\'"])([^$]*)([\'"]+\s*(,[^\)]*)*\))/U', // Regex pattern to detect keys
    'lang_path' => base_path('lang'), // Path to JSON language files
];
```

---

## **Commands**

### **1. Synchronize Translation Keys**
Synchronize translation keys between the base language (`en`) and a target language.

```bash
php artisan language:sync {locale}
```

**Options:**
- `--nomissing`: Skip adding missing keys from the base language file.
- `--nofurther`: Skip removing extra keys from the target language file.

**Example:**
```bash
php artisan language:sync fr --nomissing
```

---

### **2. Scan for Missing Keys**
Scan configured paths (Blade templates, PHP files, JavaScript files) for missing translation keys and update the JSON language files.

```bash
php artisan language:scan
```

**Options:**
- `--lang`: Specify the target language (default: `en`).

**Example:**
```bash
php artisan language:scan --lang=fr
```

---

### **3. Add Translation Keys**
Add a new translation key to the specified language file.

```bash
php artisan lang:add {key} {value} {--lang=}
```

**Example:**
```bash
php artisan lang:add welcome "Welcome to our app" --lang=fr
```

---

### **4. Remove Translation Keys**
Remove a translation key from the specified language file.

```bash
php artisan lang:remove {key} {--lang=}
```

**Example:**
```bash
php artisan lang:remove "Your email is invalid" --lang=fr
```

---

## **Usage Scenarios**

### **Synchronizing Keys**
To synchronize translation keys in the French locale:
```bash
php artisan language:sync fr
```

To skip adding missing keys:
```bash
php artisan language:sync fr --nomissing
```

To skip removing extra keys:
```bash
php artisan language:sync fr --nofurther
```

### **Scanning for Missing Keys**
To scan and automatically add all missing keys:
```bash
php artisan language:scan
```

To scan for missing keys in French:
```bash
php artisan language:scan --lang=fr
```

### **Adding New Keys**
To add a new translation key in English:
```bash
php artisan lang:add "welcome_message" "Welcome to our app" --lang=en
```

---

## **Customization**

You can customize paths, regex patterns, and language file locations in the configuration file (`config/artisan-language.php`).

---

## **Testing**

Run the test suite to ensure everything is working correctly:

```bash
php artisan test
```

---

## **Download**

You can download the latest release of Artisan Language here:

[![Download Artisan Language](https://img.shields.io/badge/Download-Artisan%20Language-blue)](https://github.com/tarektaher/artisan-language/archive/refs/tags/v1.0.0.zip)

---

## **Contributing**

Contributions are welcome! Feel free to fork this repository, submit a pull request, or open an issue on [GitHub](https://github.com/tarektaher/artisan-language/issues).

---

## **License**

This package is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT). See the [LICENSE](https://github.com/tarektaher/artisan-language/blob/main/LICENSE) file for details.

---

## **Support**

For issues or questions, please contact [Tarek Taher](https://github.com/tarektaher) or open a GitHub issue.

With **Artisan Language**, managing translations has never been easier. Start synchronizing and scanning your keys today! 🎉
