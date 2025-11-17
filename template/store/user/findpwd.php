
 <!--               _ _                     ____  _                             -->
 <!--              | (_) __ _ _ __   __ _  / ___|| |__  _   _  ___              -->
 <!--           _  | | |/ _` | '_ \ / _` | \___ \| '_ \| | | |/ _ \             -->
 <!--          | |_| | | (_| | | | | (_| |  ___) | | | | |_| | (_) |            -->
 <!--           \___/|_|\__,_|_| |_|\__, | |____/|_| |_|\__,_|\___/             -->
 <!--  ____   _____          _  __  |___/   _____   _   _  _          ____ ____ -->
 <!-- / ___| |__  /         | | \ \/ / / | |___ /  / | | || |        / ___/ ___|-->
 <!--| |  _    / /       _  | |  \  /  | |   |_ \  | | | || |_      | |  | |    -->
 <!--| |_| |  / /_   _  | |_| |  /  \  | |  ___) | | | |__   _|  _  | |__| |___ -->
 <!-- \____| /____| (_)  \___/  /_/\_\ |_| |____/  |_|    |_|   (_)  \____\____|-->
                                                                            
 <!--                              追求极致的美学                               -->

<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');
list($background_image, $background_css) = \lib\Template::getBackground();

// 为了在PC端左侧显示分类，需要从 index.php 引入分类查询逻辑
$ar_data = [];
$classhide = [];
if($is_fenzhan && !empty($siterow['class'])) $classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
while ($res = $re->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    $ar_data[] = $res;
}
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>找回密码 - <?php echo $conf['sitename'];  ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    
    <!-- 加载公共CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">

<style type="text/css">
/* ==================== 移动端样式 (保持原样) ==================== */
@media screen and (max-width: 768px) {
    body {
        width: 100%;
        max-width: 650px;
        margin: auto;
        background: #f3f3f3;
        line-height: 24px;
        font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
    }
    .label{
        color: unset;
        line-height: 1.8;
    }
    .account-main{
        height: 100% !important;
    }
    a {
        text-decoration:none;
    }
    a:hover{
        text-decoration:none;
    }
    .fui-modal{z-index: 20;}
    .pc-layout { display: none !important; }
    .mobile-layout { display: block !important; }
}

