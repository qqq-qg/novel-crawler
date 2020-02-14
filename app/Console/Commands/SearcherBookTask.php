<?php

namespace App\Console\Commands;

use App\Jobs\NewBooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use Illuminate\Console\Command;

class SearcherBookTask extends Command
{
    protected $signature = 'search:run {--title=}';

    protected $description = '搜索任务，启动队列';

    const MAX_SEARCH_PAGE = 5;
    private $searchPage = 1;

    /**
     * @return bool
     * @var BookRule $bookRule
     */

    public function handle()
    {
        $title = $this->option('title');
        $keyword = $title ?? '哈利波特之万界店主';

        $repo = new ChromeSearcherRepository($this->searchPage);
        $data = $repo->search($keyword);
        if (false === $data) {
            return $this->tries();
        }
        /**
         * @var CollectionRuleModel[] $rules
         */
        $rules = CollectionRuleModel::getAllRules()->keyBy('id');
        foreach ($rules ?? [] as $rule) {
            /**
             * @var BookRule $bookRule
             */
            $bookRule = unserialize($rule->rule_json);
            foreach ($data as $k => $datum) {
                if (strpos($datum['link'], $bookRule->host) > -1) {
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
            return false;
        }
    }
}
