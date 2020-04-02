<?php

namespace App\Http\Controllers\Home;

use App\Repositories\BookRepository;
use App\Repositories\LinkRepository;


class IndexController extends BaseController
{

  public function getIndex(BookRepository $bookRepository, LinkRepository $linkRepository)
  {
    $categories = $bookRepository->getCategories();

    //最近更新
    $newLists = \Cache::remember('newLists', 60, function () use ($bookRepository, $categories) {
      $newLists = $bookRepository->lists([], 'updated_at desc', 50, false);
      if (count($newLists)) {
        $newLists = $this->setCateName($newLists->toArray(), $categories);
      }
      return $newLists;
    });

    //最新入库
    $newInserts = \Cache::remember('newInsert', 60, function () use ($bookRepository, $categories) {
      $newInserts = $bookRepository->lists([], 'id desc', 50, false);
      if (count($newInserts)) {
        $newInserts = $this->setCateName($newInserts->toArray(), $categories);
      }
      return $newInserts;
    });

    //各分类推荐
    $tjLists = \Cache::remember('tjLists', 600, function () use ($categories, $bookRepository) {
      $tjLists = [];
      $i = 1;
      foreach ($categories as $k => $v) {
        if ($k == 9) break;
        $tjLists[$i]['catname'] = $v['name'];
        $tjLists[$i]['id'] = $k;
        $tjLists[$i]['data'] = $bookRepository->lists(['cate_id' => $k], '', 7, false)->toArray();
        $i++;
      }
      return $tjLists;
    });

    //封面推荐
    $ftLists = \Cache::remember('ftLists', 600, function () use ($bookRepository) {
      return $bookRepository->ftlists([], 'hits DESC', 6)->toArray();
    });

    //firendLinks
    $firendLinks = \Cache::remember('firendLinks', 600, function () use ($linkRepository) {
      return $linkRepository->lists();
    });

    $data = [
      'newLists' => $newLists,
      'newInserts' => $newInserts,
      'tjLists' => $tjLists,
      'ftLists' => $ftLists,
      'firendLinks' => $firendLinks
    ];
    return home_view('index.index', $data);
  }

  /**
   *
   * @param $data
   * @param $categories
   * @return array
   */
  protected function setCateName($data, $categories)
  {
    foreach ($data as $k => $v) {
      $data[$k]['catname'] = $categories[$v['cate_id']]['name'] ?? '';
    }
    return $data;
  }
}
