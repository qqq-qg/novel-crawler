<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
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
     * @var BookRule $bookRule
     * @return bool
     */

    public function handle()
    {
        $title = $this->option('title');
        $keyword = $title ?? '哈利波特之万界店主';

        $repo = new ChromeSearcherRepository($this->searchPage);
        $data = $repo->search($keyword);
        $ruleIdArr = [3, 1, 2,];
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

        echo 'Search Page ' . $this->searchPage . ' And No Match ...' . PHP_EOL;
        if ($this->searchPage++ < self::MAX_SEARCH_PAGE) {
            return $this->tries();
        }
        echo 'No More And Stop Search !' . PHP_EOL;
        return false;
    }

    public function tries()
    {
        $this->handle();
    }
}
