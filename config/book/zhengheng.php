<?php
return [
    'baseUrl' => 'http://book.zongheng.com',
    'charset' => 'utf-8',

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
        'range' => 'div.book-html-box div.book-info',
        'rules' => [
            'title' => [
                'div.book-name', 'text'
            ],
            'words_count' => [
                'div.nums>span>i:first', 'text'
            ],
            'chapter_list_url' => [
                'a.all-catalog', 'href'
            ],
        ]
    ],

    //章节列表页
    'chapter_list' => [
        'range' => '',
        'rules' => [
            'title' => [
                'ul.chapter-list>li>a', 'text'
            ],
            'from_url' => [
                'ul.chapter-list>li>a', 'href'
            ]
        ],
    ],

    //详情页
    'content' => [
        'range' => 'div.reader_box',
        'rules' => [
            'chapter_name' => [
                'div.title_txtbox', 'text'
            ],
            'author' => [
                'div.bookinfo>a:eq(0)', 'text'
            ],
            'words_num' => [
                'div.bookinfo>span:eq(0)', 'text'
            ],
            'update_time' => [
                'div.bookinfo>span:eq(1)', 'text'
            ],
            'content' => ['div.content', 'html']
        ]
    ],
];
