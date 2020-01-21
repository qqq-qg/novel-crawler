<?php
return [
    'host' => 'www.2wxs.com',
    'charset' => 'gbk',

    //目录页
    'category' => [
        'range' => '',
        'rules' => [
            'url' => [
                'ul.main_con>li span.bookname>a', 'href'
            ],
        ]
    ],

    'ranking' => [
        'range' => '',
        'rules' => [
            'url' => [
                'div.rank_d_list>div.rank_d_book_img>a', 'href'
            ],
        ]
    ],


    //简介
    'home' => [
        'range' => '',
        'rules' => [
            'title' => [
                'div.btitle>h1', 'text'
            ],
            'words_count' => [
                'none', ''
            ],
            'chapter_list_url' => [
                'self', ''
            ],
        ]
    ],

    //章节列表页
    'chapter_list' => [
        'range' => '',
        'rules' => [
            'title' => ['dl.chapterlist dd>a', 'text'],
            'from_url' => ['dl.chapterlist dd>a', 'href']
        ],
    ],

    //详情页
    'content' => [
        'range' => '',
        'rules' => [
            'content' => ['div#BookText', 'html']
        ]
    ],
];
