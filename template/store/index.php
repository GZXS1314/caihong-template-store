<?php
if (!defined('IN_CRONLITE')) die();

if($_GET['buyok']==1||$_GET['chadan']==1){include_once TEMPLATE_ROOT.'store/query.php';exit;}
if(isset($_GET['tid']) && !empty($_GET['tid']))
{
	$tid=intval($_GET['tid']);
    $tool=$DB->getRow("select tid from pre_tools where tid='$tid' limit 1");
    if($tool)
    {
		exit("<script language='javascript'>window.location.href='./?mod=buy&tid=".$tool['tid']."';</script>");
    }
}
/**
                _ _                     ____  _                             
               | (_) __ _ _ __   __ _  / ___|| |__  _   _  ___              
            _  | | |/ _` | '_ \ / _` | \___ \| '_ \| | | |/ _ \             
           | |_| | | (_| | | | | (_| |  ___) | | | | |_| | (_) |            
            \___/|_|\__,_|_| |_|\__, | |____/|_| |_|\__,_|\___/             
   ____   _____          _  __  |___/   _____   _   _  _          ____ ____ 
  / ___| |__  /         | | \ \/ / / | |___ /  / | | || |        / ___/ ___|
 | |  _    / /       _  | |  \  /  | |   |_ \  | | | || |_      | |  | |    
 | |_| |  / /_   _  | |_| |  /  \  | |  ___) | | | |__   _|  _  | |__| |___ 
  \____| /____| (_)  \___/  /_/\_\ |_| |____/  |_|    |_|   (_)  \____\____|
                                                                            
                               追求极致的美学                               
**/
$cid = intval($_GET['cid']);
if(!$cid && !empty($conf['defaultcid']) && $conf['defaultcid']!=='0'){
	$cid = intval($conf['defaultcid']);
}
$ar_data = [];
$classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
$qcid = "";
$cat_name = "";
while ($res = $re->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    if($res['cid'] == $cid){
    	$cat_name=$res['name'];
    	$qcid = $cid;
    }
    $ar_data[] = $res;
}

$class_show_num = intval($conf['index_class_num_style'])?intval($conf['index_class_num_style']):2;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title><?php echo $hometitle?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
    <link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">

<style type="text/css">
/* ==================== 移动端样式 ==================== */
@media screen and (max-width: 768px) {
    html {
        font-size: 102.4px;
    }
    
    body {
        position: absolute;
        margin: auto;
        overflow: auto;
        height: auto !important;
        max-width: 650px;
    }
    
    .fui-searchbar .searchbar.searchbar-active {
        display: flex;
    }
    .fui-searchbar .searchbar.searchbar-active .search-input {
        flex-grow: 1;
    }

    .fui-page.fui-page-from-center-to-left,
    .fui-page-group.fui-page-from-center-to-left,
    .fui-page.fui-page-from-center-to-right,
    .fui-page-group.fui-page-from-center-to-right,
    .fui-page.fui-page-from-right-to-center,
    .fui-page-group.fui-page-from-right-to-center,
    .fui-page.fui-page-from-left-to-center,
    .fui-page-group.fui-page-from-left-to-center {
        -webkit-animation: pageFromCenterToRight 0ms forwards;
        animation: pageFromCenterToRight 0ms forwards;
    }
    
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    
    .fui-goods-item .detail .price .buy {
        color: #fff;
        background: #1492fb;
        border-radius: 3px;
        line-height: 1.1rem;
    }
    
    .fui-goods-item .detail .sale {
        height: 1.7rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 0.65rem;
        line-height: 0.9rem;
    }
    
    .goods-category {
        display: flex;
        background: #fff;
        flex-wrap: wrap;
    }

    .goods-category li {
        width: 25%;
        list-style: none;
        margin: 0.4rem 0;
        color: #666;
        font-size: 0.65rem;
    }

    .goods-category li.active p {
        background: #1492fb;
        color: #fff;
    }

    body {
        padding-bottom: constant(safe-area-inset-bottom);
        padding-bottom: env(safe-area-inset-bottom);
    }

    .goods-category li p {
        width: 4rem;
        height: 2rem;
        text-align: center;
        line-height: 2rem;
        border: 1px solid #ededed;
        margin: 0 auto;
        -webkit-border-radius: 0.1rem;
        -moz-border-radius: 0.1rem;
        border-radius: 0.1rem;
    }
    
    .footer ul {
        display: flex;
        width: 100%;
        margin: 0 auto;
    }

    .footer ul li {
        list-style: none;
        flex: 1;
        text-align: center;
        position: relative;
        line-height: 2rem;
    }

    .footer ul li:after {
        content: '';
        position: absolute;
        right: 0;
        top: .8rem;
        height: 10px;
        border-right: 1px solid #999;
    }

    .footer ul li:nth-last-of-type(1):after {
        display: none;
    }

    .footer ul li a {
        color: #999;
        display: block;
        font-size: .6rem;
    }
    
    .fui-goods-group.block .fui-goods-item .image {
        width: 100%; 
        margin: unset; 
        padding-bottom: unset; 
        height: 5.5rem;
    }
    
    .pc-layout {
        display: none !important;
    }
    
    .mobile-layout {
        display: block !important;
    }
}

