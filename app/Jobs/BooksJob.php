<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use QL\QueryList;

class BooksJob extends BaseJob {
  private $url, $config;

  /**
   * BooksJob constructor.
   * @param $url
   */
  public function __construct($url, $config) {
    parent::__construct();
    $this->url = $url;
    $this->config = $config;
  }

  public function handle() {
    $data = QueryList::get($this->url)
      ->range($this->config['home']['range'])
      ->rules($this->config['home']['rules'])
      ->query()->getData()->first();
    if (!empty($data)) {
      $fromHash = md5($this->url);
      $bookModel = BooksModel::query()->where('from_hash', $fromHash)->first();
      $_bookData = [
        'title' => trim($data['title']),
        'words_count' => trim($data['words_count']),
      ];
      if (!empty($bookModel)) {
        $bookModel->update($_bookData);
      } else {
        $_bookData['from_url'] = $this->url;
        $_bookData['from_hash'] = $fromHash;
        $bookModel = BooksModel::query()->create($_bookData);
      }
      $this->chapter($bookModel, trim($data['chapter_list_url']));
    }
  }

  private function chapter($bookModel, $chapterListUrl) {
    $data = QueryList::get($chapterListUrl)
      ->range($this->config['chapter_list']['range'])
      ->rules($this->config['chapter_list']['rules'])
      ->query()->getData()->all();
    if (!empty($data)) {
      foreach ($data as $k => $item) {
        $_chapter = [
          'books_id' => $bookModel->id,
          'chapter_index' => $k + 1,
          'title' => trim($item['title']),
          'from_url' => trim($item['from_url'])
        ];
        $_chapter['from_hash'] = md5($_chapter['from_url']);
        $chapterModel = BooksChapterModel::query()->where('from_hash', $_chapter['from_hash'])->first();
        if (empty($chapterModel)) {
          $chapterModel = BooksChapterModel::query()->create($_chapter);
          //获取正文
          dispatch(new BooksContentJob($chapterModel, $this->config));
          continue;
        }
        $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
        if (empty($contentModel) || empty($contentModel->content)) {
          //再次获取正文
          dispatch(new BooksContentJob($chapterModel, $this->config));
        }
      }
    }
  }
}
