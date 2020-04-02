<?php

namespace App\Repositories\TryAnalysis;

use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

class TryAnalysisContent
{
  /**
   * @var QueryList $ql
   */
  private $url, $ql;

  public function __construct($url, $ql = null)
  {
    $this->url = $url;
    $this->ql = $ql;
  }

  public function handle()
  {
    try {
      if (is_null($this->ql)) {
        $this->ql = QueryList::get($this->url, [], ['timeout' => 30]);
        $this->ql->use(FilterHeader::class)->filterHeader();
        $this->ql->encoding(BookRule::CHARSET_UTF8);
      }
      $rootDivHtmlArr = $this->ql->find('body')->children('div')
        ->map(function ($item) {
          return $item->htmlOuter();
        });
      $leafArr = [];
      foreach ($rootDivHtmlArr as $rootDivHtml) {
        $tmp = $this->findDivLeaf($rootDivHtml);
        $leafArr = array_merge($leafArr, $tmp);
      }
      return $this->autoChoose($leafArr);
    } catch (\Exception|\Throwable $e) {
      info('Exception of TryAnalysisContent', [$e->getMessage()]);
    }
    return '';
  }

  private function findDivLeaf($divHtml)
  {
    $leaf = [];
    $subArr = QueryList::html($divHtml)
      ->find('div:eq(0)')
      ->children('div')
      ->map(function ($item) {
        return $item->htmlOuter();
      });
    if (count($subArr) === 0) {
      return [$divHtml];
    }
    foreach ($subArr as $_sub) {
      $tmp = $this->findDivLeaf($_sub);
      $leaf = array_merge($leaf, $tmp);
    }
    return $leaf;
  }

  private function autoChoose($leafArr)
  {
    foreach ($leafArr as $divHtml) {
      $pDomArr = QueryList::html($divHtml)->find('p')
        ->map(function ($item) {
          return $item->htmlOuter();
        });
      if (!empty($pDomArr) && count($pDomArr) >= 5) {
        return join("", $pDomArr->toArray());
      }

      $brDomArr = QueryList::html($divHtml)->find('br')->htmls();
      if (!empty($brDomArr) && count($brDomArr) >= 5) {
        return $divHtml;
      }

      $chWords = intval((strlen($divHtml) - mb_strlen($divHtml, "utf8")) / 2);
      if ($chWords >= 200) {
        return $divHtml;
      }
    }
    return '';
  }
}
