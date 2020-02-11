<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class BooksChapterModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:08
 * @package App\Models\Books
 *
 * @property integer id
 * @property integer books_id ID
 * @property integer chapter_index 序号
 * @property string title 标题
 * @property integer hits 浏览次数
 * @property integer status 状态
 * @property string from_url 来源链接
 * @property string from_hash 来源链接hash值
 * @property integer is_success 采集状态
 * @property string created_at
 * @property string updated_at
 */
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
