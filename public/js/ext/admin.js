;layui.define(['layer', 'element'], function (exports) {
  var $ = layui.jquery
    , element = layui.element
    , r = $(window)
    , m = '#LAY-system-side-menu'
    , t = "layadmin-layout-tabs"
    , c = ".layui-tab-close"
    , z = "#LAY_app_tabsheader>li"
    , y = ".layui-this"
  ;


  //一些事件监听
  $('body').on('click', "*[data-event]", function () {
    var el = $(this), i = el.attr("data-event");
    F[i] && F[i].call(this, el);
  });

  var F = {
    i: 0,
    get_lay_id: function () {
      let a = [];
      $(z).each(function (i, o) {
        a.push($(o).attr('lay-id'));
      });
      return a;
    },
    closeThisTabs: function () {
      F.i = $(z).find(y).index();
      $(z).eq(F.i).find(c).trigger("click");
    },
    closeOtherTabs: function (e) {
      F.i = $(z).find(y).index();
      $(z + ':not(:eq(' + F.i + '))').find(c).trigger("click");
    },
    closeAllTabs: function (e) {
      $(z).find(c).trigger("click");
    },
  };

  //触发事件
  $(m).find("a[class='open-iframe']").click(function () {
    let a = $(this);
    let u = a.data('url');
    if ($.inArray(u, F.get_lay_id()) === -1) {
      obj.open(u, $.trim(a.text()));
    } else {
      obj.focus(u);
    }
  });

  var obj = {
    open: function (url, title) {
      element.tabAdd(t, {
        title: title
        , content: '内容' + url
        , id: url
      });
      obj.focus(url);
    },
    focus: function (url) {
      element.tabChange(t, url);
    },
    close: function (url) {
      element.tabDelete(t, url);
    }
  };
  exports('admin', obj);
});
