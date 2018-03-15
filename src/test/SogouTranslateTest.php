<?php
include '../../vendor/autoload.php';
include '../main/SogouTranslate.php';
$englishStrings = [
    'China',
    'Hello World.',
];

$sogou = (new \yadjet\texTranslate\SogouTranslate())
    ->setFromLanguage('auto')
    ->setToLanguage('zh-CHS');

foreach ($englishStrings as $string) {
    echo "`$string` :" . $sogou->translate($string) . PHP_EOL;
}