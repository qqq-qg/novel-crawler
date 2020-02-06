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
}
