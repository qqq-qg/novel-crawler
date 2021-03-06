<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Proxy\KuaiDaiLiProxy;
use App\Repositories\Searcher\Plugin\CurlMulti;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

/**
 * 批量采集章节正文
 * Class BooksContentMultiJob
 * @Date: 2020/01/20 16:54
 * @package App\Jobs
 */
class BooksContentMultiJob extends BaseJob
{
  private $urls, $bookRule;
  private $tryAgain = true;

  /**
   * BooksContentMultiJob constructor.
   * @param BookRule $bookRule
   * @param array $urls
   * @param bool $tryAgain
   */
  public function __construct(BookRule $bookRule, array $urls, $tryAgain = true)
  {
    parent::__construct();
    $this->urls = $urls;
    $this->bookRule = $bookRule;
    $this->tryAgain = $tryAgain;
    $this->queue = 'Content';
  }

  public function handle()
  {
//    $curlOpt = $this->getCurlProxyOpt();
    $againUrl = [];
    $ql = QueryList::use(CurlMulti::class);
    $ql->range($this->bookRule->content->range)->rules($this->bookRule->content->rules);
    $ql->curlMulti($this->urls)
      ->success(function (QueryList $ql, CurlMulti $curl, $r) {
        try {
          $qlUrl = $r['info']['url'];
          $urlHash = md5(trim($qlUrl));
          $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
          if ($chapterModel) {
            $chapterModel->increment('fetch_times', 1);
          }
          if ($this->bookRule->needEncoding()) {
            $ql->use(FilterHeader::class)->filterHeader();
            $ql->encoding(BookRule::CHARSET_UTF8);
            if (!empty($this->bookRule->replaceTags)) {
              $html = $ql->getHtml();
              foreach ($this->bookRule->replaceTags ?? [] as $tag) {
                $html = preg_replace($tag[0], $tag[1] ?? '', $html);
              }
              $ql->setHtml($html);
            }
          }
          $data = $ql->queryData();
          $content = trim($data['content'] ?? '');
          if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
            $content = explode($this->bookRule->splitTag, $content)[0];
          }
          foreach ($this->bookRule->replaceTags ?? [] as $tag) {
            $content = preg_replace($tag[0], $tag[1] ?? '', $content);
          }
          if (!empty($content)) {
            $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
            if (!empty($contentModel)) {
              $contentModel->update(['content' => $content]);
            } else {
              BooksContentModel::query()->create(['id' => $chapterModel->id, 'content' => $content]);
            }
            $chapterModel->saveProcessed();
          }
        } catch (\Exception $e) {
          $againUrl[] = $qlUrl;
        }
      })
      ->error(function ($errorInfo, CurlMulti $curl) {
        echo "Error url:{$errorInfo['info']['url']} \r\n";
        print_r($errorInfo['error']);
      })
      ->start([
        'maxThread' => 30,
        'maxTry' => 1,
//        'opt' => $curlOpt,
      ]);

    if ($this->tryAgain && !empty($againUrl)) {
      dispatch(new BooksContentMultiJob($this->bookRule, $againUrl, false));
    }
  }

  private function getCurlProxyOpt()
  {
    $proxy = (new KuaiDaiLiProxy())->getProxyPool();
    $proxyInfo = explode(':', str_replace('//', '', $proxy));
    return [
      CURLOPT_PROXYAUTH => CURLAUTH_BASIC,//代理认证模式
      CURLOPT_PROXYTYPE => CURLPROXY_HTTP,//使用http代理模式
      CURLOPT_PROXY => $proxyInfo[1],//代理服务器地址
      CURLOPT_PROXYPORT => $proxyInfo[2],//代理服务器端口
    ];
  }
}
