<?php

$composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
$laravelVer = str_replace('.*', '', $composerJson['require-dev']['laravel/laravel']);
$laravelVerUrl = 'https://raw.githubusercontent.com/laravel/laravel/' . $laravelVer . '/composer.json';
echo 'laravelVerUrl = ' . $laravelVerUrl . PHP_EOL;
$laravelComposerJson = json_decode(file_get_contents($laravelVerUrl), true);
echo json_encode($laravelComposerJson) . PHP_EOL;

$composerJson['require-dev'] = array_merge($composerJson['require-dev'], $laravelComposerJson['require-dev']);
file_put_contents(__DIR__ . '/../composer.json', str_replace('\/', '/', json_encode($composerJson)));
