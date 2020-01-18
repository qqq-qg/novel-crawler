<?php

namespace App\Repositories\CollectionRule;

/**
 * Class BookRule
 * @author Nacrane
 * @Date: 2020/01/18
 * @Time: 16:07
 * @package App\Repositories\CollectionRule
 * @property string $baseUrl URL路径
 * @property string $charset 编码
 * @property QlRule $category 分类规则
 * @property QlRule $home 简介规则
 * @property QlRule $chapterList 章节列表规则
 * @property QlRule $content 内容规则
 */
Class BookRule
{
    public function __construct($url, $charset = '')
    {
        $this->baseUrl = $url;
        $this->charset = $charset;
    }
}
