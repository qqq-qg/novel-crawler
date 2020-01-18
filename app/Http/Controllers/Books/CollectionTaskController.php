<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;

class CollectionTaskController extends Controller
{

    public function init()
    {
        $bookRule = new BookRule();
        $bookRule->bookList = [
            'category' => new QlRule('',
                [
                    'url' => ['ul.main_con>li span.bookname>a', 'href']
                ], true, 2),

            'ranking' => new QlRule('',
                [
                    'url' => ['div.rank_d_list>div.rank_d_book_img>a', 'href']
                ], true, 2)
        ];
        $bookRule->home = new QlRule(
            'div.book-html-box div.book-info',
            [
                'title' => ['div.book-name', 'text'],
                'words_count' => ['div.nums>span>i:first', 'text'],
                'chapter_list_url' => ['a.all-catalog', 'href'],
            ]);
        $bookRule->chapterList = new QlRule('',
            [
                'title' => ['ul.chapter-list>li>a', 'text'],
                'from_url' => ['ul.chapter-list>li>a', 'href']
            ]);
        $bookRule->content = new QlRule('div.reader_box', [
            'chapter_name' => [
                'div.title_txtbox', 'text'
            ],
            'author' => [
                'div.bookinfo>a:eq(0)', 'text'
            ],
            'words_num' => [
                'div.bookinfo>span:eq(0)', 'text'
            ],
            'update_time' => [
                'div.bookinfo>span:eq(1)', 'text'
            ],
            'content' => ['div.content', 'html']
        ]);

        $model = CollectionRuleModel::query()->create(
            [
                'title' => '纵横中文网',
                'rule_json' => serialize($bookRule),
            ]
        );

        $title = '纵横月票榜';
        $url = 'http://www.zongheng.com/rank/details.html?rt=1&d=1&i=2&p={$page}';
        CollectionTaskModel::query()->create([
            'title' => $title,
            'from_url' => $url,
            'from_hash' => md5($url),
            'rule_id' => $model->id,
            'page_limit' => 2,
        ]);

        $title = '纵横中文网古典仙侠';
        $url = 'http://www.zongheng.com/store/c3/c1031/b0/u0/p{$page}/v0/s9/t0/u0/i0/ALL.html';
        CollectionTaskModel::query()->create([
            'title' => $title,
            'from_url' => $url,
            'from_hash' => md5($url),
            'rule_id' => $model->id,
            'page_limit' => 2,
        ]);
    }

}