/* ==================== PC端样式 ==================== */
@media screen and (min-width: 769px) {
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .pc-left-sidebar, .pc-right-content { /* <-- 关键修改 */
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
    .pc-left-sidebar::-webkit-scrollbar, .pc-right-content::-webkit-scrollbar { /* <-- 关键修改 */
        display: none; /* Chrome, Safari, and Opera */
    }

    html, body {
        width: 100%;
        height: 100%;
        font-size: 14px;
        overflow: hidden;
    }
    
    body {
        background: #f3f4f6;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .pc-top-navbar-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        padding: 20px;
        z-index: 1000;
        background: transparent;
    }
    
    .pc-top-navbar {
        max-width: 1400px;
        margin: 0 auto;
        height: 70px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
    }
    
    .pc-nav-logo {
        font-size: 24px;
        font-weight: 600;
        color: #000;
        text-decoration: none;
        letter-spacing: -0.5px;
        margin-right: 20px;
    }
    
    .pc-nav-search {
        display: flex;
        align-items: center;
        flex-grow: 1; 
        max-width: 280px;
    }
    .pc-nav-search .pc-search-input {
        width: 100%;
        height: 40px;
        padding: 0 15px 0 35px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        background-color: rgba(0, 0, 0, 0.05);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 12px center;
        transition: all 0.3s;
        -webkit-appearance: none;
        margin:0 0 0 270px;
    }
    .pc-nav-search .pc-search-input:focus {
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
    }
    
    .pc-nav-items {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    
    .pc-nav-item {
        padding: 10px 24px;
        background: #000;
        color: #fff;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .pc-nav-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        background: #333;
    }
    
    .pc-nav-item.active {
        background: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .pc-main-container {
        position: fixed;
        top: 110px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        display: flex;
        max-width: 1400px;
        margin: 0 auto;
        gap: 20px;
    }
    
    .pc-left-sidebar {
        width: 260px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        overflow-y: auto;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    .pc-category-title {
        font-size: 18px;
        font-weight: 600;
        color: #000;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .pc-category-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        margin-bottom: 8px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: #333;
    }
    
    .pc-category-item:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }
    
    .pc-category-item.active {
        background: #000;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .pc-category-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        margin-right: 12px;
        object-fit: cover;
    }
    
    .pc-category-name {
        font-size: 14px;
        font-weight: 500;
    }
    
    .pc-right-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
        overflow-y: auto; /* <-- 关键修改: 允许右侧整体滚动 */
    }
    
    .pc-banner-section {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    .pc-banner-swipe {
        border-radius: 16px;
        overflow: hidden;
        width: 100%; /* <-- 宽度自适应 */
        height: 280px; 
        margin: 0 auto;
    }
    
    .pc-banner-swipe img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* <-- 推荐使用cover保持比例 */
        display: block;
    }
    
    .pc-search-bar {
       display: none;
    }
    
    .pc-sort-bar {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 16px 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .pc-sort-label {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        margin-right: 8px;
    }
    
    .pc-sort-item {
        position: relative;
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
        color: #666;
        display: flex;
        align-items: center;
        gap: 6px;
        user-select: none;
    }
    
    .pc-sort-item:hover {
        background: rgba(0, 0, 0, 0.05);
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }
    
    .pc-sort-item.on {
        background: #000;
        color: #fff;
        border-color: #000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .pc-sort-item .sorting {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin-left: 2px;
    }
    
    .pc-sort-item .sorting .icon {
        font-size: 10px;
        transition: all 0.2s;
        opacity: 0.4;
    }
    
    .pc-sort-item.on .sorting .icon {
        opacity: 1;
    }
    
    .pc-sort-item.ASC .icon-sanjiao2 {
        color: #fff;
        opacity: 1 !important;
    }
    
    .pc-sort-item.DESC .icon-sanjiao1 {
        color: #fff;
        opacity: 1 !important;
    }
    
    .pc-sort-item:not(.on) .sorting .icon:hover {
        opacity: 0.8;
    }
    
    .pc-sort-view-toggle {
        margin-left: auto;
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pc-sort-view-toggle:hover {
        background: rgba(0, 0, 0, 0.05);
        border-color: rgba(0, 0, 0, 0.15);
    }
    
    .pc-sort-view-toggle .icon {
        font-size: 18px;
        color: #666;
    }
    
    .pc-goods-section {
        flex-shrink: 0; /* <-- 关键修改：防止内容过多时被压缩 */
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    #goods-list-container.block #goods_list {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
    }
    #goods-list-container.block .fui-goods-item {
        width: 100%;
        margin: 0 !important;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: block;
    }
    #goods-list-container.block .fui-goods-item .image {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 100%; 
        background-color: #f5f5f5;
    }
    #goods-list-container.block .fui-goods-item .image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    #goods-list-container:not(.block) .fui-goods-item {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 15px !important;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    #goods-list-container:not(.block) .fui-goods-item .image {
        width: 100px;
        height: 100px;
        padding-bottom: 0;
        margin-right: 20px;
        flex-shrink: 0;
    }
    #goods-list-container:not(.block) .fui-goods-item .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #goods-list-container:not(.block) .fui-goods-item .detail {
        flex-grow: 1;
        text-align: left;
    }
    #goods-list-container:not(.block) .fui-goods-item .detail .price {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #goods-list-container:not(.block) .fui-goods-item .detail .buy {
        width: auto;
        padding: 5px 20px;
    }

    .mobile-layout {
        display: none !important;
    }
    
    .pc-footer {
        text-align: center;
        padding: 30px 20px;
        margin-top: 0; /* <-- 可选调整 */
        flex-shrink: 0; /* <-- 关键修改 */
    }
    
    .pc-footer p {
        color: #999;
        font-size: 13px;
        line-height: 1.8;
    }
}

