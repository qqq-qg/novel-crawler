<?php

namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;
use App\Repositories\Books\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IndexController extends Controller
{
    public function index(Request $request, BookRepository $bookRepository)
    {
        try {
            $data = $bookRepository->lists();
            return view('wap/index', ['data' => $data]);
        } catch (\Exception $e) {
            return view('wap/index', ['data' => []]);
        }
    }

    public function readBook(Request $request, BookRepository $bookRepository)
    {
        try {
            $id = $request->get('id', 0);
            $data = $bookRepository->getBook($id);
            return view('wap/content', ['data' => $data]);
        } catch (\Exception $e) {
            return view('wap/content', ['data' => []]);
        }
    }

    public function getBookContent(Request $request, BookRepository $bookRepository)
    {
        try {
            $id = $request->get('id', 0);
            $chapterIndex = $request->get('chapter_index', null);
            $data = $bookRepository->getChapterData($id, $chapterIndex);
            return Response::json(['code' => 0, 'data' => $data]);
        } catch (\Exception $e) {
            return Response::json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function getChapterGroup(Request $request, BookRepository $bookRepository)
    {
        try {
            $id = $request->get('id', 0);
            $chapterIndex = $request->get('chapter_index', 1);
            $data = $bookRepository->getChapterGroup($id, $chapterIndex);
            return Response::json(['code' => 0, 'data' => $data]);
        } catch (\Exception $e) {
            return Response::json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }
}
