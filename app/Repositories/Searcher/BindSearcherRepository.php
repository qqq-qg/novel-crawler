<?php

namespace App\Repositories\Searcher;


use App\Repositories\Proxy\KuaiDaiLiProxy;
use App\Repositories\Proxy\ProxyRepository;
use App\Repositories\Searcher\Plugin\BingSearcher;
use QL\QueryList;

class BindSearcherRepository implements SearcherRepositoryInterface
{
    private $tries = 1;

    /**
     * @var BingSearcher $bingSearcher
     */
    private $bingSearcher = null;

    /**
     * @var ProxyRepository $proxyService
     */
    private $proxyService = null;

    public function __construct()
    {
        $this->bingSearcher = QueryList::getInstance()->use(BingSearcher::class)->BingSearcher();
        $this->proxyService = new KuaiDaiLiProxy();
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
            if ($this->tries-- > 0) {
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
     */
    private function handle($keyword)
    {
        $proxy = $this->proxyService->getProxyPool();
        $result = $this->bingSearcher->search($keyword)
            ->setHttpOption([
                'proxy' => $proxy,
                'User-Agent' => get_random_user_agent(),
                'timeout' => 10,
            ])
            ->page(1);
        return $result;
    }
}
