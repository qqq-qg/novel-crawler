<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use Illuminate\Support\Facades\Log;
use QL\QueryList;

class NewBooksJob extends BaseJob
{
    private $bookRule, $url, $rule_id;

    /**
     * NewBooksJob constructor.
     * @param $url
     * @param BookRule $bookRule
     */
    public function __construct(BookRule $bookRule, $url, $rule_id)
    {
        parent::__construct();
        $this->url = $url;
        $this->bookRule = $bookRule;
        $this->rule_id = $rule_id;
    }

    public function handle()
    {
        $fromHash = md5($this->url);
        $ql = QueryList::get($this->url);
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

        $otherBookModel = BooksModel::query()
            ->where('title', $_bookData['title'])
            ->where('from_hash', '<>', $fromHash)
            ->first();
        if ($otherBookModel) {
            Log::info('info', ['message' => "书名：【{$_bookData['title']}】 已存在其他获取源"]);
            return false;
        }

        $chapterListUrl = trim($data['chapter_list_url'] ?? $this->url);
        $chapterListUrl = get_full_url($chapterListUrl, $this->url);
        $bookModel = BooksModel::query()->where('from_hash', $fromHash)->first();
        if (!empty($bookModel)) {
            $bookModel->update($_bookData);
        } else {
            $_bookData['from_url'] = $this->url;
            $_bookData['from_hash'] = $fromHash;
            $_bookData['rule_id'] = $this->rule_id;
            $bookModel = BooksModel::query()->create($_bookData);
        }
        return $this->chapter($bookModel, $chapterListUrl);
    }

    private function chapter($bookModel, $chapterListUrl)
    {
        $ql = QueryList::get($chapterListUrl);
        if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
        }
        $data = $ql
            ->range($this->bookRule->chapterList->range)
            ->rules($this->bookRule->chapterList->rules)
            ->query()->getData()->all();

        if (empty($data)) {
            return false;
        }
        $urls = [];

        $finishedUrlArr = BooksChapterModel::query()
            ->select('from_hash')
            ->where('books_id', $bookModel->id)
            ->where('is_success', BooksChapterModel::ENABLE_STATUS)
            ->get()->pluck('from_hash')->toArray();
        foreach ($data as $k => $item) {
            $from_url = trim($item['from_url']);
            $from_url = get_full_url($from_url, $this->url);
            $_chapter = [
                'books_id' => $bookModel->id,
                'chapter_index' => $k + 1,
                'title' => trim($item['title']),
                'from_url' => $from_url,
                'from_hash' => md5($from_url)
            ];
            if (in_array($_chapter['from_hash'], $finishedUrlArr)) {
                continue;
            }
            $chapterModel = BooksChapterModel::query()->where('from_hash', $_chapter['from_hash'])->first();
            if (empty($chapterModel)) {
                BooksChapterModel::query()->create($_chapter);
                //获取正文
                $urls[] = $_chapter['from_url'];
                continue;
            }
            $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
            if (empty($contentModel) || empty($contentModel->content)) {
                //再次获取正文
                $urls[] = $_chapter['from_url'];
            }
        }
        if (empty($urls)) {
            return false;
        }
        $group = array_chunk($urls, 200);
        foreach ($group as $_urls) {
            dispatch(new BooksContentMultiJob($this->bookRule, $_urls))->onQueue('Content');
        }
        return true;
    }
}
