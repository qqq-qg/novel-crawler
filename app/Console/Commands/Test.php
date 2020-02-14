<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class Test extends Command
{
    protected $signature = 'test';

    protected $description = '';

    public function handle()
    {

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
