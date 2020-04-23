<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use App\Repositories\Background\CategoryRepository;
use App\Repositories\Background\CollectionTaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CollectionTaskController extends Controller
{
  /**
   * GET /tasks
   *
   * @param Request $request
   * @param CollectionTaskRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, CollectionTaskRepository $repository, CategoryRepository $categoryRepo)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.collection-task.index', [
      'paginate' => $paginate,
      'categories' => $categoryRepo->all(),
      'search' => $search
    ]);
  }

  /**
   * GET /tasks/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.collection-task.create');
  }

  /**
   * POST /tasks
   *
   * @param Request $request
   * @param CollectionTaskRepository $repository
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, CollectionTaskRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /tasks/{id}
   *
   * @param CollectionTaskRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(CollectionTaskRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.collection-task.detail' . ['data' => $data]);
  }

  /**
   * GET /tasks/{id}/edit
   *
   * @param CollectionTaskRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(CollectionTaskRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.collection-task.create' . ['data' => $data]);
  }

  /**
   * PUT /tasks/{id}
   *
   * @param Request $request
   * @param CollectionTaskRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, CollectionTaskRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /tasks/{id}
   *
   * @param CollectionTaskRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(CollectionTaskRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
