<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateCategoryRequest;
use App\Repositories\BookChapterRepository;
use App\Repositories\BookRepository;
use App\Repositories\CollectionRuleRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use QL\QueryList;


class BookController extends BaseController
{
    /**
     * @param BookRepository $repository
     * @return mixed
     */
    public function getIndex(BookRepository $repository)
    {
        $lists = $repository->lists();
        $categorys = $repository->getCategories();
        $data = [
            'lists' => $lists,
            'categorys' => $categorys
        ];
        return admin_view('book.index', $data);
    }

    /**
     * 栏目分类
     * @param BookRepository $repository
     * @return mixed
     * @Date: 2020/02/02 21:29
     */
    public function getCategories(BookRepository $repository)
    {
        $lists = $repository->getCategories();
        $data = [
            'lists' => $lists,
        ];
        return admin_view('book.categorys', $data);
    }

    /**
     * 新增栏目分类
     * @param CreateCategoryRequest $request
     * @param BookRepository $repository
     * @return mixed
     * @Date: 2020/02/02 21:29
     */
    public function createCategory(CreateCategoryRequest $request, BookRepository $repository)
    {
        $result = $repository->createCategory($request->all());
        if ($result) {
            return redirect()->route('Book.getCategories');
        } else {
            return back()->withErrors('创建失败')->withInput();
        }
    }

    /**
     * 删除栏目分类
     * @param Request $request
     * @param BookRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     * @Date: 2020/02/02 21:50
     */
    public function deleteCategory(Request $request, BookRepository $repository)
    {
        $result = $repository->deleteCategory($request->get('id'));
        if ($result) {
            return redirect()->route('Book.getCategories');
        } else {
            return back()->withErrors('删除失败')->withInput();
        }
    }

    /**
     * 采集规则
     * @param Request $request
     * @param CollectionRuleRepository $repository
     * @return mixed
     * @Date: 2020/02/02 22:37
     */
    public function collectionRule(Request $request, CollectionRuleRepository $repository)
    {
        $lists = $repository->collectionRule($request->all());
        $data = [
            'lists' => $lists,
        ];
        return admin_view('book.collection_rule', $data);
    }

    public function getCreateCollectionRule(Request $request, CollectionRuleRepository $repository)
    {
        $id = $request->get('id', '');
        $data = [];
        if (!empty($id)) {
            $data = $repository->getCollectionRuleById($id);
        }
        return admin_view('book.create_collection_rule', $data);
    }

    public function createCollectionRule(Request $request, CollectionRuleRepository $repository)
    {
        $result = $repository->createCollectionRule($request->all());
        if ($result) {
            return redirect()->route('Book.collectionRule');
        } else {
            return back()->withErrors('创建失败')->withInput();
        }
    }

    public function deleteCollectionRule(Request $request, CollectionRuleRepository $repository)
    {
        $result = $repository->deleteCollectionRule($request->get('id'));
        if ($result) {
            return redirect()->route('Book.collectionRule');
        } else {
            return back()->withErrors('删除失败')->withInput();
        }
    }

