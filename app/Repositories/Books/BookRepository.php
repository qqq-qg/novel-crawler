<?php

namespace App\Repositories\Books;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Repositories\BaseRepository;

class BookRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new BooksModel());
    }

    public function lists($condition = [], $order = 'id DESC', $pagesize = 10, $page = true)
    {
        $lists = $this->model->query()->where(array_merge(['status' => 1], $condition));
        if (strpos($order, ',') !== false) {
            foreach (explode(',', $order) as $v) {
                $tmp = explode(' ', $order);
                $lists->orderBy($tmp[0], $tmp[1]);
            }
        } else {
            $order = $order ? explode(' ', $order) : ['id', 'DESC'];
            $lists->orderBy($order[0], $order[1]);
        }
        if ($page) {
            return $lists->paginate($pagesize);
        } else {
            return $lists->take($pagesize)->get();
        }
    }

    public function getBook($bookId)
    {
        $book = $this->model->query()->where('id', $bookId)->first();
        if (empty($book)) {
            throw new \Exception("ID:{$bookId} 不存在");
        }
        return $book;
    }


    public function getChapterData($bookId, $chapterIndex)
    {
        $chapterModel = BooksChapterModel::getChapterByIndex($bookId, $chapterIndex);
        if (empty($chapterModel)) {
            return [];
        }
        $chapter = $chapterModel->toArray();
        $contentModel = BooksContentModel::getContent($chapterModel->id);
        $chapter['content'] = !empty($contentModel) ? $contentModel->content : '暂时没有获取到章节内容...';
        return [$chapter];
    }

    public function getChapterGroup($bookId)
    {
        $data = BooksChapterModel::query()
            ->select(['chapter_index', 'title'])
            ->where('books_id', $bookId)
            ->orderBy('chapter_index', 'asc')
            ->get()->toArray();
        return array_chunk($data, 100);
    }
}
