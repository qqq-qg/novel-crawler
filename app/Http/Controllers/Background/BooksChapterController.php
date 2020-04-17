<?php namespace App\Http\Controllers\Background;

use App\Repositories\Background\BooksChapterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BooksChapterController extends Controller {
  /**
   * GET /bookschapter
   *
   * @param Request $request
   * @param BooksChapterRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, BooksChapterRepository $repository) {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('bookschapter.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /bookschapter/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create() {
    return view('bookschapter.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /bookschapter
   */
  public function store(Request $request, BooksChapterRepository $repository) {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /bookschapter/{id}
   *
   * @param BooksChapterRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(BooksChapterRepository $repository, $id) {
    $data = $repository->show($id);
    return view('bookschapter.create' . ['data' => $data]);
  }

  /**
   * GET /bookschapter/{id}/edit
   *
   * @param BooksChapterRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(BooksChapterRepository $repository, $id) {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /bookschapter/{id}
   *
   * @param Request $request
   * @param BooksChapterRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, BooksChapterRepository $repository, $id) {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /bookschapter/{id}
   *
   * @param BooksChapterRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(BooksChapterRepository $repository, $id) {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
