<?php
if (!defined('IN_CRONLITE')) die();

$qqlink = 'https://wpa.qq.com/msgrd?v=3&uin='.$conf['kfqq'].'&site=qq&menu=yes';
if($is_fenzhan && !empty($conf['kfwx']) && file_exists(ROOT.'assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png')){
	$qrcodeimg = './assets/img/qrcode/wxqrcode_'.$siterow['zid'].'.png';
	$qrcodename = '微信';
}elseif(!empty($conf['kfwx']) && file_exists(ROOT.'assets/img/wxqrcode.png')){
	$qrcodeimg = './assets/img/wxqrcode.png';
	$qrcodename = '微信';
}else{
	$qrcodeimg = '//api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data='.urlencode($qqlink);
	$qrcodename = 'QQ';
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
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>联系客服 - <?php echo $conf['sitename'] ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
	<link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
	<link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">

<style type="text/css">
/* ==================== 移动端样式 ==================== */
@media screen and (max-width: 768px) {
    body {
		max-width: 650px;
		margin: auto;
		background-color: #f3f3f3;
	}
	.fui-content.navbar{
		padding-bottom: 2.5rem;
		background-color: #f3f3f3;
	}
	.custormer-container-mobile {
        padding: 1.5rem 0.75rem;
        margin: 0 auto;
    }
    .custormer-container-mobile .box {
        background: #fff;
        border-radius: 8px;
        text-align: center;
        padding: 1.5rem;
		box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .custormer-container-mobile .info-item {
        font-weight: bold;
        margin-bottom: 1rem;
		font-size: 15px;
    }
	.custormer-container-mobile .info-item a{
		color: #1492fb;
		font-weight: normal;
	}
    .custormer-container-mobile .info-item:last-of-type {
        margin-bottom: 1.5rem;
    }
    .custormer-container-mobile .box img {
        width: 100%;
        max-width: 250px;
        height: auto;
        box-shadow: 0px 3px 16px #eee;
		border-radius: 6px;
    }
    .custormer-container-mobile .complaint {
        display: flex;
        align-items: center;
        color: #666;
        width: 100%;
        height: 2.5rem;
        line-height: 2.5rem;
        justify-content: center;
        background: white;
        border-radius: 6px;
        margin-top: 0.75rem;
        font-size: 14px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .pc-layout { display: none !important; }
    .mobile-layout { display: block !important; }
    
    /* 【修复】底部菜单样式 (与query.php保持一致) */
    .fui-navbar {
        max-width: 650px;
        z-index: 100;
    }
}

/* ==================== PC端新版样式 ==================== */
@media screen and (min-width: 769px) {
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { width: 100%; height: 100%; font-size: 14px; overflow: hidden; background: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    
    .pc-left-sidebar, .pc-right-content { -ms-overflow-style: none; scrollbar-width: none; }
    .pc-left-sidebar::-webkit-scrollbar, .pc-right-content::-webkit-scrollbar { display: none; }
    
    .pc-top-navbar-wrapper { position: fixed; top: 0; left: 0; right: 0; padding: 20px; z-index: 1000; background: transparent; }
    .pc-top-navbar { max-width: 1400px; margin: 0 auto; height: 70px; background: rgba(255, 255, 255, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
    .pc-nav-logo { font-size: 24px; font-weight: 600; color: #000; text-decoration: none; letter-spacing: -0.5px; margin-right: auto; }
    .pc-nav-items { display: flex; gap: 8px; margin-left: auto; }
    .pc-nav-item { padding: 10px 24px; background: #000; color: #fff; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; gap: 6px; }
    .pc-nav-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); background: #333; }
    .pc-nav-item.active { background: #333; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); }
    .pc-main-container { position: fixed; top: 110px; left: 20px; right: 20px; bottom: 20px; display: flex; max-width: 1400px; margin: 0 auto; gap: 20px; }
    .pc-left-sidebar { width: 260px; flex-shrink: 0; background: rgba(255, 255, 255, 0.7); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); border-radius: 20px; padding: 20px; overflow-y: auto; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); }
    .pc-category-title { font-size: 18px; font-weight: 600; color: #000; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
    .pc-category-item { display: flex; align-items: center; padding: 12px 15px; margin-bottom: 8px; background: rgba(255, 255, 255, 0.6); border-radius: 12px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; color: #333; }
    .pc-category-item:hover { background: rgba(0, 0, 0, 0.05); transform: translateX(5px); }
    .pc-category-icon { width: 40px; height: 40px; border-radius: 10px; margin-right: 12px; object-fit: cover; }
    .pc-category-name { font-size: 14px; font-weight: 500; }
    
    .pc-right-content { flex: 1; display: flex; align-items: center; justify-content: center; overflow-y: auto; background: #fff; border-radius: 20px; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); padding: 40px; }
    
    .kf-wrapper-pc { display: flex; width: 100%; max-width: 900px; align-items: center; gap: 60px; }
    .kf-welcome-pc { flex: 1; text-align: center; }
    .kf-welcome-pc img.logo { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 20px; object-fit: cover; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .kf-welcome-pc h1 { font-size: 28px; font-weight: 600; color: #333; margin-bottom: 10px; }
    .kf-welcome-pc p { font-size: 16px; color: #888; }

    .kf-container-pc { flex: 1; max-width: 400px; text-align: center; }
    .kf-container-pc .account-title { font-size: 26px; font-weight: 600; color: #222; margin-bottom: 25px; }
    .kf-container-pc .pc-kf-info { font-size: 16px; margin-bottom: 20px; line-height: 2; color: #555; }
    .kf-container-pc .pc-kf-info span { font-weight: bold; color: #333; }
    .kf-container-pc .pc-kf-info a { color: #1492fb; text-decoration: none; font-weight: 500; margin-left: 8px; transition: color 0.2s; }
    .kf-container-pc .pc-kf-info a:hover { color: #0b78d2; }
    .kf-container-pc .pc-kf-qrcode img { width: 180px; height: 180px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); margin-bottom: 15px; }
    .kf-container-pc .pc-kf-scan-tip { font-size: 14px; color: #666; font-weight: 500; background-color: rgba(0, 0, 0, 0.04); padding: 8px 15px; border-radius: 8px; display: inline-block; }

    .mobile-layout { display: none !important; }
    .pc-layout { display: block !important; }
    .fui-navbar { display: none !important; }
}
</style>
</head>

<body ontouchstart="">
<div id="body">
    
    <!-- ==================== PC端布局 ==================== -->
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper">
            <div class="pc-top-navbar">
                <a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a>
                <div class="pc-nav-items">
                    <a href="./" class="pc-nav-item">
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
                    <a href="./?mod=kf" class="pc-nav-item active">
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
                <a href="./" class="pc-category-item">
                    <img src="./assets/store/picture/1562225141902335.jpg" class="pc-category-icon" alt="全部">
                    <span class="pc-category-name">返回首页</span>
                </a>
                <?php foreach($ar_data as $v){ ?>
                <a href="./?cid=<?php echo $v['cid']?>" class="pc-category-item">
                    <img src="<?php echo !empty($v['shopimg']) ? $v['shopimg'] : 'assets/store/picture/1562225141902335.jpg'; ?>" class="pc-category-icon" onerror="this.src='assets/store/picture/1562225141902335.jpg'" alt="<?php echo $v['name']?>">
                    <span class="pc-category-name"><?php echo $v['name']?></span>
                </a>
                <?php }?>
            </div>
            <div class="pc-right-content">
                 <div class="kf-wrapper-pc">
                    <div class="kf-welcome-pc">
                        <img src="<?php echo !empty($conf['logo_url']) ? $conf['logo_url'] : 'assets/img/logo.png'; ?>" alt="Logo" class="logo" onerror="this.src='assets/img/logo.png'">
                        <h1>欢迎来到 <?php echo $conf['sitename']; ?></h1>
                        <p>我们随时准备为您提供帮助</p>
                    </div>
                    <div class="kf-container-pc">
                        <div class="account-title">联系我们</div>
                        <div class="pc-kf-info">
                            客服QQ：<span><?php echo $conf['kfqq'] ?></span><a href="<?php echo $qqlink ?>" target="_blank">[点击添加]</a><br/>
                            <?php if(!empty($conf['kfwx'])){?>
                            客服微信：<span><?php echo $conf['kfwx']; ?></span><a href="javascript:;" class="wx_hao" data-clipboard-text="<?php echo $conf['kfwx']; ?>">[点击复制]</a>
                            <?php }?>
                        </div>
                        <div class="pc-kf-qrcode">
                             <img src="<?php echo $qrcodeimg ?>" alt="客服二维码">
                        </div>
                        <div class="pc-kf-scan-tip">
                            请打开<?php echo $qrcodename?>扫一扫
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ==================== 移动端布局 ==================== -->
    <div class="mobile-layout">
        <div class="fui-page-group">
            <div class="fui-page fui-page-current">
                <div class="fui-header">
                    <div class="fui-header-left">
                        <a class="back" href="javascript:history.back(-1)"></a>
                    </div>
                    <div class="title">联系客服</div>
                    <div class="fui-header-right"></div>
                </div>
                <div class="fui-content navbar">
                     <div class="custormer-container-mobile">
                        <div class="box">
                            <div class="info-item">客服QQ：<?php echo $conf['kfqq'] ?> <a href="<?php echo $qqlink ?>" target="_blank">[添加]</a></div>
                            <?php if(!empty($conf['kfwx'])){?>
                            <div class="info-item">客服微信：<?php echo $conf['kfwx']; ?> <a href="javascript:;" class="wx_hao" data-clipboard-text="<?php echo $conf['kfwx']; ?>">[复制]</a></div>
                            <?php }?>
                            <img src="<?php echo $qrcodeimg ?>">
                        </div>
                        <div class="complaint">
                            打开<?php echo $qrcodename?>扫一扫添加客服
                        </div>
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
                    <?php if($conf['shoppingcart']!=0){?>
                    <a href="./?mod=cart" class="nav-item"> 
                        <span class="icon icon-cart2"></span> 
                        <span class="label" style="color:#999;">购物车</span> 
                    </a>
                    <?php }?>
                    <a href="./?mod=kf" class="nav-item active"> 
                        <span class="icon icon-service1"></span> 
                        <span class="label" style="color:#999;">客服</span>
                    </a>
                    <a href="./user/" class="nav-item"> 
                        <span class="icon icon-person2"></span> 
                        <span class="label" style="color:#999;">会员中心</span> 
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.wx_hao');
	clipboard.on('success', function (e) {
        layer.msg('客服微信号复制成功');
    });
    clipboard.on('error', function (e) {
        layer.msg('复制失败，请手动复制');
    });
</script>
</body>
</html>
