<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use App\Repositories\TryAnalysis\TryAnalysisCategory;
use QL\QueryList;

class UpdateBooksFuzzyJob extends BaseJob
{
    private $bookRule, $book;

    /**
     * UpdateBooksJob constructor.
     * @param BooksModel $book
     */
    public function __construct(BooksModel $book)
    {
        parent::__construct();
        $this->book = $book;
    }

    public function handle()
    {
        $chapterList = (new TryAnalysisCategory($this->book->from_url))->handle();
        if (empty($chapterList)) {
            return false;
        }
//        todo
//        $this->book->update($_bookData);
//        return $this->chapter($chapterListUrl);
    }

    private function chapter($chapterListUrl)
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
        /**
         * @var BooksChapterModel $lastChapter
         */
        $lastChapter = BooksChapterModel::query()->where('books_id', $this->book->id)->orderBy('chapter_index', 'DESC')->first();
        $lastChapterIndex = $this->findLastTitleIndex($data, $lastChapter->title ?? '', $lastChapter->chapter_index ?? 0);
        $urls = [];
        foreach ($data as $k => $item) {
            if ($k < $lastChapterIndex) {
                continue;
            }
            $from_url = trim($item['from_url']);
            $from_url = get_full_url($from_url, $this->book->from_url);
            $_chapter = [
                'books_id' => $this->book->id,
                'chapter_index' => $k + 1,
                'title' => trim($item['title']),
                'from_url' => $from_url,
                'from_hash' => md5($from_url)
            ];
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
        $this->book->update(['last_chapter_title' => $_chapter['title']]);
        if (empty($urls)) {
            return false;
        }
        $group = array_chunk($urls, 200);
        foreach ($group as $_urls) {
            dispatch(new BooksContentMultiJob($this->bookRule, $_urls))->onQueue('Content');
        }
        return true;
    }

    /**
     * @param $data
     * @param $title
     * @param $chapterIndex
     * @return int
     */
    private function findLastTitleIndex($data, $title, $chapterIndex)
    {
        $index = 0;
        if (empty($title)) {
            return $index;
        }
        $len = count($data);
        for ($i = $len - 1; $i >= 0; $i--) {
            if (trim($data[$i]['title']) == $title) {
                $index = $i;
                if ($i >= $chapterIndex - 10 && $i <= $chapterIndex + 10) {
                    break;
                }
            }
        }
        return $index;
    }
}
