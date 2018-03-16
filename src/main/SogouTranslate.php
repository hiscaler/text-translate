<?php

namespace yadjet\texTranslate;

use GuzzleHttp\Client;
use HTMLPurifier;
use InvalidArgumentException;

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
        $pairs = [];
        $htmlPurifier = new HTMLPurifier();
        $imgReplacePrefix = '0000111100001111';
        foreach ($text as $key => $string) {
            $translatedTexts[$key] = null;
            $string = strip_tags($string, '<img>');
            $string = $htmlPurifier->purify($string);
            preg_match_all('/<img.*>?/', $string, $matches);
            if ($matches) {
                foreach ($matches[0] as $matchKey => $matchValue) {
                    $pairs[$key][$matchValue] = $imgReplacePrefix . $matchKey;
                }
            }

            if (isset($pairs[$key])) {
                $string = strtr($string, $pairs[$key]);
            }

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
                $body = $result['translate']['dit'];
                if (isset($pairs[$key]) && $pairs[$key]) {
                    $body = strtr($body, array_flip($pairs[$key]));
                }
                $translatedTexts[$key] = nl2br($body);
            }
        }

        return $returnArray ? $translatedTexts : array_shift($translatedTexts);
    }

}