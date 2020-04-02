<?php

namespace App\Console\Commands;

use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\CollectionRule\QlRule;
use Illuminate\Console\Command;

class InitRule extends Command
{
  protected $signature = 'rule:init';
  protected $description = '初始化规则';

  public function handle()
  {
    //truncate table
    CollectionRuleModel::query()->truncate();
    CollectionTaskModel::query()->truncate();

    $model1 = $this->rule1();
    $this->task($model1);

    $model2 = $this->rule2();

    $model3 = $this->rule3();

    $model4 = $this->rule4();

    echo "Rule init completed successfully.\n";
  }

  private function rule1()
  {
    $bookRule = new BookRule();
    $bookRule->host = 'www.zongheng.com';
    $bookRule->bookList = [
      'category' => new QlRule('ul.main_con>li',
        [
          'url' => ['span.bookname>a', 'href']
        ], true, 2),

      'ranking' => new QlRule('div.rankpage_box>rank_d_list',
        [
          'url' => ['a', 'href']
        ], true, 2)
    ];
    $bookRule->home = new QlRule('',
      [
        'title' => ['div.book-name', 'text'],
        'words_count' => ['div.nums>span>i:first', 'text'],
        'chapter_list_url' => ['a.all-catalog', 'href'],
      ]);
    $bookRule->chapterList = new QlRule('ul.chapter-list>li',
      [
        'title' => ['a', 'text'],
        'from_url' => ['a', 'href']
      ]);
    $bookRule->content = new QlRule('', [
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
    ]);

    $model = CollectionRuleModel::query()->create(
      [
        'title' => '纵横中文网',
        'host' => $bookRule->host,
        'rule_json' => serialize($bookRule),
      ]
    );
    return $model;
  }

  private function rule2()
  {
    $bookRule = new BookRule();
    $bookRule->host = 'www.2wxs.com';
    $bookRule->charset = BookRule::CHARSET_GBK;
    $bookRule->bookList = [
      'category' => new QlRule('',
        [
          'url' => ['ul.item-con>li span.s2>a', 'href']
        ], true, 2),

      'ranking' => new QlRule('',
        [
          'url' => ['ul.item-con>li span.s2>a', 'href']
        ], true, 2)
    ];
    $bookRule->home = new QlRule('',
      [
        'title' => ['div.btitle>h1', 'text'],
        'words_count' => ['none', ''],
        'chapter_list_url' => ['self', ''],
      ]);
    $bookRule->chapterList = new QlRule('',
      [
        'title' => ['dl.chapterlist dd>a', 'text'],
        'from_url' => ['dl.chapterlist dd>a', 'href']
      ]);
    $bookRule->content = new QlRule('div.reader_box', [
      'content' => ['div#BookText', 'html']
    ]);

    $model = CollectionRuleModel::query()->create(
      [
        'title' => '顶点小说网-2wxs',
        'host' => $bookRule->host,
        'rule_json' => serialize($bookRule),
      ]
    );
    return $model;
  }

  private function rule3()
  {
    $bookRule = new BookRule();
    $bookRule->host = 'www.biquge.lu';
    $bookRule->charset = BookRule::CHARSET_GBK;
    $bookRule->bookList = [
      'category' => new QlRule('',
        [
          'url' => ['div.l ul>li span.s2>a', 'href']
        ], true, 1),

      'ranking' => new QlRule('',
        [
          'url' => ['ul.tli li a', 'href']
        ], true, 2)
    ];
    $bookRule->home = new QlRule('',
      [
        'title' => ['div.book h2', 'text'],
        'words_count' => ['none', ''],
        'chapter_list_url' => ['self', ''],
      ]);
    $bookRule->chapterList = new QlRule('',
      [
        'title' => ['.listmain dd>a', 'text'],
        'from_url' => ['.listmain dd>a', 'href']
      ]);
    $bookRule->content = new QlRule('', [
      'content' => ['div#content', 'html', 'script']
    ]);
    $bookRule->splitTag = '';
    $bookRule->replaceTags = [
      ['/<script[\s\S]*?<\/script>/i', '']
    ];

    $model = CollectionRuleModel::query()->create(
      [
        'title' => '笔趣阁-biquge',
        'host' => $bookRule->host,
        'rule_json' => serialize($bookRule),
      ]
    );
    return $model;
  }

  private function rule4()
  {
    $bookRule = new BookRule();
    $bookRule->host = 'www.xbequge.com';
    $bookRule->charset = BookRule::CHARSET_GBK;
    $bookRule->bookList = [
      'category' => new QlRule('',
        [
          'url' => ['ul.item-con li span.s2>a', 'href']
        ], true, 1),

      'ranking' => new QlRule('',
        [
          'url' => ['ul.item-con li span.s2>a', 'href']
        ], true, 2)
    ];
    $bookRule->home = new QlRule('',
      [
        'title' => ['div.info h1', 'text'],
        'words_count' => ['none', ''],
        'chapter_list_url' => ['self', ''],
      ]);
    $bookRule->chapterList = new QlRule('',
      [
        'title' => ['#chapterlist li>a', 'text'],
        'from_url' => ['#chapterlist li>a', 'href']
      ]);
    $bookRule->content = new QlRule('', [
      'content' => ['div#book_text', 'html']
    ]);
    $bookRule->splitTag = '';
    $bookRule->replaceTags = [
      ['/<p>.+?(天才一秒记住本站地址).+?<\/p>/is', ''],
      ['/<p class="content_detail">/', '<p>  '],
      ['/<br>/', ''],
    ];

    $model = CollectionRuleModel::query()->create(
      [
        'title' => '笔趣阁-www.xbequge.com',
        'host' => $bookRule->host,
        'rule_json' => serialize($bookRule),
      ]
    );
    return $model;
  }

  private function task($model)
  {
    $title = '纵横月票榜';
    $url = 'http://www.zongheng.com/rank/details.html?rt=1&d=1&i=2&p={$page}';
    CollectionTaskModel::query()->create([
      'title' => $title,
      'from_url' => $url,
      'from_hash' => md5($url),
      'rule_id' => $model->id,
      'page_limit' => 2,
    ]);

    $title = '纵横中文网古典仙侠';
    $url = 'http://www.zongheng.com/store/c3/c1031/b0/u0/p{$page}/v0/s9/t0/u0/i0/ALL.html';
    CollectionTaskModel::query()->create([
      'title' => $title,
      'from_url' => $url,
      'from_hash' => md5($url),
      'rule_id' => $model->id,
      'page_limit' => 2,
    ]);
  }
}
