<?php

namespace App\Repositories\BookConfig;

interface BookRepositoryInterface {
  public function getConfig($location);
}