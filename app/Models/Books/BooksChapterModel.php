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
}
