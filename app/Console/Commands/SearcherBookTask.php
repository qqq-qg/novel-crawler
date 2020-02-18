<?php

namespace App\Console\Commands;

use App\Jobs\NewBooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\BookRequestRepository;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use Illuminate\Console\Command;

class SearcherBookTask extends Command
{
    protected $signature = 'search:run {--title=}';

    protected $description = '搜索任务，启动队列';

    const MAX_SEARCH_PAGE = 5;
    private $searchPage = 1;
    private $searchResultData = [];
    private $keyword = '';

    /**
     * @return bool
     * @var BookRule $bookRule
     */

    public function handle()
    {
        $title = $this->option('title');
        $this->keyword = $title ?? '哈利波特之万界店主';

        $repo = new ChromeSearcherRepository($this->searchPage);
        $data = $repo->search($this->keyword);
        if (false === $data) {
            return $this->tries();
        }
        //汇总搜索结果集
        $this->searchResultData = array_merge($this->searchResultData, $data);
        /**
         * @var CollectionRuleModel[] $rules
         */
        $rules = CollectionRuleModel::getAllRules()->keyBy('id');
        foreach ($rules ?? [] as $rule) {
            /**
             * @var BookRule $bookRule
             */
            foreach ($data as $k => $datum) {
                if (strpos($datum['link'], $rule->host) > -1) {
                    $bookRule = unserialize($rule->rule_json);
                    dispatch(new NewBooksJob($bookRule, $datum['link'], $rule->id));
                    return true;
                }
            }
        }
        //try next page
        return $this->tries();
    }

    public function tries()
    {
        if ($this->searchPage++ < self::MAX_SEARCH_PAGE) {
            echo 'Search page ' . $this->searchPage . ' and no match ...' . PHP_EOL;
            return $this->handle();
        } else {
            echo 'No more and stop search !' . PHP_EOL;
            echo 'Try without rule, pls wait ...' . PHP_EOL;
            return $this->handelWithoutRule();
        }
    }

    private function handelWithoutRule()
    {
        foreach ($this->searchResultData as $k => $datum) {
            if (strpos($datum['title'], $this->keyword) > -1) {
//                $res = $this->tryGetWithoutRule($datum['link']);
//                if (!empty($res)) {
                    //todo
                    return true;
//                }
            }
        }
        echo 'No result match, task end !!!' . PHP_EOL;
        return false;
    }

    private function tryGetWithoutRule($link)
    {
        $res = BookRequestRepository::tryPregCategory($link);

        $res = BookRequestRepository::tryPregContent($link);

        return false;
    }
}
