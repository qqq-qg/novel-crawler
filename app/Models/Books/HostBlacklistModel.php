<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class HostBlacklistModel
 * @author Nacrane
 * @Date: 2020/02/11 16:08
 * @package App\Models\Books
 *
 * @property integer id
 * @property string host 域名
 * @property integer status 状态,1-正常，2-无效
 * @property string created_at
 * @property string updated_at
 */
class HostBlacklistModel extends BaseModel
{
    protected $table = 'books_host_blacklist';

    public static function getALlEnableHost()
    {
        return HostBlacklistModel::query()->where('status', HostBlacklistModel::ENABLE_STATUS)->pluck('host')->toArray();
    }
}
