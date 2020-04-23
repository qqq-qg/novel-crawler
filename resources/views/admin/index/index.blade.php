@extends('admin.layouts.app')
@section('content')
  首页
@endsection

@section('script')
  <script>
    layui.use(['index'], function () {
      var layer = layui.layer //获得layer模块
        , laypage = layui.laypage //获得laypage模块
        , laydate = layui.laydate //获得laydate模块
        , $ = layui.jquery //获得laydate模块
        , admin = layui.admin; //获得laydate模块
    });
  </script>
@endsection
