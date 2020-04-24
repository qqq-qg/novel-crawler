@extends('admin.layouts.iframe')
@section('content')
  <div class="layui-fluid">
    <div class="layui-card lay-ui/">
      <div class="layui-card-body">
        <div class="layui-row">
          <div id="form-card" class="layui-col-md12">
            <form class="layui-form layui-form-pane" action="" lay-filter="create-form">
              <input type="hidden" name="id">
              <div class="layui-form-item">
                <label class="layui-form-label">规则标题</label>
                <div class="layui-input-block">
                  <input type="text" name="title" class="layui-input" autocomplete="off" placeholder="请输入标题 (必填)"
                         lay-verify="required" value="<?php echo $data['title'] ?? ''?>">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">域名地址</label>
                <div class="layui-input-block">
                  <input type="text" name="host" class="layui-input" autocomplete="off" placeholder="请输入域名地址 (必填)"
                         lay-verify="required" value="<?php echo $data['bookRule']->host ?? ''?>">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">编码</label>
                <div class="layui-input-block">
                  <select name="charset" lay-filter="charset">
                    <option value="UTF-8" <?php echo $data['bookRule']->charset == 'UTF_8' ? 'selected' : ''?>>UTF-8
                    </option>
                    <option value="GBK" <?php echo $data['bookRule']->charset == 'GBK' ? 'selected' : ''?>>GBK</option>
                  </select>
                </div>
              </div>

              <div class="layui-collapse">
                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">排行列表</h2>
                  <div class="layui-colla-content layui-show">
                    <div class="layui-form-item">
                      <label class="layui-form-label">范围选择器</label>
                      <div class="layui-input-block">
                        <input type="text" name="ranking[range]" class="layui-input" autocomplete="off"
                               placeholder="请输入范围选择器 (必填)" lay-verify="required"
                               value="<?php echo $data['bookRule']->bookList['ranking']->range?>">
                      </div>
                    </div>
                    <table lay-even id="rank-data" lay-filter="rank-data" lay-size="sm"></table>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">分类列表</h2>
                  <div class="layui-colla-content layui-show">
                    <div class="layui-form-item">
                      <label class="layui-form-label">范围选择器</label>
                      <div class="layui-input-block">
                        <input type="text" name="category[range]" class="layui-input" autocomplete="off"
                               placeholder="请输入范围选择器 (必填)" lay-verify="required"
                               value="<?php echo $data['bookRule']->bookList['category']->range?>">
                      </div>
                    </div>
                    <table lay-even id="category-data" lay-filter="category-data" lay-size="sm"></table>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">目录详情</h2>
                  <div class="layui-colla-content layui-show">
                    <div class="layui-form-item">
                      <label class="layui-form-label">范围选择器</label>
                      <div class="layui-input-block">
                        <input type="text" name="chapterList[range]" class="layui-input" autocomplete="off"
                               placeholder="请输入范围选择器 (必填)" lay-verify="required"
                               value="<?php echo $data['bookRule']->home->range?>">
                      </div>
                    </div>
                    <table lay-even id="chapterList-data" lay-filter="chapterList-data" lay-size="sm"></table>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">简介详情</h2>
                  <div class="layui-colla-content layui-show">
                    <table lay-even id="home-data" lay-filter="home-data" lay-size="sm"></table>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">正文详情</h2>
                  <div class="layui-colla-content layui-show">
                    <table lay-even id="content-data" lay-filter="content-data" lay-size="sm"></table>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">正文截断标记</h2>
                  <div class="layui-colla-content layui-show">
                    <div class="layui-form-item">
                      <label class="layui-form-label">正文截断标记</label>
                      <div class="layui-input-block">
                        <input type="text" name="splitTag" class="layui-input" autocomplete="off"
                               placeholder="正文截断标记，忽略标签后面内容" lay-verify="required"
                               value="<?php echo $data['bookRule']->splitTag?>">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="layui-colla-item">
                  <h2 class="layui-colla-title">替换正则</h2>
                  <div class="layui-colla-content layui-show">
                    <table lay-even id="replaceTags-data" lay-filter="replaceTags-data" lay-size="sm"></table>
                  </div>
                </div>
              </div>

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <div class="layui-footer" style="left: 0;">
                    <button class="layui-btn" lay-submit lay-filter="create-submit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    <button type="button" class="layui-btn layui-btn-normal" id="test-btn">测试</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div id="test-card" class="display-none">
            <form class="layui-form" action="" lay-filter="test-form">
              <div class="layui-form-item">
                <label class="layui-form-label">分类/排行URL</label>
                <div class="layui-input-block">
                  <input type="text" name="category_url" class="layui-input" autocomplete="off"
                         placeholder="http://xxx.com/xxx/{$page}.html">
                  <span style="color:#dbdbdb;">{$page}表示页码</span><br/>
                  <a href="#" id="test-category">测试</a>
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">简介URL</label>
                <div class="layui-input-block">
                  <input type="text" name="home_url" class="layui-input" autocomplete="off"
                         placeholder="http://xxx.com/xxx">
                  <a href="#" id="test-home">测试</a>
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">结果</label>
                <div class="layui-input-block">
                  <textarea id="result" class="layui-textarea" placeholder="输出结果" readonly
                            rows="20"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script>
    layui.use(['table', 'index'], function () {
      var $ = layui.jquery
        , table = layui.table
        , form = layui.form;
      var bookRule = @json($data->bookRule);
      //排行表格
      var rankTable = table.render({
        elem: '#rank-data'
        , data: [{
          'key': 'url'
          , 'ranking_0': bookRule.bookList.ranking.rules.url[0]
          , 'ranking_1': bookRule.bookList.ranking.rules.url[1]
          , 'ranking_2': bookRule.bookList.ranking.rules.url[2]
          , 'ranking_3': bookRule.bookList.ranking.rules.url[3]
          , 'ranking_page': bookRule.bookList.ranking.page
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'ranking_0', title: '选择器', edit: 'text'}
          , {field: 'ranking_1', title: '属性', edit: 'text'}
          , {field: 'ranking_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'ranking_3', title: '保留元素(选填)', edit: 'text'}
          , {field: 'ranking_page', title: '页数(默认1)', edit: 'text'}
        ]]
      });
      //分类表格
      var categoryTable = table.render({
        elem: '#category-data'
        , data: [{
          'key': 'url'
          , 'category_0': bookRule.bookList.category.rules.url[0]
          , 'category_1': bookRule.bookList.category.rules.url[1]
          , 'category_2': bookRule.bookList.category.rules.url[2]
          , 'category_3': bookRule.bookList.category.rules.url[3]
          , 'category_page': bookRule.bookList.category.page
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'category_0', title: '选择器', edit: 'text'}
          , {field: 'category_1', title: '属性', edit: 'text'}
          , {field: 'category_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'category_3', title: '保留元素(选填)', edit: 'text'}
          , {field: 'category_page', title: '页数(默认1)', edit: 'text'}
        ]]
      });
      //目录详情
      var chapterTable = table.render({
        elem: '#chapterList-data'
        , data: [{
          'key': 'title'
          , 'chapterList_0': bookRule.chapterList.rules.title[0]
          , 'chapterList_1': bookRule.chapterList.rules.title[1]
          , 'chapterList_2': bookRule.chapterList.rules.title[2]
          , 'chapterList_3': bookRule.chapterList.rules.title[3]
        }, {
          'key': 'from_url'
          , 'chapterList_0': bookRule.chapterList.rules.from_url[0]
          , 'chapterList_1': bookRule.chapterList.rules.from_url[1]
          , 'chapterList_2': bookRule.chapterList.rules.from_url[2]
          , 'chapterList_3': bookRule.chapterList.rules.from_url[3]
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'chapterList_0', title: '选择器', edit: 'text'}
          , {field: 'chapterList_1', title: '属性', edit: 'text'}
          , {field: 'chapterList_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'chapterList_3', title: '保留元素(选填)', edit: 'text'}
        ]]
      });
      //简介表格
      var homeTable = table.render({
        elem: '#home-data'
        , data: [{
          'key': 'title'
          , 'home_0': bookRule.home.rules.title[0]
          , 'home_1': bookRule.home.rules.title[1]
          , 'home_2': bookRule.home.rules.title[2]
          , 'home_3': bookRule.home.rules.title[3]
        }, {
          'key': 'words_count'
          , 'home_0': bookRule.home.rules.words_count[0]
          , 'home_1': bookRule.home.rules.words_count[1]
          , 'home_2': bookRule.home.rules.words_count[2]
          , 'home_3': bookRule.home.rules.words_count[3]
        }, {
          'key': 'chapter_list_url'
          , 'home_0': bookRule.home.rules.chapter_list_url[0]
          , 'home_1': bookRule.home.rules.chapter_list_url[1]
          , 'home_2': bookRule.home.rules.chapter_list_url[2]
          , 'home_3': bookRule.home.rules.chapter_list_url[3]
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'home_0', title: '选择器', edit: 'text'}
          , {field: 'home_1', title: '属性', edit: 'text'}
          , {field: 'home_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'home_3', title: '保留元素(选填)', edit: 'text'}
        ]]
      });
      //正文表格
      var contentTable = table.render({
        elem: '#content-data'
        , data: [{
          'key': 'content'
          , 'content_0': bookRule.content.rules.content[0]
          , 'content_1': bookRule.content.rules.content[1]
          , 'content_2': bookRule.content.rules.content[2]
          , 'content_3': bookRule.content.rules.content[3]
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'content_0', title: '选择器', edit: 'text'}
          , {field: 'content_1', title: '属性', edit: 'text'}
          , {field: 'content_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'content_3', title: '保留元素(选填)', edit: 'text'}
        ]]
      });
      var replaceArr = [];
      $.each(bookRule.replaceTags, function (k, v) {
        replaceArr.push({'preg': v[0], 'replace': v[1]});
      });
      //文本替换正则
      var replaceTable = table.render({
        elem: '#replaceTags-data'
        , data: replaceArr
        , page: false
        , cols: [[{field: 'preg', title: '正则表达式', edit: 'text'}
          , {field: 'replace', title: '替换内容', edit: 'text'}
        ]]
      });


      $('#test-btn').click(function (e) {
        let formCardDom = $('#form-card');
        let testCardDom = $('#test-card');
        if (formCardDom.hasClass('layui-col-md12')) {
          formCardDom.addClass('layui-col-md8').removeClass('layui-col-md12');
          testCardDom.addClass('layui-col-md4').removeClass('display-none');
        } else {
          formCardDom.addClass('layui-col-md12').removeClass('layui-col-md8');
          testCardDom.addClass('display-none').removeClass('layui-col-md4');
        }
        rankTable.reload();
        categoryTable.reload();
        chapterTable.reload();
        homeTable.reload();
        contentTable.reload();
        replaceTable.reload();
      });
    });
  </script>
@endsection
