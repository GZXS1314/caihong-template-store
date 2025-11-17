<?php
if (!defined('IN_CRONLITE')) die();

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
// 获取订单信息
$orderid = trim(daddslashes($_GET['orderid']));
$row = $DB->getRow("select * from pre_pay where trade_no='$orderid' limit 1");
if (!$row) sysmsg('当前订单不存在');

// 移动端适配
if ($row['status'] != 1 && checkmobile()) {
    include_once TEMPLATE_ROOT . 'store/orderm.php';
    exit;
}
if ($row['status'] == 1) {
    exit("<script language='javascript'>alert('当前订单已完成支付！');window.location.href='./?buyok=1';</script>");
}

// 获取商品信息
$tool = $DB->getRow("SELECT A.*,B.blockpay FROM pre_tools A LEFT JOIN pre_class B ON A.cid=B.cid WHERE tid='{$row['tid']}' LIMIT 1");
$isfaka = ($tool['is_curl'] == 4);

// 处理下单输入信息展示
$input_orig = $tool['input'] ?: '下单账号';
$inputs = explode('|', $tool['inputs']);
$inputsdata = explode('|', $row['input']);
$show_details = [];
$show_details[$input_orig] = $inputsdata[0];
$i = 1;
foreach ($inputs as $input_item) {
    if (!$input_item) continue;
    $input_name = preg_replace('/\{.*?\}|$.*?$/', '', $input_item);
    $show_details[$input_name] = (stripos($input_name, '密码') === false ? $inputsdata[$i] : '******');
    $i++;
}
if (isset($_GET['set'])) {
    $show_details = ['提示' => '<span class="text-warning">出于隐私保护，下单信息已隐藏</span>'];
}

// 处理价格和用户等级
$level = '<span class="level-tag user">普通用户</span>';
if ($islogin2 == 1) {
    if ($userrow['power'] == 2) {
        $level = '<span class="level-tag agent-pro">高级代理</span>';
    } elseif ($userrow['power'] == 1) {
        $level = '<span class="level-tag agent">普通代理</span>';
    }
}

// 处理支付方式
$share_link = '我钱不够买这个东西，能够帮我买一下嘛~，这是付款订单,谢谢啦 ' . $siteurl . '?mod=order&orderid=' . $orderid . '&set';
if ($conf['forcermb'] == 1) {
    $conf['alipay_api'] = 0; $conf['wxpay_api'] = 0; $conf['qqpay_api'] = 0;
}
if (!empty($tool['blockpay'])) {
    $blockpay = explode(',', $tool['blockpay']);
    if (in_array('alipay', $blockpay)) $conf['alipay_api'] = 0;
    if (in_array('qqpay', $blockpay)) $conf['qqpay_api'] = 0;
    if (in_array('wxpay', $blockpay)) $conf['wxpay_api'] = 0;
}

// 复用左侧分类栏逻辑
list($background_image, $background_css) = \lib\Template::getBackground();
$ar_data = [];
$classhide = ($is_fenzhan && !empty($siterow['class'])) ? explode(',', $siterow['class']) : [];
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
while ($res = $re->fetch()) {
    if ($is_fenzhan && in_array($res['cid'], $classhide)) continue;
    $ar_data[] = $res;
}
// -------------------- 数据处理结束 --------------------
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title><?php echo '确认订单 - ' . $conf['sitename'] ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>">
    
    <!-- 加载公共CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link href="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">

