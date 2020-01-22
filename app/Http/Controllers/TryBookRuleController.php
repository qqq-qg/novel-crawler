<?php

namespace App\Http\Controllers;

use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;
use App\Repositories\Searcher\Plugin\CurlMulti;
use QL\QueryList;

class TryBookRuleController extends Controller
{
    /**
     * @var BookRule $bookRule
     */
    private $bookRule;
    private $url;
    private $urls;

    public function wxs2()
    {
        $bookRule = new BookRule();
        $bookRule->host = 'www.2wxs.com';
        $bookRule->charset = BookRule::CHARSET_GBK;
        $bookRule->bookList = [
            'category' => new QlRule('',
                [
                    'url' => ['ul.main_con>li span.bookname>a', 'href']
                ], true, 2),

            'ranking' => new QlRule('',
                [
                    'url' => ['div.rank_d_list>div.rank_d_book_img>a', 'href']
                ], true, 2)
        ];
        $bookRule->home = new QlRule('',
            [
                'title' => ['div.btitle>h1', 'text'],
                'words_count' => ['none', ''],
                'chapter_list_url' => ['self', ''],
            ]);
        $bookRule->chapterList = new QlRule('',
            [
                'title' => ['dl.chapterlist dd>a', 'text'],
                'from_url' => ['dl.chapterlist dd>a', 'href']
            ]);
        $bookRule->content = new QlRule('', [
            'content' => ['div#BookText', 'html']
        ]);
        $this->bookRule = $bookRule;

        $url = 'https://www.2wxs.com/xsbook/2/{$page}.html';
        $page = rand(1, 10);
        $url = str_replace('{$page}', rand(1, 10), $url);
        echo "获取目录 --page={$page}";
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($url)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->bookList['category']->range)
                ->rules($this->bookRule->bookList['category']->rules)
                ->query()->getData()->all();
        } else {
            $data = QueryList::get($url)
                ->range($this->bookRule->bookList['category']->range)
                ->rules($this->bookRule->bookList['category']->rules)
                ->query()->getData()->all();
        }
        if (empty($data)) {
            dd(1);
        }
        dd($data);


        $this->url = $url = 'https://www.2wxs.com/xstxt/279882/';
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($url)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        } else {
            $data = QueryList::get($url)
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        }
        if (empty($data)) {
            dd(1);
        }
        $_bookData = [
            'title' => trim($data['title'] ?? ''),
            'words_count' => trim($data['words_count'] ?? ''),
        ];
        echo "title => {$_bookData['title']} <br/>";
        $chapterListUrl = trim($data['chapter_list_url'] ?? $this->url);
        $chapterListUrl = get_full_url($chapterListUrl, $url);
        echo '$chapterListUrl => ' . "{$chapterListUrl} <br/>";

        //
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($chapterListUrl)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->chapterList->range)
                ->rules($this->bookRule->chapterList->rules)
                ->query()->getData()->all();
        } else {
            $data = QueryList::get($chapterListUrl)
                ->range($this->bookRule->chapterList->range)
                ->rules($this->bookRule->chapterList->rules)
                ->query()->getData()->all();
        }

        if (empty($data)) {
            dd(2);
        }
        $urls = [];
        foreach ($data as $k => $item) {
            $from_url = trim($item['from_url']);
            $urls[] = get_full_url($from_url, $this->url);
        }
        $url = $urls[array_rand($urls)];
        echo " -- Random Url => {$url} <br/>";
        $this->urls = [$url];
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($this->urls)
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                if ($this->bookRule->needEncoding()) {
                    $html = $ql->removeHead()
                        ->encoding('utf-8', $this->bookRule->charset)->getHtml();
                    $data = $ql
                        ->setHtml($html)
                        ->range($this->bookRule->content->range)
                        ->rules($this->bookRule->content->rules)
                        ->query()->getData()->first();
                } else {
                    $data = $ql
                        ->range($this->bookRule->content->range)
                        ->rules($this->bookRule->content->rules)
                        ->query()->getData()->first();
                }

                if (empty($data)) {
                    dd(3);
                }
                $content = trim($data['content'] ?? '');

                echo "Content => <p>{$content}</p> <br/>";
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Current url:{$errorInfo['info']['url']} <br/>";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 10,
                'maxTry' => 3,
            ]);
    }

    public function bqg()
    {
        $bookRule = new BookRule();
        $bookRule->host = 'www.biquge.lu';
        $bookRule->charset = BookRule::CHARSET_GBK;
        $bookRule->bookList = [
            'category' => new QlRule('',
                [
                    'url' => ['div.l ul>li span.s2>a', 'href']
                ], true, 1),

            'ranking' => new QlRule('',
                [
                    'url' => ['ul.tli li a', 'href']
                ], true, 2)
        ];
        $bookRule->home = new QlRule('',
            [
                'title' => ['div.book h2', 'text'],
                'words_count' => ['none', ''],
                'chapter_list_url' => ['self', ''],
            ]);
        $bookRule->chapterList = new QlRule('',
            [
                'title' => ['.listmain dd>a', 'text'],
                'from_url' => ['.listmain dd>a', 'href']
            ]);
        $bookRule->content = new QlRule('', [
            'content' => ['div#content', 'html', 'script']
        ]);
        $this->bookRule = $bookRule;


        $url = 'https://www.biquge.lu/paihangbang/';
        echo "获取目录 --page=1";
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($url)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->bookList['ranking']->range)
                ->rules($this->bookRule->bookList['ranking']->rules)
                ->query()->getData()->all();
        } else {
            $data = QueryList::get($url)
                ->range($this->bookRule->bookList['ranking']->range)
                ->rules($this->bookRule->bookList['ranking']->rules)
                ->query()->getData()->all();
        }
        if (empty($data)) {
            dd(1);
        }
        dd($data);


        $this->url = $url = 'https://www.biquge.lu/book/58046/';
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($url)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        } else {
            $data = QueryList::get($url)
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        }
        if (empty($data)) {
            dd(1);
        }
        $_bookData = [
            'title' => trim($data['title'] ?? ''),
            'words_count' => trim($data['words_count'] ?? ''),
        ];
        echo "title => {$_bookData['title']} <br/>";
        $chapterListUrl = trim($data['chapter_list_url'] ?? $this->url);
        $chapterListUrl = get_full_url($chapterListUrl, $url);
        echo '$chapterListUrl => ' . "{$chapterListUrl} <br/>";

        //
        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($chapterListUrl)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->chapterList->range)
                ->rules($this->bookRule->chapterList->rules)
                ->query()->getData()->all();
        } else {
            $data = QueryList::get($chapterListUrl)
                ->range($this->bookRule->chapterList->range)
                ->rules($this->bookRule->chapterList->rules)
                ->query()->getData()->all();
        }

        if (empty($data)) {
            dd(2);
        }
        $urls = [];
        foreach ($data as $k => $item) {
            $from_url = trim($item['from_url']);
            $urls[] = get_full_url($from_url, $this->url);
        }
        $url = $urls[array_rand($urls)];
        echo " -- Random Url => {$url} <br/>";
        $this->urls = [$url];
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($this->urls)
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                if ($this->bookRule->needEncoding()) {
                    $html = $ql->removeHead()
                        ->encoding('utf-8', $this->bookRule->charset)->getHtml();
                    $data = $ql
                        ->setHtml($html)
                        ->range($this->bookRule->content->range)
                        ->rules($this->bookRule->content->rules)
                        ->query()->getData()->first();
                } else {
                    $data = $ql
                        ->range($this->bookRule->content->range)
                        ->rules($this->bookRule->content->rules)
                        ->query()->getData()->first();
                }

                if (empty($data)) {
                    dd(3);
                }
                $content = trim($data['content'] ?? '');

                echo "Content => <p>{$content}</p> <br/>";
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Current url:{$errorInfo['info']['url']} <br/>";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 10,
                'maxTry' => 3,
            ]);
    }
}
