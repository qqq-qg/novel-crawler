<div class="top">
    <div class="main">
        <div class="lf">

        </div>
        <div class="rt">
            
            <a href="<?php echo wapurl(); ?>" target="_blank">手机版</a> |
            
            
            <a href="javascript:void(0);" onclick="AddFavorite('<?php echo e($SET['title']??''); ?>',location.href)"
               target="_self" rel="nofollow">收藏本站</a>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="logo">
        <a href="/"><?php echo e($SET['title']??''); ?></a>
    </div>
    <div class="seach">

    </div>
</div>
<div class="nav">
    <div class="main">
        <ul class="nav_l">
            <li><a href="/">首页</a></li>
            <?php $__currentLoopData = $categories??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a href="<?php echo bookurl($v['id']); ?>"><?php echo e($v['name']); ?></a></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <ul class="nav_r">
            <li><a href="javascript:void(0);">收藏</a></li>
            <li><a href="javascript:void(0);">新书</a></li>
            <li><a href="javascript:void(0);">完本</a></li>
            <li><a href="javascript:void(0);" rel="nofollow">求书</a></li>
        </ul>
    </div>
</div><?php /**PATH D:\htdocs\mars\resources\views/home/header.blade.php ENDPATH**/ ?>