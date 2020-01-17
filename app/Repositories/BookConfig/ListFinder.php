<?php

namespace App\Repositories\BookConfig;


Class ListFinder {
  protected $url = '';
  protected $pageNum = 10;
  protected $config = [];

  public function __construct($url, $pageNum, $config) {
    $this->url = $url;
    $this->pageNum = $pageNum;
    $this->config = $config;
  }

  public function getConfig() {

  }

  public function setConfig() {

  }
}