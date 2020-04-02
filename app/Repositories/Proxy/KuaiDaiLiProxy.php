<?php

namespace App\Repositories\Proxy;

use QL\QueryList;

class KuaiDaiLiProxy extends ProxyRepository
{
  public $originUrl = 'https://www.kuaidaili.com/free/inha/{$page}/';
  public $rules = [
    'ip' => [
      "table.table>tbody>tr>td[data-title='IP']", 'text'
    ],
    'port' => [
      "table.table>tbody>tr>td[data-title='PORT']", 'text'
    ],
    'http' => [
      "table.table>tbody>tr>td[data-title='类型']", 'text'
    ],
  ];

  public function getProxyPool($pageNumber = 1)
  {
    static $proxyPools = null;
    if (is_null($proxyPools)) {
      $pools = [];
      for ($i = 1; $i <= $pageNumber; $i++) {
        $url = str_replace('{$page}', $i, $this->originUrl);
        $data = QueryList::get($url)
          ->rules($this->rules)
          ->query()->getData()->all();
        foreach ($data as $datum) {
          $pools [] = join(':', [
            strtolower(trim($datum['http'])),
            '//' . trim($datum['ip']),
            trim($datum['port']),
          ]);
        }
        sleep(1);
      }
      $proxyPools = $pools;
    }
    $r = array_rand($proxyPools);
    return $proxyPools[$r];
  }
}
