<?php

namespace yadjet\texTranslate;

use GuzzleHttp\Client;
use InvalidArgumentException;
use function print_r;

/**
 * Class SogouTranslate
 *
 * @package yadjet\texTranslate
 * @author hiscaler <hiscaler@gmail.com>
 */
class SogouTranslate extends Translate
{

    protected $_params = [
        'client' => 'pc',
        'fr' => 'browser_pc',
    ];

    public function __construct()
    {
        $this->_fromLanguage = 'auto';
    }

    public function translate($text)
    {
        if ($this->_fromLanguage == $this->_toLanguage) {
            throw new InvalidArgumentException('无效的 fromLanuage 和 toLanguage 参数');
        }
        $translatedTexts = [];
        if (!is_array($text)) {
            $returnArray = false;
            $text = [(string) $text];
        } else {
            $returnArray = true;
        }
        $client = new Client();
        $promises = [];
        foreach ($text as $key => $string) {
            $translatedTexts[$key] = null;
            $params = array_merge($this->_params, [
                'from' => $this->_fromLanguage,
                'to' => $this->_toLanguage,
                'text' => $string,
            ]);
            $promises[$key] = $client->postAsync('https://fanyi.sogou.com/reventondc/translate', [
                'debug' => false,
                'headers' => [
                    'Accept' => 'text/html',
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
                ],
                'form_params' => $params
            ]);
        }
        $results = \GuzzleHttp\Promise\unwrap($promises);
        foreach ($results as $key => $result) {
            $result = json_decode($result->getBody(), true);
            if (isset($result['translate']['dit'])) {
                $translatedTexts[$key] = $result['translate']['dit'];
            }
        }

        return $returnArray ? $translatedTexts : array_shift($translatedTexts);
    }

}