<?php

namespace App\Console\Commands;

use App\Events\BooksFetchContentEvent;
use App\Models\Books\BooksModel;
use Illuminate\Console\Command;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class Test extends Command
{
    protected $signature = 'test';

    protected $description = '';

    public function handle()
    {
        $booksArr = BooksModel::query()
            ->where(['status' => BooksModel::ENABLE_STATUS, 'update_status' => BooksModel::UPT_STATUS_LOADING])
            ->get();
        foreach ($booksArr as $book) {
            event(new BooksFetchContentEvent($book->id));
        }
        die;
        $puppeteer = new Puppeteer();
        $browser = $puppeteer->launch(['headless' => false]);

        $page = $browser->newPage();
        $page->goto('https://www.baidu.com');
        // Get the "viewport" of the page, as reported by the page.
        $dimensions = $page->evaluate(JsFunction::createWithBody("
    return {
        width: document.documentElement.clientWidth,
        height: document.documentElement.clientHeight,
        deviceScaleFactor: window.devicePixelRatio
    };
"));

        printf('Dimensions: %s', print_r($dimensions, true));

        $browser->close();
    }
}
