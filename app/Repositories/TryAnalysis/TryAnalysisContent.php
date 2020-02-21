<?php

namespace App\Repositories\TryAnalysis;

use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

class TryAnalysisContent
{
    /**
     * @var QueryList $ql
     */
    private $url, $ql;

    public function __construct($url, $ql = null)
    {
        $this->url = $url;
        $this->ql = $ql;
    }

    public function handle()
    {
        $contentArr = [];
        if (is_null($this->ql)) {
            $this->ql = QueryList::get($this->url, [], ['timeout' => 30]);
            $this->ql->use(FilterHeader::class)->filterHeader();
            $this->ql->encoding(BookRule::CHARSET_UTF8);
        }
        $pHtmlList = $this->ql->find('div>p')->parent('div')->htmls();
        foreach ($pHtmlList ?? [] as $pHtml) {
            $temp = QueryList::html($pHtml)->find('p')->htmls();
            if (!empty($temp) && count($temp) > 0) {
                $contentArr[] = $temp->map(function ($val) {
                    return "<p>{$val}</p>";
                });
            }
        }
        $content = collect($contentArr)->sortByDesc(function ($items, $key) {
            return count($items);
        })->first()->toArray();

        return join("<br/>", $content ?? []);
    }
}
