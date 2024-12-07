<?php

return [
    'default_language' => 'en',
    'languages' => [
        'en' => 'English',
        'fr' => 'French',
    ],
    "scan_paths" => [
        app_path(),
        resource_path('views')
    ],
    'scan_pattern' => '/(@lang|__|\$t|\$tc|\$translate)\s*(\(\s*[\'"])([^$]*)([\'"]+\s*(,[^\)]*)*\))/U',
    "lang_path" => base_path('lang'),
];
