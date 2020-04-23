@extends('admin.layouts.iframe')
@section('content')
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-body">
        <table lay-even id="list-data" lay-filter="list-data" lay-size="sm"></table>
        <div id="lay-page"></div>
        <script type="text/html" id="toolbar">
          <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add">新增</button>
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
  <div id="create-dialog" class="dialog-template">
    <form class="layui-form" action="" lay-filter="create-form">
      <input type="hidden" name="id">
      <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
          <input type="text" name="title" class="layui-input" autocomplete="off" placeholder="请输入标题 (必填)"
                 lay-verify="required">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
          <select name="cate_id" lay-filter="cate_id">
            @foreach($categories??[] as $cate)
              <option value="{{$cate['id']}}">{{$cate['name']}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">采集规则</label>
        <div class="layui-input-block">
          <select name="rule_id" lay-filter="rule_id">
            @foreach($rules??[] as $rule)
              <option value="{{$rule['id']}}">{{$rule['title']}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">采集链接</label>
        <div class="layui-input-block">
          <input type="text" name="from_url" class="layui-input" placeholder="请输入采集链接" autocomplete="off"
                 lay-verify="">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">采集页数</label>
        <div class="layui-input-block">
          <input type="text" name="page_limit" class="layui-input" autocomplete="off" placeholder="请输入采集页数"
                 lay-verify="author">
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
@endsection
@section('script')
  <script>
    layui.use(['table', 'index'], function () {
      var $ = layui.jquery
        , table = layui.table
        , laypage = layui.laypage
        , form = layui.form;

      var paginate = @json($paginate);
      var keyword = @json($search??[]);
      var storeUrl = '<?php echo route('tasks.store')?>';
      var updateUrl = '<?php echo route('tasks.update', ['task' => '_id_'])?>';
      var deleteUrl = '<?php echo route('tasks.destroy', ['task' => '_id_'])?>';
      var openCallback = () => {
      };

      //渲染表格
      table.render({
        elem: '#list-data'
        , toolbar: '#toolbar'
        // , height: 680
        , data: paginate.data
        , limit: paginate.per_page
        , page: false
        , cols: [[{field: 'id', title: 'ID', width: 50}
          , {field: 'title', title: '标题', width: 150}
          , {field: 'category_name', title: '分类名称', width: 150}
          , {field: 'from_url', title: '采集链接', width: 300}
          , {field: 'rule_name', title: '规则名称', width: 120}
          , {field: 'page_limit', title: '采集页数', width: 72}
          , {field: 'task_code', title: '采集状态', width: 72}
          , {field: 'retries', title: '尝试次数', width: 72}
          , {field: 'created_at', title: '添加时间', width: 160}
          , {field: 'updated_at', title: '更新时间', width: 160}
          , {fixed: 'right', title: '操作', width: 160, align: 'center', toolbar: '#bar'}
        ]]
      });
      //渲染分页
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
      //监听表格工具条
      table.on('toolbar(list-data)', function (obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        switch (obj.event) {
          case 'add':
            layer.msg('添加');
            fillCreateForm({});
            let index = openCreateDialog('新增采集任务');
            openCallback = () => {
              window.location.reload();
              setTimeout(() => {
                layer.close(index);
              }, 1000);
            };
            break;
          case 'delete':
            layer.msg('删除');
            break;
          case 'update':
            layer.msg('编辑');
            break;
        }
      });
      //监听复选框
      table.on('checkbox(list-data)', function (obj) {
        // console.log(obj.checked); //当前是否选中状态
        // console.log(obj.data); //选中行的相关数据
        // console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
      });
      //监听行工具条
      table.on('tool(list-data)', function (obj) {
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
        if (layEvent === 'detail') {
          //todo
        } else if (layEvent === 'delete') { //删除
          layer.confirm('我跟你讲，删掉就真的木有了！', function (index) {
            obj.del();
            layer.close(index);
            $.ajax({
              url: deleteUrl.replace('_id_', data.id),
              type: 'DELETE',
              dataType: 'JSON',
              success: (res) => {
                if (res.code !== 0) {
                  layer.msg('删除失败 ' + res.message, {icon: 5});
                  return false;
                }
                layer.msg('删除成功');
              },
              error: (jqXHR, textStatus, errorMessage) => {
                layer.msg('删除失败 ' + errorMessage, {icon: 5});
              }
            });
          });
        } else if (layEvent === 'edit') { //编辑
          fillCreateForm(data);
          let index = openCreateDialog('编辑采集任务');
          openCallback = (param) => {
            if (typeof param === 'object' && param.id == data.id) {
              delete param.id;
              obj.update(param);
            }
            setTimeout(() => {
              layer.close(index);
            }, 1000);
          };
        }
      });
      //监听提交操作
      form.on('submit(create-submit)', function (data) {
        if (data.field.id != '') {
          $.ajax({
            url: updateUrl.replace('_id_', data.field.id),
            type: 'put',
            dataType: 'json',
            data: data.field,
            success: (res) => {
              if (res.code !== 0) {
                layer.msg('更新失败 ' + res.message, {icon: 5});
                return false;
              }

              layer.msg('更新成功');
              openCallback(data.field);
            },
            error: (jqXHR, textStatus, errorMessage) => {
              layer.msg('更新失败 ' + errorMessage, {icon: 5});
              openCallback();
            }
          });
        } else {
          $.ajax({
            url: storeUrl,
            type: 'POST',
            dataType: 'json',
            data: data.field,
            success: (res) => {
              if (res.code !== 0) {
                layer.msg('添加失败 ' + res.message, {icon: 5});
                return false;
              }
              layer.msg('添加成功');
              openCallback();
            },
            error: (jqXHR, textStatus, errorMessage) => {
              layer.msg('添加失败 ' + errorMessage, {icon: 5});
              openCallback();
            }
          });
        }
        return false;
      });
      //填充表单值
      var fillCreateForm = function (rowObj) {
        form.val("create-form", {
          id: rowObj.id || ''
          , title: rowObj.title
          , cate_id: rowObj.cate_id || ''
          , from_url: rowObj.from_url || ''
          , rule_id: rowObj.rule_id || ''
          , page_limit: rowObj.page_limit || ''
          , task_code: rowObj.task_code || ''
          , retries: rowObj.retries || ''
        });
      };
      //打开弹窗
      var openCreateDialog = function (title) {
        return layer.open({
          type: 1
          , title: title
          , content: $('#create-dialog')
          , shadeClose: true
          , area: parent.layui.index.screen() < 2 ? ['100%', '80%'] : ['650px', '500px']
          , maxmin: true
        });
      };
      //搜索
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
