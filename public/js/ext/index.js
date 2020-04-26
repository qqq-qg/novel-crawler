;layui.define(['layer', 'element'], function (exports) {
  var $ = layui.jquery
    , element = layui.element
    , l = layui.view
    , o = $('body')
    , r = $(window)
    , u = $("#LAY_app")
    , m = '#LAY-system-side-menu'
    , z = "#LAY_app_tabsheader"
    , a = "#LAY_app_body"
    , t = "layadmin-layout-tabs"
    , c = ".layui-tab-close"
    , C = "layadmin-side-shrink"
    , y = ".layui-this"
    , h = "LAY_app_flexible"
    , x = "layui-icon-spread-left"
    , g = "layui-icon-shrink-right"
    , v = "layadmin-side-spread-sm"
    , p = "layadmin-layout-tabs"
    , d = "layui-show"
    , b = ".layui-tab-item"
  ;
  var get_lay_id = function () {
    let a = [];
    $(z).find('li').each(function (i, o) {
      a.push($(o).attr('lay-id'));
    });
    return a;
  };
  var S = function (index) {
    $(a).find(b + '.' + d).removeClass(d);
    $(a).find(b + ':eq(' + index + ')').addClass(d);
  }, A = function (url) {
    $(a).append('<div class="layui-tab-item">\n' +
      '    <iframe src="' + url + '" frameborder="0" class="layadmin-iframe"></iframe>\n' +
      '  </div>');
  }, R = function (index) {
    $(a).find(b + ':eq(' + index + ')').find('iframe')[0].contentWindow.location.reload(!0);
  };
  element.on("tab(" + p + ")", function (e) {
    P.tabsPage.index = e.index;
    S(e.index);
  });
  element.on("tabDelete(" + p + ")", function (e) {
    $(a).find(b).eq(e.index || 0).remove();
    let id = $(z).find('li' + y).index();
    S(id || 0);
  });

  //一些事件监听
  o.on('click', "*[data-event]", function () {
    let el = $(this), i = el.attr("data-event");
    P[i] && P[i].call(this, el);
  }).on("mouseenter", "*[lay-tips]", function () {
    var e = $(this);
    if (!e.parent().hasClass("layui-nav-item")) {
      var i = e.attr("lay-tips"),
        t = e.attr("lay-offset"),
        n = e.attr("lay-direction"),
        l = layer.tips(i, this, {
          tips: n || 1, time: -1, success: function (e, a) {
            t && e.css("margin-left", t + "px")
          }
        });
      e.data("index", l);
    }
  }).on("mouseleave", "*[lay-tips]", function () {
    layer.close($(this).data("index"))
  });


  var P = {
    tabsPage: {},
    closeThisTabs: function () {
      $(z).find('li' + y).find(c).trigger("click");
    },
    closeOtherTabs: function (e) {
      let id = $(z).find('li' + y).index();
      let eles = $(z).find('li');
      for (let i = eles.length; i > 0; i--) {
        if (i !== id) {
          obj.closeIframe($(eles[i]).attr('lay-id'))
        }
      }
    },
    closeAllTabs: function (e) {
      $(z).find('li:gt(0)').find(c).trigger("click");
    },
    screen: function () {
      var e = r.width();
      return e > 1200 ? 3 : e > 992 ? 2 : e > 768 ? 1 : 0
    },
    fullScreen: function () {
      var e = document.documentElement,
        a = e.requestFullScreen || e.webkitRequestFullScreen || e.mozRequestFullScreen || e.msRequestFullscreen;
      "undefined" != typeof a && a && a.call(e)
    },
    exitScreen: function () {
      document.documentElement;
      document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen ? document.webkitCancelFullScreen() : document.msExitFullscreen && document.msExitFullscreen()
    },
    sideFlexible: function (e) {
      var i = u
        , t = $("#" + h)
        , l = P.screen();
      if (i.hasClass(C) || "spread" === e) {
        t.removeClass(x).addClass(g);
        l < 2 ? i.addClass(v) : i.removeClass(v);
        i.removeClass(C);
      } else {
        t.removeClass(g).addClass(x);
        l < 2 ? i.removeClass(C) : i.addClass(C);
        i.removeClass(v);
      }
    },

    flexible: function (e) {
      var a = e.find("#" + h), i = a.hasClass(x);
      P.sideFlexible(i ? "spread" : null)
    },
    refresh: function () {
      R($(z).find('li' + y).index());
    },
    serach: function (e) {
      e.off("keypress").on("keypress", function (a) {
        if (this.value.replace(/\s/g, "") && 13 === a.keyCode) {
          var i = e.attr("lay-action"), t = e.attr("lay-text") || "搜索";
          i += this.value, t = t + ' <span style="color: #FF5722;">' + P.escape(this.value) + "</span>", layui.index.openTabsPage(i, t), P.serach.keys || (P.serach.keys = {}), P.serach.keys[P.tabsPage.index] = this.value, this.value === P.serach.keys[P.tabsPage.index] && P.refresh(e), this.value = ""
        }
      })
    },
    message: function (e) {
      e.find(".layui-badge-dot").remove()
    },
    fullscreen: function (e) {
      var a = "layui-icon-screen-full", i = "layui-icon-screen-restore", t = e.children("i");
      t.hasClass(a) ? (P.fullScreen(), t.addClass(i).removeClass(a)) : (P.exitScreen(), t.addClass(a).removeClass(i))
    },

    back: function () {
      history.back()
    },
    rollPage: function (e, i) {
      var t = $("#LAY_app_tabsheader"), n = t.children("li"), l = (t.prop("scrollWidth"), t.outerWidth()),
        s = parseFloat(t.css("left"));
      if ("left" === e) {
        if (!s && s <= 0) return;
        var r = -s - l;
        n.each(function (e, i) {
          var n = $(i), l = n.position().left;
          if (l >= r) return t.css("left", -l), !1
        })
      } else "auto" === e ? !function () {
        var e, r = n.eq(i);
        if (r[0]) {
          if (e = r.position().left, e < -s) return t.css("left", -e);
          if (e + r.outerWidth() >= l - s) {
            var o = e + r.outerWidth() - (l - s);
            n.each(function (e, i) {
              var n = $(i), l = n.position().left;
              if (l + s > 0 && l - s > o) return t.css("left", -l), !1
            })
          }
        }
      }() : n.each(function (e, i) {
        var n = $(i), r = n.position().left;
        if (r + n.outerWidth() >= l - s) return t.css("left", -r), !1
      })
    },
    leftPage: function () {
      P.rollPage("left")
    },
    rightPage: function () {
      P.rollPage()
    },
    shade: function () {
      P.sideFlexible()
    },
  };

  //触发事件
  $(m).find("a[class='open-iframe']").click(function () {
    let a = $(this);
    let u = a.data('url');
    if ($.inArray(u, get_lay_id()) === -1) {
      obj.openIframe(u, $.trim(a.text()));
    } else {
      obj.focusIframe(u);
    }
  });

  var obj = {
    openIframe: function (url, title) {
      element.tabAdd(t, {title: title, id: url});
      A(url);
      obj.focusIframe(url);
    },
    focusIframe: function (url) {
      element.tabChange(t, url);
    },
    closeIframe: function (url) {
      element.tabDelete(t, url);
    },

    screen: function () {
      var e = r.width();
      return e > 1200 ? 3 : e > 992 ? 2 : e > 768 ? 1 : 0
    },

    refresh: function (url) {
      R($(z).find("li[lay-id='" + url + "']").index());
    },
  };
  exports('index', obj);
});