/* ==================== PC端新版样式 (与login.php保持一致) ==================== */
@media screen and (min-width: 769px) {
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { width: 100%; height: 100%; font-size: 14px; overflow: hidden; background: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    
    /* ===== 隐藏滚动条样式 ===== */
    .pc-left-sidebar { -ms-overflow-style: none; scrollbar-width: none; }
    .pc-left-sidebar::-webkit-scrollbar { display: none; }
    
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
    .findpwd-wrapper-pc { display: flex; width: 100%; max-width: 900px; align-items: center; gap: 60px; }
    .findpwd-welcome-pc { flex: 1; text-align: center; }
    .findpwd-welcome-pc img.logo { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 20px; object-fit: cover; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .findpwd-welcome-pc h1 { font-size: 28px; font-weight: 600; color: #333; margin-bottom: 10px; }
    .findpwd-welcome-pc p { font-size: 16px; color: #888; }
    .findpwd-container-pc { flex: 1; max-width: 400px; text-align: center; }
    .findpwd-container-pc .account-title { font-size: 26px; font-weight: 600; color: #222; margin-bottom: 25px; }
    .findpwd-container-pc #loginmsg_pc { font-weight: bold; font-size: 16px; color: #555; }
    .findpwd-container-pc #loginload_pc { padding-left: 10px;color: #790909; }
    .findpwd-container-pc #qrimg_pc { margin: 20px auto; width: 180px; height: 180px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center; }
    .findpwd-container-pc #qrimg_pc img { width: 100%; height: 100%; }
    .findpwd-container-pc .alert { text-align: left; font-size: 13px; line-height: 1.6; }
    .findpwd-container-pc .link-group { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
    .findpwd-container-pc .link-group a { color: #3b82f6; text-decoration: none; }
    .findpwd-container-pc .link-group a:hover { text-decoration: underline; }
    #mobile_pc .btn{ width: 100%; margin-bottom: 10px; height: 45px; font-size: 15px; border-radius: 10px; }

    .pc-layout { display: block !important; }
    .mobile-layout { display: none !important; }
    .fui-navbar { display: none !important; }
}
</style>
</head>
<?php echo str_replace('body','html',$background_css)?>
<body>
<div id="body">

    <!-- ==================== PC端布局 ==================== -->
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper">
            <div class="pc-top-navbar">
                <a href="../" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a>
                <div class="pc-nav-items">
                    <a href="../" class="pc-nav-item">
                        <span class="icon icon-home"></span>
                        <span>首页</span>
                    </a>
                    <a href="../?mod=query" class="pc-nav-item">
                        <span class="icon icon-dingdan1"></span>
                        <span>订单查询</span>
                    </a>
                    <?php if($conf['shoppingcart']!=0){?>
                    <a href="../?mod=cart" class="pc-nav-item">
                        <span class="icon icon-cart2"></span>
                        <span>购物车</span>
                    </a>
                    <?php }?>
                    <a href="../?mod=kf" class="pc-nav-item">
                        <span class="icon icon-service1"></span>
                        <span>联系客服</span>
                    </a>
                    <a href="./" class="pc-nav-item active">
                        <span class="icon icon-person2"></span>
                        <span>会员中心</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="pc-main-container">
            <div class="pc-left-sidebar">
                <div class="pc-category-title">商品分类</div>
                <a href="../" class="pc-category-item">
                    <img src="../assets/store/picture/1562225141902335.jpg" class="pc-category-icon" alt="全部">
                    <span class="pc-category-name">全部分类</span>
                </a>
                <?php foreach($ar_data as $v){ ?>
                <a href="../?cid=<?php echo $v['cid']?>" class="pc-category-item">
                    <img src="<?php echo !empty($v['shopimg']) ? '../'.$v['shopimg'] : '../assets/store/picture/1562225141902335.jpg'; ?>" class="pc-category-icon" onerror="this.src='../assets/store/picture/1562225141902335.jpg'" alt="<?php echo $v['name']?>">
                    <span class="pc-category-name"><?php echo $v['name']?></span>
                </a>
                <?php }?>
            </div>
            <div class="pc-right-content">
                <div class="findpwd-wrapper-pc">
                    <div class="findpwd-welcome-pc">
                        <img src="<?php echo !empty($conf['logo_url']) ? $conf['logo_url'] : '../assets/img/logo.png'; ?>" alt="Logo" class="logo" onerror="this.src='../assets/img/logo.png'">
                        <h1>找回您的账户密码</h1>
                        <p>通过QQ扫码验证，安全快捷地重置密码</p>
                    </div>
                    <div class="findpwd-container-pc">
                        <div class="account-title">安全验证</div>
                        <span id="loginmsg_pc">请使用QQ手机版扫描二维码</span><span id="loginload_pc">.</span>
                        <div id="qrimg_pc"><div class="loading-dot"></div></div>
                        <div id="mobile_pc" style="display:none; margin-top: 15px;">
                            <button type="button" id="mlogin_pc" onclick="mloginurl()" class="btn btn-warning btn-block">跳转QQ快捷登录</button>
                            <button type="button" onclick="qrlogin()" class="btn btn-success btn-block">我已完成登录</button>
                        </div>

                        <?php if($conf['login_qq']==1){?>
                        <div class="alert alert-info" style="margin-top: 20px;">
                           <b>提示：</b>只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不支持该方式。
                        </div>
                        <?php }?>
                         <div class="link-group">
                            <a href="login.php">返回登录</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== 移动端布局 (保持原样，作为PC端的数据源) ==================== -->
    <div class="mobile-layout">
        <div class="fui-page-group statusbar" style="max-width: 650px;left: auto;">
            <div class="fui-modal popup-modal in">
                <div class="account-layer login" style="max-height:unset;margin:-13rem 0 0 -7.75rem;">
                    <div class="account-main">
                        <div class="account-back" onclick="goback()"><i class="icon icon-back"></i></div>
                        <div class="account-title">找　回　密　码</div>
                        <div class="form-group" style="text-align: center;">
                            <br/><span id="loginmsg" style="font-weight: bold;">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span><br/><br/>
                            <div id="qrimg"></div><br>
                            <div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="btn btn-warning btn-block">跳转QQ快捷登录</button><br/><button type="button" onclick="qrlogin()" class="btn btn-success btn-block">我已完成登录</button></div>
                        </div>
                    </div>
                    <?php if($conf['login_qq']==1){?><center><div class="alert alert-info" style="position:unset;width:90%;">提示：只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不支持该方式找回密码。</div></center><?php }?>
                    <div style="text-align:center;"><a href="javascript:goback();" class="">返回</a></div>
                    <br/>
                </div>
            </div>
        </div>
        <div class="fui-navbar" style="z-index: 100000;max-width: 650px;">
            <a href="../" class="nav-item"> <span class="icon icon-home"></span> <span class="label">首页</span> </a>
            <a href="../?mod=query" class="nav-item"> <span class="icon icon-dingdan1"></span> <span class="label">订单</span> </a>
            <a href="../?mod=cart" class="nav-item " <?php if($conf['shoppingcart']==0){?>style="display:none"<?php }?>> <span class="icon icon-cart2"></span> <span class="label">购物车</span> </a>
            <a href="../?mod=kf" class="nav-item"> <span class=" icon icon-service1"></span> <span class="label">客服</span> </a>
            <a href="./" class="nav-item active"> <span class="icon icon-person2"></span> <span class="label">会员中心</span> </a>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/qrlogin.js?ver=<?php echo VERSION ?>"></script>
<script>
function goback(){
    document.referrer === '' ? window.location.href = '/' : window.history.go(-1);
}

$(document).ready(function() {
    // 仅在PC端执行的逻辑，用于同步二维码和状态信息
    if (window.innerWidth > 768) {
        // 创建一个MutationObserver来监视移动端DOM的变化
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                // 同步二维码图片
                var qrImgContent = $('#qrimg').html();
                if (qrImgContent && $('#qrimg_pc').html() !== qrImgContent) {
                    $('#qrimg_pc').html(qrImgContent);
                }
                
                // 同步登录信息
                $('#loginmsg_pc').html($('#loginmsg').html());
                $('#loginload_pc').html($('#loginload').html());
                
                // 同步手机端按钮的显示状态
                if ($('#mobile').is(':visible')) {
                    $('#mobile_pc').show();
                } else {
                    $('#mobile_pc').hide();
                }
            });
        });

        // 配置observer监视的变动类型
        var config = { attributes: true, childList: true, subtree: true };

        // 指定要监视的节点 (移动端布局的根节点)
        var targetNode = document.querySelector('.mobile-layout');
        if(targetNode) {
             // 开始监视
            observer.observe(targetNode, config);
        }
    }
});
</script>
</body>
</html>
