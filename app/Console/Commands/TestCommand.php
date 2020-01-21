<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use Illuminate\Console\Command;
use QL\QueryList;

class TestCommand extends Command
{
    protected $signature = 'test';

    protected $description = '测试';

    private $url = '';
    /**
     * @var BookRule $bookRule
     */
    private $bookRule;

    /**
     * @var BookRule $bookRule
     * @return bool
     */
    public function handle()
    {
        $this->url = 'https://www.2wxs.com/xstxt/289368/';
        $rule = CollectionRuleModel::getRuleById([2])[0];
        $this->bookRule = unserialize($rule->rule_json);

        $fromHash = md5($this->url);
        if ($this->bookRule->needEncoding()) {
            $data = QueryList::getInstance()
                ->setHtml(QueryList::get($this->url)->removeHead()->getHtml())
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        } else {
            $data = QueryList::get($this->url)
                ->range($this->bookRule->home->range)
                ->rules($this->bookRule->home->rules)
                ->query()->getData()->first();
        }
        dd($data);
    }
}
