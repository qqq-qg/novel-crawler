<?php

namespace App\Console\Commands;

use App\Jobs\NewBooksFuzzyJob;
use App\Jobs\NewBooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use App\Repositories\TryAnalysis\TryAnalysisCategory;
use App\Repositories\TryAnalysis\TryAnalysisContent;
use Illuminate\Console\Command;

class SearcherBookTask extends Command
{
    protected $signature = 'search:run {--title=}';

    protected $description = '搜索任务，启动队列';

    const MAX_SEARCH_PAGE = 2;
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
        $this->keyword = $title ?? '哈利波特之眠龙勿扰';

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
        echo 'Search page ' . $this->searchPage . ' and no match ...' . PHP_EOL;
        if ($this->searchPage++ <= self::MAX_SEARCH_PAGE) {
            return $this->handle();
        } else {
            echo 'No more and stop search !' . PHP_EOL;
            echo 'Try without rule, pls wait ...' . PHP_EOL;
            return $this->handelWithoutRule();
        }
    }

    private function handelWithoutRule()
    {
        info('$this->searchResultData', $this->searchResultData);
        foreach ($this->searchResultData as $k => $datum) {
            if (strpos($datum['title'], $this->keyword) > -1) {
                $res = $this->tryGetWithoutRule($datum['link']);
                if (!empty($res)) {
                    dispatch(new NewBooksFuzzyJob($this->keyword, $datum['link']));
                    return true;
                }
            }
        }
        echo 'No result match, task end !!!' . PHP_EOL;
        return false;
    }

    private function tryGetWithoutRule($link)
    {
        $chapterList = (new TryAnalysisCategory($link))->handle();
        if (empty($chapterList)) {
            return false;
        }

        $chapterInfo = $chapterList[array_rand($chapterList)];
        $content = (new TryAnalysisContent($chapterInfo['from_url']))->handle();
        if (empty($content)) {
            return false;
        }
        return $chapterList;
    }
}