/* ==================== 通用样式 ==================== */
.layui-flow-more {
    width: 100%;
    float: left;
}

.fui-goods-group .fui-goods-item .image img {
    border-radius: 5px;    
}

.fui-goods-group .fui-goods-item .detail .minprice {
    font-size: .6rem;
}

.fui-goods-group .fui-goods-item .detail .name {
    height: 1.9rem;
}

.swiper-pagination-bullet {
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    font-size: 12px;
    color: #000;
    opacity: 1;
    background: rgba(0, 0, 0, 0.2);
}

.swiper-pagination-bullet-active {
    color: #fff;
    background: #ed414a;
}

.swiper-pagination {
    position: unset;
}

.swiper-container {
    --swiper-theme-color: #ff6600;
    --swiper-navigation-color: #007aff;
    --swiper-navigation-size: 18px;
}

.goods_sort {
    position: relative;
    width: 100%;
    -webkit-box-align: center;
    padding: .4rem 0;
    background: #fff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
}

.goods_sort:after {
    content: " ";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    border-bottom: 1px solid #e7e7e7;
}

.goods_sort .item {
    position: relative;
    width: 1%;
    display: table-cell;
    text-align: center;
    font-size: 0.7rem;
    border-left: 1px solid #e7e7e7;
    color: #666;
}

.goods_sort .item .sorting {
    width: .2rem;
    height: .2rem;
    position: relative;
}

.goods_sort .item:first-child {
    border: 0;
}

.goods_sort .item.on .text {
    color: #fd5454;
}

.goods_sort .item .sorting .icon {
    position: absolute;
    -webkit-transform: scale(0.6);
    -ms-transform: scale(0.6);
    transform: scale(0.6);
}

.goods_sort .item-price .sorting .icon-sanjiao1 {
    top: .15rem;
    left: 0;
}

.goods_sort .item-price .sorting .icon-sanjiao2 {
    top: -.15rem;
    left: 0;
}

.goods_sort .item-price.DESC .sorting .icon-sanjiao1 {
    color: #ef4f4f
}

.goods_sort .item-price.ASC .sorting .icon-sanjiao2 {
    color: #ef4f4f
}

.content-slide .shop_active .icon-title {
    color: #ff5555;
}

.xz {
    background-color: #3399ff;
    color: white !important;
    border-radius: 5px;
}

