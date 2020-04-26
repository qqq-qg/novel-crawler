<?php

namespace App\Repositories\Admin;

use App\Models\Admin\CollectionRuleModel;
use App\Repositories\Books\BookRequestRepository;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;
use Illuminate\Database\Eloquent\Builder;

class CollectionRuleRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(CollectionRuleModel::query(), $search)->paginate($search['pageSize'] ?? 10);
    foreach ($paginate->items() as $k => $item) {
      //todo
    }
    return $paginate;
  }

  private function searchQuery(Builder $query, $search)
  {
    if (!empty($search['status'])) {
      $query->where('status', $search['status']);
    }
    return $query->orderByDesc('id');
  }

  public function store($data)
  {
    $ruleData = [
      'title' => $data['title'] ?? '',
      'host' => $data['host'] ?? '',
    ];
    $bookRule = $this->getBookRule($data);
    $ruleData['rule_json'] = serialize($bookRule);
    if (empty($data['id'])) {
      $model = CollectionRuleModel::query()->create($ruleData);
      if (!$model->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $model = CollectionRuleModel::query()->where('id', $data['id'])->first();
      $rst = $model->update($ruleData);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $model->id;
  }

  public function getEmptyData()
  {
    $rule = new CollectionRuleModel();
    $rule->bookRule = $this->getBookRule([]);
    return $rule;
  }

  public function show($id)
  {
    $rule = CollectionRuleModel::query()->find($id);
    $rule->bookRule = unserialize($rule->rule_json);
    return $rule;
  }

  public function destroy($id)
  {
    $rst = CollectionRuleModel::query()->where('id', $id)->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }

  public function all()
  {
    return CollectionRuleModel::query()->orderBy('id')->get();
  }

  public function getRuleName($ruleId)
  {
    static $rules = null;
    if (is_null($rules)) {
      $res = CollectionRuleModel::query()->select(['id', 'title'])->get()->toArray();
      $rules = array_column($res, 'title', 'id');
    }
    return $rules[$ruleId] ?? '';
  }

  /**
   * @param $data
   * @return array
   * @author Nacrane
   * @Date: 2020/04/25 20:55
   */
  public function testCollectionRule($data)
  {
    $bookRule = $this->getBookRule($data);
    $requestRepository = new BookRequestRepository($bookRule);
    return $this->tryRequestCollection($requestRepository, $data['test_type'], $data['test_url']);
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
    $nullRule = ['', '', null, null];
    $bookRule->bookList = [
      'category' => new QlRule($data['category']['range'] ?? '',
        ['url' => $data['category']['url'] ?? $nullRule], $cPage > 1, $cPage),
      'ranking' => new QlRule($data['ranking']['range'] ?? '',
        ['url' => $data['ranking']['url'] ?? $nullRule], $rPage > 1, $rPage)
    ];
    $bookRule->home = new QlRule('', $data['home'] ?? [
        'title' => $nullRule,
        'words_count' => $nullRule,
        'chapter_list_url' => $nullRule,
      ]);
    $bookRule->chapterList = new QlRule($data['chapterList']['range'] ?? '', [
      'title' => $data['chapterList']['title'] ?? $nullRule,
      'from_url' => $data['chapterList']['from_url'] ?? $nullRule
    ]);
    $bookRule->content = new QlRule('', $data['content'] ?? [
        'content' => $nullRule,
      ]);
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
   * @param BookRequestRepository $requestRepository
   * @param $type
   * @param $url
   * @param int $tries
   * @return array|mixed
   * @author Nacrane
   * @Date: 2020/04/25 20:55
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
}
