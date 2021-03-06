<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>后台管理系统</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layui/css/layui.css" media="all">
  {{--  <link id="layuicss-layer" rel="stylesheet" href="/layui/css/modules/layer/default/layer.css" media="all">--}}
  <link id="layuicss-layuiAdmin" rel="stylesheet" href="/layui/style/admin.css" media="all">
  {{--  <script src="/js/bootstrap.min.js"></script>--}}

  <script>
  </script>
  @yield('style')
</head>
<body class="layui-layout-body">
<div id="LAY_app">
  <div class="layui-layout layui-layout-admin">
    @include('admin.layouts._header')
  </div>
</div>
</body>
<script src="/layui/layui.js"></script>
<script>
  layui.use('element', function () {
    var element = layui.element
      , $ = layui.jquery;
    $.ajaxSetup({
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
  }).extend({
    index: '/js/ext/index'
  }).use('index');
</script>
@yield('script')
</html>
