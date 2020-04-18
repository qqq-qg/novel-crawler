<?php

namespace App\Http\Controllers\Background;

use App\Repositories\Background\LinkRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class LinkController extends Controller
{
  /**
   * GET /link
   *
   * @param Request $request
   * @param LinkRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, LinkRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.link.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /link/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.link.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /link
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
   * GET /link/{id}
   *
   * @param LinkRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(LinkRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.link.create' . ['data' => $data]);
  }

  /**
   * GET /link/{id}/edit
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
   * PUT /link/{id}
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
   * DELETE /link/{id}
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
