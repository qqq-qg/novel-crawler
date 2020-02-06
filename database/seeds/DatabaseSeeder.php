<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menus')->truncate();
        DB::table('admin_menus')->insert(array(
            array(
                'id' => 1,
                'pid' => 0,
                'name' => '后台首页',
                'prefix' => 'Admin',
                'route' => 'getIndex',
                'ico' => 'fa fa-home',
                'listorder' => 1,
                'items' => 0,
            ),
            array(
                'id' => 2,
                'pid' => 0,
                'name' => '菜单管理',
                'prefix' => 'Menu',
                'route' => 'getIndex',
                'ico' => 'fa fa-list',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 3,
                'pid' => 0,
                'name' => '数据管理',
                'prefix' => 'Database',
                'route' => 'getIndex',
                'ico' => 'fa fa-database',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 4,
                'pid' => 0,
                'name' => '管理员管理',
                'prefix' => 'Manager',
                'route' => 'getIndex',
                'ico' => 'fa fa-users',
                'listorder' => 0,
                'items' => 2,
            ),
            array(
                'id' => 5,
                'pid' => 4,
                'name' => '添加管理员',
                'prefix' => 'Manager',
                'route' => 'getCreate',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 6,
                'pid' => 4,
                'name' => '管理员列表',
                'prefix' => 'Manager',
                'route' => 'getIndex',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 7,
                'pid' => 0,
                'name' => '系统配置',
                'prefix' => 'Setting',
                'route' => 'getIndex',
                'ico' => 'fa fa-cog',
                'listorder' => 0,
                'items' => 4,
            ),
            array(
                'id' => 8,
                'pid' => 7,
                'name' => '站点配置',
                'prefix' => 'Setting',
                'route' => 'getIndex',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 9,
                'pid' => 0,
                'name' => '小说管理',
                'prefix' => 'Book',
                'route' => 'getIndex',
                'ico' => 'fa fa-book',
                'listorder' => 0,
                'items' => 2,
            ),
            array(
                'id' => 10,
                'pid' => 9,
                'name' => '小说列表',
                'prefix' => 'Book',
                'route' => 'getIndex',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 11,
                'pid' => 9,
                'name' => '栏目管理',
                'prefix' => 'Book',
                'route' => 'getCategories',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 12,
                'pid' => 7,
                'name' => '友情链接',
                'prefix' => 'Setting',
                'route' => 'getFriendLinks',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 13,
                'pid' => 7,
                'name' => '图片上传',
                'prefix' => 'Setting',
                'route' => 'getImageUpload',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 14,
                'pid' => 7,
                'name' => '链接推送',
                'prefix' => 'Setting',
                'route' => 'getLinkSubmit',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 15,
                'pid' => 9,
                'name' => '采集规则',
                'prefix' => 'Book',
                'route' => 'collectionRule',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
            array(
                'id' => 16,
                'pid' => 9,
                'name' => '采集任务',
                'prefix' => 'Book',
                'route' => 'collectionTask',
                'ico' => '',
                'listorder' => 0,
                'items' => 0,
            ),
        ));

        DB::table('managers')->truncate();
        DB::table('managers')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'username' => 'admin',
                    'password' => '$2y$10$UNtvtqYOoBuxl4AB/fDvcuvMn4RPV80SCx2O.c/wMgRwD3qB891QC',
                    'truename' => 'Nacrane',
                    'email' => 'nacrane2006@163.com',
                    'salt' => '',
                    'lastip' => '127.0.0.1',
                    'lasttime' => time(),
                    'remember_token' => NULL,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ),
        ));

        DB::table('settings')->truncate();
        DB::table('settings')->insert(array(
            0 =>
                array(
                    'item' => 'admin_email',
                    'name' => '管理员邮箱',
                    'value' => 'nacrane2006@163.com',
                ),
            1 =>
                array(
                    'item' => 'description',
                    'name' => '网站简介',
                    'value' => '天下书屋网免费为您提供小说在线阅读服务，没有弹窗广告。为大家分享优质小说！',
                ),
            2 =>
                array(
                    'item' => 'icp',
                    'name' => '网站备案号',
                    'value' => '皖ICP备15001767号',
                ),
            3 =>
                array(
                    'item' => 'keywords',
                    'name' => '网站关键词',
                    'value' => '天下书屋网,免费小说,无弹窗',
                ),
            4 =>
                array(
                    'item' => 'oauth_qq_appid',
                    'name' => 'QQ登录APP ID',
                    'value' => '101343019',
                ),
            5 =>
                array(
                    'item' => 'oauth_qq_appkey',
                    'name' => 'QQ互联APP KEY',
                    'value' => '8c975059d21cfb6cab0a5ae57be79c68',
                ),
            6 =>
                array(
                    'item' => 'powerby',
                    'name' => '网站版权',
                    'value' => 'Copyright © 2017 天下书屋网 All Rights Reserved.',
                ),
            7 =>
                array(
                    'item' => 'title',
                    'name' => '网站标题',
                    'value' => '天下书屋 - 无弹窗小说阅读网',
                ),
        ));


        DB::table('category')->truncate();
        DB::table('category')->insert([
            ['id' => 1, 'name' => '玄幻魔法', 'listorder' => 1],
            ['id' => 2, 'name' => '武侠修真', 'listorder' => 2],
            ['id' => 3, 'name' => '都市言情', 'listorder' => 3],
            ['id' => 4, 'name' => '历史穿越', 'listorder' => 4],
            ['id' => 5, 'name' => '恐怖悬疑', 'listorder' => 5],
            ['id' => 6, 'name' => '游戏竞技', 'listorder' => 6],
            ['id' => 7, 'name' => '军事科幻', 'listorder' => 7],
            ['id' => 8, 'name' => '综合类型', 'listorder' => 8],
            ['id' => 9, 'name' => '女生频道', 'listorder' => 9],
        ]);
    }
}
