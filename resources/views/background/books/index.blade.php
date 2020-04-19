@extends('background.layouts.iframe')
@section('content')
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-form layui-card-header layuiadmin-card-header-auto">
        <div class="layui-form-item">
          <div class="layui-inline">
            <label class="layui-form-label">文章ID</label>
            <div class="layui-input-inline">
              <input type="text" name="id" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
          </div>
          <div class="layui-inline">
            <label class="layui-form-label">作者</label>
            <div class="layui-input-inline">
              <input type="text" name="author" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
          </div>
          <div class="layui-inline">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-inline">
              <input type="text" name="title" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
          </div>
          <div class="layui-inline">
            <label class="layui-form-label">文章标签</label>
            <div class="layui-input-inline">
              <select name="label">
                <option value="">请选择标签</option>
                <option value="0">美食</option>
                <option value="1">新闻</option>
                <option value="2">八卦</option>
                <option value="3">体育</option>
                <option value="4">音乐</option>
              </select>
            </div>
          </div>
          <div class="layui-inline">
            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="LAY-app-contlist-search">
              <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="layui-card-body">
        <div style="padding-bottom: 10px;">
          <div class="layui-btn-container">
            <div class="layui-btn-group">
              <button class="layui-btn layui-btn-sm" data-type="add">添加</button>
              <button class="layui-btn layui-btn-sm" data-type="batchdel">删除</button>
            </div>
          </div>
        </div>
        <table lay-filter="data-list">
          <thead>
          <tr>
            <th lay-data="{field:'username', width:100}">昵称</th>
            <th lay-data="{field:'experience', width:80, sort:true}">积分</th>
            <th lay-data="{field:'sign'}">签名</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>贤心1</td>
            <td>66</td>
            <td>人生就像是一场修行a</td>
          </tr>
          <tr>
            <td>贤心2</td>
            <td>88</td>
            <td>人生就像是一场修行b</td>
          </tr>
          <tr>
            <td>贤心3</td>
            <td>33</td>
            <td>人生就像是一场修行c</td>
          </tr>
          </tbody>
        </table>

        {{--        <script type="text/html" id="buttonTpl">--}}
        {{--          <button class="layui-btn layui-btn-xs">已发布</button>--}}
        {{--          <button class="layui-btn layui-btn-primary layui-btn-xs">待修改</button>--}}
        {{--        </script>--}}
        {{--        <script type="text/html" id="table-content-list">--}}
        {{--          <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>--}}
        {{--          <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i--}}
        {{--              class="layui-icon layui-icon-delete"></i>删除</a>--}}
        {{--        </script>--}}
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    layui.use('table', function () {
      var table = layui.table;

      table.init('data-list', {
        height: 315
        ,limit: 10
        , page: true
      });
      //第一个实例
      // table.render({
      //   elem: '#demo'
      //   , height: 312
      //   , url: '/demo/table/user/' //数据接口
      //   , page: true //开启分页
      //   , cols: [[ //表头
      //     { field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
      //     , {field: 'username', title: '用户名', width: 80}
      //     , {field: 'sex', title: '性别', width: 80, sort: true}
      //     , {field: 'city', title: '城市', width: 80}
      //     , {field: 'sign', title: '签名', width: 177}
      //     , {field: 'experience', title: '积分', width: 80, sort: true}
      //     , {field: 'score', title: '评分', width: 80, sort: true}
      //     , {field: 'classify', title: '职业', width: 80}
      //     , {field: 'wealth', title: '财富', width: 135, sort: true}
      //   ]]
      // });

    });
  </script>
@endsection
