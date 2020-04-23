<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
  /**
   * GET /settings
   *
   * @param Request $request
   * @param SettingRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, SettingRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('admin.setting.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /settings/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('admin.setting.create');
  }

  /**
   * POST /settings
   *
   * @param Request $request
   * @param SettingRepository $repository
   * @return \Illuminate\Http\JsonResponse
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
   * GET /settings/{id}
   *
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(SettingRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.setting.detail' . ['data' => $data]);
  }

  /**
   * GET /settings/{id}/edit
   *
   * @param SettingRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(SettingRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.setting.create' . ['data' => $data]);
  }

  /**
   * PUT /settings/{id}
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
   * DELETE /settings/{id}
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
