<?php

namespace App\Repositories\CollectionRule;

/**
 * Class BookRule
 * @author Nacrane
 * @Date: 2020/01/18
 * @Time: 16:07
 * @package App\Repositories\CollectionRule
 * @property string $host 域名
 * @property string $charset 编码
 * @property QlRule[] $bookList 分类规则
 * @property QlRule $home 简介规则
 * @property QlRule $chapterList 章节列表规则
 * @property QlRule $content 内容规则
 * @property string $splitTag
 * @property array $replaceTags
 */
Class BookRule
{
    const CHARSET_UTF8 = 'utf-8';
    const CHARSET_GBK = 'gbk';

    public $host = '';
    public $charset = '';
    public $splitTag = '';
    public $replaceTags = [];

    public function __construct($host = '', $charset = '')
    {
        $this->host = $host;
        $this->charset = $charset;
    }

    public function toArray()
    {
        return [
            'host' => $this->host,
            'charset' => $this->charset,
            'bookList' => array_map(function (QlRule $rule) {
                return $rule->toArray();
            }, $this->bookList),
            'home' => $this->home->toArray(),
            'chapterList' => $this->chapterList->toArray(),
            'content' => $this->content->toArray(),
            'splitTag' => $this->splitTag,
            'replaceTags' => $this->replaceTags,
        ];
    }

    public function needEncoding()
    {
        if (empty($this->charset) || $this->charset == 'utf-8') {
            return false;
        }
        return true;
    }
}
