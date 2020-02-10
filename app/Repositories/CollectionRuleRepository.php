<?php

namespace App\Repositories;

use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;

class CollectionRuleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new CollectionRuleModel());
    }

    /**
     * @param $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Date: 2020/02/03 17:06
     */
    public function collectionRule($keyword)
    {
        $query = CollectionRuleModel::query()
            ->where('status', CollectionRuleModel::ENABLE_STATUS)
            ->orderBy('id', 'desc');
        $lists = $query->paginate(static::$pageSize);
        foreach ($lists->items() as $item) {
            $item['rule_json'] = unserialize($item['rule_json'])->toArray();
        }
        return $lists;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @Date: 2020/02/03 17:09
     */
    public function getCollectionRuleById($id)
    {
        $model = CollectionRuleModel::query()->where('id', $id)->first();
        $model->rule_json = unserialize($model['rule_json'])->toArray();
        return $model;
    }

    /**
     * 新增编辑规则
     * @param $data
     * @return bool
     * @Date: 2020/02/03 17:06
     */
    public function createCollectionRule($data)
    {
        if (!empty($data['id'])) {
            $model = CollectionRuleModel::query()->findOrNew($data['id']);
        } else {
            $model = new CollectionRuleModel();
        }

        $bookRule = $this->getBookRule($data);

        $model->title = $data['title'] ?? '';
        $model->host = $bookRule->host;
        $model->rule_json = serialize($bookRule);
        return $model->save();

    }

    /**
     * @param $data
     * @return BookRule
     * @Date: 2020/02/04 1:50
     */
    private function getBookRule($data)
    {
        $bookRule = new BookRule();
        $bookRule->host = $data['host'] ?? '';
        $bookRule->charset = $data['charset'] ?? '';

        $cPage = empty($data['category']['page']) ? 1 : intval($data['category']['page']);
        $rPage = empty($data['ranking']['page']) ? 1 : intval($data['ranking']['page']);
        $bookRule->bookList = [
            'category' => new QlRule('', ['url' => $data['category']['url']], $cPage > 1, $cPage),
            'ranking' => new QlRule('', ['url' => $data['ranking']['url']], $rPage > 1, $rPage)
        ];
        $bookRule->home = new QlRule('', $data['home'] ?? []);
        $bookRule->chapterList = new QlRule('', $data['chapterList'] ?? []);
        $bookRule->content = new QlRule('', $data['content'] ?? []);
        $bookRule->splitTag = $data['splitTag'] ?? '';
        $replaceTags = [];
        foreach ($data['replaceTags'] ?? [] as $item) {
            if (!empty($item[0])) {
                $replaceTags[] = [$item[0], $item[1] ?? ''];
            }
        }
        $bookRule->replaceTags = $replaceTags;
        return $bookRule;
    }

    /**
     * 删除规则
     * @param $id
     * @return mixed
     * @Date: 2020/02/03 17:06
     */
    public function deleteCollectionRule($id)
    {
        return CollectionRuleModel::query()->where('id', $id)->delete();
    }

    /**
     * @param $data
     * @return array
     * @Date: 2020/02/04 14:17
     */
    public function testCollectionRule($data)
    {
        $bookRule = $this->getBookRule($data);
        $requestRepository = new BookRequestRepository($bookRule);
        return $this->tryRequestCollection($requestRepository, $data['test_type'], $data['test_url']);
    }

    /**
     * @param BookRequestRepository $requestRepository
     * @param $type
     * @param $url
     * @param int $tries
     * @return array
     * @Date: 2020/02/04 23:13
     */
    private function tryRequestCollection(BookRequestRepository $requestRepository, $type, $url, $tries = 3)
    {
        try {
            if ($type == 'home') {
                $result = $requestRepository->getHome($url);
            } else if ($type == 'content') {
                $result = $requestRepository->getContent($url);
            } else {
                $url = str_replace('{$page}', rand(1, 5), $url);
                $result = $requestRepository->getCategory($url);
                if (empty($result)) {
                    $result = $requestRepository->getRanking($url);
                }
            }
            return $result;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (strpos($message, 'Connection refused') > -1
                || strpos($message, 'timed out') > -1
            ) {
                if ($tries-- > 0) {
                    sleep(1);
                    return $this->tryRequestCollection($requestRepository, $type, $url, $tries);
                }
                return ["Message" => "请求超时，请重试！"];
            }
            return ["Message" => $message];
        }
    }

    /**
     * @return array
     * @Date: 2020/02/06 10:43
     */
    public function getRules()
    {
        $result = CollectionRuleModel::query()
            ->select(['id', 'title'])
            ->where('status', CollectionRuleModel::ENABLE_STATUS)
            ->orderBy('id', 'desc')
            ->get()->toArray();
        return $result;
    }

    public function collectionTask($keyword)
    {
        $query = CollectionTaskModel::query()
            ->where('status', CollectionTaskModel::ENABLE_STATUS);
        $query->orderBy('id', 'desc');
        return $query->paginate(static::$pageSize);
    }

    public function createCollectionTask($data)
    {
        if (!empty($data['id'])) {
            $model = CollectionTaskModel::query()->findOrNew($data['id']);
        } else {
            $model = new CollectionTaskModel();
        }
        $model->title = $data['title'];
        $model->from_url = $data['from_url'];
        $model->from_hash = md5($data['from_url']);
        $model->rule_id = $data['rule_id'];
        $model->page_limit = !empty($data['page_limit']) ? intval($data['page_limit']) : 1;
        $model->retries = !empty($data['retries']) ? intval($data['retries']) : 3;
        return $model->save();
    }

    public function deleteCollectionTask($id)
    {
        return CollectionTaskModel::query()->where('id', $id)->delete();
    }
}
