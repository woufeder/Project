<?php
$currentPath = $_SERVER['REQUEST_URI'];
?>

<aside class="sidebar px-4">
    <div class="sidebar-top">
        <div class="logo">
            Logo Here
        </div>
        <!-- <h1>Site Name</h1> -->
        <p>後台管理系統</p>
    </div>
    <nav id="side-nav" class="collapse collapse-horizontal show d-flex flex-column">

        <!-- 會員管理 -->
        <div class="main-nav w-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-user"></i>
                <h6 class="mb-0">會員管理</h6>
            </div>
        </div>
        <div class="btn-group-vertical w-100">
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, '/users/index.php') !== false) ? 'active' : '' ?>" href="../users/index.php">會員列表</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, '/users/deleteIndex.php') !== false) ? 'active' : '' ?>" href="../users/deleteIndex.php">停權會員列表</a>
        </div>

        <!-- 商品管理 -->
        <div class="main-nav w-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-cube"></i>
                <h6 class="mb-0">商品管理</h6>
            </div>
        </div>
        <div class="btn-group-vertical w-100">
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, '/products/index.php') !== false) ? 'active' : '' ?>" href="../products/index.php">商品列表</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, '/products/add.php') !== false) ? 'active' : '' ?>"  href="../products/add.php">新增商品</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, '/products/deleted.php') !== false) ? 'active' : '' ?>" href="../products/deleted.php">已下架商品</a>
        </div>

        <!-- 優惠券管理 -->
        <div class="main-nav w-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-ticket"></i>
                <h6 class="mb-0">優惠券管理</h6>
            </div>
        </div>
        <div class="btn-group-vertical w-100">
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'coupon/index') !== false) ? 'active' : '' ?>" href="../coupon/indexList.php">優惠券列表</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'coupon/add.php') !== false) ? 'active' : '' ?>" href="../coupon/add.php">新增優惠券</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'coupon/expiredList.php') !== false) ? 'active' : '' ?>" href="../coupon/expiredList.php">已失效優惠券</a>
        </div>

        <!-- 文章管理 -->
        <div class="main-nav w-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-file-lines"></i>
                <h6 class="mb-0">文章管理</h6>
            </div>
        </div>
        <div class="btn-group-vertical w-100">
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'articles/index.php') !== false) ? 'active' : '' ?>" href="../articles/index.php">文章列表</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'articles/add.php') !== false) ? 'active' : '' ?>" href="../articles/add.php">新增文章</a>
            <a class="btn-subnav btn-sm w-100 <?= (strpos($currentPath, 'articles/delete_list.php') !== false) ? 'active' : '' ?>" href="../articles/delete_list.php">已下架文章</a>
        </div>
        <section class="setting w-100 mt-auto mb-3">
            <div class="main-nav d-flex align-items-center justify-content-between">
                <a href="../users/doLogout.php">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-power-off"></i>
                        <h6 class="mb-0">系統登出</h6>
                    </div>
                </a>
            </div>
        </section>
    </nav>

</aside>




