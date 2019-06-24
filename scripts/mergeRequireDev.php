<?php
$composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
$laravelVer = str_replace('.*', '', $composerJson['require-dev']['laravel/laravel']);
$laravelComposerJson = json_decode(file_get_contents('https://raw.githubusercontent.com/laravel/laravel/' . $laravelVer . '/composer.json'), true);

$composerJson['require-dev'] = array_merge($composerJson['require-dev'], $laravelComposerJson['require-dev']);
file_put_contents(__DIR__ . '/../composer.json', str_replace('\/', '/', json_encode($composerJson)));
