<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($book['title']); ?>,<?php echo e($book['title']); ?>最新章节,<?php echo e($book['title']); ?>无弹窗,<?php echo e($SET['title']); ?></title>
    <meta name="keywords"
          content="<?php echo e($book['title']); ?>,<?php echo e($book['title']); ?>最新章节,<?php echo e($book['title']); ?>无弹窗,<?php echo e($SET['title']); ?>">
    <meta name="description"
          content="<?php echo e($SET['title']); ?>为您提供<?php echo e($book['title']); ?>最新章节，<?php echo e($book['title']); ?>无弹窗。更多<?php echo e($book['title']); ?>小说尽在<?php echo e($SET['title']); ?>，好看记得告诉您的朋友哦！">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="mobile-agent" content="format=html5; url=<?php echo wapurl($catid,$id); ?>">
    
    <meta property="og:type" content="novel">
    <meta property="og:title" content="<?php echo e($book['title']); ?>">
    <meta property="og:description" content="    ”
    各位书友要是觉得《<?php echo e($book['title']); ?>》还不错的话请不要忘记向您QQ群和微博里的朋友推荐哦！
">
    <meta property="og:image" content="<?php echo e(bookimg($book['thumb'])); ?>">
    <meta property="og:novel:category" content="<?php echo e($CAT['name']); ?>">
    <meta property="og:novel:author" content="<?php echo e($book['author']); ?>">
    <meta property="og:novel:book_name" content="<?php echo e($book['title']); ?>">
    <meta property="og:novel:read_url" content="<?php echo Request::getUri(); ?>">
    <meta property="og:url" content="<?php echo Request::getUri(); ?>">
    <meta property="og:novel:status" content="连载中">
    <meta property="og:novel:update_time" content="<?php echo e(date('m-d',strtotime($book['updated_at']))); ?>">
    <meta property="og:novel:latest_chapter_name" content="<?php echo e($lastDetail['title']); ?>">
    <meta property="og:novel:latest_chapter_url" content="<?php echo bookurl($catid,$id,'lastest'); ?>"/>
    <link rel="stylesheet" href="<?php echo staticPath('/default/css/index.min.css'); ?>">
    <script type="text/javascript" src="<?php echo staticPath('/js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo staticPath('/js/jquery.lazyload.min.js'); ?>"></script>
    <script>
    UA = navigator.userAgent.toLowerCase();
    if ((UA.indexOf("iphone") != -1 || UA.indexOf("mobile") != -1 || UA.indexOf("android") != -1 || UA.indexOf("windows ce") != -1 || UA.indexOf("ipod") != -1) && UA.indexOf("ipod") == -1) {
      location.href = '<?php echo wapurl($catid,$id); ?>';
    }

    function share() {
      document.writeln('<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_isohu" data-cmd="isohu" title="分享到我的搜狐"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="bds_copy" data-cmd="copy" title="分享到复制网址"></a></div>');
      document.writeln('<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{},"image":{"viewList":["weixin","sqq","qzone","tsina","isohu","tqq","renren","tieba","copy"],"viewText":"分享到：","viewSize":"24"}};with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=\'+~(-new Date()/36e5)];<\/script>');
    }
    </script>
</head>
<body>

<?php echo $__env->make('home.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="yd_ad">
    
    
    
    
    
    
</div>
<div class="place">
    当前位置：<a href="/"><?php echo e($SET['title']); ?></a> > <a href="<?php echo bookurl($catid); ?>"><?php echo e($CAT['name']); ?></a>
    > <?php echo e($book['title']); ?>

</div>
<div class="jieshao">
    <div class="lf">
        <img src="<?php echo bookimg($book['thumb']); ?>" alt="<?php echo e($book['title']); ?>">
    </div>
    <div class="rt">

        <h1><?php echo e($book['title']); ?></h1>

        <div class="msg">
            <em>作者：<?php echo e($book['author']); ?> </em>
            <em>状态：连载中 </em>
            <em>更新时间：<?php echo e(date('m-d',strtotime($book['updated_at']))); ?></em>
            <em>最新章节：
                <a href="<?php echo bookurl($catid,$id,'lastest'); ?>"><?php echo e($book['zhangjie']); ?></a>
            </em>
        </div>
        <div>
            <script>share();</script>
        </div>
        
        
        
        
        
        
        
        

        <div class="intro">
            <?php echo e($book['introduce']); ?>

            各位书友要是觉得《<?php echo e($book['title']); ?>》还不错的话请不要忘记向您QQ群和微博里的朋友推荐哦！

        </div>
    </div>
    <div class="aside">

    </div>
</div>

<div class="mulu">
    <ul>
        <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><a href="<?php echo bookurl($catid,$v['books_id'],$v['id']); ?>"><?php echo e($v['title']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<div class="pages" style="text-align:center;">
    <?php echo $lists->render(); ?>

</div>
<?php echo $__env->make('home.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
$(function() {
  $("img.lazy").lazyload({
    event: "sporty"
  });
  $(window).bind("load", function() {
    var timeout = setTimeout(function() {
      $("img.lazy").trigger("sporty");
    }, 800);
  });
});
</script>
</body>
</html><?php /**PATH D:\htdocs\mars\resources\views/home/book/lists.blade.php ENDPATH**/ ?>