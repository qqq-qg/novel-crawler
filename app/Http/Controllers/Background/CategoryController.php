<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use App\Repositories\Background\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
  /**
   * GET /categories
   *
   * @param Request $request
   * @param CategoryRepository $repository
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request, CategoryRepository $repository)
  {
    $search = $request->all();
    $paginate = $repository->index($search);
    return view('background.category.index', [
      'paginate' => $paginate,
      'search' => $search
    ]);
  }

  /**
   * GET /categories/create
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create()
  {
    return view('background.category.create');
  }

  /**
   * POST /categories
   *
   * @param Request $request
   * @param CategoryRepository $repository
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request, CategoryRepository $repository)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * GET /categories/{id}
   *
   * @param CategoryRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(CategoryRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.category.create' . ['data' => $data]);
  }

  /**
   * GET /categories/{id}/edit
   *
   * @param CategoryRepository $repository
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @author Nacrane
   * @Date: 2020/04/23 17:23
   */
  public function edit(CategoryRepository $repository, $id)
  {
    $data = $repository->show($id);
    return view('background.category.create', ['data' => $data]);
  }

  /**
   * PUT /categories/{id}
   *
   * @param Request $request
   * @param CategoryRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, CategoryRepository $repository, $id)
  {
    try {
      $result = $repository->store($request->all());
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }

  /**
   * DELETE /categories/{id}
   *
   * @param CategoryRepository $repository
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(CategoryRepository $repository, $id)
  {
    try {
      $result = $repository->destroy($id);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => $result]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    }
  }
}
