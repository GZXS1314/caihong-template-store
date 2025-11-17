<?php
if (!defined('IN_CRONLITE')) die();

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退单</font>';
	else
		return '<font color=blue>待处理</font>';
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
if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)==17 && is_numeric($data))
	{
	   $sql=" A.tradeno='{$data}'"; 
	}else{
	   $sql=" A.input='{$data}'";
	}
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
}
else $sql=" A.userid='{$cookiesid}'";

$q_status=isset($_GET['status'])?trim(daddslashes($_GET['status'])):"";
if(isset($q_status) && $q_status != ""){
	$qu_status = intval($q_status);
	$sql .= " AND A.status = '{$qu_status}'";
}
$limit = 10;
$start = $limit * ($page-1);

$total = $DB->getColumn("SELECT count(*) FROM `pre_orders` A WHERE{$sql} ");
$total_page = ceil($total/$limit);
$sql = "SELECT A.*,B.`name`,B.`shopimg` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
while($res = $rs->fetch()){
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'money'=>$res['money'],'name'=>$res['name'],'shopimg'=>$res['shopimg'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $conf['sitename'].($conf['title']==''?'':' - '.$conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic ?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <script src="<?php echo $cdnpublic ?>modernizr/2.8.3/modernizr.min.js"></script>

<style>
/* ==================== 移动端样式 ==================== */
@media screen and (max-width: 768px) {
    html {
        font-size: 20px;
    }
    
    body {
        width: 100%;
        max-width: 650px;
        margin: auto;
    }
    
    .fix-iphonex-bottom {
        padding-bottom: 34px;
    }
    
    .fui-tab.fui-tab-primary a.active {
        color: #1492fb;
        border-color: #1492fb;
    }

    .qt-header {
        height: 10vh;
        background: #11998e;
        background: -webkit-linear-gradient(to right, #38ef7d, #11998e);
        background: linear-gradient(to right, #38ef7d, #11998e);
        line-height: 10vh;
    }

    .qt-header > input {
        height: 5vh;
        width: 100%;
        border: none;
        text-indent: 2.5em;
        line-height: 5vh;
        border-radius: 0.5em;
        font-size: 0.7rem;
    }

    .qt-header > span {
        position: absolute;
        margin-left: 0.6rem;
        font-size: 0.7rem;
    }

    .qt-card {
        box-shadow: 0px 0px 6px #eee;
        border-radius: 0.5em;
    }

    .qt-card img {
        width: 6em;
        max-width: 100%;
        height: 6em;
        border-radius: 0.5em;
        box-shadow: 3px 3px 16px #eee;
    }

    .qt-btn {
        border-radius: 0.5em;
        border: solid 1px #eee;
    }
    
    .pc-layout {
        display: none !important;
    }
    
    .mobile-layout {
        display: block !important;
    }
    
    document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";
}

/* ==================== PC端样式（iOS风格） ==================== */
@media screen and (min-width: 769px) {
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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
    
    /* 顶部导航栏 */
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
    }
    
    .pc-nav-items {
        display: flex;
        gap: 8px;
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
    }
    
    /* 主容器 */
    .pc-main-container {
        position: fixed;
        top: 110px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    /* 搜索栏 */
    .pc-search-section {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    .pc-search-form {
        display: flex;
        gap: 12px;
    }
    
    .pc-search-input {
        flex: 1;
        padding: 14px 20px;
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s;
    }
    
    .pc-search-input:focus {
        outline: none;
        border-color: #000;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
    }
    
    .pc-search-btn {
        padding: 14px 36px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pc-search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        background: #333;
    }
    
    /* 标签栏 */
    .pc-tabs-section {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    .pc-tabs {
        display: flex;
        gap: 12px;
    }
    
    .pc-tab-item {
        padding: 10px 24px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .pc-tab-item:hover {
        background: rgba(0, 0, 0, 0.05);
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }
    
    .pc-tab-item.active {
        background: #000;
        color: #fff;
        border-color: #000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* 订单列表区域 */
    .pc-orders-section {
        flex: 1;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-radius: 20px;
        padding: 20px;
        overflow-y: auto;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }
    
    .pc-orders-section::-webkit-scrollbar {
        width: 8px;
    }
    
    .pc-orders-section::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .pc-orders-section::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
    
    .pc-orders-section::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }
    
    /* 订单卡片 */
    .pc-order-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pc-order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .pc-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
    }
    
    .pc-order-title {
        font-size: 16px;
        font-weight: 600;
        color: #000;
        max-width: 70%;
    }
    
    .pc-order-status {
        font-size: 14px;
        font-weight: 500;
    }
    
    .pc-order-body {
        display: flex;
        gap: 20px;
    }
    
    .pc-order-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }
    
    .pc-order-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }
    
    .pc-order-info-item {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }
    
    .pc-order-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .pc-order-btn {
        padding: 10px 20px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    
    .pc-order-btn:hover {
        background: #333;
        transform: scale(1.05);
    }
    
    .pc-order-btn.secondary {
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        border: 2px solid rgba(0, 0, 0, 0.08);
    }
    
    .pc-order-btn.secondary:hover {
        background: rgba(0, 0, 0, 0.05);
        border-color: rgba(0, 0, 0, 0.15);
    }
    
    .pc-order-btn.danger {
        background: #ff3b30;
    }
    
    .pc-order-btn.danger:hover {
        background: #ff2d1f;
    }
    
    /* 分页 */
    .pc-pagination {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    
    .pc-page-btn {
        padding: 10px 24px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pc-page-btn:hover {
        background: #333;
        transform: translateY(-2px);
    }
    
    /* 空状态 */
    .pc-empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .pc-empty-state img {
        width: 200px;
        margin-bottom: 20px;
        opacity: 0.8;
    }
    
    .pc-empty-state p {
        color: #999;
        font-size: 16px;
        margin-bottom: 20px;
    }
    
    .pc-empty-btn {
        padding: 12px 32px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-block;
    }
    
    .pc-empty-btn:hover {
        background: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    /* 说明按钮 */
    .pc-help-btn {
        position: fixed;
        right: 30px;
        bottom: 30px;
        width: 60px;
        height: 60px;
        background: #ff3b30;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(255, 59, 48, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 999;
        text-align: center;
        line-height: 1.2;
    }
    
    .pc-help-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(255, 59, 48, 0.4);
    }
    
    .mobile-layout {
        display: none !important;
    }
}

/* ==================== 通用样式 ==================== */
td.stitle {
    max-width: 380px;
}

td.wbreak {
    max-width: 420px;
    word-break: break-all;
}

#orderItem .orderTitle {
    word-break: keep-all;
}

#orderItem .orderContent {
    word-break: break-all;
}

#orderItem .btn {
    height: 100%;
    margin: 0;
}

#orderItem .orderContent img {
    max-width: 100%;
}

a, a:focus, a:hover, a:active {
    outline: none;
    text-decoration: none;
}

.btn.btn-primary-o {
    color: #1492fb;
    border: 1px solid #1492fb;
}

.elevator_item {
    position: fixed;
    right: 5px;
    bottom: 95px;
    z-index: 11;
}

.elevator_item .feedback {
    width: 36px;
    font-size: 12px;
    padding: 5px 6px;
    display: block;
    border-radius: 5px;
    text-align: center;
    margin-top: 10px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .35);
    cursor: pointer;
}

.graHover {
    position: relative;
    overflow: hidden;
}

.toplan div .choose {
    border-radius: .3rem;
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
</style>

<?php echo str_replace('body','html',$background_css)?>
</head>

<body>
<div id="body">
    <!-- ==================== PC端布局 ==================== -->
    <div class="pc-layout">
        <!-- 顶部导航栏 -->
        <div class="pc-top-navbar-wrapper">
            <div class="pc-top-navbar">
                <a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a>
                <div class="pc-nav-items">
                    <a href="./" class="pc-nav-item">
                        <span class="icon icon-home"></span>
                        <span>首页</span>
                    </a>
                    <a href="./?mod=query" class="pc-nav-item active">
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
        
        <!-- 主容器 -->
        <div class="pc-main-container">
            <!-- 搜索栏 -->
            <div class="pc-search-section">
                <div class="pc-search-form">
                    <input type="hidden" id="page" value="<?php echo $page?>">
                    <input type="hidden" id="q_status" value="<?php echo $q_status?>">
                    <input type="search" id="query" onkeydown="if(event.keyCode==13){OrderQuery()}" class="pc-search-input" value="<?php echo $data; ?>" placeholder="输入订单号或下单账号查询...">
                    <button class="pc-search-btn" onclick="OrderQuery();">搜索</button>
                </div>
            </div>
            
            <!-- 标签栏 -->
            <div class="pc-tabs-section">
                <div class="pc-tabs">
                    <a href="?mod=query&data=<?php echo $data; ?>" class="pc-tab-item <?php if(isset($q_status) && $q_status === ""){echo "active";} ?>">全部</a>
                    <a href="?mod=query&status=0&data=<?php echo $data; ?>" class="pc-tab-item <?php if($q_status === '0'){echo "active";} ?>">待处理</a>
                    <a href="?mod=query&status=2&data=<?php echo $data; ?>" class="pc-tab-item <?php if($q_status === '2'){echo "active";} ?>">处理中</a>
                    <a href="?mod=query&status=1&data=<?php echo $data; ?>" class="pc-tab-item <?php if($q_status === '1'){echo "active";} ?>">已完成</a>
                    <a href="?mod=query&status=4&data=<?php echo $data; ?>" class="pc-tab-item <?php if($q_status === '4'){echo "active";} ?>">已退单</a>
                </div>
            </div>
            
            <!-- 订单列表 -->
            <div class="pc-orders-section">
                <?php if($record){ ?>
                    <?php foreach($record as $row){?>
                    <div class="pc-order-card">
                        <div class="pc-order-header">
                            <div class="pc-order-title"><?php echo $row['name']?></div>
                            <div class="pc-order-status"><?php echo display_zt($row['status'])?></div>
                        </div>
                        <div class="pc-order-body">
                            <a href="?mod=buy&tid=<?php echo $row['tid']?>">
                                <img src="<?php echo $row['shopimg']?>" class="pc-order-image" onerror="this.src='assets/store/picture/error_img.png'">
                            </a>
                            <div class="pc-order-info">
                                <div class="pc-order-info-item">下单时间：<?php echo $row['addtime']?></div>
                                <div class="pc-order-info-item">商品总价：<?php echo $row['money']?>元</div>
                            </div>
                            <div class="pc-order-actions">
                                <button class="pc-order-btn secondary xiangqing" data-id="<?php echo $row['id']?>" data-skey="<?php echo $row['skey']?>" onclick="showOrder(<?php echo $row['id']?>,'<?php echo $row['skey']?>')">
                                    查看详情
                                </button>
                                <?php if($row['djzt'] == 3){ ?>
                                <button class="pc-order-btn danger" onclick="window.location.href='?mod=faka&id=<?php echo $row['id']?>&skey=<?php echo $row['skey']?>'">
                                    提取卡密
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    
                    <!-- 分页 -->
                    <div class="pc-pagination">
                        <?php if($page>1){?>
                        <button class="pc-page-btn" onclick="LastPage()">上一页</button>
                        <?php } else { ?>
                        <div></div>
                        <?php } ?>
                        
                        <?php if($total_page!=$page){?>
                        <button class="pc-page-btn" onclick="NextPage()">下一页</button>
                        <?php } ?>
                    </div>
                <?php }else{ ?>
                    <div class="pc-empty-state">
                        <img src="./assets/store/picture/nolist.png" alt="空状态">
                        <?php if($_GET['data']){ ?>
                            <p>没有查询到相关订单</p>
                        <?php }else{ ?>
                            <p>您暂时没有任何订单哦！</p>
                            <a href="./" class="pc-empty-btn">去首页逛逛吧</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <!-- 帮助按钮 -->
        <?php if($record){ ?>
        <div class="pc-help-btn" onclick="$('.tzgg').show()">
            订单<br>说明
        </div>
        <?php } ?>
    </div>
    
    <!-- ==================== 移动端布局 ==================== -->
    <div class="mobile-layout">
        <div style="width: 100%;max-width: 650px">
            <div class="fui-page-group statusbar" style="max-width: 650px;left: auto;overflow: hidden;">
                <div class="layui-row layui-col-space6">
                    <div class="layui-card" style="background-color: unset;box-shadow: unset;">
                        <div class="fui-searchbar bar">
                            <div class="searchbar center searchbar-active" style="padding-right:50px">
                                <input type="hidden" id="page_mobile" value="<?php echo $page?>">
                                <input type="hidden" id="q_status_mobile" value="<?php echo $q_status?>">
                                <input type="button" class="searchbar-cancel searchbtn" value="搜索" onclick="OrderQuery();">
                                <div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;margin-right: 5px">
                                    <i class="icon icon-search"></i>
                                    <input type="search" id="query_mobile" onkeydown="if(event.keyCode==13){OrderQuery()}" class="search" value="<?php echo $data; ?>" placeholder="输入下单账号..">
                                </div>
                            </div>
                        </div>

                        <div id="tab" class="fui-tab fui-tab-danger">
                            <a data-tab="tab" class="external <?php if(isset($q_status) && $q_status === ""){echo "active";} ?>" onclick="window.location.href='?mod=query&data=<?php echo $data; ?>'">全部</a>
                            <a data-tab="tab0" class="external <?php if($q_status === '0'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=0&data=<?php echo $data; ?>'">待处理</a>
                            <a data-tab="tab1" class="external <?php if($q_status === '2'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=2&data=<?php echo $data; ?>'">处理中</a>
                            <a data-tab="tab2" class="external <?php if($q_status === '1'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=1&data=<?php echo $data; ?>'">已完成</a>
                            <a data-tab="tab3" class="external <?php if($q_status === '4'){echo "active";} ?>" onclick="window.location.href='?mod=query&status=4&data=<?php echo $data; ?>'">已退单</a>
                        </div>

                        <?php if($record){ ?>
                        <div class="elevator_item" id="elevator_item" style="display:block;">
                            <a class="feedback graHover" style="background-color: #FF3399;color:#fff;" onclick="$('.tzgg').show()" rel="nofollow">订<br/>单<br/>状<br/>态<br/>说<br/>明</a>
                        </div>

                        <div class="layui-card-body" style="padding: 1em;padding-bottom: 3em;overflow:hidden;overflow-y: auto;height: 80vh;">
                            <div class="layui-tab-item layui-show" id="order_all">
                                <?php foreach($record as $row){?>
                                <div class="layui-card qt-card">
                                    <div class="layui-card-header">
                                        <p style="width: 70%" class="layui-elip"><?php echo $row['name']?></p>
                                        <span class="layui-layout-right layui-elip" style="width:30%;text-align: right;margin-right: 0.5em">
                                            <?php echo display_zt($row['status'])?>
                                        </span>
                                    </div>
                                    <div class="layui-card-body">
                                        <div class="layui-row layui-col-space10">
                                            <div class="layui-col-xs4">
                                                <a href="?mod=buy&tid=<?php echo $row['tid']?>">
                                                    <img src="<?php echo $row['shopimg']?>" onerror="this.src='assets/store/picture/error_img.png'">
                                                </a>
                                            </div>
                                            <div class="layui-col-xs8" style="font-size: 0.8em;color:black;font-family: '微软雅黑'">
                                                下单时间：<?php echo $row['addtime']?><br>
                                                商品总价：<?php echo $row['money']?>元<br>
                                            </div>
                                            <div style="width: 100%;text-align: right" class="showorders">
                                                <button class="layui-btn qt-btn layui-btn-sm layui-btn-primary xiangqing" data-id="<?php echo $row['id']?>" data-skey="<?php echo $row['skey']?>" onclick="showOrder(<?php echo $row['id']?>,'<?php echo $row['skey']?>')">
                                                    查看详情
                                                </button>
                                                <?php if($row['djzt'] == 3){ ?>
                                                <button class="layui-btn qt-btn layui-btn-sm layui-btn-danger" onclick="window.location.href='?mod=faka&id=<?php echo $row['id']?>&skey=<?php echo $row['skey']?>'">
                                                    提取卡密
                                                </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="layui-tab-item layui-show" id="order_all" style="margin-top: 5px;">
                                <?php if($page>1){?>
                                <button class="layui-btn layui-btn-sm layui-btn-normal" onclick="LastPage()">上一页</button>
                                <?php }
                                if($total_page!=$page){?>
                                <button class="layui-btn layui-btn-sm layui-btn-normal pull-right" onclick="NextPage()">下一页</button>
                                <?php }?>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <div class="fui-content navbar order-list">
                            <div class="fui-content-inner">
                                <div class="content-empty">
                                    <img src="./assets/store/picture/nolist.png" style="width: 6rem;margin-bottom: .5rem;"><br>
                                    <?php if($_GET['data']){ ?>
                                        <p style="color: #999;font-size: .75rem">没有查询到数据</p>
                                    <?php }else{ ?>
                                        <p style="color: #999;font-size: .75rem">您暂时没有任何订单哦！</p>
                                        <br>
                                        <a href="./" class="btn btn-sm btn-primary-o" style="border-radius: 100px; height: 1.9rem; line-height: 1.4rem; width: 7rem; font-size: 0.75rem;">去首页逛逛吧</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>	
                        <?php } ?>
                    </div>
                </div>

                <div class="fui-navbar" style="max-width: 650px;z-index: 100;">
                    <a href="./" class="nav-item"> 
                        <span class="icon icon-home"></span> 
                        <span class="label" style="color:#999;">首页</span>
                    </a>
                    <a href="./?mod=query" class="nav-item active"> 
                        <span class="icon icon-dingdan1"></span> 
                        <span class="label" style="color:#999;">订单</span> 
                    </a>
                    <?php if($conf['shoppingcart']!=0){?>
                    <a href="./?mod=cart" class="nav-item"> 
                        <span class="icon icon-cart2"></span> 
                        <span class="label" style="color:#999;">购物车</span> 
                    </a>
                    <?php }?>
                    <a href="./?mod=kf" class="nav-item"> 
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

    <!-- 公告弹窗 -->
    <div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000" class="tzgg"></div>
    <div class="tzgg" type="text/html" style="display: none">
        <div class="account-layer">
            <div class="account-main">
                <div class="account-title">订单状态说明</div>
                <div class="account-verify">
                    <?php echo $conf['gg_search'] ?>
                </div>
                <div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">确认</div>
                <div class="account-close" onclick="$('.tzgg').hide()">
                    <i class="icon icon-guanbi1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnserver ?>assets/store/js/query.js"></script>
<script>
// 移动端字体大小适配
if(window.innerWidth <= 768) {
    document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";
}

// 统一查询函数，兼容PC和移动端
function OrderQuery() {
    var query = window.innerWidth > 768 ? $('#query').val() : $('#query_mobile').val();
    var q_status = window.innerWidth > 768 ? $('#q_status').val() : $('#q_status_mobile').val();
    var url = '?mod=query&data=' + encodeURIComponent(query);
    if(q_status !== '') {
        url += '&status=' + q_status;
    }
    window.location.href = url;
}

function LastPage() {
    var page = $('#page').length ? $('#page').val() : $('#page_mobile').val();
    var query = window.innerWidth > 768 ? $('#query').val() : $('#query_mobile').val();
    var q_status = window.innerWidth > 768 ? $('#q_status').val() : $('#q_status_mobile').val();
    var url = '?mod=query&page=' + (parseInt(page) - 1) + '&data=' + encodeURIComponent(query);
    if(q_status !== '') {
        url += '&status=' + q_status;
    }
    window.location.href = url;
}

function NextPage() {
    var page = $('#page').length ? $('#page').val() : $('#page_mobile').val();
    var query = window.innerWidth > 768 ? $('#query').val() : $('#query_mobile').val();
    var q_status = window.innerWidth > 768 ? $('#q_status').val() : $('#q_status_mobile').val();
    var url = '?mod=query&page=' + (parseInt(page) + 1) + '&data=' + encodeURIComponent(query);
    if(q_status !== '') {
        url += '&status=' + q_status;
    }
    window.location.href = url;
}
</script>
</body>
</html>
