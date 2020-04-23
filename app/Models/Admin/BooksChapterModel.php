<?php namespace App\Models\Admin;

use App\Models\BaseModel;

/**
 * @property integer id
 * @property integer books_id ID
 * @property integer chapter_index 序号
 * @property string title 标题
 * @property integer fetch_times 获取次数
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
}
