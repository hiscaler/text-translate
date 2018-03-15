<?php

namespace yadjet\texTranslate;

/**
 * Class Translate
 *
 * @package yadjet\texTranslate
 * @author hiscaler <hiscaler@gmail.com>
 */
abstract class Translate
{

    /**
     * 翻译前的文本语种
     *
     * @var string
     */
    protected $_fromLanguage;

    /**
     * 翻译后的文本语种
     *
     * @var string
     */
    protected $_toLanguage;

    /**
     * 其他参数
     *
     * @var array
     */
    protected $_params = [];

    /**
     * 需要翻译的文本
     *
     * @var string
     */
    public $sourceText;

    public function setFromLanguage($from)
    {
        $this->_fromLanguage = $from;

        return $this;
    }

    public function setToLanguage($to)
    {
        $this->_toLanguage = $to;

        return $this;
    }

    public function setParams($params)
    {
        $this->_params = $params;

        return $this;
    }

    public function setSourceText($text)
    {
        $this->sourceText = $text;

        return $this;
    }

    /**
     * 翻译操作
     *
     * @param $text string|array
     * @return boolean
     */
    abstract public function translate($text);
}