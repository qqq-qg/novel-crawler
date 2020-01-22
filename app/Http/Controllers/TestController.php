<?php

namespace App\Http\Controllers;

use App\Jobs\BooksContentMultiJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use QL\QueryList;

class TestController extends Controller
{
    /**
     * @var BookRule $bookRule
     */
    private $bookRule;
    private $chapterUrl;

    public function index()
    {
        $rule = CollectionRuleModel::query()->where('id', 3)->first();
        $this->bookRule = unserialize($rule->rule_json);
        $urls = BooksChapterModel::query()
            ->select('from_url')
            ->where('books_id',1)
            ->orderBy('chapter_index','asc')
            ->get()->pluck('from_url')->toArray();

        dispatch(new BooksContentMultiJob($this->bookRule, $urls))->onQueue('Content');
        die();

        $this->chapterUrl = 'https://www.biquge.lu/book/58046/497205808.html';
        $this->chapterUrl = 'http://www.ql.com/content.html';
//        file_put_contents('content.html', QueryList::get($this->chapterUrl)->getHtml());
//        dd();
        $this->bookRule->content->rules['content'][2] = '-script';
        $this->bookRule->splitTag = '(https';
        $this->bookRule->replaceTags = [];
//        dd($this->bookRule->content);
//        print_r($this->bookRule->content->rules);

        if ($this->bookRule->needEncoding()) {
            $html = QueryList::get($this->chapterUrl)->removeHead()
                ->encoding('utf-8', $this->bookRule->charset)->getHtml();
            $data = QueryList::getInstance()
                ->setHtml($html)
                ->range($this->bookRule->content->range)
                ->rules($this->bookRule->content->rules)
                ->query()->getData()->first();
        } else {
            $data = QueryList::get($this->chapterUrl)
                ->range($this->bookRule->content->range)
                ->rules($this->bookRule->content->rules)
                ->query()->getData()->first();
        }
        $content = trim($data['content'] ?? '');
        if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
            $content = explode($this->bookRule->splitTag, $content)[0];
        }
        foreach ($this->bookRule->replaceTags ?? [] as $tag) {
            $content = str_replace($tag[0], $tag[1] ?? '', $content);
        }
        dd($content);
    }
}
