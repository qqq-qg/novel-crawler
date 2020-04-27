<style>
  .layui-header .layui-layout-right .layui-badge-dot {
    margin-left: 0;
  }
</style>

{{-- 顶部菜单 --}}
<div class="layui-header">
  <!-- 头部区域（可配合layui已有的水平导航） -->
  <ul class="layui-nav layui-layout-left">
    <li class="layui-nav-item layadmin-flexible" lay-unselect="">
      <a href="javascript:;" data-event="flexible" title="侧边伸缩">
        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="http://www.ql.com" target="_blank" title="前台">
        <i class="layui-icon layui-icon-website"></i>
      </a>
    </li>
    <li class="layui-nav-item" lay-unselect="">
      <a href="javascript:;" data-event="refresh" title="刷新">
        <i class="layui-icon layui-icon-refresh-3"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search"
             data-event="serach" lay-action="template/search.html?keywords=">
    </li>
  </ul>

  <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
    <li class="layui-nav-item" lay-unselect="">
      <a lay-href="app/message/index.html" data-event="message" lay-text="消息中心">
        <i class="layui-icon layui-icon-notice"></i>

        <!-- 如果有新消息，则显示小圆点 -->
        <span class="layui-badge-dot"></span>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" data-event="fullscreen">
        <i class="layui-icon layui-icon-screen-full"></i>
      </a>
    </li>
    <li class="layui-nav-item" lay-unselect="">
      <a href="javascript:;">
        <cite>{{$data['username']}}</cite>
        <span class="layui-nav-more"></span></a>
      <dl class="layui-nav-child layui-anim layui-anim-upbit">
        {{--        <dd><a lay-href="set/user/info.html">基本资料</a></dd>--}}
        {{--        <dd><a lay-href="set/user/password.html">修改密码</a></dd>--}}
        <hr>
        <dd data-event="logout" style="text-align: center;"
            data-url="<?php echo route('admin-login-out');?>"><a>退出</a>
        </dd>
      </dl>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" data-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
    </li>
  </ul>
</div>

<!-- 侧边菜单 -->
<div class="layui-side layui-side-menu">
  <div class="layui-side-scroll">
    <div class="layui-logo">
      <span>后台管理系统</span>
    </div>

    <ul class="layui-nav layui-nav-tree" id="LAY-system-side-menu"
        lay-filter="layadmin-system-side-menu" lay-shrink="all">
      <li class="layui-nav-item">
        <a href="#" data-event="home">
          <i class="layui-icon layui-icon-home" lay-tips="首页"></i>
          <cite>首页</cite>
        </a>
      </li>
      <li class="layui-nav-item">
        <a href="javascript:;">
          <i class="layui-icon layui-icon-template-1" lay-tips="小说管理"></i>
          <cite>小说管理</cite>
        </a>
        <dl class="layui-nav-child">
          <dd>
            <a href="javascript:;" class="open-iframe" data-url="<?php echo route('books.index');?>">
              <cite>小说列表</cite>
            </a>
          </dd>
          <dd>
            <a href="javascript:;" class="open-iframe" data-url="<?php echo route('categories.index');?>">
              <cite>分类列表</cite>
            </a>
          </dd>
          <dd>
            <a href="javascript:;">
              <i class="layui-icon layui-icon-set" lay-tips="配置"></i>
              <cite>配置</cite>
            </a>
            <dl class="layui-nav-child">
              <dd>
                <a href="javascript:;" class="open-iframe" data-url="<?php echo route('rules.index');?>">
                  <cite>采集规则</cite>
                </a>
              </dd>
              <dd>
                <a href="javascript:;" class="open-iframe" data-url="<?php echo route('tasks.index');?>">
                  <cite>采集任务</cite>
                </a>
              </dd>
            </dl>
          </dd>
        </dl>
      </li>
      <li class="layui-nav-item">
        <a href="javascript:;">
          <i class="layui-icon layui-icon-username" lay-tips="用户管理"></i>
          <cite>用户管理</cite>
        </a>
        <dl class="layui-nav-child">
          <dd><a href="javascript:;" class="open-iframe" data-url="<?php echo route('managers.index');?>">
              <cite>管理列表</cite></a>
          </dd>
        </dl>
      </li>
    </ul>
  </div>
</div>

<!-- 页面标签 -->
<div class="layadmin-pagetabs" id="LAY_app_tabs">
  <div class="layui-icon layadmin-tabs-control layui-icon-prev" data-event="leftPage"></div>
  <div class="layui-icon layadmin-tabs-control layui-icon-next" data-event="rightPage"></div>
  <div class="layui-icon layadmin-tabs-control layui-icon-down">
    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
      <li class="layui-nav-item" lay-unselect="">
        <a href="javascript:;"><span class="layui-nav-more"></span></a>
        <dl class="layui-nav-child layui-anim-fadein">
          <dd><a href="javascript:;" data-event="closeThisTabs">关闭当前标签页</a></dd>
          <dd><a href="javascript:;" data-event="closeOtherTabs">关闭其它标签页</a></dd>
          <dd><a href="javascript:;" data-event="closeAllTabs">关闭全部标签页</a></dd>
        </dl>
      </li>
    </ul>
  </div>
  <div class="layui-tab" lay-unauto="" lay-allowclose="true" lay-filter="layadmin-layout-tabs">
    <ul class="layui-tab-title" id="LAY_app_tabsheader">
      <li lay-id="HOME" class="layui-this">
        <i class="layui-icon layui-icon-home"></i><i class="layui-icon layui-unselect layui-tab-close">ဆ</i>
      </li>
    </ul>
  </div>
</div>

<div class="layui-tab-content layui-layout-admin layui-body" id="LAY_app_body">
  <div class="layui-tab-item layui-show">
    @include('admin.index.index')
  </div>
</div>
