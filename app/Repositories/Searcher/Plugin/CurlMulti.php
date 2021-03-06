<?php

namespace App\Repositories\Searcher\Plugin;

use Ares333\Curl\Curl;
use Closure;
use QL\Contracts\PluginContract;
use QL\QueryList;

class CurlMulti implements PluginContract
{
  protected $urls = [];
  protected $queryList;
  protected $successCallback;
  protected $curl;
  protected $isRunning = false;


  public function __construct(QueryList $queryList, $urls)
  {
    $this->urls = is_string($urls) ? [$urls] : $urls;
    $this->queryList = $queryList;
    $this->curl = new Curl();
    $this->curl->opt = [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CONNECTTIMEOUT => 30,
      CURLOPT_HTTPHEADER => [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
      ]
    ];
  }

  public static function install(QueryList $queryList, ...$opt)
  {
    $name = $opt[0] ?? 'curlMulti';
    $queryList->bind($name, function ($urls = []) {
      return new CurlMulti($this, $urls);
    });
  }

  public function getUrls()
  {
    return $this->urls;
  }

  public function add($urls)
  {
    is_string($urls) && $urls = [$urls];
    $this->urls = array_merge($this->urls, $urls);
    //如果当前任务正在运行就实时动态添加任务
    $this->isRunning && $this->addTasks($urls);
    return $this;
  }

  public function success(Closure $callback)
  {
    $this->successCallback = function ($r) use ($callback) {
      $this->queryList->setHtml($r['body']);
      $callback($this->queryList, $this, $r);
    };
    return $this;
  }

  public function error(Closure $callback)
  {
    $this->curl->cbFail = function ($info) use ($callback) {
      $callback($info, $this);
    };
    return $this;
  }

  public function start(array $opt = [])
  {
    $this->bindOpt($opt);
    $this->addTasks($this->urls);

    $this->isRunning = true;
    $this->curl->start();
    $this->isRunning = false;

    $this->urls = [];
    return $this;
  }

  protected function bindOpt($opt)
  {
    foreach ($opt as $key => $value) {
      if ($key == 'opt') {
        $this->curl->opt = $this->arrayMerge($this->curl->opt, $value);
      } else {
        $this->curl->$key = $value;
      }
    }
  }

  protected function addTasks($urls)
  {
    foreach ($urls as $url) {
      $this->curl->add([
        'opt' => array(
          CURLOPT_URL => $url
        )
      ], $this->successCallback);
    }
  }

  protected function arrayMerge($arr1, $arr2)
  {
    foreach ($arr2 as $key => $value) {
      $arr1[$key] = $value;
    }
    return $arr1;
  }

}
