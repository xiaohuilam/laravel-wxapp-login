<?php
$composerJsonFile = __DIR__ . '/../composer.json';
echo 'composerJsonFile = ' . $composerJsonFile . PHP_EOL;
echo 'exists = ' . var_export(file_exists($composerJsonFile), 1) . PHP_EOL;

$fp = fopen($composerJsonFile, 'r');
$json = fread($fp, 100000);

$composerJson = json_decode($json, true);
$laravelVer = str_replace('.*', '', $composerJson['require-dev']['laravel/laravel']);
$laravelVerUrl = 'https://raw.githubusercontent.com/laravel/laravel/' . $laravelVer . '/composer.json';
echo 'laravelVerUrl = ' . $laravelVerUrl . PHP_EOL;
$laravelComposerJson = json_decode(file_get_contents($laravelVerUrl), true);
echo json_encode($laravelComposerJson) . PHP_EOL;

$composerJson['require-dev'] = array_merge($composerJson['require-dev'], $laravelComposerJson['require-dev']);

$fp = fopen($composerJsonFile, 'w');
fwrite($fp, str_replace('\/', '/', json_encode($composerJson)));
