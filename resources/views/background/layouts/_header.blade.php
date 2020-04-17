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
      <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
        <i class="layui-icon layui-icon-spread-left"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="http://www.layui.com/admin/" target="_blank" title="前台">
        <i class="layui-icon layui-icon-website"></i>
      </a>
    </li>
    <li class="layui-nav-item" lay-unselect="">
      <a href="javascript:;" layadmin-event="refresh" title="刷新">
        <i class="layui-icon layui-icon-refresh-3"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search"
             layadmin-event="serach" lay-action="template/search.html?keywords=">
    </li>
  </ul>

  <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
    <li class="layui-nav-item" lay-unselect="">
      <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
        <i class="layui-icon layui-icon-notice"></i>

        <!-- 如果有新消息，则显示小圆点 -->
        <span class="layui-badge-dot"></span>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" layadmin-event="theme">
        <i class="layui-icon layui-icon-theme"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" layadmin-event="note">
        <i class="layui-icon layui-icon-note"></i>
      </a>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" layadmin-event="fullscreen">
        <i class="layui-icon layui-icon-screen-full"></i>
      </a>
    </li>
    <li class="layui-nav-item" lay-unselect="">
      <a href="javascript:;">
        <cite>贤心</cite>
        <span class="layui-nav-more"></span></a>
      <dl class="layui-nav-child layui-anim layui-anim-upbit">
        <dd><a lay-href="set/user/info.html">基本资料</a></dd>
        <dd><a lay-href="set/user/password.html">修改密码</a></dd>
        <hr>
        <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
      </dl>
    </li>
    <li class="layui-nav-item layui-hide-xs" lay-unselect="">
      <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
    </li>
    <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect="">
      <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
    </li>
  </ul>
</div>

<!-- 侧边菜单 -->
<div class="layui-side layui-side-menu">
  <div class="layui-side-scroll">
    <div class="layui-logo">
      <span>后台管理系统</span>
    </div>

    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu">
      <li class="layui-nav-item">
        <a href="#">
          <i class="layui-icon layui-icon-home"></i>
          前台首页
        </a>
      </li>
      <li class="layui-nav-item layui-nav-itemed">
        <a class="" href="javascript:;">
          <i class="layui-icon layui-icon-template-1"></i>
          小说管理
        </a>
        <dl class="layui-nav-child">
          <dd><a href="javascript:;" class="open-iframe" data-url="/books">小说列表</a></dd>
          <dd><a href="javascript:;" class="open-iframe" data-url="/categories">分类列表</a></dd>
          <dd>
            <a href="javascript:;">
              <i class="layui-icon layui-icon-set"></i>
              配置
            </a>
            <dl class="layui-nav-child">
              <dd><a href="javascript:;" class="open-iframe" data-url="/rules">采集规则</a></dd>
              <dd><a href="javascript:;" class="open-iframe" data-url="/tasks">采集任务</a></dd>
            </dl>
          </dd>
        </dl>
      </li>
      <li class="layui-nav-item">
        <a href="javascript:;">
          <i class="layui-icon layui-icon-username"></i>
          用户管理
        </a>
        <dl class="layui-nav-child">
          <dd><a href="javascript:;" class="open-iframe" data-url="/users">管理列表</a></dd>
        </dl>
      </li>
    </ul>
  </div>
</div>

<!-- 页面标签 -->
<div class="layadmin-pagetabs" id="LAY_app_tabs">
  <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
  <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
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
    <div class="layui-tab-content">
      <div class="layui-tab-item layui-show">HOME</div>
    </div>
  </div>
</div>
