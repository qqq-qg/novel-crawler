<?php

namespace App\Jobs;

use App\Models\Books\CollectionRuleModel;
use App\Models\Books\HostBlacklistModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\ChromeSearcherRepository;
use App\Repositories\TryAnalysis\TryAnalysisCategory;
use App\Repositories\TryAnalysis\TryAnalysisContent;

class SearchBooksJob extends BaseJob
{
  private $title;
  private $maxSearchPage = 2;

  private $searchPage = 1;
  private $searchResultData = [];

  public function __construct($title, $maxSearchPage = 2)
  {
    parent::__construct();
    $this->title = $title;
    $this->maxSearchPage = $maxSearchPage;
  }

  public function handle()
  {
    $repo = new ChromeSearcherRepository($this->searchPage);
    $data = $repo->search($this->title);
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
    if ($this->searchPage++ <= $this->maxSearchPage) {
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
    $blacklist = HostBlacklistModel::getALlEnableHost();
    foreach ($this->searchResultData as $k => $datum) {
      if (strpos($datum['title'], $this->title) > -1) {
        //黑名单
        $host = parse_url($datum['link'])['host'] ?? '';
        if (in_array($host, $blacklist)) {
          continue;
        }
        //尝试是否可采集
        $res = $this->tryGetWithoutRule($datum['link']);
        if (!empty($res)) {
          dispatch(new NewBooksFuzzyJob($this->title, $datum['link']));
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
