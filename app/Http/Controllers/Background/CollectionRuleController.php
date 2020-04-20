<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use App\Repositories\Background\CollectionRuleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CollectionRuleController extends Controller
{
  /**
   * GET /rules
   *
   * @param Request $request
   * @param CollectionRuleRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, CollectionRuleRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.collection-rule.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /rules/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.collection-rule.create');
  }

  /**
   * Store a newly created resource in storage.
   * POST /rules
   */
  public function store(Request $request, CollectionRuleRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /rules/{id}
   *
   * @param CollectionRuleRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(CollectionRuleRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.collection-rule.create' . ['data' => $data]);
  }

  /**
   * GET /rules/{id}/edit
   *
   * @param CollectionRuleRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function edit(CollectionRuleRepository $repository, $id)
  {
    try {
      $data = $repository->show($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $data]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * PUT /rules/{id}
   *
   * @param Request $request
   * @param CollectionRuleRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, CollectionRuleRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /rules/{id}
   *
   * @param CollectionRuleRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(CollectionRuleRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
