@extends('admin.layouts.iframe')
@section('content')
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-body">
        <form class="layui-form layui-form-pane" action="" lay-filter="create-form">
          <input type="hidden" name="id">
          <div class="layui-form-item">
            <label class="layui-form-label">规则标题</label>
            <div class="layui-input-block">
              <input type="text" name="title" class="layui-input" autocomplete="off" placeholder="请输入标题 (必填)"
                     lay-verify="required">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label">域名地址</label>
            <div class="layui-input-block">
              <input type="text" name="host" class="layui-input" autocomplete="off" placeholder="请输入域名地址 (必填)"
                     lay-verify="required">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label">编码</label>
            <div class="layui-input-block">
              <select name="charset" lay-filter="charset">
                <option value="UTF-8" selected>UTF-8</option>
                <option value="GBK">GBK</option>
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
                           placeholder="请输入范围选择器 (必填)" lay-verify="required">
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
                           placeholder="请输入范围选择器 (必填)" lay-verify="required">
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
                           placeholder="请输入范围选择器 (必填)" lay-verify="required">
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
                           placeholder="正文截断标记，忽略标签后面内容" lay-verify="required">
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
              </div>
            </div>
          </div>
        </form>
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

      //排行表格
      table.render({
        elem: '#rank-data'
        , data: [{
          'key': 'url'
          , 'ranking_0': ''
          , 'ranking_1': ''
          , 'ranking_2': ''
          , 'ranking_3': ''
          , 'ranking_page': '1'
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
      table.render({
        elem: '#category-data'
        , data: [{
          'key': 'url'
          , 'category_0': ''
          , 'category_1': ''
          , 'category_2': ''
          , 'category_3': ''
          , 'category_page': '1'
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
      table.render({
        elem: '#chapterList-data'
        , data: [{
          'key': 'title'
          , 'chapterList_0': ''
          , 'chapterList_1': ''
          , 'chapterList_2': ''
          , 'chapterList_3': ''
        }, {
          'key': 'from_url'
          , 'chapterList_0': ''
          , 'chapterList_1': ''
          , 'chapterList_2': ''
          , 'chapterList_3': ''
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
      table.render({
        elem: '#home-data'
        , data: [{
          'key': 'title'
          , 'home_0': ''
          , 'home_1': ''
          , 'home_2': ''
          , 'home_3': ''
        }, {
          'key': 'words_count'
          , 'home_0': ''
          , 'home_1': ''
          , 'home_2': ''
          , 'home_3': ''
        }, {
          'key': 'chapter_list_url'
          , 'home_0': ''
          , 'home_1': ''
          , 'home_2': ''
          , 'home_3': ''
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
      table.render({
        elem: '#content-data'
        , data: [{
          'key': 'content'
          , 'content_0': ''
          , 'content_1': ''
          , 'content_2': ''
          , 'content_3': ''
        }]
        , page: false
        , cols: [[{field: 'key', title: 'KEY', width: 150}
          , {field: 'content_0', title: '选择器', edit: 'text'}
          , {field: 'content_1', title: '属性', edit: 'text'}
          , {field: 'content_2', title: '过滤元素(选填)', edit: 'text'}
          , {field: 'content_3', title: '保留元素(选填)', edit: 'text'}
        ]]
      });

      table.render({
        elem: '#replaceTags-data'
        , data: [{
          'preg': ''
          , 'replace': ''
        }]
        , page: false
        , cols: [[{field: 'preg', title: '正则表达式', edit: 'text'}
          , {field: 'replace', title: '替换内容', edit: 'text'}
        ]]
      });
    });
  </script>
@endsection
