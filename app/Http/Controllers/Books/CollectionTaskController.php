<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;
use App\Repositories\Searcher\BindSearcherRepository;
use liesauer\QLPlugin\BingSearcher;
use QL\Ext\Baidu;
use QL\QueryList;

class CollectionTaskController extends Controller
{

    public function initRule()
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

    public function addCollectionTask()
    {
        $ruleId = 1;
        $pageLimit = 2;
        $title = '纵横中文网古典仙侠';
        $url = 'http://www.zongheng.com/store/c3/c1031/b0/u0/p{$page}/v0/s9/t0/u0/i0/ALL.html';
        CollectionTaskModel::query()->create([
            'title' => $title,
            'from_url' => $url,
            'from_hash' => md5($url),
            'rule_id' => $ruleId,
            'page_limit' => $pageLimit,
        ]);

    }

    public function addSearchTask()
    {
        $bookName = '哈利波特之万界店主';
        $optionPools = ['zongheng', 'biqukan', '2wxs'];

//        $url = 'https://cn.bing.com/search?q='.$bookName;
//        $ql = QueryList::get($url)->getHtml();
//        dd($ql);
//        echo $ql;
//        die;



        $proxyRepository = new BindSearcherRepository();
        $data = $proxyRepository->search($bookName);
        dd($data);


        $ql = QueryList::getInstance();
        $ql->use(BingSearcher::class);
        $bingSearcher = $ql->BingSearcher();
        $result = $bingSearcher->search($bookName)
            ->setHttpOption([
                'proxy' => 'http://119.23.110.100:8000',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36',
                'timeout' => 10,
            ])->pages(1);
        var_dump($result);
        die;
        //baidu
        $ql = QueryList::getInstance()->use(Baidu::class);
        $searcher = $ql->baidu(2)->search($bookName);
        $data = $searcher->setHttpOpt([
            'proxy' => 'http://123.169.163.227:9999',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36',
            'timeout' => 10,
        ])->page(1, true);

        dd($data);
    }
}
