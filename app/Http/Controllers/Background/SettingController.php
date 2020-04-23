<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use App\Repositories\Background\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
  /**
   * GET /setting
   *
   * @param Request $request
   * @param SettingRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, SettingRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.setting.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /setting/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.setting.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /setting
   */
  public function store(Request $request, SettingRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /setting/{id}
   *
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(SettingRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.setting.detail' . ['data' => $data]);
  }

  /**
   * GET /setting/{id}/edit
   *
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(SettingRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.setting.create' . ['data' => $data]);
  }

  /**
   * PUT /setting/{id}
   *
   * @param Request $request
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, SettingRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /setting/{id}
   *
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(SettingRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
