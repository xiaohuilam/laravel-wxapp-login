<?php
$composerJsonFile = __DIR__ . '/../composer.json';
$composerJson = json_decode(file_get_contents($composerJsonFile), true);

$laravelVer = str_replace('.*', '', $composerJson['require-dev']['laravel/laravel']);
$laravelVerUrl = 'https://raw.githubusercontent.com/laravel/laravel/' . ($laravelVer == '5.8' ? 'master' : $laravelVer) . '/composer.json';
$laravelComposerJson = json_decode(file_get_contents($laravelVerUrl), true);

$composerJson['require-dev'] = array_merge($composerJson['require-dev'], $laravelComposerJson['require-dev']);

file_put_contents($composerJsonFile, str_replace('\/', '/', json_encode($composerJson)));
