<?php namespace App\Http\Controllers\Background;

use App\Repositories\Background\BooksRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BooksController extends Controller {
  /**
   * GET /books
   *
   * @param Request $request
   * @param BooksRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, BooksRepository $repository) {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('books.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /books/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create() {
    return view('books.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /books
   */
  public function store(Request $request, BooksRepository $repository) {
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
  public function show(BooksRepository $repository, $id) {
    $data = $repository->show($id);
    return view('books.create' . ['data' => $data]);
  }

  /**
   * GET /books/{id}/edit
   *
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(BooksRepository $repository, $id) {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /books/{id}
   *
   * @param Request $request
   * @param BooksRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, BooksRepository $repository, $id) {
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
  public function destroy(BooksRepository $repository, $id) {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}