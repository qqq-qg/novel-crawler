<?php

namespace App\Http\Controllers\Background;

use App\Repositories\Background\ManagerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ManagerController extends Controller
{
  /**
   * GET /manager
   *
   * @param Request $request
   * @param ManagerRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, ManagerRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.manager.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /manager/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.manager.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /manager
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
   * GET /manager/{id}
   *
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(ManagerRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.manager.create' . ['data' => $data]);
  }

  /**
   * GET /manager/{id}/edit
   *
   * @param ManagerRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(ManagerRepository $repository, $id)
  {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /manager/{id}
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
   * DELETE /manager/{id}
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