<style type="text/css">
/* ==================== 移动端适配 (由orderm.php处理) ==================== */
@media screen and (max-width: 991px) {
    .pc-layout { display: none !important; }
    .mobile-placeholder { display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center; font-size: 16px; color: #888; }
}

/* ==================== PC端全新订单页样式 (>=992px) ==================== */
@media screen and (min-width: 992px) {
    :root { --theme-color: #2563eb; --theme-color-light: #eff6ff; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { width: 100%; height: 100%; font-size: 14px; overflow: hidden; background: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    
    .pc-left-sidebar { -ms-overflow-style: none; scrollbar-width: none; }
    .pc-left-sidebar::-webkit-scrollbar { display: none; }

    .pc-top-navbar-wrapper { position: fixed; top: 0; left: 0; right: 0; padding: 20px; z-index: 1000; background: transparent; }
    .pc-top-navbar { max-width: 1400px; margin: 0 auto; height: 70px; background: rgba(255, 255, 255, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
    .pc-nav-logo { font-size: 24px; font-weight: 600; color: #000; text-decoration: none; letter-spacing: -0.5px; margin-right: auto; }
    .pc-nav-items { display: flex; gap: 8px; margin-left: auto; }
    .pc-nav-item { padding: 10px 24px; background: #f3f4f6; color: #333; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; gap: 6px; }
    .pc-nav-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); background: #e5e7eb; }
    .pc-nav-item.active { background: var(--theme-color); color: #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); }

    .pc-main-container { position: fixed; top: 110px; left: 20px; right: 20px; bottom: 20px; display: flex; max-width: 1400px; margin: 0 auto; gap: 20px; }
    .pc-left-sidebar { width: 260px; flex-shrink: 0; background: rgba(255, 255, 255, 0.7); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); border-radius: 20px; padding: 20px; overflow-y: auto; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); }
    .pc-category-title { font-size: 18px; font-weight: 600; color: #000; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
    .pc-category-item { display: flex; align-items: center; padding: 12px 15px; margin-bottom: 8px; background: rgba(255, 255, 255, 0.6); border-radius: 12px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; color: #333; }
    .pc-category-item:hover { background: rgba(0, 0, 0, 0.05); transform: translateX(5px); }
    .pc-category-icon { width: 40px; height: 40px; border-radius: 10px; margin-right: 12px; object-fit: cover; }
    .pc-category-name { font-size: 14px; font-weight: 500; }

    .pc-right-content-wrapper { flex-grow: 1; display: flex; gap: 20px; overflow: hidden; }
    .content-main { flex-grow: 1; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; padding-right: 10px; }
    .content-sidebar { width: 320px; flex-shrink: 0; }
    .order-card { background: #fff; border-radius: 20px; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); padding: 25px 30px; }
    .card-header { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px; }
    
    .product-info-card { display: flex; align-items: center; gap: 20px; }
    .product-info-card img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; cursor: pointer; }
    .product-details { flex-grow: 1; }
    .product-details h3 { margin: 0 0 8px 0; font-size: 1.2em; font-weight: 600; }
    .product-tags { display: flex; gap: 8px; font-size: 12px; }
    .level-tag { padding: 3px 8px; border-radius: 6px; color: #fff; }
    .level-tag.user { background: #10b981; } .level-tag.agent { background: #f59e0b; } .level-tag.agent-pro { background: #ef4444; } .delivery-tag { background: #3b82f6; }

    .order-details-table { width: 100%; font-size: 14px; }
    .order-details-table tr { border-bottom: 1px solid #f5f5f5; }
    .order-details-table tr:last-child { border-bottom: none; }
    .order-details-table td { padding: 12px 0; }
    .order-details-table td:first-child { color: #888; width: 100px; }
    .order-details-table td:last-child { text-align: right; font-weight: 500; color: #333; }

    /* 全新支付方式UI */
    .payment-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .payment-options-grid label { position: relative; display: flex; align-items: center; padding: 15px; border: 1px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.2s ease; }
    .payment-options-grid label:hover { border-color: var(--theme-color); background: var(--theme-color-light); }
    /* 这次的关键：直接隐藏原生input，让label作为点击目标 */
    .payment-options-grid input[type="radio"] { display: none; } 
    /* 当radio被选中时，让它相邻的label元素改变样式 */
    .payment-options-grid input[type="radio"]:checked + label { border-color: var(--theme-color); background: var(--theme-color-light); box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2); }
    .payment-options-grid input[type="radio"]:checked + label::after {
        content: '选中'; font-family: FontAwesome; font-size: 9px; color: #fff;
        position: absolute; top: -1px; right: -1px; width: 24px; height: 24px;
        background: var(--theme-color); border-radius: 0 11px 0 11px;
        display: flex; justify-content: center; align-items: center;
    }
    .payment-options-grid .pay-icon { width: 24px; margin-right: 12px; }
    .payment-options-grid .pay-title { font-weight: 500; }
    .payment-options-grid .pay-balance { margin-left: auto; font-size: 12px; color: #888; }
    
    .summary-card .price-item { display: flex; justify-content: space-between; padding: 8px 0; }
    .summary-card .price-item.total { font-size: 1.3em; font-weight: bold; padding-top: 15px; margin-top: 10px; border-top: 1px solid #f0f0f0; }
    .summary-card .price-item.total span:last-child { color: #ef4444; }
    #dopay { width: 100%; height: 50px; border: none; border-radius: 12px; background: var(--theme-color); color: #fff; font-size: 16px; font-weight: bold; cursor: pointer; transition: all 0.3s ease; margin-top: 20px; }
    #dopay:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); }

    .mobile-placeholder { display: none; }
    .pc-layout { display: block !important; }
}
</style>
</head>
<?php echo str_replace('body', 'html', $background_css) ?>
<body>
<div id="body">
    <!-- ==================== PC端布局 ==================== -->
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper">
            <div class="pc-top-navbar">
                <a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a>
                <div class="pc-nav-items">
                    <a href="./" class="pc-nav-item"><span class="icon icon-home"></span><span>首页</span></a>
                    <a href="./?mod=query" class="pc-nav-item active"><span class="icon icon-dingdan1"></span><span>订单查询</span></a>
                    <?php if ($conf['shoppingcart'] != 0) { ?><a href="./?mod=cart" class="pc-nav-item"><span class="icon icon-cart2"></span><span>购物车</span></a><?php } ?>
                    <a href="./?mod=kf" class="pc-nav-item"><span class="icon icon-service1"></span><span>联系客服</span></a>
                    <a href="./user/" class="pc-nav-item"><span class="icon icon-person2"></span><span>会员中心</span></a>
                </div>
            </div>
        </div>
        <div class="pc-main-container">
            <div class="pc-right-content-wrapper">
                <!-- 中间主体 -->
                <main class="content-main">
                    <div class="order-card">
                        <div class="card-header"><i class="fa fa-shopping-basket"></i> 商品信息</div>
                        <div class="product-info-card" id="layer-photos-demo">
                            <img layer-src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/store/picture/error_img.png' ?>" src="<?php echo $tool['shopimg'] ?: 'assets/store/picture/error_img.png' ?>" alt="<?php echo $tool['name'] ?>">
                            <div class="product-details">
                                <h3><?php echo $tool['name'] ?></h3>
                                <div class="product-tags">
                                    <?php echo $level; ?>
                                    <span class="level-tag delivery-tag"><?php echo $isfaka ? '自动发卡' : '自动发货' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="card-header"><i class="fa fa-list-alt"></i> 您的下单信息</div>
                        <table class="order-details-table">
                            <tbody>
                            <?php foreach ($show_details as $key => $value): ?>
                            <tr><td><?php echo htmlspecialchars($key); ?></td><td><?php echo $value; ?></td></tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="order-card">
                        <div class="card-header"><i class="fa fa-credit-card"></i> 选择支付方式</div>
                        <!-- 支付模块 HTML 结构 -->
                        <div class="payment-options-grid">
                            <?php $first_pay_method_set = false; ?>
                            <?php if($islogin2==1 && $userrow['rmb'] > 0){ ?>
                                <div><input type="radio" name="pay" id="pay_rmb" value="rmb" <?php if(!$first_pay_method_set){ echo 'checked'; $first_pay_method_set = true; } ?>><label for="pay_rmb"><img class="pay-icon" src="./assets/img/rmb.png"><span class="pay-title">余额</span><span class="pay-balance">剩 <?php echo $userrow['rmb']?> 元</span></label></div>
                            <?php } ?>
                            <?php if($conf['alipay_api'] != 0){ ?>
                                <div><input type="radio" name="pay" id="pay_alipay" value="alipay" <?php if(!$first_pay_method_set){ echo 'checked'; $first_pay_method_set = true; } ?>><label for="pay_alipay"><img class="pay-icon" src="./assets/img/alipay.png"><span class="pay-title">支付宝</span></label></div>
                            <?php } ?>
                            <?php if($conf['wxpay_api'] != 0){ ?>
                                <div><input type="radio" name="pay" id="pay_wxpay" value="wxpay" <?php if(!$first_pay_method_set){ echo 'checked'; $first_pay_method_set = true; } ?>><label for="pay_wxpay"><img class="pay-icon" src="./assets/img/wxpay.png"><span class="pay-title">微信</span></label></div>
                            <?php } ?>
                            <?php if($conf['qqpay_api'] != 0){ ?>
                                <div><input type="radio" name="pay" id="pay_qqpay" value="qqpay" <?php if(!$first_pay_method_set){ echo 'checked'; $first_pay_method_set = true; } ?>><label for="pay_qqpay"><img class="pay-icon" src="./assets/img/qqpay.png"><span class="pay-title">QQ钱包</span></label></div>
                            <?php } ?>
                            <div id="demo_url" data-url="<?php echo $share_link ?>"><input type="radio" name="pay" id="pay_help" value="help" <?php if(!$first_pay_method_set){ echo 'checked'; $first_pay_method_set = true; } ?>><label for="pay_help"><img class="pay-icon" src="./assets/img/payd.png"><span class="pay-title">朋友代付</span></label></div>
                        </div>
                    </div>
                </main>
                <!-- 右侧固定栏 -->
                <aside class="content-sidebar">
                    <div class="order-card summary-card">
                        <div class="card-header"><i class="fa fa-file-text-o"></i> 订单总览</div>
                        <div class="price-item">
                            <span>商品单价</span>
                            <span>¥ <?php echo number_format($row['money'] / $row['num'], 2, '.', ''); ?></span>
                        </div>
                        <div class="price-item">
                            <span>购买数量</span>
                            <span>x <?php echo $row['num']; ?></span>
                        </div>
                        <div class="price-item total">
                            <span>应付总额</span>
                            <span>¥ <?php echo $row['money']; ?></span>
                        </div>
                        <input type="hidden" id="orderid" value="<?php echo $orderid ?>">
                        <input type="hidden" id="tid" value="<?php echo $row['tid'] ?>">
                        <button id="dopay" class="layui-btn">立即支付</button>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <!-- ==================== 移动端占位提示 ==================== -->
    <div class="mobile-placeholder">
        <p>页面加载中，请稍候...<br/>如果长时间未响应，请尝试刷新页面。</p>
    </div>
</div>

<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<!-- 引入原始的、可靠的order.js -->
<script src="assets/store/js/order.js?ver=<?php echo VERSION ?>"></script>
<script>
$(document).ready(function(){
    if (window.innerWidth <= 991) {
        // 移动端跳转逻辑
        $('.mobile-placeholder').html('正在跳转到移动版页面...如果没有反应，请<a href="?orderid=<?php echo $orderid; ?>&p=1">点击这里</a>');
        if (!/p=1/.test(window.location.href)) {
             window.location.href = '?orderid=<?php echo $orderid; ?>&p=1';
        }
    } else {
        // PC端初始化
        layer.photos({
            photos: '#layer-photos-demo',
            anim: 5
        });
        layer.tips('点击图片查看大图', '#layer-photos-demo img', {
            tips: [1, '#2563eb']
        });
    }
});
</script>
</body>
</html>
