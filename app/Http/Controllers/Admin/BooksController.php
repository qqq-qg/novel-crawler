<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\BooksRepository;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BooksController extends Controller
{
  /**
   * GET /books
   *
   * @param Request $request
   * @param BooksRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, BooksRepository $repository, CategoryRepository $categoryRepo)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('admin.books.index', [
      'paginate' => $paginate,
      'categories' => $categoryRepo->all(),
      'search' => $search,
    ]);
  }

  /**
   * GET /books/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('admin.books.create');
  }

  /**
   * POST /books
   *
   * @param Request $request
   * @param BooksRepository $repository
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, BooksRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /books/{id}
   *
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(BooksRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.books.detail', ['data' => $data]);
  }

  /**
   * GET /books/{id}/edit
   *
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @author Nacrane
   * @Date: 2020/04/23 17:23
   */
  public function edit(BooksRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.books.create', ['data' => $data]);
  }

  /**
   * PUT /books/{id}
   *
   * @param Request $request
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, BooksRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /books/{id}
   *
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(BooksRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
