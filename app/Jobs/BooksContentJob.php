<?php

namespace App\Jobs;

use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

/**
 * 单个章节采集队列
 * Class BooksContentJob
 * @Date: 2020/01/20 16:54
 * @package App\Jobs
 */
class BooksContentJob extends BaseJob
{
  private $bookRule, $chapterId, $chapterUrl;

  /**
   * BooksContentJob constructor.
   * @param BookRule $bookRule
   * @param $chapterId
   * @param $chapterUrl
   */
  public function __construct(BookRule $bookRule, $chapterId, $chapterUrl)
  {
    parent::__construct();
    $this->bookRule = $bookRule;
    $this->chapterId = $chapterId;
    $this->chapterUrl = $chapterUrl;
    $this->queue = 'Content';
  }

  public function handle()
  {
    $ql = QueryList::get($this->chapterUrl);
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
    $data = $ql
      ->range($this->bookRule->content->range)
      ->rules($this->bookRule->content->rules)
      ->query()->getData()->toArray();
    $content = trim($data['content'] ?? '');
    if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
      $content = explode($this->bookRule->splitTag, $content)[0];
    }
    foreach ($this->bookRule->replaceTags as $tag) {
      $content = str_replace($tag[0], $tag[1] ?? '', $content);
    }
    if (!empty($data) && !empty($content)) {
      $contentModel = BooksContentModel::query()->where('id', $this->chapterId)->first();
      if (!empty($contentModel)) {
        $contentModel->update(['content' => $content]);
      } else {
        BooksContentModel::query()->create(['id' => $this->chapterId, 'content' => $content]);
      }
    }
  }
}
