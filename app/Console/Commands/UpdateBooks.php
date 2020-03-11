<?php

namespace App\Console\Commands;

use App\Events\BooksUpdateEvent;
use App\Models\Books\BooksModel;
use Illuminate\Console\Command;

class UpdateBooks extends Command
{
    protected $signature = 'book:update {id?}';

    protected $description = '搜索任务，启动队列';

    public function handle()
    {
        $ids = format_ranger_array($this->argument('id'));
        /**
         * @var BooksModel[] $booksModelArr
         */
        if ($ids) {
            $booksModelArr = BooksModel::query()->whereIn('id', $ids)->get();
        } else {
            $booksModelArr = BooksModel::query()->get();
        }
        foreach ($booksModelArr as $book) {
            event(new BooksUpdateEvent($book));
        }
    }
}
