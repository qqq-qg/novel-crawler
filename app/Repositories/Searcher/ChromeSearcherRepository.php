<?php

namespace App\Repositories\Searcher;

use Illuminate\Support\Collection;
use QL\Ext\Chrome;
use QL\QueryList;

class ChromeSearcherRepository implements SearcherRepositoryInterface
{
    private static $tries = 1;
    private static $currentPage = 1;

    public function __construct($currentPage = 1)
    {
        self::$currentPage = $currentPage;
    }

    public function search($keyword)
    {
        try {
            return $this->handle($keyword);
        } catch (\Exception $e) {
            echo '$e->getMessage()=' . $e->getMessage() . "<br/>";
            if (!is_time_out($e->getMessage())) {
                return false;
            }
            if (self::$tries-- > 0) {
                echo 'try again ...' . "<br/>";
                sleep(1);
                return $this->search($keyword);
            }
            return false;
        }
    }

    /**
     * @param $keyword
     * @Date: 2020/01/20 19:55
     * @return array
     * @throws \Exception
     */
    private function handle($keyword)
    {
        $ql = QueryList::getInstance()->use(Chrome::class, 'chrome');
        $queryData = ['wd' => $keyword];
        if (self::$currentPage > 1) {
            $queryData['pn'] = self::$currentPage * 10;
        }
        $queryString = http_build_query($queryData);

        $result = $ql->chrome(function ($page, $browser) use ($queryString) {
            $page->goto('https://www.baidu.com/s?' . $queryString);
            $html = $page->content();
//            sleep(100);
            $browser->close();
            // 返回值一定要是页面的HTML内容
            return $html;
        }, [
//            'headless' => false, // 启动可视化Chrome浏览器,方便调试
//            'devtools' => true, // 打开浏览器的开发者工具
        ])
            ->rules([
                'title' => ['h3', 'text'],
                'link' => ['h3>a', 'href']
            ])->query()->getData();
        if (!($result instanceof Collection)) {
            throw new \Exception('Connection timed out');
        }
        $data = $result->all();
        foreach ($data as $k => $datum) {
            try {
                $data[$k]['link'] = get_real_url($datum['link']);
            } catch (\Exception|\Throwable $e) {

            }
        }
        return $data;
    }
}
