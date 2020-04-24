<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\ManagerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ManagerController extends Controller
{
  /**
   * GET /managers
   *
   * @param Request $request
   * @param ManagerRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, ManagerRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('admin.manager.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /managers/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('admin.manager.create');
  }

  /**
   * POST /managers
   *
   * @param Request $request
   * @param ManagerRepository $repository
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, ManagerRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /managers/{id}
   *
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(ManagerRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.manager.detail' . ['data' => $data]);
  }

  /**
   * GET /managers/{id}/edit
   *
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(ManagerRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.manager.create', ['data' => $data]);
  }

  /**
   * PUT /managers/{id}
   *
   * @param Request $request
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, ManagerRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /managers/{id}
   *
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(ManagerRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
