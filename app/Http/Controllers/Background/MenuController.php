<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use App\Repositories\Background\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MenuController extends Controller
{
  /**
   * GET /menu
   *
   * @param Request $request
   * @param MenuRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, MenuRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.menu.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /menu/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.menu.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /menu
   */
  public function store(Request $request, MenuRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /menu/{id}
   *
   * @param MenuRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(MenuRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.menu.create' . ['data' => $data]);
  }

  /**
   * GET /menu/{id}/edit
   *
   * @param MenuRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(MenuRepository $repository, $id)
  {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /menu/{id}
   *
   * @param Request $request
   * @param MenuRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, MenuRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /menu/{id}
   *
   * @param MenuRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(MenuRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
