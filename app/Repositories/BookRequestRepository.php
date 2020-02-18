<?php

namespace App\Repositories;

use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Proxy\KuaiDaiLiProxy;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

class BookRequestRepository
{
    /**
     * @var BookRule $bookRule
     */
    private $bookRule;

    private $proxyService;
    private static $timeout = 5;

    public function __construct(BookRule $bookRule)
    {
        $this->bookRule = $bookRule;
        $this->proxyService = new KuaiDaiLiProxy();
    }

    public function getCategory($url)
    {
        $ql = QueryList::get($url, [], ['timeout' => self::$timeout]);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
        }
        $data = $ql
            ->range($this->bookRule->bookList['category']->range)
            ->rules($this->bookRule->bookList['category']->rules)
            ->query()->getData()->all();
        foreach ($data as $key => $homeUrl) {
            $data[$key]['url'] = get_full_url($homeUrl['url'], $url);
        }
        return $data;
    }

    public function getRanking($url)
    {
        $ql = QueryList::get($url, [], ['timeout' => self::$timeout]);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
        }
        $data = $ql
            ->range($this->bookRule->bookList['ranking']->range)
            ->rules($this->bookRule->bookList['ranking']->rules)
            ->query()->getData()->all();
        foreach ($data as $key => $homeUrl) {
            $data[$key]['url'] = get_full_url($homeUrl['url'], $url);
        }
        return $data;
    }

    public function getHome($url)
    {
        $ql = QueryList::get($url, [], ['timeout' => self::$timeout,]);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
        }
        $data = $ql
            ->range($this->bookRule->home->range)
            ->rules($this->bookRule->home->rules)
            ->query()->getData()->first();
        $_bookData = [
            'title' => trim($data['title'] ?? ''),
            'words_count' => trim($data['words_count'] ?? ''),
        ];
        $chapterListUrl = trim($data['chapter_list_url'] ?? $url);
        $chapterListUrl = get_full_url($chapterListUrl, $url);
        $_bookData['chapter_list_url'] = $chapterListUrl;
        return $_bookData;
    }

    public function getContent($chapterListUrl)
    {
        $ql = QueryList::get($chapterListUrl, [], ['timeout' => self::$timeout]);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
        }
        $data = $ql
            ->range($this->bookRule->chapterList->range)
            ->rules($this->bookRule->chapterList->rules)
            ->query()->getData()->all();
        foreach ($data as $k => $item) {
            $from_url = trim($item['from_url']);
            $from_url = get_full_url($from_url, $chapterListUrl);
            $data[$k]['from_url'] = $from_url;
        }
        $randItem = $data[array_rand($data)];

        $ql = QueryList::get($randItem['from_url'], [], ['timeout' => self::$timeout]);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
            if (!empty($this->bookRule->replaceTags)) {
                $html = $ql->getHtml();
                foreach ($this->bookRule->replaceTags ?? [] as $tag) {
                    $html = preg_replace($tag[0], $tag[1] ?? '', $html);
                }
                $ql->setHtml($html);
            }
        }
        $data = $ql
            ->range($this->bookRule->content->range)
            ->rules($this->bookRule->content->rules)
            ->query()->getData()->first();
        $content = trim($data['content'] ?? '');
        if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
            $content = explode($this->bookRule->splitTag, $content)[0];
        }
        $randItem['content'] = $content;
        return $randItem;
    }

    public static function tryPregCategory($url)
    {
        $chapterListArr = [];
        $ql = QueryList::get($url, [], ['timeout' => 30]);
        $ql->use(FilterHeader::class)->filterHeader();
        $ql->encoding(BookRule::CHARSET_UTF8);
        $ulHtmlList = $ql->find('ul')->htmls();
        foreach ($ulHtmlList ?? [] as $ulHtml) {
            $temp = QueryList::html($ulHtml)->rules([
                'title' => ['li>a', 'text'],
                'from_url' => ['li>a', 'href']
            ])->queryData();
            if (is_array($temp) && count($temp) >= 30) {
                $chapterListArr[] = $temp;
            }
        }
        $chapterList = collect($chapterListArr)->sortByDesc(function ($items, $key) {
            return count($items);
        })->first();

        foreach ($chapterList as $k => $info) {
            $chapterList[$k]['title'] = $info['title'] ?? '';
            $chapterList[$k]['from_url'] = get_full_url($info['from_url'] ?? '', $url);
        }
        return $chapterList;
    }

    public static function tryPregContent($url, $ql = null)
    {
        $contentArr = [];
        if (is_null($ql)) {
            $ql = QueryList::get($url, [], ['timeout' => 30]);
        }
        $ql->use(FilterHeader::class)->filterHeader();
        $ql->encoding(BookRule::CHARSET_UTF8);
        $pHtmlList = $ql->find('div>p')->parent('div')->htmls();
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
