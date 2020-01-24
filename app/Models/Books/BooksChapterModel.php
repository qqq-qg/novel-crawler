<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class BooksChapterModel extends BaseModel
{
    protected $table = 'books_chapter';

    //标记章节采集完成
    public function saveProcessed()
    {
        if (!empty($this->id)) {
            $this->update(['is_success' => self::ENABLE_STATUS]);
        }
    }

    public static function getChapterByIndex($booksId, $index = null)
    {
        $query = self::query()
            ->where('books_id', $booksId)
            ->where('status', BooksChapterModel::ENABLE_STATUS)
            ->orderBy('chapter_index', 'asc');
        if (!empty($index)) {
            $query->where('chapter_index', '>=', $index);
        }
        return $query->first();
    }
}
