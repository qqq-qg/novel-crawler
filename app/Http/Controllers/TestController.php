<?php

namespace App\Http\Controllers;


use App\Events\BooksChangeSourceEvent;
use App\Events\BooksFetchContentEvent;
use App\Jobs\BooksContentFuzzyJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\CurlMulti;
use App\Repositories\Searcher\Plugin\FilterHeader;
use App\Repositories\TryAnalysis\TryAnalysisContent;
use QL\QueryList;

class TestController extends Controller
{
    public function index()
    {
        $urls = BooksChapterModel::query()
            ->where('books_id', 1)
            ->where('is_success', 0)
            ->orderBy('id', 'asc')
//            ->limit(10)
            ->pluck('from_url')->toArray();
//        $job = new BooksContentFuzzyJob($urls);
//        $job->handle();
//        dd(123);

        foreach (array_chunk($urls, 200) as $_urls) {
            dispatch(new BooksContentFuzzyJob($_urls));
        }
        dd(date('Y-m-d H:i:s'));

        $ql = QueryList::html(file_get_contents('1.html'));
        $content = (new TryAnalysisContent('', $ql))->handle();
        dd($content);
//        $url = 'http://www.biquge.info/34_34863/';
//        $a = (new TryAnalysisCategory($url))->handle();
//        dd($a);

        $content = (new TryAnalysisContent('http://www.biquge.info/12930814.html'))->handle();
        dd($content);

//        $link = 'https://www.biqukan.com/75_75307/';
//        $res = BookRequestRepository::tryPregCategory($link);
//        dd($res);
//        $redis = app("redis.connection");
//        $redis->set('library', 'phpredis');//存储key为library ，值phpredis得记录
//        $val = $redis->get("library");//获取key为library得记录值
//        dd($val);
//        $this->jobUnserialize();
//        $this->changeSource();
//        $this->fetchContent();
        dd(11);
    }


    private function preg()
    {

        $pattern = '/<div[\s\S]*?>\s*?(<p[\s\S]*?>[\s\S]*?<\/p>)\s*?<\/div>/';
        $pattern = '/<div\s*?.*?>\s*(<p\s*?.*?>[\s\S]*?<\/p>\s*?)<\/div>/';
        $string1 = <<<EOF
<div>
  <div class="a">
    <p>abc</p>
    <p class="p-c">
    de
    f</p></div>
</div>
EOF;
        if (preg_match($pattern, $string1, $arr)) {
            dd($arr);
        }
        dd(false);
    }

    private function changeSource()
    {
        $book = BooksModel::query()->where('id', 41)->first();
        event(new BooksChangeSourceEvent($book));
    }

    /**
     * @var BookRule $bookRule
     */
    private $bookRule;

