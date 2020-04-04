<?php

namespace App\Repositories\Proxy;

abstract class ProxyRepository
{
  protected $originUrl = '';
  protected $range = '';
  protected $rules = [];

  abstract function getProxyPool($pageNumber = 1);
}
