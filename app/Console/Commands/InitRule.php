<?php

namespace App\Console\Commands;

use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;
use Illuminate\Console\Command;

class InitRule extends Command
{
    protected $signature = 'rule:init';
    protected $description = '初始化规则';

    public function handle()
    {
        //truncate table
        CollectionRuleModel::query()->truncate();
        CollectionTaskModel::query()->truncate();

        $model1 = $this->rule1();
        $this->task($model1);

        $model2 = $this->rule2();

        $model3 = $this->rule3();
    }

    private function rule1()
    {
        $bookRule = new BookRule();
        $bookRule->host = 'www.zongheng.com';
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
        return $model;
    }

    private function rule2()
    {
        $bookRule = new BookRule();
        $bookRule->host = 'www.2wxs.com';
        $bookRule->charset = BookRule::CHARSET_GBK;
        $bookRule->bookList = [
            'category' => new QlRule('',
                [
                    'url' => ['ul.item-con>li span.s2>a', 'href']
                ], true, 2),

            'ranking' => new QlRule('',
                [
                    'url' => ['ul.item-con>li span.s2>a', 'href']
                ], true, 2)
        ];
        $bookRule->home = new QlRule('',
            [
                'title' => ['div.btitle>h1', 'text'],
                'words_count' => ['none', ''],
                'chapter_list_url' => ['self', ''],
            ]);
        $bookRule->chapterList = new QlRule('',
            [
                'title' => ['dl.chapterlist dd>a', 'text'],
                'from_url' => ['dl.chapterlist dd>a', 'href']
            ]);
        $bookRule->content = new QlRule('div.reader_box', [
            'content' => ['div#BookText', 'html']
        ]);

        $model = CollectionRuleModel::query()->create(
            [
                'title' => '顶点小说网-2wxs',
                'rule_json' => serialize($bookRule),
            ]
        );
        return $model;
    }

    private function rule3()
    {
        $bookRule = new BookRule();
        $bookRule->host = 'www.biquge.lu';
        $bookRule->charset = BookRule::CHARSET_GBK;
        $bookRule->bookList = [
            'category' => new QlRule('',
                [
                    'url' => ['div.l ul>li span.s2>a', 'href']
                ], true, 1),

            'ranking' => new QlRule('',
                [
                    'url' => ['ul.tli li a', 'href']
                ], true, 2)
        ];
        $bookRule->home = new QlRule('',
            [
                'title' => ['div.book h2', 'text'],
                'words_count' => ['none', ''],
                'chapter_list_url' => ['self', ''],
            ]);
        $bookRule->chapterList = new QlRule('',
            [
                'title' => ['.listmain dd>a', 'text'],
                'from_url' => ['.listmain dd>a', 'href']
            ]);
        $bookRule->content = new QlRule('', [
            'content' => ['div#content', 'html', '-script']
        ]);
        $bookRule->splitTag = '(https';

        $model = CollectionRuleModel::query()->create(
            [
                'title' => '笔趣阁-biquge',
                'rule_json' => serialize($bookRule),
            ]
        );
        return $model;
    }

    private function task($model)
    {
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
