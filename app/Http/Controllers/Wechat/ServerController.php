<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use Log;

class ServerController extends Controller
{
    public function getIndex()
    {
        Log::info('Wechat Debug Start');
        $wechat = app('EasyWechat');
        $wechat->server->setMessageHandler(function ($Message) {
            return '还原管';
        });
        $wechat->server->serve();
    }
}
