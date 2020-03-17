<div id="footer">
        {{--        {{ $SET['powerby']??'' }}--}}
        {{--<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");--}}
        {{--document.write(unescape("%3Cspan id='cnzz_stat_icon_1261657948'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1261657948%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>--}}
    {{--</p>--}}
</div>
<script>
function AddFavorite(c, a) {
  try {
    window.external.addFavorite(a, c)
  } catch (b) {
    try {
      window.sidebar.addPanel(c, a, "")
    } catch (b) {
      alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加")
    }
  }
}

// (function() {
//   var bp = document.createElement('script');
//   var curProtocol = window.location.protocol.split(':')[0];
//   if (curProtocol === 'https') {
//     bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
//   }
//   else {
//     bp.src = 'http://push.zhanzhang.baidu.com/push.js';
//   }
//   var s = document.getElementsByTagName("script")[0];
//   s.parentNode.insertBefore(bp, s);
// })();
</script>
