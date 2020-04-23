<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\LinkRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LinkController extends Controller
{
  /**
   * GET /links
   *
   * @param Request $request
   * @param LinkRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, LinkRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('admin.link.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /links/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('admin.link.create');
  }

  /**
   * POST /links
   *
   * @param Request $request
   * @param LinkRepository $repository
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, LinkRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /links/{id}
   *
   * @param LinkRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(LinkRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('admin.link.create', ['data' => $data]);
  }

  /**
   * GET /links/{id}/edit
   *
   * @param LinkRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(LinkRepository $repository, $id)
  {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /links/{id}
   *
   * @param Request $request
   * @param LinkRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, LinkRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /links/{id}
   *
   * @param LinkRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(LinkRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