    private function fetchContent()
    {
        event(new BooksFetchContentEvent(41));
        dd(123);

        $urls = ['https://www.2wxs.com/xstxt/282699/34623439.html'];
//        $html = QueryList::get($urls[0], [], ['verify' => false,'timeout'=>30])->getHtml();
//        echo $html;die;
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($urls)
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                try {
                    $qlUrl = $r['info']['url'];
                    $urlHash = md5(trim($qlUrl));
                    $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                    if ($this->bookRule->needEncoding()) {
                        $ql->use(FilterHeader::class)->filterHeader();
                        $ql->encoding(BookRule::CHARSET_UTF8);
                        if (!empty($this->bookRule->replaceTags)) {
                            $html = $ql->getHtml();
                            foreach ($this->bookRule->replaceTags ?? [] as $tag) {
                                $html = preg_replace($tag[0], $tag[1] ?? '', $html);
                            }
                            $ql->setHtml($html);
                        }
                    }
                    $data = $ql
                        ->range($this->bookRule->content->range)
                        ->rules($this->bookRule->content->rules)
                        ->query()->getData()->first();

                    $content = trim($data['content'] ?? '');
                    if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
                        $content = explode($this->bookRule->splitTag, $content)[0];
                    }
                    foreach ($this->bookRule->replaceTags ?? [] as $tag) {
                        $content = preg_replace($tag[0], $tag[1] ?? '', $content);
                    }
                    if (!empty($content)) {
                        $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
                        if (!empty($contentModel)) {
                            $contentModel->update(['content' => $content]);
                        } else {
                            BooksContentModel::query()->create(['id' => $chapterModel->id, 'content' => $content]);
                        }
                        $chapterModel->saveProcessed();
                    }
                } catch (\Exception $e) {
                    $againUrl[] = $qlUrl;
                }
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Error url:{$errorInfo['info']['url']} \r\n";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 30,
                'maxTry' => 1,
            ]);
    }

    private function jobUnserialize()
    {
        $jobStr = <<<'EOF'
{"displayName":"App\\Jobs\\NewBooksJob","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":null,"delay":null,"timeout":null,"timeoutAt":null,"data":{"commandName":"App\\Jobs\\NewBooksJob","command":"O:20:\"App\\Jobs\\NewBooksJob\":11:{s:30:\"\u0000App\\Jobs\\NewBooksJob\u0000bookRule\";O:40:\"App\\Repositories\\CollectionRule\\BookRule\":8:{s:4:\"host\";s:12:\"www.2wxs.com\";s:7:\"charset\";s:3:\"gbk\";s:8:\"splitTag\";s:0:\"\";s:11:\"replaceTags\";a:0:{}s:8:\"bookList\";a:2:{s:8:\"category\";O:38:\"App\\Repositories\\CollectionRule\\QlRule\":4:{s:5:\"range\";s:0:\"\";s:5:\"rules\";a:1:{s:3:\"url\";a:2:{i:0;s:24:\"ul.item-con>li span.s2>a\";i:1;s:4:\"href\";}}s:8:\"nextPage\";b:1;s:4:\"page\";i:2;}s:7:\"ranking\";O:38:\"App\\Repositories\\CollectionRule\\QlRule\":4:{s:5:\"range\";s:0:\"\";s:5:\"rules\";a:1:{s:3:\"url\";a:2:{i:0;s:24:\"ul.item-con>li span.s2>a\";i:1;s:4:\"href\";}}s:8:\"nextPage\";b:1;s:4:\"page\";i:2;}}s:4:\"home\";O:38:\"App\\Repositories\\CollectionRule\\QlRule\":4:{s:5:\"range\";s:0:\"\";s:5:\"rules\";a:3:{s:5:\"title\";a:2:{i:0;s:13:\"div.btitle>h1\";i:1;s:4:\"text\";}s:11:\"words_count\";a:2:{i:0;s:4:\"none\";i:1;s:0:\"\";}s:16:\"chapter_list_url\";a:2:{i:0;s:4:\"self\";i:1;s:0:\"\";}}s:8:\"nextPage\";b:0;s:4:\"page\";i:0;}s:11:\"chapterList\";O:38:\"App\\Repositories\\CollectionRule\\QlRule\":4:{s:5:\"range\";s:0:\"\";s:5:\"rules\";a:2:{s:5:\"title\";a:2:{i:0;s:19:\"dl.chapterlist dd>a\";i:1;s:4:\"text\";}s:8:\"from_url\";a:2:{i:0;s:19:\"dl.chapterlist dd>a\";i:1;s:4:\"href\";}}s:8:\"nextPage\";b:0;s:4:\"page\";i:0;}s:7:\"content\";O:38:\"App\\Repositories\\CollectionRule\\QlRule\":4:{s:5:\"range\";s:14:\"div.reader_box\";s:5:\"rules\";a:1:{s:7:\"content\";a:2:{i:0;s:12:\"div#BookText\";i:1;s:4:\"html\";}}s:8:\"nextPage\";b:0;s:4:\"page\";i:0;}}s:25:\"\u0000App\\Jobs\\NewBooksJob\u0000url\";s:34:\"https:\/\/www.2wxs.com\/xstxt\/282699\/\";s:29:\"\u0000App\\Jobs\\NewBooksJob\u0000rule_id\";i:2;s:6:\"\u0000*\u0000job\";N;s:10:\"connection\";N;s:5:\"queue\";N;s:15:\"chainConnection\";N;s:10:\"chainQueue\";N;s:5:\"delay\";N;s:10:\"middleware\";a:0:{}s:7:\"chained\";a:0:{}}"}}
EOF;
        $job = json_decode($jobStr, true);
        $command = $job['data']['command'];
        $obj = unserialize($command);
        dd($obj);
    }
}
