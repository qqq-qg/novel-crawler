<?php

namespace App\Console\Commands;

use App\Events\BooksFetchContentEvent;
use App\Models\Books\BooksModel;
use Illuminate\Console\Command;

class FetchContent extends Command
{
    protected $signature = 'book:fix {id}';

    protected $description = '搜索任务，启动队列';

    public function handle()
    {
        $ids = format_ranger_array($this->argument('id'));
        $booksIdArr = BooksModel::query()->whereIn('id', $ids)->pluck('id')->toArray();

        event(new BooksFetchContentEvent($booksIdArr));
    }
}
