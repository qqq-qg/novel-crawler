<?php

namespace App\Repositories\Searcher\Plugin;

use QL\Contracts\PluginContract;
use QL\QueryList;

class FilterHeader implements PluginContract
{
    protected $ql;

    public function __construct($ql)
    {
        $this->ql = $ql;
    }

    public static function install(QueryList $queryList, ...$opt)
    {
        $queryList->bind('filterHeader', function ($url = null) {
            return (new FilterHeader($this))->get($url);
        });
    }

    public function get($url)
    {
        if (!empty($url)) {
            $html = $this->ql->get($url)->getHtml();
        } else {
            $html = $this->ql->getHtml();
        }
        $html = preg_replace('/<head.+?>.+<body>/is', '<body>', $html);
        $this->ql->setHtml($html);
        return $this->ql;
    }
}
