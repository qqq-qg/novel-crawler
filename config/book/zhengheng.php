<?php
return [
    'baseUrl' => 'http://book.zongheng.com',
    'charset' => 'gb2312',
    //列表页
    'lists' => [
        //区域选择器
        'range' => '.mainarea .con ul',
        //规则
        'rules' => [
            'title' => [
                'li a.f14', 'text'
            ],
            'fromurl' => [
                'li a.f14', 'href'
            ],
            'author' => [
                'li.ro3 a ', 'text'
            ],
            //'wordcount'=> [
            //    '.zs','text'
            //],
            'updatetime' => [
                'li.ro4', 'text'
            ],
            'zhangjie' => [
                'li a.f14+a', 'text'
            ]
        ],
        //列表页数量
        'pagesize' => 50,
        'pageurl' => 'Book/ShowBookList.aspx?tclassid=%d&page=%d',
    ],
    //简介
    'home' => [
        //区域选择器
        'range' => '#content .list:eq(1) li',
        //规则
        'rules' => [
            'title' => [
                'a', 'text'
            ],
            'fromurl' => [
                'a', 'href'
            ],
        ],
        'pageurl' => 'article/%d/%d/Default.shtml',
    ],

    //章节列表页
    'chapter_list' => [
        //区域选择器
        'range' => '.mainarea .con ul',
        //规则
        'rules' => [
            'title' => [
                'li a.f14', 'text'
            ],
            'fromurl' => [
                'li a.f14', 'href'
            ],
            'author' => [
                'li.ro3 a ', 'text'
            ],
            'updatetime' => [
                'li.ro4', 'text'
            ],
            'zhangjie' => [
                'li a.f14+a', 'text'
            ]
        ],
        //列表页数量
        'pagesize' => 50,
        'pageurl' => 'Book/ShowBookList.aspx?tclassid=%d&page=%d',
    ],

    //详情页
    'content' => [
        'range' => '',
        'rules' => [
            //过滤div和p标签
            'content' => ['#box', 'text', '-p -div -script']
        ]
    ],
];
