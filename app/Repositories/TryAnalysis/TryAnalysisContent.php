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
//        if (is_null($this->ql)) {
//            $this->ql = QueryList::get($this->url, [], ['timeout' => 30]);
//            $this->ql->use(FilterHeader::class)->filterHeader();
//            $this->ql->encoding(BookRule::CHARSET_UTF8);
//        }
        $this->ql = QueryList::html(file_get_contents('t.html'));
        $rootDivHtmlArr = $this->ql->find('body')->children('div')->map(function ($item) {
            return $item->htmlOuter();
        });
        $leafArr = [];
        foreach ($rootDivHtmlArr as $rootDivHtml) {
            $tmp = $this->findDivLeaf($rootDivHtml);
            $leafArr = array_merge($leafArr, $tmp);
        }
        //刷选 $leafArr

        dd($leafArr);
        //
        $pHtmlList = $this->ql->find('div>p')->parent('div')->htmls();
        foreach ($pHtmlList ?? [] as $pHtml) {
            $temp = QueryList::html($pHtml)->find('p')->htmls();
            if (!empty($temp) && count($temp) > 0) {
                $contentArr[] = $temp->map(function ($val) {
                    return "<p>{$val}</p>";
                });
            }
        }
        if (empty($contentArr)) {
            return '';
        }
        $content = collect($contentArr)->sortByDesc(function ($items, $key) {
            return count($items);
        })->first()->toArray();

        return join("<br/>", $content ?? []);
    }


    private function findDivLeaf($divHtml)
    {
        $leaf = [];
        $subArr = QueryList::html($divHtml)->find('div:eq(0)')->children('div')->map(function ($item) {
            return $item->htmlOuter();
        });
        if (count($subArr) === 0) {
            return [$divHtml];
        }
        foreach ($subArr as $_sub) {
            $tmp = $this->findDivLeaf($_sub);
            $leaf = array_merge($leaf, $tmp);
        }
        return $leaf;
    }
}
