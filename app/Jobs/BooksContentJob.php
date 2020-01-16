<?php

namespace App\Jobs;

use App\Models\Books\BooksContentModel;
use QL\QueryList;

class BooksContentJob extends BaseJob {
  private $chapterModel, $config;

  /**
   * BooksJob constructor.
   * @param $url
   */
  public function __construct($chapterModel, $config) {
    parent::__construct();
    $this->chapterModel = $chapterModel;
    $this->config = $config;
  }

  public function handle() {
    $data = QueryList::get($this->chapterModel->from_url)
      ->range($this->config['content']['range'])
      ->rules($this->config['content']['rules'])
      ->query()->getData()->first();
    $content = trim($data['content'] ?? '');
    if (!empty($data) && !empty($content)) {
      $contentModel = BooksContentModel::query()->where('id', $this->chapterModel->id)->first();
      if (!empty($contentModel)) {
        $contentModel->update([
          'content' => $content,
        ]);
      } else {
        BooksContentModel::query()->create([
          'id' => $this->chapterModel->id,
          'content' => $content,
        ]);
      }
    }
  }
}
