<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\CollectionRuleRepository;
use App\Repositories\Admin\CollectionTaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CollectionTaskController extends Controller
{
  /**
   * GET /tasks
   *
   * @param Request $request
   * @param CollectionRuleRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request
    , CollectionTaskRepository $repository
    , CategoryRepository $categoryRepo
    , CollectionRuleRepository $ruleRepo)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('admin.collection-task.index', [
      'paginate' => $paginate,
      'categories' => $categoryRepo->all(),
      'rules' => $ruleRepo->all(),
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
    return view('admin.collection-task.create');
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
    return view('admin.collection-task.detail' . ['data' => $data]);
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
    return view('admin.collection-task.create', ['data' => $data]);
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
