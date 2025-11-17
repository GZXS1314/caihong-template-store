<?php
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
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
list($background_image, $background_css) = \lib\Template::getBackground();
$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;
$x = new \lib\hieroglyphy();
$addsalt_js = $x->hieroglyphyString($addsalt);

// PC端左侧分类逻辑 (保持不变)
$ar_data = [];
if($is_fenzhan && !empty($siterow['class'])) $classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
while ($res = $re->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    $ar_data[] = $res;
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no">
    <title>购物车 - <?php echo $conf['sitename'] ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <!-- 原始CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $cdnserver ?>assets/store/css/cart.css"/>

<style type="text/css">
/* ==================== 移动端新版样式 ==================== */
@media screen and (max-width: 768px) {
    :root {
        --theme-color: #3b82f6;
        --danger-color: #ef4444;
        --background-color: #f3f4f6;
        --card-background: #ffffff;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }
    html, body {
        background-color: var(--background-color);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .pc-layout { display: none !important; }
    .mobile-layout { display: block !important; }
    
    /* 隐藏原始移动端布局 */
    .mobile-layout .gwcbox { display: none; }

    /* 顶部导航栏 */
    .m-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
        background-color: var(--card-background);
        box-shadow: 0 1px 0 var(--border-color);
        height: 56px;
        display: flex;
        align-items: center;
        padding: 0 12px;
        max-width: 650px;
        margin: auto;
    }
    .m-header-back {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--text-primary);
        cursor: pointer;
    }
    .m-header-title {
        flex: 1;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        padding-right: 40px;
    }

    /* 顶部悬浮结算栏 */
    .m-checkout-bar {
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        z-index: 998;
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-bottom: 1px solid var(--border-color);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        max-width: 650px;
        margin: auto;
        transition: opacity 0.3s, transform 0.3s;
    }
    .m-checkout-bar.hidden {
        opacity: 0;
        transform: translateY(-100%);
        pointer-events: none;
    }
    .m-select-all {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
    }
    .m-select-all .m-checkbox {
        width: 22px; height: 22px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
    }
    .m-select-all .m-checkbox.checked { background-color: var(--theme-color); border-color: var(--theme-color); }
    .m-select-all .m-checkbox.checked::after { content: ''; width: 6px; height: 10px; border: solid white; border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); }
    .m-select-all span { font-size: 15px; color: var(--text-primary); }
    .m-total { flex: 1; text-align: right; }
    .m-total span { font-size: 14px; color: var(--text-secondary); }
    .m-total strong { font-size: 20px; font-weight: bold; color: var(--danger-color); margin-left: 4px; }
    .m-submit {
        background-color: var(--theme-color); color: white; border: none; border-radius: 50px; padding: 10px 20px; font-size: 15px; font-weight: 500; box-shadow: var(--shadow-sm); cursor: pointer; display: flex; align-items: center; gap: 6px;
    }
    .m-submit .CartCount-M {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 50px;
        font-size: 12px;
        min-width: 20px;
        height: 20px;
        line-height: 20px;
        padding: 0 6px;
        font-weight: 500;
        text-align: center;
    }

    /* 主内容区 */
    .m-container {
        padding: calc(56px + 65px + 12px) 12px 90px 12px;
    }
    #CartContent-M { display: flex; flex-direction: column; gap: 12px; }
    .m-cart-item {
        background-color: var(--card-background); border-radius: var(--border-radius-lg); padding: 16px; display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow-sm);
    }
    .m-cart-item .m-checkbox {
        flex-shrink: 0; width: 22px; height: 22px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
    }
    .m-cart-item .m-checkbox.checked { background-color: var(--theme-color); border-color: var(--theme-color); }
    .m-cart-item .m-checkbox.checked::after { content: ''; width: 6px; height: 10px; border: solid white; border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); }
    .m-cart-item img { width: 80px; height: 80px; border-radius: var(--border-radius-md); object-fit: cover; }
    .m-cart-item-info { flex: 1; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .m-cart-item-name { font-size: 15px; font-weight: 500; color: var(--text-primary); line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .m-cart-item-input { font-size: 12px; color: var(--text-secondary); background-color: #f3f4f6; padding: 4px 8px; border-radius: 6px; display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .m-cart-item-footer { display: flex; justify-content: space-between; align-items: center; }
    .m-cart-item-price { font-size: 18px; font-weight: bold; color: var(--danger-color); }
    .m-cart-item-delete { width: 32px; height: 32px; border-radius: 50%; background-color: #f3f4f6; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; }
    
    /* 购物车为空 */
    #CartNull-M { text-align: center; padding-top: 50px; }
    #CartNull-M img { width: 160px; height: 160px; opacity: 0.7; }
    #CartNull-M p { font-size: 16px; color: var(--text-secondary); margin: 20px 0; }
    #CartNull-M a { display: inline-block; background-color: var(--theme-color); color: white; padding: 12px 30px; border-radius: 50px; font-size: 15px; font-weight: 500; text-decoration: none; box-shadow: var(--shadow-md); }

    /* 猜你喜欢 */
    .m-recommend { margin: 24px 0; }
    .m-recommend-title { font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; padding: 0 4px; }
    #GoodsRound-M { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .m-recommend-item { background-color: var(--card-background); border-radius: var(--border-radius-lg); overflow: hidden; text-decoration: none; box-shadow: var(--shadow-sm); }
    .m-recommend-item img { width: 100%; aspect-ratio: 1; object-fit: cover; }
    .m-recommend-item-info { padding: 12px; }
    .m-recommend-item-name { font-size: 14px; color: var(--text-primary); line-height: 1.4; height: 38px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .m-recommend-item-price { font-size: 16px; font-weight: bold; color: var(--danger-color); margin-top: 8px; }
    .m-recommend-item-price i { font-size: 12px; font-style: normal; }

    /* 【修复】底部菜单样式 (与query.php保持一致) */
    .fui-navbar {
        max-width: 650px;
        z-index: 100;
    }
}
/* ==================== PC端样式 (保持不变) ==================== */
@media screen and (min-width: 769px) {
    * { margin: 0; padding: 0; box-sizing: border-box; } html, body { width: 100%; height: 100%; font-size: 14px; overflow: hidden; background: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; } .pc-left-sidebar, .pc-cart-list-section, .pc-recommend-section { -ms-overflow-style: none; scrollbar-width: none; } .pc-left-sidebar::-webkit-scrollbar, .pc-cart-list-section::-webkit-scrollbar, .pc-recommend-section::-webkit-scrollbar { display: none; } .pc-top-navbar-wrapper { position: fixed; top: 0; left: 0; right: 0; padding: 20px; z-index: 1000; background: transparent; } .pc-top-navbar { max-width: 1400px; margin: 0 auto; height: 70px; background: rgba(255, 255, 255, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; } .pc-nav-logo { font-size: 24px; font-weight: 600; color: #000; text-decoration: none; letter-spacing: -0.5px; margin-right: auto; } .pc-nav-items { display: flex; gap: 8px; margin-left: auto; } .pc-nav-item { padding: 10px 24px; background: #000; color: #fff; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; gap: 6px; } .pc-nav-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); background: #333; } .pc-nav-item.active { background: #333; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); } .pc-main-container { position: fixed; top: 110px; left: 20px; right: 20px; bottom: 20px; display: flex; max-width: 1400px; margin: 0 auto; gap: 20px; } .pc-left-sidebar { width: 260px; flex-shrink: 0; background: rgba(255, 255, 255, 0.7); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); border-radius: 20px; padding: 20px; overflow-y: auto; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); } .pc-category-title { font-size: 18px; font-weight: 600; color: #000; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.1); } .pc-category-item { display: flex; align-items: center; padding: 12px 15px; margin-bottom: 8px; background: rgba(255, 255, 255, 0.6); border-radius: 12px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; color: #333; } .pc-category-item:hover { background: rgba(0, 0, 0, 0.05); transform: translateX(5px); } .pc-category-icon { width: 40px; height: 40px; border-radius: 10px; margin-right: 12px; object-fit: cover; } .pc-category-name { font-size: 14px; font-weight: 500; } .pc-right-content { flex: 1; display: flex; gap: 20px; overflow: hidden; } .pc-cart-content { flex: 1; display: flex; flex-direction: column; gap: 20px; overflow: hidden; } .pc-cart-list-section { flex: 1; background: #fff; border-radius: 20px; padding: 30px; overflow-y: auto; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); } .pc-cart-title { font-size: 24px; font-weight: 600; color: #000; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 10px; } .pc-cart-title .CartCount-PC { font-size: 16px; color: #999; font-weight: 400; } .pc-cart-item { background: #fff; border: 1px solid #f0f0f0; border-radius: 16px; padding: 20px; margin-bottom: 15px; display: flex; align-items: center; gap: 20px; transition: all 0.3s; } .pc-cart-item:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.06); border-color: transparent; } .pc-cart-item-image { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; } .pc-cart-item-info { flex: 1; display: flex; flex-direction: column; gap: 8px; } .pc-cart-item-name { font-size: 16px; font-weight: 500; color: #000; line-height: 1.4; } .pc-cart-item-input { font-size: 13px; color: #666; background: #f7f7f7; padding: 6px 10px; border-radius: 6px; } .pc-cart-item-price { font-size: 20px; font-weight: 600; color: #ff3b30; margin-right: 15px; } .pc-cart-item-delete { padding: 8px 20px; background: #f7f7f7; color: #888; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.3s; } .pc-cart-item-delete:hover { background: #ff3b30; color: #fff; } .pc-cart-footer { background: #fff; border-radius: 20px; padding: 25px 30px; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px; } .pc-custom-checkbox { display: flex; align-items: center; cursor: pointer; user-select: none; } .pc-custom-checkbox input[type="checkbox"] { opacity: 0; position: absolute; width: 1px; height: 1px; } .pc-custom-checkbox .checkmark { position: relative; height: 22px; width: 22px; background-color: #eee; border: 1px solid #ddd; border-radius: 6px; transition: all 0.2s; } .pc-custom-checkbox:hover .checkmark { background-color: #ccc; } .pc-custom-checkbox input[type="checkbox"]:checked + .checkmark { background-color: #000; border-color: #000; } .pc-custom-checkbox .checkmark:after { content: ""; position: absolute; display: none; left: 7px; top: 3px; width: 5px; height: 10px; border: solid white; border-width: 0 3px 3px 0; transform: rotate(45deg); } .pc-custom-checkbox input[type="checkbox"]:checked + .checkmark:after { display: block; } .pc-cart-select-all .checkmark { margin-right: 10px; } .pc-cart-select-all span { font-size: 15px; font-weight: 500; color: #000; } .pc-cart-total { flex: 1; text-align: right; font-size: 16px; color: #666; } .pc-cart-total-price { font-size: 28px; font-weight: 600; color: #ff3b30; margin-left: 10px; } .pc-cart-submit { padding: 14px 40px; background: #000; color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 500; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; } .pc-cart-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); background: #333; } .pc-cart-empty { text-align: center; padding: 60px 20px; } .pc-cart-empty img { width: 180px; height: 180px; margin-bottom: 30px; opacity: 0.6; } .pc-cart-empty p { font-size: 18px; color: #999; margin-bottom: 30px; } .pc-cart-empty a { display: inline-block; padding: 14px 40px; background: #000; color: #fff; border-radius: 12px; text-decoration: none; font-size: 15px; font-weight: 500; transition: all 0.3s; } .pc-cart-empty a:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); background: #333; } .pc-recommend-section { width: 360px; flex-shrink: 0; background: #fff; border-radius: 20px; padding: 25px; overflow-y: auto; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); } .pc-recommend-title { font-size: 18px; font-weight: 600; color: #000; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; } .pc-recommend-item { background: #fff; border: 1px solid #f0f0f0; border-radius: 12px; padding: 12px; margin-bottom: 12px; cursor: pointer; transition: all 0.3s; display: flex; gap: 12px; } .pc-recommend-item:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-color: transparent; } .pc-recommend-item img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; } .pc-recommend-item-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; } .pc-recommend-item-name { font-size: 14px; font-weight: 500; color: #000; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; } .pc-recommend-item-price { font-size: 16px; font-weight: 600; color: #ff3b30; }
    .mobile-layout { display: none !important; }
    .pc-layout { display: block !important; }
}
</style>
</head>

<body>
<div id="body">
    
    <!-- PC端布局 (保持不变) -->
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper"><div class="pc-top-navbar"><a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a><div class="pc-nav-items"><a href="./" class="pc-nav-item"><span class="icon icon-home"></span><span>首页</span></a><a href="./?mod=query" class="pc-nav-item"><span class="icon icon-dingdan1"></span><span>订单查询</span></a><a href="./?mod=cart" class="pc-nav-item active"><span class="icon icon-cart2"></span><span>购物车</span></a><a href="./?mod=kf" class="pc-nav-item"><span class="icon icon-service1"></span><span>联系客服</span></a><a href="./user/" class="pc-nav-item"><span class="icon icon-person2"></span><span>会员中心</span></a></div></div></div><div class="pc-main-container"><div class="pc-left-sidebar"><div class="pc-category-title">商品分类</div><a href="./" class="pc-category-item"><img src="./assets/store/picture/1562225141902335.jpg" class="pc-category-icon" alt="全部"><span class="pc-category-name">返回首页</span></a><?php foreach($ar_data as $v){ ?><a href="./?cid=<?php echo $v['cid']?>" class="pc-category-item"><img src="<?php echo !empty($v['shopimg']) ? $v['shopimg'] : 'assets/store/picture/1562225141902335.jpg'; ?>" class="pc-category-icon" onerror="this.src='assets/store/picture/1562225141902335.jpg'" alt="<?php echo $v['name']?>"><span class="pc-category-name"><?php echo $v['name']?></span></a><?php }?></div><div class="pc-right-content"><div class="pc-cart-content"><div class="pc-cart-list-section"><div class="pc-cart-title">购物车<span class="CartCount-PC"></span></div><div id="CartContent-PC"></div><div id="CartNull-PC" style="display: none"><div class="pc-cart-empty"><img src="assets/store/images/gwc.jpg" alt="空购物车"><p>购物车还是空的</p><a href="./">去逛逛</a></div></div></div><div class="pc-cart-footer" style="display: none;"><label class="pc-custom-checkbox pc-cart-select-all"><input type="checkbox" id="pc-check-all"><span class="checkmark"></span><span>全选</span></label><div class="pc-cart-total">合计：<span class="pc-cart-total-price" id="price_all_pc">￥0.00</span></div><button class="pc-cart-submit" onclick="GoodsCart.submit()"><span>去结算</span><span class="CartCount-PC"></span></button></div></div><div class="pc-recommend-section"><div class="pc-recommend-title"><span>猜你喜欢</span></div><div id="GoodsRound-PC"></div></div></div></div>
    </div>
    
    <!-- 移动端布局 -->
    <div class="mobile-layout">
        <div class="m-header">
            <div class="m-header-back icon icon-left" onclick="javascript:history.back(-1)"></div>
            <div class="m-header-title">购物车</div>
        </div>
        <div class="m-checkout-bar hidden">
            <div class="m-select-all">
                <div class="m-checkbox"></div>
                <span>全选</span>
            </div>
            <div class="m-total">
                <span>合计:</span>
                <strong id="price_all_m">￥0.00</strong>
            </div>
            <button class="m-submit" onclick="GoodsCart.submit()">
                结算
                <span class="CartCount-M"></span>
            </button>
        </div>
        <div class="m-container">
            <div id="CartContent-M"></div>
            <div id="CartNull-M" style="display: none;">
                <img src="assets/store/images/gwc.jpg" alt="空购物车">
                <p>购物车里什么都没有</p>
                <a href="./">去逛逛吧</a>
            </div>
            <div class="m-recommend">
                <div class="m-recommend-title">猜你喜欢</div>
                <div id="GoodsRound-M"></div>
            </div>
        </div>
        
        <!-- 【修复】底部菜单HTML (与query.php保持一致) -->
        <div class="fui-navbar">
            <a href="./" class="nav-item"> 
                <span class="icon icon-home"></span> 
                <span class="label" style="color:#999;">首页</span>
            </a>
            <a href="./?mod=query" class="nav-item"> 
                <span class="icon icon-dingdan1"></span> 
                <span class="label" style="color:#999;">订单</span> 
            </a>
            <a href="./?mod=cart" class="nav-item active"> 
                <span class="icon icon-cart2"></span> 
                <span class="label" style="color:#999;">购物车</span> 
            </a>
            <a href="./?mod=kf" class="nav-item"> 
                <span class="icon icon-service1"></span> 
                <span class="label" style="color:#999;">客服</span>
            </a>
            <a href="./user/" class="nav-item"> 
                <span class="icon icon-person2"></span> 
                <span class="label" style="color:#999;">会员中心</span> 
            </a>
        </div>
        
        <!-- 原始HTML数据源 (隐藏) -->
        <div class="gwcbox" style="display: none !important;">
            <div id="CartContent" class="gwcbox_1"></div>
            <div class="hejiBox"><div class="heji"><div class="heji_1"><div class="gwccheck on"></div></div><div class="heji_2">全选</div><div class="heji_3"><p>合计：<span id="price_all">￥0</span></p></div><div class="heji_5"><a href="javascript:GoodsCart.submit()">去结算<span class="CartCount"></span></a></div></div></div>
            <div id="CartNull"><div class="paysuccess"><div class="pay30"><img src="assets/store/images/gwc.jpg"><p>购物车还是空的</p></div><div class="pay40"><a href="./">去逛逛</a></div></div></div>
            <div class="likebox"><div class="likeTit"><span>猜你喜欢</span></div><ul id="GoodsRound"></ul></div>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script type="text/javascript">var hashsalt=<?php echo $addsalt_js?>;</script>
<script src="assets/store/js/cart.js?ver=<?php echo VERSION ?>"></script>
<script>
$(document).ready(function() {
    var isMobile = window.innerWidth <= 768;
    var originalSuccess = GoodsCart.Success;
    var originalCartNull = GoodsCart.CartNull;
    var originalGoodsRound = GoodsCart.GoodsRound;

    GoodsCart.Success = function(data) {
        originalSuccess.apply(this, arguments);
        if (isMobile) {
            renderMobileCart();
            bindMobileEvents();
            syncMobileUI();
            $('#CartNull-M').hide();
            $('.m-checkout-bar').removeClass('hidden');
        } else {
            renderPCCart(); bindPCEvents(); syncPCUI(); $('#CartNull-PC').hide(); $('.pc-cart-footer').show();
        }
    };

    GoodsCart.CartNull = function() {
        originalCartNull.apply(this, arguments);
        if (isMobile) {
            $('#CartContent-M').empty();
            $('#CartNull-M').show();
            $('.m-checkout-bar').addClass('hidden');
        } else {
            $('#CartContent-PC').empty(); $('#CartNull-PC').show(); $('.pc-cart-footer').hide(); $('.CartCount-PC').text('');
        }
    };

    GoodsCart.GoodsRound = function() {
        originalGoodsRound.apply(this, arguments);
        var observer = new MutationObserver(function() {
            if ($('#GoodsRound').html().length > 0) {
                if (isMobile) { renderMobileRound(); } else { renderPCRound(); }
                observer.disconnect();
            }
        });
        observer.observe(document.getElementById('GoodsRound'), { childList: true, subtree: true });
    }

    function renderMobileCart() {
        var $temp = $('<div>').html($('#CartContent').html()); var mobileHtml = '';
        $temp.find('.gwcone').each(function() {
            var $item = $(this); var mobileId = $item.find('.go1.CartSelect_1').attr('id');
            mobileHtml += `<div class="m-cart-item" data-mobile-id="${mobileId}"><div class="m-checkbox"></div><img src="${$item.find('.go2 img').attr('src')}" alt="商品图片"><div class="m-cart-item-info"><div class="m-cart-item-name">${$item.find('.go3_1 .p1').text()}</div><div class="m-cart-item-input">${$item.find('.go3_2 .p3').html() || '无'}</div><div class="m-cart-item-footer"><div class="m-cart-item-price">${$item.find('.go3_2 .p4').text()}</div><div class="m-cart-item-delete icon icon-delete" data-id="${$item.find('.del').data('id')}" data-name="${$item.find('.del').data('name')}"></div></div></div></div>`;
        });
        $('#CartContent-M').html(mobileHtml);
    }
    
    function renderMobileRound() {
        var $temp = $('<div>').html($('#GoodsRound').html()); var mobileHtml = '';
        $temp.find('li a').each(function() {
            var $link = $(this);
            mobileHtml += `<a href="${$link.attr('href')}" class="m-recommend-item"><img src="${$link.find('img.proimg').attr('src')}" alt="${$link.find('p.tit').text()}"><div class="m-recommend-item-info"><div class="m-recommend-item-name">${$link.find('p.tit').text()}</div><div class="m-recommend-item-price"><i>￥</i>${$link.find('p.price').text().replace('￥', '')}</div></div></a>`;
        });
        $('#GoodsRound-M').html(mobileHtml);
    }
    
    function bindMobileEvents() {
        $('#CartContent-M').off('click', '.m-cart-item').on('click', '.m-cart-item', function(e) {
            if ($(e.target).hasClass('m-cart-item-delete')) return;
            var mobileId = $(this).data('mobile-id');
            $('#' + mobileId).trigger('click'); setTimeout(syncMobileUI, 50); 
        });
        $('#CartContent-M').off('click', '.m-cart-item-delete').on('click', '.m-cart-item-delete', function(e) {
            e.stopPropagation(); var mobileId = $(this).closest('.m-cart-item').data('mobile-id');
            var shopId = mobileId.replace('Cart_', 'CartShop_');
            $('#' + shopId).find('.del').trigger('click');
        });
        $('.m-checkout-bar .m-select-all').off('click').on('click', function() {
            $('.mobile-layout .heji_1').trigger('click'); setTimeout(syncMobileUI, 50);
        });
    }

    function syncMobileUI() {
        $('#price_all_m').html($('#price_all').html());
        var cartCountText = $('.mobile-layout .CartCount').first().text();
        if (cartCountText && cartCountText.trim() !== '()') {
            $('.CartCount-M').text(cartCountText.replace(/[()]/g, '')).show();
        } else {
            $('.CartCount-M').hide();
        }
        var isAllChecked = $('.mobile-layout .heji_1 .gwccheck').hasClass('on');
        $('.m-select-all .m-checkbox').toggleClass('checked', isAllChecked);
        $('#CartContent-M .m-cart-item').each(function() {
            var mobileId = $(this).data('mobile-id');
            var isChecked = $('#' + mobileId + ' .gwccheck').hasClass('on');
            $(this).find('.m-checkbox').toggleClass('checked', isChecked);
        });
    }

    function renderPCCart() { var $temp = $('<div>').html($('#CartContent').html()); var pcHtml = ''; $temp.find('.gwcone').each(function() { var $item = $(this); pcHtml += `<div class="pc-cart-item" data-mobile-id="${$item.find('.go1.CartSelect_1').attr('id')}"><label class="pc-custom-checkbox"><input type="checkbox" class="pc-item-check"><span class="checkmark"></span></label><img src="${$item.find('.go2 img').attr('src')}" class="pc-cart-item-image"><div class="pc-cart-item-info"><div class="pc-cart-item-name">${$item.find('.go3_1 .p1').text()}</div><div class="pc-cart-item-input">${$item.find('.go3_2 .p3').html() || '无'}</div></div><div class="pc-cart-item-price">${$item.find('.go3_2 .p4').text()}</div><button class="pc-cart-item-delete" data-id="${$item.find('.del').data('id')}" data-name="${$item.find('.del').data('name')}">删除</button></div>`; }); $('#CartContent-PC').html(pcHtml); }
    function renderPCRound() { var $temp = $('<div>').html($('#GoodsRound').html()); var pcHtml = ''; $temp.find('li a').each(function() { var $link = $(this); pcHtml += `<a href="${$link.attr('href')}" class="pc-recommend-item"><img src="${$link.find('img.proimg').attr('src')}" alt="${$link.find('p.tit').text()}"><div class="pc-recommend-item-info"><div class="pc-recommend-item-name">${$link.find('p.tit').text()}</div><div class="pc-recommend-item-price">${$link.find('p.price').html().replace(/<img[^>]*>/g, '')}</div></div></a>`; }); $('#GoodsRound-PC').html(pcHtml); }
    function bindPCEvents() { $('#CartContent-PC').off('click', '.pc-custom-checkbox').on('click', '.pc-custom-checkbox', function(e) { if (e.target.tagName !== 'INPUT') e.preventDefault(); var mobileId = $(this).closest('.pc-cart-item').data('mobile-id'); $('#' + mobileId).trigger('click'); setTimeout(syncPCUI, 50); }); $('#CartContent-PC').off('click', '.pc-cart-item-delete').on('click', '.pc-cart-item-delete', function(e) { e.preventDefault(); var mobileId = $(this).closest('.pc-cart-item').data('mobile-id'); var shopId = mobileId.replace('Cart_', 'CartShop_'); $('#' + shopId).find('.del').trigger('click'); }); $('.pc-cart-footer .pc-cart-select-all').off('click').on('click', function(e) { if (e.target.tagName !== 'INPUT') e.preventDefault(); $('.mobile-layout .heji_1').trigger('click'); setTimeout(syncPCUI, 50); }); }
    function syncPCUI() { $('#price_all_pc').html($('#price_all').html()); var cartCountText = $('.mobile-layout .CartCount').first().text(); $('.pc-layout .CartCount-PC').text(cartCountText); $('#pc-check-all').prop('checked', $('.mobile-layout .heji_1 .gwccheck').hasClass('on')); $('#CartContent-PC .pc-cart-item').each(function() { var mobileId = $(this).data('mobile-id'); var isChecked = $('#' + mobileId + ' .gwccheck').hasClass('on'); $(this).find('.pc-item-check').prop('checked', isChecked); }); }
    
    GoodsCart.CartList();
});
</script>
</body>
</html>
