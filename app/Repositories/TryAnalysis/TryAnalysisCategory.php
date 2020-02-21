<?php

namespace App\Repositories\TryAnalysis;

use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

class TryAnalysisCategory
{
    /**
     * @var QueryList $ql
     */
    private $url, $ql;

    private static $categoryRules = [
        [
            'type' => 'single',
            'node' => 'ul',
            'rules' => [
                'title' => ['li>a', 'text'],
                'from_url' => ['li>a', 'href']
            ],
            'limit_count' => 30
        ],
        [
            'type' => 'single',
            'node' => 'dl',
            'rules' => [
                'title' => ['dd>a', 'text'],
                'from_url' => ['dd>a', 'href']
            ],
            'limit_count' => 30
        ],
//        [
//            'type' => 'multiple',
//            'node' => 'ul',
//            'rules' => [
//                'title' => ['li>a', 'text'],
//                'from_url' => ['li>a', 'href']
//            ],
//            'limit_count' => 10
//        ],
    ];

    public function __construct($url, $ql = null)
    {
        $this->url = $url;
        $this->ql = $ql;
    }

    public function handle()
    {
        if (is_null($this->ql)) {
            $this->ql = QueryList::get($this->url, [], ['timeout' => 30]);
            $this->ql->use(FilterHeader::class)->filterHeader();
            $this->ql->encoding(BookRule::CHARSET_UTF8);
        }
        foreach (self::$categoryRules as $categoryRule) {
            $res = call_user_func_array([$this, $categoryRule['type']], ['categoryRule' => $categoryRule]);
            if (!empty($res)) {
                return $res;
            }
        }
        return [];
    }

    private function single($categoryRule)
    {
        $chapterListArr = [];
        $ulHtmlList = $this->ql->find($categoryRule['node'])->htmls();
        foreach ($ulHtmlList ?? [] as $ulHtml) {
            $temp = QueryList::html($ulHtml)->rules($categoryRule['rules'])->queryData();
            if (is_array($temp) && count($temp) >= $categoryRule['limit_count']) {
                $chapterListArr[] = $temp;
            }
        }
        if (empty($chapterListArr)) {
            return [];
        }
        $chapterList = collect($chapterListArr)->sortByDesc(function ($items, $key) {
            return count($items);
        })->first();
        foreach ($chapterList ?? [] as $k => $info) {
            $chapterList[$k]['title'] = $info['title'] ?? '';
            $chapterList[$k]['from_url'] = get_full_url($info['from_url'] ?? '', $this->url);
        }
        return $chapterList;
    }

    private function multiple($categoryRule)
    {
        var_dump('multiple...');
        return [];
    }
}