.tab_con > ul > li.layui-this {
    background: linear-gradient(to right, #73b891, #53bec5);
    color: #fff;
    border-radius: 6px;
    text-align: center;
}

#audio-play #audio-btn {
    width: 44px;
    height: 44px;
    background-size: 100% 100%;
    position: fixed;
    bottom: 5%;
    right: 6px;
    z-index: 111;
}

#audio-play .on {
    background: url('assets/img/music_on.png') no-repeat 0 0;
    -webkit-animation: rotating 1.2s linear infinite;
    animation: rotating 1.2s linear infinite;
}

#audio-play .off {
    background: url('assets/img/music_off.png') no-repeat 0 0
}

@-webkit-keyframes rotating {
    from {
        -webkit-transform: rotate(0);
        transform: rotate(0)
    }
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg)
    }
}

@keyframes rotating {
    from {
        transform: rotate(0)
    }
    to {
        transform: rotate(360deg)
    }
}

.tzgg .account-layer {
    z-index: 100000000;
}

.tzgg .account-main {
    padding: 0.8rem;
    height: auto;
}

.tzgg .account-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
}

.tzgg .account-verify {
    display: block;
    max-height: 15rem;
    overflow: auto;
    margin-top: -10px;
}

.tzgg.show {
    display: block !important;
}

#xn_text {
    position: fixed;
    z-index: 100;
    top: 30px;
    left: 20px;
    color: white;
    padding: 2px 8px;
    background-color: rgba(0, 0, 0, 0.4);
    border-radius: 5px;
    display: none;
}
</style>

<?php echo str_replace('body','html',$background_css)?>
</head>

