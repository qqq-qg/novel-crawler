<?php

namespace App\Listeners;

use App\Events\BooksChangeSourceEvent;

class BooksChangeSourceListener
{
    /**
     * 处理事件
     *
     * @param BooksChangeSourceEvent $event
     * @author Nacrane
     * @Date: 2020/02/11
     * @Time: 11:08
     */
    public function handle(BooksChangeSourceEvent $event)
    {
        //clear old chapter,content
        //add fetch new data
    }
}