    public function testCollectionRule(Request $request, CollectionRuleRepository $repository)
    {
        try {
            $data = $repository->testCollectionRule($request->all());
            return Response::json(['code' => 0, 'data' => $data]);
        } catch (\Exception $e) {
            return Response::json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function collectionTask(Request $request, CollectionRuleRepository $repository)
    {
        $lists = $repository->collectionTask($request->all());
        $data = [
            'lists' => $lists,
        ];
        return admin_view('book.collection_task', $data);
    }


    /**
     * 更新
     * @param Request $request
     * @param BookRepository $repository
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postUpdate(Request $request, BookRepository $repository)
    {
        $result = $repository->update($request::all());
        if ($result) {
            return redirect()->route('Book.getIndex')->with('Message', '修改成功');
        } else {
            return back()->withErrors('修改失败')->withInput();
        }
    }

    /**
     * 删除
     * @param Request $request
     * @param BookRepository $repository
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getDelete(Request $request, BookRepository $repository)
    {
        $result = $repository->delete($request::input('id'));
        if ($result) {
            return redirect()->route('Book.getIndex')->with('Message', '删除成功');
        } else {
            return back()->withErrors('删除失败')->withInput();
        }
    }


    /**
     * 章节列表
     * @param Request $request
     * @param BookChapterRepository $repository
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getChapters(Request $request, BookChapterRepository $repository)
    {
        $condition = [
            'pid' => $request::input('id')
        ];
        $lists = $repository->lists($condition, 'chapterid DESC', 10);
        return $lists;
    }

    /**
     * 按要求更新
     * @param Request $request
     * @param BookRepository $repository
     * @param BookChapterRepository $bookChapterRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQueue(Request $request, BookRepository $repository, BookChapterRepository $bookChapterRepository)
    {
        $type = $request->updateType;
        $number = intval($request->number) < 1 ? 10 : abs(intval($request->number));
        $zhangjieNumber = intval($request->zhangjieNumber) < 1 ? 50 : abs(intval($request->zhangjieNumber));

        $lists = [];
        if ($type == 1) {//指定栏目

            $catids = $request->catid;
            $lists = DB::table('books')->whereIn('catid', $catids)->orderBy('updated_at', 'asc')->take($number)->get();

        } else if ($type == 2) {//指定范围

            $startId = (int)$request->startId;
            $endId = $request->has('endId') ? $request->endId : $startId + 100;

            $lists = DB::table('books')->whereBetween('id', [$startId, $endId])->get();

        } else if ($type == 3) {//指定文章

            $targetId = $request->targetId;
            $lists = DB::table('books')->where('id', $targetId)->get();

        } else if ($type == 4) {//修复空白数据
            // 功能暂停
            return;
            $ids = DB::table('books_content')->where('content', '')->orderBy('id', 'asc')->take($zhangjieNumber)->lists('id');
            $lists = DB::table('books_detail')->whereIn('id', $ids)->get();
            $rules = ['content' => ['.yd_text2', 'html']];
            $contentTotal = 0;

            foreach ($lists as $v) {
                $html = QueryList::Query($v['fromurl'], $rules, '', 'UTF-8', 'GBK', true)->getData();
                $result = array_shift($html);
                if (!empty($result['content'])) {
                    DB::table('books_content')->where('id', $v['id'])->update(['content' => $result['content']]);
                    $contentTotal++;
                }
            }
            $zhangjieCount = count($lists);
            $successPercent = sprintf('%.2f', $contentTotal / $zhangjieCount) * 100;
            return redirect()->route('Book.getIndex')->with('Message', '成功恢复 ' . $contentTotal . ' 章节，成功率 ' . $successPercent . ' %');
        }

        $sourceLists = array_keys($repository::sourceLists(1));
        $successCount = 0;
        foreach ($lists as $v) {
            if (in_array($v['source'], $sourceLists)) {
                $successCount++;
                $ClassName = '\App\Jobs\Books\\' . ucfirst($v['source']) . 'Chapter';
                $this->dispatch(new $ClassName($v, $zhangjieNumber));
            }

        }
        return redirect()->route('Book.getIndex')->with('Message', '操作成功，共更新 ' . $successCount . '本');
    }

    /**
     * 采集指定文章章节
     * @param Request $request
     * @param BookRepository $repository
     * @return int
     */
    public function updateChapters(Request $request, BookRepository $repository)
    {
        $id = $request->id;
        $number = intval($request->number);
        $data = $repository->find($id);
        $ClassName = '\App\Jobs\Books\\' . ucfirst($data['source']) . 'Chapter';
        $this->dispatch(new $ClassName($data, $number));
        return 1;
    }

    protected function dushu88Detail($data, $number)
    {
        return true;
    }

    /**
     * 获取内容
     * @param Request $request
     * @param BookChapterRepository $repository
     * @return mixed
     */
    public function chapterContent(Request $request, BookChapterRepository $repository)
    {
        return $repository->getContent($request->pid, $request->id);
    }

    /**
     * 更新指定章节
     * @param Request $request
     * @param BookChapterRepository $repository
     * @return mixed
     */
    public function updateChapter(Request $request, BookChapterRepository $repository)
    {
        $item = $repository->find($request->id);
        if ($request->isMethod('get')) {
            $item->content = $repository->getContent($item->pid, $item->id);
            return admin_view('book.create_detail', $item);
        }
        $data = $request->all();
        $result = $item->update($data);
        if ($result) {
            $repository->setContent($item->pid, $item->id, $data['content']);
            return redirect()->route('Book.getIndex')->with('Message', '修改成功');
        } else {
            return back()->withErrors('更新失败')->withInput();
        }
    }

    /**
     * 删除指定章节
     * @param Request $request
     * @param BookChapterRepository $bookChapterRepository
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function deleteChapter(Request $request, BookChapterRepository $bookChapterRepository)
    {
        $detail = $bookChapterRepository->find($request->id);
        $result = $detail->delete();
        if ($result) {
            //$bookContent->where('id',$request->id)->delete();
            $bookChapterRepository->deleteContent($detail->pid, $detail->id);
            return redirect()->route('Book.getIndex')->with('Message', '删除成功');
        } else {
            return back()->withErrors('删除失败')->withInput();
        }
    }

    /**
     * 加入任务队列
     * @param Request $request
     * @return mixed
     */
    public function createQueue(Request $request)
    {
        $data = $request->all();
        $source = $data['source'];
        return $this->$source($data);
    }

    /**
     * 获取当前队列数量
     * @return int
     */
    public function queueNumber()
    {
        if (config('queue.default') == 'database') {
            return DB::table('jobs')->count();
        } else {
            return 0;
        }
    }

}
