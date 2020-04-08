<?php

namespace App\Repositories\Searcher;

use App\Repositories\Searcher\Plugin\BaiduSearcher;
use Illuminate\Support\Collection;
use QL\Ext\Chrome;
use QL\QueryList;

/**
 * puppeteer 搜索
 * Class ChromeSearcherRepository
 * @author Nacrane
 * @Date: 2020/04/08 14:54
 * @package App\Repositories\Searcher
 */
class ChromeSearcherRepository implements SearcherRepositoryInterface
{
  private static $tries = 1;
  private static $currentPage = 1;

  public function __construct($currentPage = 1)
  {
    self::$currentPage = $currentPage;
  }

  public function search($keyword)
  {
    try {
      return $this->handle($keyword);
    } catch (\Exception $e) {
      echo '$e->getMessage()=' . $e->getMessage() . "<br/>";
      if (!is_time_out($e->getMessage())) {
        return false;
      }
      if (self::$tries-- > 0) {
        echo 'try again ...' . "<br/>";
        sleep(1);
        return $this->search($keyword);
      }
      return false;
    }
  }

  /**
   * @param $keyword
   * @Date: 2020/01/20 19:55
   * @return array
   * @throws \Exception
   */
  private function handle($keyword)
  {
    $ql = QueryList::getInstance()->use(Chrome::class, 'chrome');
    $queryData = ['wd' => $keyword];
    if (self::$currentPage > 1) {
      $queryData['pn'] = self::$currentPage * 10;
    }
    $queryString = http_build_query($queryData);

    $result = $ql->chrome(function ($page, $browser) use ($queryString) {
      $page->goto(BaiduSearcher::API . '?' . $queryString);
      $html = $page->content();
      $browser->close();
      return $html;// 返回值一定要是页面的HTML内容
    }, [
      'headless' => true, // 启动可视化Chrome浏览器,方便调试
      //'devtools' => true, // 打开浏览器的开发者工具
      'args' => ['--no-sandbox', '--disable-setuid-sandbox']
    ])
      ->range(BaiduSearcher::RANGE)
      ->rules(BaiduSearcher::RULES)
      ->query()->getData();
    if (!($result instanceof Collection)) {
      throw new \Exception('Connection timed out');
    }
    $data = $result->toArray();
    foreach ($data as $k => $datum) {
      try {
        $data[$k]['link'] = get_real_url($datum['link']);
      } catch (\Exception|\Throwable $e) {

      }
    }
    return $data;
  }
}
