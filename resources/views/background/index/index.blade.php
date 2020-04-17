@extends('background.layouts.app')
@section('content')
  首页
  <a href="#" id="aaa">aaaa</a>
@endsection

@section('script')
  <script>
    layui.use(['admin'], function () {
      var layer = layui.layer //获得layer模块
        , laypage = layui.laypage //获得laypage模块
        , laydate = layui.laydate //获得laydate模块
        , $ = layui.jquery //获得laydate模块
        , admin = layui.admin; //获得laydate模块
      $("#aaa").click(function () {
        console.log(admin.tabs());
      });

    });
  </script>
@endsection