<body ontouchstart="">
<div id="body">
    <div id="xn_text"></div>
    
    <!-- ==================== PC端布局 ==================== -->
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper">
            <div class="pc-top-navbar">
                <a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a>
                <div class="pc-nav-search">
                    <form action="" method="get" id="goods_search" style="width: 100%;">
                         <input type="hidden" value="yes" name="search">
                         <input type="text" class="pc-search-input" value="<?php echo trim(daddslashes($_GET['kw']));?>" name="kw" placeholder="搜索商品..." id="kw">
                         <button type="submit" style="display:none;"></button>
                    </form>
                </div>
                <div class="pc-nav-items">
                    <a href="./" class="pc-nav-item active">
                        <span class="icon icon-home"></span>
                        <span>首页</span>
                    </a>
                    <a href="./?mod=query" class="pc-nav-item">
                        <span class="icon icon-dingdan1"></span>
                        <span>订单查询</span>
                    </a>
                    <?php if($conf['shoppingcart']!=0){?>
                    <a href="./?mod=cart" class="pc-nav-item">
                        <span class="icon icon-cart2"></span>
                        <span>购物车</span>
                    </a>
                    <?php }?>
                    <a href="./?mod=kf" class="pc-nav-item">
                        <span class="icon icon-service1"></span>
                        <span>联系客服</span>
                    </a>
                    <a href="./user/" class="pc-nav-item">
                        <span class="icon icon-person2"></span>
                        <span>会员中心</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="pc-main-container">
            <div class="pc-left-sidebar">
                <div class="pc-category-title">商品分类</div>
                <a href="javascript:void(0)" class="pc-category-item get_cat <?php echo empty($cid)?'active':''?>" data-cid="0" data-name="全部分类">
                    <img src="assets/store/picture/1562225141902335.jpg" class="pc-category-icon" alt="全部">
                    <span class="pc-category-name">全部分类</span>
                </a>
                <?php foreach($ar_data as $v){ ?>
                <a href="javascript:void(0)" class="pc-category-item get_cat <?php echo $v['cid']==$cid?'active':''?>" data-cid="<?php echo $v['cid']?>" data-name="<?php echo $v['name']?>">
                    <img src="<?php echo $v['shopimg']?>" class="pc-category-icon" onerror="this.src='assets/store/picture/1562225141902335.jpg'" alt="<?php echo $v['name']?>">
                    <span class="pc-category-name"><?php echo $v['name']?></span>
                </a>
                <?php }?>
            </div>
            <div class="pc-right-content">
                <div class="pc-banner-section">
                    <div class="fui-swipe pc-banner-swipe">
                        <div class="fui-swipe-wrapper swiper-wrapper">
                            <?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item swiper-slide" href="' . $image_url[1] . '">
                                    <img src="' . $image_url[0] . '" alt="banner" />
                                </a>';
                            }
                            ?>
                        </div>
                        <div class="fui-swipe-page swiper-pagination"></div>
                    </div>
                </div>
                <div class="pc-sort-bar">
                    <span class="pc-sort-label">排序:</span>
                    <div class="pc-sort-item goods_sort_item item-price" data-order="sort" data-sort="ASC">
                        <span class="text">综合</span>
                    </div>
                    <div class="pc-sort-item goods_sort_item item-price" data-order="sales" data-sort="ASC">
                        <span class="text">销量</span>
                        <span class="sorting">
                            <i class="icon icon-sanjiao2"></i>
                            <i class="icon icon-sanjiao1"></i>
                        </span>
                    </div>
                    <div class="pc-sort-item goods_sort_item item-price" data-order="price" data-sort="ASC">
                        <span class="text">价格</span>
                        <span class="sorting">
                            <i class="icon icon-sanjiao2"></i>
                            <i class="icon icon-sanjiao1"></i>
                        </span>
                    </div>
                    <div class="pc-sort-view-toggle">
                        <i class="icon icon-sort" id="listblock" data-state="list"></i>
                    </div>
                </div>
                <div class="pc-goods-section">
                    <div class="fui-goods-group" id="goods-list-container">
                    </div>
                </div>
                <div class="pc-footer">
                    <p>© <?php echo $conf['sitename'] ?>. All rights reserved.</p>
                    <p><?php echo $conf['footer']?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ==================== 移动端布局 ==================== -->
    <div class="mobile-layout">
        <div class="fui-page-group" style="height: auto">
            <div class="fui-page fui-page-current" style="height:auto; overflow: inherit">
                <div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit">
                    <div class="default-items">
                        <div class="fui-swipe">
                            <div class="fui-swipe-wrapper" style="transition-duration: 500ms;">
                                <?php
                                $banner = explode('|', $conf['banner']);
                                foreach ($banner as $v) {
                                    $image_url = explode('*', $v);
                                    echo '<a class="fui-swipe-item" href="' . $image_url[1] . '">
                                    <img src="' . $image_url[0] . '" style="display: block; width: 100%; height: auto;" />
                                </a>';
                                }
                                ?>
                            </div>
                            <div class="fui-swipe-page right round" style="padding: 0 5px; bottom: 5px;"></div>
                        </div>
                        
                        <div class="fui-notice">
                            <div class="image">
                                <a href="JavaScript:void(0)" onclick="$('.tzgg').show()"><img src="assets/store/picture/1571065042489353.jpg"></a>
                            </div>
                            <div class="text" style="height: 1.2rem;line-height: 1.2rem">
                                <ul>
                                    <li><a href="JavaScript:void(0)" onclick="$('.tzgg').show()">
                                            <marquee behavior="alternate">
                                                <span style="color:red">❤️诚邀各级大咖合作共赢-24小时自助下单-售后稳定❤️</span>
                                            </marquee>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <form action="" method="get" id="goods_search_mobile">
                            <input type="hidden" value="yes" name="search">
                            <div class="fui-searchbar bar">
                                <div class="searchbar center searchbar-active" style="padding-right:2.5rem">
                                    <input type="submit" class="searchbar-cancel searchbtn" value="搜索">
                                    <div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;">
                                        <i class="icon icon-search"></i>
                                        <input type="text" class="search" value="<?php echo trim(daddslashes($_GET['kw']));?>" name="kw" placeholder="输入商品关键字..." id="kw_mobile">
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="device">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    $arry = 0;
                                    $au = 1;
                                    foreach ($ar_data as $v) {
                                        if (($arry / ($class_show_num*5)) == ($au - 1)) {
                                            echo '<div class="swiper-slide" data-swiper-slide-index="' . $au . '">
                                            <div class="content-slide">';
                                        }
                                        echo '<a data-cid="'.$v['cid'].'" data-name="'.$v['name'].'" class="get_cat">
                                                   <div class="mbg">
                                                       <p class="ico"><img src="' . $v['shopimg'] . '" onerror="this.src=\'assets/store/picture/1562225141902335.jpg\'"></p>
                                                       <p class="icon-title">' . $v['name'] . '</p>
                                                  </div>
                                              </a>';

                                        if ((($arry + 1) / ($class_show_num*5)) == ($au)) {
                                            echo '</div></div>';
                                            $au++;
                                        }
                                        $arry++;
                                    }
                                    if (floor((($arry) / ($class_show_num*5))) != (($arry) / ($class_show_num*5))) {
                                        echo '</div></div>';
                                    }
                                    ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <div style="height: 1px"></div>
                    </div>

                    <div class="goods_sort">
                        <div class="item item-price" data-order="sort" data-sort="ASC"><span class="text">综合</span>
                            <span class="sorting">
                                <i class="icon icon-sanjiao2"></i>
                                <i class="icon icon-sanjiao1"></i>
                            </span>
                        </div>
                        <div class="item item-price" data-order="sales" data-sort="ASC"><span class="text">销量</span>
                            <span class="sorting">
                                <i class="icon icon-sanjiao2"></i>
                                <i class="icon icon-sanjiao1"></i>
                            </span>
                        </div>
                        <div class="item item-price" data-order="price" data-sort="ASC"><span class="text">价格</span>
                            <span class="sorting">
                                <i class="icon icon-sanjiao2"></i>
                                <i class="icon icon-sanjiao1"></i>
                            </span>
                        </div>
                        <div class="item"><span class="text"><a href="javascript:;"><i class="icon icon-sort" id="listblock_mobile" data-state="list" style="font-size:20px;"></i></a></span></div>
                    </div>
                    
                    <section style="text-align: center;display:none;height: 1.5rem;line-height: 1.6rem;" class="show_class">
                        <section style="display: inline-block;">
                            <section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;text-align: center;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px;">
                                <span style="color: #f79646;"><strong><span style="font-size: 15px;"><span class="catname_show">正在获取数据...</span></span></strong></span>
                            </section>
                        </section>
                    </section>
                    
                    <div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
                        <ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;"></ul>
                    </div>
                    
                    <div class="fui-goods-group block three" style="background: #f3f3f3;" id="goods-list-container-mobile">
                        
                        <div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
                            <ul>
                                <li>© <?php echo $conf['sitename'] ?>. All rights reserved.</li>
                            </ul>
                            <p style="text-align: center"><?php echo $conf['footer']?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fui-navbar" style="bottom:-34px;background-color: white;max-width: 650px"></div>
        <div class="fui-navbar" style="max-width: 650px;z-index: 100;">
            <a href="./" class="nav-item active">
                <span class="icon icon-home"></span>
                <span class="label">首页</span>
            </a>
            <a href="./?mod=query" class="nav-item">
                <span class="icon icon-dingdan1"></span>
                <span class="label">订单</span>
            </a>
            <?php if($conf['shoppingcart']!=0){?>
            <a href="./?mod=cart" class="nav-item">
                <span class="icon icon-cart2"></span>
                <span class="label">购物车</span>
            </a>
            <?php }?>
            <a href="./?mod=kf" class="nav-item">
                <span class="icon icon-service1"></span>
                <span class="label">客服</span>
            </a>
            <a href="./user/" class="nav-item">
                <span class="icon icon-person2"></span>
                <span class="label">会员中心</span>
            </a>
        </div>
    </div>

    <!-- 隐藏表单数据 -->
    <input type="hidden" name="_cid" value="<?php echo $cid; ?>">
    <input type="hidden" name="_cidname" value="<?php echo $cat_name; ?>">
    <input type="hidden" name="_curr_time" value="<?php echo time(); ?>">
    <input type="hidden" name="_template_virtualdata" value="<?php echo $conf['template_virtualdata']?>">
    <input type="hidden" name="_template_showsales" value="<?php echo $conf['template_showsales']?>">
    <input type="hidden" name="_sort_type" value="">
    <input type="hidden" name="_sort" value="">

    <!-- 公告弹窗 -->
    <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000" class="tzgg"></div>
    <div class="tzgg" type="text/html" style="display: none">
        <div class="account-layer">
            <div class="account-main">
                <div class="account-title">系统公告</div>
                <div class="account-verify">
                    <?php echo $conf['anounce'] ?>
                </div>
            </div>
            <div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">确认</div>
        </div>
    </div>
