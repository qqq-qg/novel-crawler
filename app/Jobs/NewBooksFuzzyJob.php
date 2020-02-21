<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Repositories\TryAnalysis\TryAnalysisCategory;
use Illuminate\Support\Facades\Log;

class NewBooksFuzzyJob extends BaseJob
{
    private $title, $url;

    /**
     * NewBooksFuzzyJob constructor.
     * @param $title
     * @param $url
     */
    public function __construct($title, $url)
    {
        parent::__construct();
        $this->url = $url;
        $this->title = $title;
    }

    public function handle()
    {
        $fromHash = md5($this->url);
        $otherBookModel = BooksModel::query()
            ->where('title', $this->title)
            ->where('from_hash', '<>', $fromHash)
            ->first();
        if ($otherBookModel) {
            Log::info('info', ['message' => "书名：【{$this->title}】 已存在其他获取源"]);
            return false;
        }
        $_bookData = [
            'title' => $this->title,
            'words_count' => '',
        ];

        $bookModel = BooksModel::query()->where('from_hash', $fromHash)->first();
        if (!empty($bookModel)) {
            $bookModel->update($_bookData);
        } else {
            $_bookData['from_url'] = $this->url;
            $_bookData['from_hash'] = $fromHash;
            $_bookData['rule_id'] = 0;
            $bookModel = BooksModel::query()->create($_bookData);
        }

        $chapterList = (new TryAnalysisCategory($this->url))->handle();
        if (empty($chapterList)) {
            return false;
        }
        return $this->chapter($bookModel, $chapterList);
    }

    private function chapter($bookModel, $chapterList)
    {
        $urls = [];

        $finishedUrlArr = BooksChapterModel::query()
            ->select('from_hash')
            ->where('books_id', $bookModel->id)
            ->where('is_success', BooksChapterModel::ENABLE_STATUS)
            ->pluck('from_hash')->toArray();
        foreach ($chapterList as $k => $item) {
            $from_url = trim($item['from_url']);
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
            dispatch(new BooksContentFuzzyJob($_urls));
        }
        return true;
    }
}
