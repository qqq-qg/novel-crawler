<?php

namespace App\Http\Controllers;

use App\Jobs\BooksJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\CollectionTaskModel;
use QL\Ext\CurlMulti;
use QL\QueryList;

class TestController extends Controller
{

    private $config;
    private $bookRule;

    public function __construct()
    {
        $this->config = config('book.2wxs');
    }

    public function ranking()
    {
        $tasks = (new CollectionTaskModel())->getTasks(1);
        $this->bookRule = unserialize($tasks[0]->rule['rule_json']);

        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti([
            'http://book.zongheng.com/chapter/912604/59026792.html',
            'http://book.zongheng.com/chapter/912604/59047790.html',
            'http://book.zongheng.com/chapter/912604/59047804.html',
            'http://book.zongheng.com/chapter/912604/59098949.html',
        ])
            // Called if task is success
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                echo "success return url:{$r['info']['url']}" . PHP_EOL;
                $data = $ql
                    ->range($this->bookRule->content->range)
                    ->rules($this->bookRule->content->rules)
                    ->query()->getData()->first();
                $content = trim($data['content'] ?? '');
                $urlHash = md5(trim($r['info']['url']));
                $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                if (!empty($data) && !empty($content)) {
                    $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
                    if (!empty($contentModel)) {
                        $contentModel->update(['content' => $content,]);
                    } else {
                        BooksContentModel::query()->create(['id' => $chapterModel->id, 'content' => $content]);
                    }
                }
            })
            // Task fail callback
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Current url:{$errorInfo['info']['url']} \r\n";
                print_r($errorInfo['error']);
            })
            ->start([
                // Maximum number of threads
                'maxThread' => 10,
                // Number of error retries
                'maxTry' => 3,
            ]);
    }

    public function category()
    {
        $url = $this->config['host'] . '/rank/details.html?rt=8&d=1&p={$page}';
        //    file_put_contents('category.html', QueryList::get($url)->getHtml());
        //    die;
        $url = 'http://www.ql.com/category.html';

        $data = QueryList::get($url)
            ->range($this->config['category']['range'])
            ->rules($this->config['category']['rules'])
            ->query()->getData();
        print_r($data->pluck('url')->all());

        die;
        for ($i = 1; $i <= 3; $i++) {
            $findUrl = str_replace('{$page}', $i, $url);
            //      var_dump($findUrl);
            //      continue;
            $data = QueryList::get($findUrl)
                ->range($this->config['category']['range'])
                ->rules($this->config['category']['rules'])
                ->query()->getData();
            print_r($data->all());
        }
    }

    public function home()
    {
        $url = 'https://' . $this->config['host'] . '/xstxt/279882/';
//        file_put_contents('home.html', QueryList::get($url)->getHtml());
//        die;
        $url = 'http://www.ql.com/home.html';


        $urlArr = parse_url($url);
        dd($urlArr);

//        $title = QueryList::get($url)
////            ->encoding('gbk','utf-8')
////            ->encoding('utf-8','gbk')
//            ->encoding('gbk')
//            ->find('div.btitle>h1')->texts();
//        dd($title);

        $ql = QueryList::get($url);
        $data = $ql
            ->range($this->config['home']['range'])
            ->rules($this->config['home']['rules'])
            ->query()->getData()->first();
        dd($data);



        if (empty($this->config['charset']) || $this->config['charset'] == 'utf-8') {
            $data = $ql
                ->range($this->config['home']['range'])
                ->rules($this->config['home']['rules'])
                ->query()->getData()->first();
            dd($data);
        }
        $ql->encoding($this->config['charset']);
        $title = $this->findSingleValue($ql, $this->config['home']['rules']['title']);
        $wordsCount = $this->findSingleValue($ql, $this->config['home']['rules']['words_count']);
        $chapterListUrl = $this->findSingleValue($ql, $this->config['home']['rules']['chapter_list_url']);

        echo 'title=' . $title . "<br/>";
        echo '$wordsCount=' . $wordsCount . "<br/>";
        echo '$chapterListUrl=' . $chapterListUrl . "<br/>";
        dd();
    }

    private function findSingleValue(QueryList $ql, $ruleValue)
    {
        if ($ruleValue[0] == 'self') {
            return 'self';
        }
        if ($ruleValue[0] == 'none') {
            return '';
        }
        switch ($ruleValue[1]) {
            case 'text':
                return $ql->find($ruleValue[0])->texts()[0] ?? '';
                break;
            case 'html':
                return $ql->find($ruleValue[0])->htmls()[0] ?? '';
                break;
            default:
                return $ql->find($ruleValue[0])->attrs($ruleValue[1])[0] ?? '';
        }
    }

    private function findMultiValue(QueryList $ql, $ruleValue)
    {
        if ($ruleValue[0] == 'none') {
            return [];
        }
        switch ($ruleValue[1]) {
            case 'text':
                return $ql->find($ruleValue[0])->texts() ?? [];
                break;
            case 'html':
                return $ql->find($ruleValue[0])->htmls() ?? [];
                break;
            default:
                return $ql->find($ruleValue[0])->attrs($ruleValue[1]) ?? [];
        }
    }

    public function getChapter()
    {
        $url = 'https://' . $this->config['host'] . '/xstxt/289368/';
//        $url = 'https://www.2wxs.com/xstxt/289368/';
//        file_put_contents('home.html', QueryList::get($url)->removeHead()->getHtml());
//        dd('ok!');
        $url = 'http://www.ql.com/home.html';

//        $html = QueryList::get($url)
//            ->encoding('utf-8', $this->config['charset'])
//            ->removeHead()->getHtml();
//        file_put_contents('home.html', $html);
//        dd('ok!');
        $data = QueryList::get($url)
//            ->encoding($this->config['charset'])
            ->rules($this->config['chapter_list']['rules'])
            ->query()->getData();
        dd($data->all());

        $ql = QueryList::get($url)->encoding($this->config['charset']);
        $titleArr = $this->findMultiValue($ql, $this->config['chapter_list']['rules']['title']);
        $fromUrlArr = $this->findMultiValue($ql, $this->config['chapter_list']['rules']['from_url']);

        dd($titleArr, $fromUrlArr);


        $data = QueryList::get($url)
            ->encoding($this->config['charset'])
//            ->range($this->config['chapter_list']['range'])
            ->rules($this->config['chapter_list']['rules'])
            ->query()->getData();
        print_r($data->all());
        die;
    }

    public function content()
    {
        $url = $this->config['host'] . '/xstxt/279882/32328251.html';
//        file_put_contents('content.html', QueryList::get($url)->getHtml());
//        die;
        $url = 'http://www.ql.com/content.html';

//        $html = iconv('gbk', 'utf-8', QueryList::get($url)->getHtml());

//        $html = mb_convert_encoding(QueryList::get($url)->removeHead()->getHtml(), "UTF-8", "GBK");
//        file_put_contents('content1.html', QueryList::get($url)->removeHead()->getHtml());
//        die;
        $html = file_get_contents('content1.html');

        $data = QueryList::getInstance()->setHtml(QueryList::get($url)->removeHead()->getHtml())
//            ->encoding('utf-8')
            ->rules($this->config['content']['rules'])
            ->query()->getData()->first();
        dd($data);

        $ql = QueryList::get($url);
//        if (empty($this->config['charset']) || $this->config['charset'] == 'utf-8') {
        $data = $ql->encoding('utf-8', $this->config['charset'])->removeHead()
            ->range($this->config['content']['range'])
            ->rules($this->config['content']['rules'])
            ->query()->getData()->first();
        dd($data);
//        }
        $ql->removeHead();
        $content = $ql->find('#BookText')->html();

//        $content = $this->findSingleValue($ql, $this->config['content']['rules']['content']);
        dd($content);

//        $data = QueryList::get($url)
//            ->encoding($this->config['charset'])
//            ->range($this->config['chapter_list']['range'])
//            ->rules($this->config['content']['rules'])
//            ->query()->getData();
//        dd($data->all());
    }
}
