<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class BooksModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:02
 * @package App\Models\Books
 *
 * @property integer id
 * @property string title 标题
 * @property string category 分类
 * @property string introduce 简介
 * @property string thumb 缩略图
 * @property string last_chapter_title 最新章节
 * @property string author 作者
 * @property string words_count 字数
 * @property integer level 等级
 * @property integer follow 关注人数
 * @property integer hits 浏览次数
 * @property string update_status 更新状态
 * @property integer status 状态
 * @property integer rule_id 规则ID
 * @property string from_url 来源网址
 * @property string from_hash 来源网址hash,用来判断是否插入过
 * @property string created_at
 * @property string updated_at
 */
class BooksModel extends BaseModel
{
    protected $table = 'books';

    const UPT_STATUS_LOADING = 'loading';
    const UPT_STATUS_FINISHED = 'finished';
}
