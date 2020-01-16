<?php
return [
  'baseUrl' => 'http://book.zongheng.com',
  'charset' => 'gb2312',
  //简介
  'home' => [
    //区域选择器
    'range' => 'div.book-html-box div.book-info',
    //规则
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
    //区域选择器
    'range' => '',
    //规则
    'rules' => [
//      'book_name' => [
//        'div.book-meta>h1:first', 'text'
//      ],
//      'author' => [
//        'div.book-meta>p>span:eq(0)>a', 'text'
//      ],
//      'update_time' => [
//        'div.book-meta>p>span:eq(1)', 'text'
//      ],
//      'last_chapter_title' => [
//        'div.book-meta>p>span:eq(2)', 'text'
//      ],
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
      //过滤div和p标签
      'content' => ['div.content', 'html']
    ]
  ],
];