</div>

<!-- 音乐代码 -->
<div id="audio-play" <?php if(empty($conf['musicurl'])){?>style="display:none;"<?php }?>>
    <div id="audio-btn" class="on" onclick="audio_init.changeClass(this,'media')">
        <audio loop="loop" src="<?php echo $conf['musicurl']?>" id="media" preload="preload"></audio>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>

<script>
    (function () {
        var goodsListHtml = '<div class="flow_load"><div id="goods_list"></div></div>';
        if (window.innerWidth > 768) {
            var pcContainer = document.getElementById('goods-list-container');
            if (pcContainer) pcContainer.innerHTML = goodsListHtml;
        } else {
            var mobileContainer = document.getElementById('goods-list-container-mobile');
            if (mobileContainer) {
                var footer = mobileContainer.querySelector('.footer');
                if (footer) {
                    footer.insertAdjacentHTML('beforebegin', goodsListHtml);
                } else {
                    mobileContainer.innerHTML = goodsListHtml + mobileContainer.innerHTML;
                }
            }
        }
    })();
</script>

<script>var limit = 10;</script>

<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/foxui.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/layui.flow.js"></script>
<script src="<?php echo $cdnserver?>assets/store/js/index.js?ver=<?php echo VERSION ?>"></script>

<!-- FIX: 终极修复补丁，重写get_goods函数以修复PC懒加载 -->
<script>
if (window.innerWidth > 768) {
    // 重写get_goods函数
    function get_goods() {
        $("#goods_list").remove();
        $(".flow_load").append("<div id=\"goods_list\"></div>");
        layui.use(['flow'], function () {
            var flow = layui.flow;
            var cid = $("input[name=_cid]").val();
            var name = $("input[name=_cidname]").val();
            var kw = $("input[name=kw]").val();
            var sort_type = $("input[name=_sort_type]").val();
            var sort = $("input[name=_sort]").val();
            var end = kw ? "没有更多数据了" : " ";
            var limit = 10; // 确保PC也是10个

            // 关键修复：为PC端指定正确的滚动容器
            var flowOptions = {
                elem: '#goods_list',
                isAuto: true,
                mb: 100,
                isLazyimg: true,
                end: end,
                scrollElem: '.pc-right-content', // <-- 关键修改：指定右侧整体为滚动容器
                done: function (page, next) {
                    $.ajax({
                        type: "post",
                        url: "./ajax.php?act=gettoolnew",
                        data: { page: page, limit: limit, cid: cid, kw: kw, sort_type: sort_type, sort: sort },
                        dataType: 'json',
                        success: function (res) {
                            var lis = [];
                            layui.each(res.data, function (index, item) {
                                var html = '<a class="fui-goods-item" title="' + item.name + '" href="./?mod=buy&tid=' + item.tid + '">';
                                html += '<div class="image">';
                                if (!item.shopimg) {
                                    item.shopimg = "./assets/store/picture/error_img.png"
                                }
                                var show_tag_html = "";
                                if (item.show_tag || (curr_time - item.addtime) <= 259200) {
                                    var show_tag = item.show_tag || "新款";
                                    show_tag_html = '<div style="transform: rotate(-45deg);background-color: #FF0000;color:#FFFFFF;width: 100px;text-align: center;margin-top: 15px;margin-left: -27px;font-size: 14px;position: absolute;">' + show_tag + '</div>';
                                }
                                var shoukong = item.is_stock_err == 1 ? '<img class="lazy" lay-src="./assets/store/picture/ysb.png" alt="" style="width:100%;top: 0;position: absolute;height:100%">' : '';
                                var kucun = item.stock > 0 ? '库存:' + item.stock + '份' : '';
                                var sales_html = template_showsales == 1 ? '<div style="border-radius: 4px 0 0 4px;text-align:center;padding: 1px;background-color: rgb(57, 61, 73,0.5);color: #FFFFFF;text-align: center;font-size: 10px;position: absolute;right: 1px;bottom: 1px;"><i class="layui-icon layui-icon-fire" style="font-size:10px;"></i>' + item.sales + '</div>' : '';
                                
                                html += show_tag_html + '<img class="lazy" lay-src="' + item.shopimg + '" src="./assets/store/picture/loadimg.gif" alt="' + item.name + '">' + shoukong + sales_html;
                                html += '</div>';
                                html += '<div class="detail" style="height:unset;">';
                                html += '<div class="name" style="color: #000000;">' + item.name + '</div>';
                                html += '<div style="line-height:0.7rem;height:0.7rem;color:#b2b2b2;font-size:0.6rem;margin-top: .2rem;">' + kucun + '</div>';
                                
                                var buy_button = '';
                                if (item.close == 1) {
                                    buy_button = '<div style="height: 1rem"><span class="buy" style="background: red;">已下架</span></div>';
                                } else if (item.stock == 0) {
                                     buy_button = '<div style="height: 1rem"><span class="buy" style="background: red;">缺货</span></div>';
                                } else if (item.price <= 0) {
                                    buy_button = '<div style="height: 1rem"><span class="buy" style="background-color: yellowgreen;">领取</span></div>';
                                } else {
                                    buy_button = '<div style="height: 1rem"><span class="buy">购买</span></div>';
                                }
                                
                                html += '<div class="price" style="margin-top: 0.2rem;"><span class="text" style="color: #ff5555;"> <p class="minprice">￥' + item.price + '</p> </span>' + buy_button + '</div>';
                                html += '</div>';
                                html += '</a>';
                                lis.push(html);
                            });
                            
                            var total_text = '系统共有<font style="color:#ed414a;">' + res.total + '</font>个商品';
                            if (name) {
                                total_text = '<font style="color:#ed414a;">' + name + '</font>共有<font style="color:#ed414a;">' + res.total + '</font>个商品';
                            } else if (kw) {
                                total_text = '包含<font style="color:#ed414a;">' + kw + '</font>共有<font style="color:#ed4a4a;">' + res.total + '</font>个商品';
                            }
                            $(".catname_show").html(total_text);
                            layer.closeAll();
                            next(lis.join(''), page < res.pages);
                        },
                        error: function (data) {
                            layer.msg("获取数据超时");
                            layer.closeAll();
                        }
                    });
                }
            };
            flow.load(flowOptions);
        });
    }
}

