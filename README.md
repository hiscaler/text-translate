# text-translate
利用在线翻译接口翻译文本内容

## 安装
composer require "yadjet/text-translate:dev-master" 

## 使用
### 搜狗翻译
```php
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
```

