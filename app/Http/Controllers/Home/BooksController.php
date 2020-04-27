<?php

namespace App\Http\Controllers\Home;

use App\Models\Books\BooksContentModel;
use App\Repositories\BookChapterRepository;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\DB;

class BooksController extends BaseController
{
  /**
   * 栏目列表
   * @param BookRepository $repository
   * @param $catid
   * @return mixed
   */
  public function getIndex(BookRepository $repository, $catid)
  {
    //封面推荐
    $ftLists = \Cache::remember('catid.' . $catid . '.ftLists', 600, function () use ($repository, $catid) {
      return $repository->ftlists(['cate_id' => $catid], 'hits DESC', 6)->toArray();
    });

    $newLists = $repository->lists(['cate_id' => $catid], 'updated_at desc', 30);

    $data = [
      'ftLists' => $ftLists,
      'newLists' => $newLists
    ];
    return home_view('book.index', $data);
  }

  /**
   * @param BookRepository $bookRepository
   * @param BookChapterRepository $bookChapterRepository
   * @param $catid
   * @param $id
   * @return mixed
   */
  public function getLists(BookRepository $bookRepository, BookChapterRepository $bookChapterRepository, $catid, $id)
  {
    $book = $bookRepository->find($id);
    $lists = $bookChapterRepository->lists(['books_id' => $id], 'chapter_index ASC', 1000);
    $lastDetail = $bookChapterRepository->lastDetail($id);
    $data = ['book' => $book, 'lists' => $lists, 'lastDetail' => $lastDetail,];
    DB::table('books')->where('id', $id)->increment('hits');
    return home_view('book.lists', $data);
  }

  /**
   * 章节详情
   * @param BookRepository $bookRepository
   * @param BookChapterRepository $bookChapterRepository
   * @param $catid
   * @param $id
   * @param $aid
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
   */
  public function getContent(BookRepository $bookRepository, BookChapterRepository $bookChapterRepository, $catid, $id, $aid)
  {
    $book = $bookRepository->find($id);
    $detail = $bookChapterRepository->find($aid);

    $content = BooksContentModel::getContent($aid);

    if (!$content) {
      abort(404, '该章节已经删除辣, 换个章节看看吧');
    }
    $detail->content = $content->content;

    $prevPage = $bookChapterRepository->prevPage($id, $aid);
    $nextPage = $bookChapterRepository->nextPage($id, $aid);

    $data = [
      'book' => $book,
      'detail' => $detail,
      'prevPage' => $prevPage,
      'nextPage' => $nextPage,
    ];
    DB::table('books')->where('id', $id)->increment('hits');
    DB::table('books_chapter')->where('id', $aid)->increment('hits');
    return home_view('book.content', $data);
  }
}
