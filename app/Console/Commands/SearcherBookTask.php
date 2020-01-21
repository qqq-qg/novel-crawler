<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use Illuminate\Console\Command;
use QL\QueryList;

class SearcherBookTask extends Command
{
    protected $signature = 'search:run';

    protected $description = '搜索任务，启动队列';

    /**
     * @var BookRule $bookRule
     */

    public function handle()
    {
        $keyword = '哈利波特之万界店主';

        $repo = new ChromeSearcherRepository();
//        $data = $repo->search($keyword);
        $data = [
            0 => [
                "title" => "《哈利波特之万界店主》_子爵的青花瓷著_奇幻_起点中文网",
                "link" => "https://book.qidian.com/info/1015116643"
            ],
            1 => [
                "title" => "哈利波特之万界店主最新章节_哈利波特之万界店主无弹窗_笔趣阁",
                "link" => "https://www.biqukan.com/66_66566/"
            ],
            2 => [
                "title" => "哈利波特之万界店主最新章节,哈利波特之万界店主无弹窗全文阅读 -...",
                "link" => "https://www.2wxs.com/xstxt/279882/"
            ],
            3 => [
                "title" => "哈利波特之万界店主最新章节|首发小说哈利波特之万界店主最新章节...",
                "link" => "https://www.123du.cc/dudu-32/367830/"
            ],
            4 => [
                "title" => "哈利波特之万界店主无弹窗_哈利波特之万界店主最新章节列表_笔趣阁",
                "link" => "https://www.biquge.lu/book/58046/"
            ],
            5 => [
                "title" => "哈利波特之万界店主最新章节列表_哈利波特之万界店主最新章节目录...",
                "link" => "https://www.biqumo.com/27_27715/"
            ],
            6 => [
                "title" => "哈利波特之万界店主最新章节,哈利波特之万界店主无弹窗全文阅读 -...",
                "link" => "https://www.xs98.com/xs264777/"
            ],
            7 => [
                "title" => "哈利波特之万界店主最新章节|小说哈利波特之万界店主-读者吧小说网",
                "link" => "https://www.duzheba.cc/dzb-56-367851/"
            ],
            8 => [
                "title" => "哈利波特之万界店主_哈利波特之万界店主(子爵的青花瓷)最新章节_...",
                "link" => "https://www.zanghaihuatxt.com/21_21843/"
            ],
            9 => [
                "title" => "哈利波特之万界店主_作者子爵的青花瓷_哈利波特之万界店主最新...",
                "link" => "https://www.xszww.com/html/88/88341/"
            ]
        ];
        $ruleIdArr = [2, 3, 1];


        $rules = CollectionRuleModel::getRuleById($ruleIdArr)->keyBy('id');
        foreach ($ruleIdArr as $ruleId) {
            $rule = $rules[$ruleId] ?? [];
            if (empty($rule)) {
                continue;
            }
            /**
             * @var BookRule $bookRule
             */
            $bookRule = unserialize($rule->rule_json);
            foreach ($data as $k => $datum) {
                if (strpos($datum['link'], $bookRule->host) > -1) {
                    dispatch(new BooksJob($bookRule, $datum['link']));
                    return true;
                }
            }
        }

        echo 'not match ...' . "<br/>";
        return false;
    }
}
