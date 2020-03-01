<?php

namespace App\Console\Commands;

use App\Jobs\SearchBooksJob;
use Illuminate\Console\Command;

class SearcherBookTask extends Command
{
    protected $signature = 'search {title}';

    protected $description = '搜索任务，启动队列';

    public function handle()
    {
        $title = $this->argument('title');
        dispatch(new SearchBooksJob($title));
        echo "{$title} 已加入搜索任务队列!";
    }
}
