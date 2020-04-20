@extends('background.layouts.iframe')
@section('content')
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-body">
        <table lay-even id="list-data" lay-filter="list-data" lay-size="sm"></table>
        <div id="lay-page"></div>
        <script type="text/html" id="toolbar">
          <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="delete">删除</button>
          </div>
        </script>
        <script type="text/html" id="bar">
          <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
          <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
          <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="delete">删除</a>
        </script>
      </div>
    </div>
  </div>
@endsection
@section('outbody')
  <div id="edit-dialog" class="dialog-template">
    <form class="layui-form" action="" lay-filter="component-form-group">
      <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
          <select name="cat_id" lay-filter="categories">
            <option value="">--分类--</option>
            @foreach($categories as $cate)
              <option value="{{$cate['id']}}">{{$cate['name']}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
          <input type="text" name="title" class="layui-input" autocomplete="off" placeholder="请输入标题 (必填)"
                 lay-verify="required">
        </div>
      </div>
      <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
          <textarea name="introduce" class="layui-textarea" placeholder="请输入简介"></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">最新章节</label>
        <div class="layui-input-block">
          <input type="text" name="last_chapter_title" class="layui-input" placeholder="请输入最新章节" autocomplete="off"
                 lay-verify="last_chapter_title">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">作者</label>
        <div class="layui-input-block">
          <input type="text" name="author" class="layui-input" autocomplete="off" placeholder="请输入作者"
                 lay-verify="author">
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="component-form-demo1">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
@section('script')
  <script>
    layui.use(['table', 'index'], function () {
      var $ = layui.jquery
        , table = layui.table
        , laypage = layui.laypage
        , admin = layui.index;

      var paginate = @json($paginate);
      var keyword = @json($search??[]);
      var categories = @json($categories??[]);

      table.render({
        elem: '#list-data'
        , toolbar: '#toolbar'
        , height: 680
        , data: paginate.data
        , limit: paginate.per_page
        , page: false
        , cols: [[
          {field: 'id', title: '选择', width: 60, type: 'checkbox'}
          , {field: 'id', title: 'ID', width: 60}
          , {field: 'category_name', title: '分类名称', width: 100}
          , {field: 'title', title: '标题', width: 150}
          , {field: 'thumb', title: '图片', width: 100}
          , {field: 'last_chapter_title', title: '最新', width: 120}
          , {field: 'author', title: '作者', width: 80}
          , {field: 'wordcount', title: '字数', width: 72}
          , {field: 'follow', title: '关注人数', width: 86}
          , {field: 'hits', title: '浏览量', width: 72}
          , {field: 'created_at', title: '添加时间', width: 160}
          , {field: 'created_at', title: '更新时间', width: 160}
          , {field: 'updated_at', title: '更新时间', width: 160}
          , {fixed: 'right', title: '操作', width: 160, align: 'center', toolbar: '#bar'}
        ]]
      });
      laypage.render({
        elem: 'lay-page'
        , curr: paginate.current_page || 1
        , limit: paginate.per_page
        , count: paginate.total
        , jump: function (obj, first) {
          //首次不执行
          if (!first) {
            search(obj.curr);
          }
        }
      });
      table.on('toolbar(list-data)', function (obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        console.log(checkStatus);
        switch (obj.event) {
          case 'add':
            layer.msg('添加');
            break;
          case 'delete':
            layer.msg('删除');
            break;
          case 'update':
            layer.msg('编辑');
            break;
        }
      });
      table.on('checkbox(list-data)', function (obj) {
        console.log(obj.checked); //当前是否选中状态
        console.log(obj.data); //选中行的相关数据
        console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
      });
      //监听工具条
      table.on('tool(list-data)', function (obj) { //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
        if (layEvent === 'detail') { //查看
          //do somehing
        } else if (layEvent === 'delete') { //删除
          layer.confirm('真的删除行么', function (index) {
            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
            layer.close(index);
            //向服务端发送删除指令
          });
        } else if (layEvent === 'edit') { //编辑
          layer.open({
            type: 1
            , title: '编辑小说'
            , content: $('#edit-dialog')
            , shadeClose: true
            , area: admin.screen() < 2 ? ['100%', '80%'] : ['650px', '400px']
            , maxmin: true
          });

          //同步更新缓存对应的值
          obj.update({
            username: '123'
            , title: 'xxx'
          });
        } else if (layEvent === 'LAYTABLE_TIPS') {
          layer.alert('Hi，头部工具栏扩展的右侧图标。');
        }
      });


      var dialog_edit = function () {
        layer.open({
          title: '编辑小说'
          , type: 1
          , shadeClose: true
          , area: admin.screen() < 2 ? ['80%', '300px'] : ['700px', '500px']
          , content: '<div style="padding: 20px;">放入任意HTML</div>'
        });
      };

      var search = function (page) {
        let params = {};
        params.page = page || paginate.current_page;
        params.pageSize = paginate.per_page;
        var paramString = Object.keys(params).map(function (key) {
          return encodeURIComponent(key) + "=" + encodeURIComponent(params[key]);
        }).join("&");
        window.location.href = window.location.pathname + "?" + paramString;
      };
    });
  </script>
@endsection
