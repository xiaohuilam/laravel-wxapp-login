<?php
$latestLaravelVerContent = file_get_contents('https://api.github.com/repos/xiaohuilam/laravel-wxapp-login/issues/10', false, stream_context_create([
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36\r\n"
    ],
]));
$json = json_decode($latestLaravelVerContent);

if (json_last_error() !== JSON_ERROR_NONE || !$latestLaravelVerContent || !$json || !isset($json->body)) {
    exit(1);
}

$ver = $json->body;

$composerJsonFile = __DIR__ . '/../composer.json';
$composerJson = json_decode(file_get_contents($composerJsonFile), true);

$laravelVer = str_replace('.*', '', $composerJson['require-dev']['laravel/laravel']);
$laravelVerUrl = 'https://raw.githubusercontent.com/laravel/laravel/' . ($laravelVer == $ver ? 'master' : $laravelVer) . '/composer.json';
$laravelComposerJson = json_decode(file_get_contents($laravelVerUrl), true);

$composerJson['require-dev'] = array_merge($composerJson['require-dev'], $laravelComposerJson['require-dev']);

file_put_contents($composerJsonFile, str_replace('\/', '/', json_encode($composerJson)));