$(document).ready(function() {
    // ---- 新增PC端分类点击事件处理逻辑 START ----
    $('.pc-category-item.get_cat').on('click', function() {
        var self = $(this);
        var cid = self.data('cid');
        var name = self.data('name');

        // 1. 移除所有分类的选中效果
        $('.pc-category-item.get_cat').removeClass('active');
        // 2. 为当前点击的分类添加选中效果
        self.addClass('active');

        // 3. 更新隐藏的input值，以便商品列表加载函数能获取到正确的分类ID
        $("input[name=_cid]").val(cid);
        $("input[name=_cidname]").val(name);
        $("input[name=kw]").val(''); // 清空搜索框

        // 4. 调用函数，重新加载商品列表
        get_goods();
    });
    // ---- 新增PC端分类点击事件处理逻辑 END ----


    if(window.innerWidth <= 768) {
        // 移动端
        document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";
        
        $('#listblock_mobile').on('click', function() {
            var container = $('#goods-list-container-mobile'); 
            var icon = $(this);
            var state = icon.data('state');

            if (state === 'gongge') {
                icon.data('state', 'list').removeClass('icon-app').addClass('icon-sort');
                container.addClass('block three');
                 $.cookie('goods_list_style', 'list'); 
            } else {
                icon.data('state', 'gongge').removeClass('icon-sort').addClass('icon-app');
                container.removeClass('block three');
                 $.cookie('goods_list_style', 'gongge'); 
            }
        });

    } else { 
        // PC端
        new Swiper('.pc-banner-swipe', {
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
        
        $('.pc-sort-bar .goods_sort_item').on('click', function() {
            var self = $(this);
            var sort = self.data('order');
            if (!sort) return false;

            var sort_type = 'ASC';
            if (self.find('.sorting').length > 0) {
                 sort_type = self.data('sort') === 'ASC' ? 'DESC' : 'ASC';
                 self.data('sort', sort_type);
            }
            
            $("input[name=_sort_type]").val(sort);
            $("input[name=_sort]").val(sort_type);
            
            $('.pc-sort-bar .goods_sort_item').removeClass('on ASC DESC');
            self.addClass('on ' + sort_type);

            get_goods();
        });

        $('#listblock').off('click').on('click', function(){
            var container = $('#goods-list-container');
            var icon = $(this);
            var state = icon.data('state');

            if (state === 'gongge') {
                icon.data('state', 'list').removeClass('icon-app').addClass('icon-sort');
                container.addClass('block');
                $.cookie('pc_goods_list_style', 'list');
            } else {
                icon.data('state', 'gongge').removeClass('icon-sort').addClass('icon-app');
                container.removeClass('block');
                $.cookie('pc_goods_list_style', 'gongge');
            }
        });
        
        if ($.cookie('pc_goods_list_style') === 'gongge') { 
            $('#goods-list-container').removeClass('block');
            $('#listblock').data('state', 'gongge').removeClass('icon-sort').addClass('icon-app');
        } else { 
            $('#goods-list-container').addClass('block');
            $('#listblock').data('state', 'list').removeClass('icon-app').addClass('icon-sort');
        }
    }
});

</script>
</body>
</html>
