<?php
if (!defined('IN_CRONLITE')) die();
$tid=intval($_GET['tid']);
$tool=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
if(!$tool)sysmsg('没有找到商品！');
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
$cid = $tool['cid']; // 当前商品所在分类ID
$ar_data = [];
$classhide = explode(',',$siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
while ($res = $re->fetch()) {
    if($is_fenzhan && in_array($res['cid'], $classhide))continue;
    $ar_data[] = $res;
}
// -----------------------------------------------------------------

function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') {
    $return = '';
    if (function_exists('mb_get_info')) {
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) {
            $str = mb_substr ( $string, $x, 1, $in_encoding );
            if (strlen ( $str ) > 1) { // 多字节字符
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) );
            } else {
                $return .= '%' . strtoupper ( bin2hex ( $str ) );
            }
        }
    }
    return $return;
}

$level = '<font color="#48d1cc">普通用户售价</font>';
if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	if($userrow['power']==2){
		$level = '<font color="orange">高级代理售价</font>';
	}elseif($userrow['power']==1){
		$level = '<font color="red">普通代理售价</font>';
	}
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}

if(isset($price_obj)){
	$price_obj->setToolInfo($tool['tid'],$tool);
	if($price_obj->getToolDel($tool['tid'])==1)sysmsg('商品已下架');
	$price=$price_obj->getToolPrice($tool['tid']);
	$islogin3=$islogin2;
	unset($islogin2);
	$price_pt=$price_obj->getToolPrice($tool['tid']);
	$price_1=$price_obj->getToolCost($tool['tid']);
	$price_2=$price_obj->getToolCost2($tool['tid']);
	$islogin2=$islogin3;
}else{
   $price=$tool['price'];
   $price_pt=$tool['price'];
   $price_1=$tool['cost1'];
   $price_2=$tool['cost2'];
}

if($tool['is_curl']==4){
	$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$tool['tid']}' and orderid=0");
	$fakainput = getFakaInput();
	$tool['input']=$fakainput;
	$isfaka = 1;
	$stock = '<span class="stock">库存:<span class="quota">'.$count.'</span>份</span>';
}elseif($tool['stock']!==null){
	$count = $tool['stock'];
	$isfaka = 1;
	$stock = '<span class="stock">库存:<span class="quota">'.$count.'</span>份</span>';
}else{
	$isfaka = 0;
    $stock = '';
}

if($tool['prices']){
	$arr = explode(',',$tool['prices']);
	if($arr[0]){
		$arr = explode('|',$tool['prices']);
		$view_mall = '<font color="#bdbdbd" size="2">购买'.$arr[0].'个以上按批发价￥'.($price-$arr[1]).'计算</font>';
	}
}

// 定义下单按钮状态
if($tool['active'] == 0){
    $button_msg = '商品已下架';
    $button_style = "background: #ccc; cursor: not-allowed;";
    $button_onclick = "return false;";
}else if($tool['close'] == 1){
    $button_msg = '商品维护中';
    $button_style = "background: #ccc; cursor: not-allowed;";
    $button_onclick = "return false;";
}else if($isfaka == 1 && $count==0){
    $button_msg = '商品已售罄';
    $button_style = "background: #ccc; cursor: not-allowed;";
    $button_onclick = "return false;";
}else{
    $button_msg = '立即购买';
    $button_style = ""; // 默认样式
    $button_onclick = "$('#submit_buy').click();";
}
$cart_button_onclick = str_replace('submit_buy', 'submit_cart_shop', $button_onclick);

$tool_desc = $tool['desc'] ? $tool['desc'] : '暂无商品说明';
$tool_shopimg = $tool['shopimg'] ? $tool['shopimg'] : 'assets/store/picture/error_img.png';
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <title><?php echo $tool['name']?> - <?php echo $conf['sitename']?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo strip_tags($tool['desc']) ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
    <link href="<?php echo $cdnpublic ?>limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">
    <link href="<?php echo $cdnpublic ?>animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/detail.css" media="screen and (max-width: 768px)">

<style>
@media screen and (max-width: 768px) {
    .pc-layout { display: none !important; }
    .mobile-layout { display: block !important; }
    html { font-size: 20px; }
    body { overflow: auto; height: auto !important; max-width: 650px; margin: auto; }
    .fui-page-group { max-width: 650px; left: auto; }
    .fui-page, .fui-page-group { -webkit-overflow-scrolling: auto; }
    .pro_content{background-image:linear-gradient(130deg,#00F5B2,#1FC3FF,#00dbde);height:120px;position:relative;margin-bottom:4rem;background-size:300%;animation:bganimation 10s infinite}@keyframes bganimation{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .hd_intro img { max-width: 100%; }
    .payment-method{position:fixed;bottom:0;background:white;width:100%;padding:.75rem .7rem;z-index:1000 !important}.payment-method .title{font-size:.75rem;text-align:center;color:#333;line-height:.75rem;margin-bottom:1rem}.payment-method .title span{height:.75rem;position:absolute;right:.3rem;width:2rem}.payment-method .title .close:before{font-family:'iconfont';content:'\e654';display:inline-block;transform:scale(1.5);color:#ccc}.payment-method .payment{display:flex;flex-wrap:nowrap;align-items:center;padding:.7rem 0}.payment-method .payment .paytext{flex:1;font-size:.8rem;color:#333}.payment-method button{margin-top:.8rem;background:#2e8cf0;color:white;letter-spacing:1px;font-size:.7rem;border:none;outline:none;width:17.25rem;height:1.75rem;border-radius:1.75rem}
}
@media screen and (min-width: 769px) {
    * { margin: 0; padding: 0; box-sizing: border-box; }
    .pc-left-sidebar, .pc-right-content { -ms-overflow-style: none; scrollbar-width: none; }
    .pc-left-sidebar::-webkit-scrollbar, .pc-right-content::-webkit-scrollbar { display: none; }
    html, body { width: 100%; height: 100%; font-size: 14px; overflow: hidden; }
    body { background: #f2f5f8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    .pc-top-navbar-wrapper{position:fixed;top:0;left:0;right:0;padding:20px;z-index:1000;background:transparent}
    .pc-top-navbar{max-width:1400px;margin:0 auto;height:70px;background:rgba(255,255,255,.8);backdrop-filter:saturate(180%) blur(20px);-webkit-backdrop-filter:saturate(180%) blur(20px);box-shadow:0 2px 16px rgba(0,0,0,.08);border-radius:20px;display:flex;align-items:center;justify-content:space-between;padding:0 30px}
    .pc-nav-logo{font-size:24px;font-weight:600;color:#000;text-decoration:none;letter-spacing:-.5px;margin-right:20px}
    .pc-nav-items{display:flex;gap:8px;margin-left:auto}
    .pc-nav-item{padding:10px 24px;background:#000;color:#fff;border-radius:12px;text-decoration:none;font-size:14px;font-weight:500;transition:all .3s cubic-bezier(.4,0,.2,1);display:flex;align-items:center;gap:6px}
    .pc-nav-item:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.2);background:#333}
    .pc-nav-item.active{background:#333;box-shadow:0 2px 8px rgba(0,0,0,.15)}
    .pc-main-container{position:fixed;top:110px;left:20px;right:20px;bottom:20px;display:flex;max-width:1400px;margin:0 auto;gap:20px}
    .pc-left-sidebar{width:260px;background:rgba(255,255,255,.7);backdrop-filter:saturate(180%) blur(20px);-webkit-backdrop-filter:saturate(180%) blur(20px);border-radius:20px;padding:20px;overflow-y:auto;box-shadow:0 2px 16px rgba(0,0,0,.08);flex-shrink:0}
    .pc-category-title{font-size:18px;font-weight:600;color:#000;margin-bottom:15px;padding-bottom:10px;border-bottom:1px solid rgba(0,0,0,.1)}
    .pc-category-item{display:flex;align-items:center;padding:12px 15px;margin-bottom:8px;background:rgba(255,255,255,.6);border-radius:12px;cursor:pointer;transition:all .3s cubic-bezier(.4,0,.2,1);text-decoration:none;color:#333}
    .pc-category-item:hover{background:rgba(0,0,0,.05);transform:translateX(5px)}
    .pc-category-item.active{background:#000;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.15)}
    .pc-category-icon{width:40px;height:40px;border-radius:10px;margin-right:12px;object-fit:cover}
    .pc-category-name{font-size:14px;font-weight:500}
    .mobile-layout { display: none !important; }
    .pc-layout { display: block !important; }
    .pc-detail-container { flex: 1; display: flex; gap: 20px; height: 100%; overflow: hidden; }
    .pc-detail-left { width: 50%; flex-shrink: 0; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; }
    .pc-detail-right { width: 50%; flex-shrink: 0; overflow-y: auto; }
    .pc-detail-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); border-radius: 20px; padding: 30px; box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08); }
    .pc-cover-image { width: 100%; max-width: 450px; margin: 0 auto; border-radius: 16px; overflow: hidden; cursor: pointer; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
    .pc-cover-image img { width: 100%; display: block; }
    .pc-detail-title { font-size: 20px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.08); }
    .pc-detail-content { font-size: 15px; line-height: 1.8; color: #444; }
    .pc-detail-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
    .pc-detail-content p { margin-bottom: 1em; }
    .pc-detail-content table { width: 100% !important; border-collapse: collapse; }
    .pc-detail-content th, .pc-detail-content td { border: 1px solid #ddd; padding: 8px; }
    .pc-order-panel h1 { font-size: 24px; font-weight: 600; line-height: 1.4; margin-bottom: 15px; }
    .pc-order-price { margin-bottom: 20px; }
    .pc-order-price .price-tag { font-size: 16px; color: #ff5555; font-weight: 500; margin-right: 5px; }
    .pc-order-price .price-value { font-size: 32px; font-weight: bold; color: #ff5555; }
    .pc-order-price .stock { font-size: 14px; color: #888; margin-left: 15px; }
    .pc-order-level { background: #f8f8f8; padding: 8px 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: inline-flex; align-items: center; gap: 10px; }
    .pc-order-level .show_daili_price { background: #fff; border: 1px solid #ddd; padding: 2px 8px; border-radius: 5px; cursor: pointer; }
    #inputsname_pc .form-group, .pc-form-group { margin-bottom: 15px; } /* Changed selector */
    #inputsname_pc .form-group label, .pc-form-group label { display: block; font-weight: 500; margin-bottom: 8px; font-size: 14px; } /* Changed selector */
    #inputsname_pc .form-control, .pc-form-group .layui-input { width: 100% !important; height: 45px; padding: 0 15px; border-radius: 10px; border: 1px solid #ddd; background: #fff; transition: all 0.3s; } /* Changed selector */
    #inputsname_pc .form-control:focus, .pc-form-group .layui-input:focus { border-color: #000; box-shadow: 0 0 0 2px rgba(0,0,0,0.1); } /* Changed selector */
    #inputsname_pc .input-group-addon { display: none; } /* Changed selector */
    .pc-quantity-selector { display: flex; }
    .pc-quantity-selector .input-group-addon { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border: 1px solid #ddd; cursor: pointer; font-size: 20px; user-select: none; transition: background 0.2s; }
    .pc-quantity-selector .input-group-addon:hover { background: #e0e0e0; }
    .pc-quantity-selector #num { text-align: center; border-left: none; border-right: none; }
    #num_min { border-radius: 10px 0 0 10px; }
    #num_add { border-radius: 0 10px 10px 0; }
    .pc-action-buttons { margin-top: 25px; display: flex; gap: 12px; }
    .pc-action-buttons .pc-btn { flex: 1; height: 50px; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
    .pc-action-buttons .pc-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    .pc-btn-cart { background: #fff; color: #000; border: 2px solid #000; }
    .pc-btn-buy { background: #000; color: #fff; }
    .layui-layer-title { font-weight: 600 !important; }
}
</style>
<?php echo str_replace('body','html',$background_css)?>
</head>
<body ontouchstart="">
<div id="body">
    <div class="pc-layout">
        <div class="pc-top-navbar-wrapper"><div class="pc-top-navbar"><a href="./" class="pc-nav-logo"><?php echo $conf['sitename'] ?></a><div class="pc-nav-items"><a href="./" class="pc-nav-item"><span class="icon icon-home"></span><span>首页</span></a><a href="./?mod=query" class="pc-nav-item"><span class="icon icon-dingdan1"></span><span>订单查询</span></a><?php if($conf['shoppingcart']!=0){?><a href="./?mod=cart" class="pc-nav-item"><span class="icon icon-cart2"></span><span>购物车</span></a><?php }?><a href="./?mod=kf" class="pc-nav-item"><span class="icon icon-service1"></span><span>联系客服</span></a><a href="./user/" class="pc-nav-item"><span class="icon icon-person2"></span><span>会员中心</span></a></div></div></div>
        <div class="pc-main-container">
            <div class="pc-left-sidebar"><div class="pc-category-title">商品分类</div><a href="./" class="pc-category-item"><img src="assets/store/picture/1562225141902335.jpg" class="pc-category-icon" alt="全部"><span class="pc-category-name">返回首页</span></a><?php foreach($ar_data as $v){ ?><a href="./?cid=<?php echo $v['cid']?>" class="pc-category-item <?php echo $v['cid']==$cid?'active':''?>"><img src="<?php echo $v['shopimg']?>" class="pc-category-icon" onerror="this.src='assets/store/picture/1562225141902335.jpg'" alt="<?php echo $v['name']?>"><span class="pc-category-name"><?php echo $v['name']?></span></a><?php }?></div>
            <div class="pc-detail-container">
                <div class="pc-detail-left pc-right-content"><div class="pc-detail-panel" id="layer-photos-demo"><a href="javascript:void(0);" title="点击查看大图"><div class="pc-cover-image"><img layer-src="<?php echo $tool_shopimg; ?>" src="<?php echo $tool_shopimg; ?>" alt="<?php echo $tool['name']; ?>"></div></a></div><div class="pc-detail-panel"><h2 class="pc-detail-title">商品说明</h2><div class="pc-detail-content hd_intro"><?php echo $tool_desc; ?></div></div></div>
                <div class="pc-detail-right pc-right-content"><div class="pc-detail-panel pc-order-panel"><h1><?php echo $tool['name']?></h1><div class="pc-order-price"><span class="price-tag">售价:</span><span class="price-value">￥<span id="need_pc"><?php echo $price; ?></span></span><span class="stock"><?php echo $stock; ?></span></div><div class="pc-order-level"><span>当前级别: <?php echo $level?></span><?php if($conf['template_showprice']==1){?><button type="button" class="show_daili_price"><i class="layui-icon layui-icon-fire"></i> 查看代理价</button><?php } ?></div>
                    <!-- PC端输入框容器 -->
                    <div id="inputsname_pc"></div>
                    <div class="pc-form-group" <?php echo $tool['multi']==0?'style="display: none"':null;?>><label>购买数量 <?php if($isfaka == 1){echo "<span style='float:right;font-weight:normal;color:#888;'>剩余：<font color='red'>".$count."</font>份</span>";} ?></label><div class="input-group pc-quantity-selector"><div class="input-group-addon" id="num_min">-</div><input id="num" name="num" class="layui-input" type="number" value="1" placeholder="1" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>><div class="input-group-addon" id="num_add">+</div></div></div>
                    <div class="pc-action-buttons"><?php if($conf['shoppingcart']!=0){?><button type="button" id="submit_cart_shop_pc" class="pc-btn pc-btn-cart" onclick="<?php echo $cart_button_onclick; ?>" style="<?php echo $button_style; ?>"><span class="icon icon-cart2"></span> 加入购物车</button><?php }?><button type="button" id="submit_buy_pc" class="pc-btn pc-btn-buy" onclick="<?php echo $button_onclick; ?>" style="<?php echo $button_style; ?>"><?php echo $button_msg; ?></button></div>
                </div></div>
            </div>
        </div>
    </div>
    <div class="mobile-layout">
        <div class="fui-page-group statusbar"><div class="fui-page fui-page-current" style="overflow: inherit"><div id="container" class="fui-content" style="background-color: rgb(255, 255, 255); padding-bottom: 60px;"><div class="pro_content" style="margin-bottom: 3.5rem;"><div class="list_item_box" style="top: 53px;"><div class="bor_detail"><div class="thumb" id="layer-photos-demo-mb"><img alt="<?php echo $tool['name']?>" layer-src="<?php echo $tool_shopimg;?>"  src="<?php echo $tool_shopimg;?>"></div><div class="pro_right fl"><span id="level">当前级别：<?php echo $level?></span><a href="./?mod=cart" class="icon icon-cart2 color" style="float: right;margin-right: 1em;background-color: #0079fa;color: white;padding: 0.3rem;border-radius: 3em;box-shadow: 3px 3px 6px #eee;<?php if($conf['shoppingcart']==0){?>display:none;<?php }?>" title="打开购物车"></a><span class="list_item_title" id="gootsp"><?php echo $tool['name']?></span><div class="list_tag"><div class="price"><span class="t_price pay_prices">售价：<span class="pay_price"><?php echo $price?>元</span><?php if($conf['template_showprice']==1){?>&nbsp;&nbsp;<button type="button" class="show_daili_price layui-btn layui-btn-warm layui-btn-xs layui-btn-radius "><i class="layui-icon layui-icon-fire"></i>查看等级价格</button><?php } ?></span><span class="stock" style="">剩余:<span class="quota"><?php echo ($isfaka==1?$count:'充足');?></span>份</span></div></div></div></div></div></div><?php if (!$islogin2) { ?><marquee style="margin:1em;"><a href="./user/login.php?back=index" style="color: salmon">您当前未登录，点击登录后下单售后更方便哦~</a></marquee><?php } ?><div class="content_friends"><div class="top_tit">商品说明</div><div class="hd_intro" style="word-break: break-all;"><?php echo $tool_desc; ?></div></div><br/></div>
                <div class="assemble-footer footer">
                <a href="javascript:;" onclick="goback();" class="left" style="width: 25% !important;border-left: solid 1px #eee"><div class="wid all"><span class="icon icon-left top"></span><p>返回</p></div></a>
                    <?php
                        if($tool['active'] == 0){ $msg = '<span class="pay_price">'.$price.'元</span><p>商品已下架</p>'; $onclick_fun = "layer.alert('当前商品已下架，停止下单！');";
                        }else if($tool['close'] == 1){ $msg = '<span class="pay_price">'.$price.'元</span><p>商品维护中</p>'; $onclick_fun = "layer.alert('当前商品缺货或维护中！');";
                        }else if($isfaka == 1 && $count==0){ $msg = '<span class="pay_price">'.$price.'元</span><p>商品缺货中</p>'; $onclick_fun = "layer.alert('当前商品已售空！');";
                        }else{ $msg = '<span class="pay_price">'.$price.'元</span><p id="submit_buys">购买商品</p>'; $onclick_fun = "$('#paymentmethod').show();"; }
                    ?>
                    <a class="middle" href="javascript:<?php echo $onclick_fun; ?>" style="width: 50%"><div class="wid y_buy"><?php echo $msg; ?></div></a>
                    <a href="javascript:share_shop()" class="left" style="width: 25% !important;border-left: solid 1px #eee"><div class="wid all"><span class="icon icon-share top"></span><p>分享</p></div></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="form1">
    <input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $price;?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
    <input type="hidden" id="leftcount" value="<?php echo $isfaka?$count:100?>">
</div>

<div id="paymentmethod" class="common-mask" style="display:none;max-width: 650px">
    <div class="payment-method" style="position: absolute;max-height:70vh;">
        <div class="title" style="color: salmon;font-size: 1.3em;">下单信息确认<span class="close" onclick="$('#paymentmethod').hide()"></span></div><hr>
        <div style="height: 52vh;overflow:hidden;overflow-y: auto; padding: 0 10px;">
            <div class="layui-form-item"><label class="layui-form-label" style="width: 100%;text-align: left;padding:0">商品价格</label><div class="layui-input-"><input type="text" id="need" disabled class="layui-input" value="<?php echo $price?> 元"></div></div>
            <!-- 这个容器是给 main.js 使用的，无论PC还是移动端，都先在这里生成 -->
            <div id="inputsname"></div>
            <div class="layui-form-item" <?php echo $tool['multi']==0?'style="display: none"':null;?>><label class="layui-form-label" style="width: 100%;text-align: left;padding:0">下单份数：<?php if($isfaka == 1){echo "<span style='float:right;'>剩余：<font color='red'>".$count."</font>份</span>";} ?></label><div class="input-group"><div class="input-group-addon" id="num_min_mb" style="background-color: #FBFBFB;cursor: pointer;">-</div><input id="num_mb" name="num_mb" class="layui-input" type="number" value="1" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>><div class="input-group-addon" id="num_add_mb" style="background-color: #FBFBFB;cursor: pointer;">+</div></div></div>
        </div>
        <div class="form-group" style="text-align: center">
            <button type="button" style="width: 48%; <?php if($conf['shoppingcart']==0){?>display:none;<?php }?>;" id="submit_cart_shop" class="layui-btn layui-btn-warm"><span class="icon icon-cart2"></span> 加入购物车</button>
            <button type="button" style="width: 48%;" id="submit_buy" class="layui-btn layui-btn-normal">立即购买</button>
        </div>
    </div>
</div>

<div id="show_daili_price_html" style="display:none;"><div class="price" style="padding: 20px; text-align:center;"><p class="pay_prices" id="level" style="margin-bottom:10px;"><font color="#48d1cc">普通用户售价</font>：<span class="pay_price"><?php echo $price_pt?>元</span></p><p class="pay_prices" id="level" style="margin-bottom:10px;"><font color="red">普通代理售价</font>：<span class="pay_price"><?php echo $price_1?>元</span></p><p class="pay_prices" id="level" style="margin-bottom:15px;"><font color="orange">高级代理售价</font>：<span class="pay_price"><?php echo $price_2?>元</span></p><hr><p class="pay_prices" id="level" style="margin-top:15px;"><font color="blue">您当前所在级别</font>：<span class="pay_price"><?php echo $level?></span></p></div></div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic?>limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<!-- 关键修复：像旧代码一样，直接、完整地调用 main.js -->
<script src="assets/store/js/main.js?ver=<?php echo VERSION ?>"></script>

<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
function goback(){
    document.referrer === '' ? window.location.href = './' : window.history.go(-1);
}
// 使用独立的 $(document).ready，避免冲突
$(document).ready(function(){
    // 等待 main.js 执行完毕后，再进行我们的操作
    // 使用 setTimeout 延迟执行，确保DOM元素已由 main.js 生成
    setTimeout(function() {
        if (window.innerWidth > 768) {
            // 是PC端，将已生成的输入框从隐藏的弹窗“搬运”到PC的下单区
            var inputsContent = $('#inputsname').children();
            $('#inputsname_pc').append(inputsContent);
        }
        // 移动端不需要做任何事，因为 main.js 已经正确地在 #inputsname 中生成了所有内容
    }, 100); // 100毫秒延迟足够

    $(".show_daili_price").on("click",function(){
        layer.open({
            type: 1, title: "<b>" + <?php echo json_encode($tool['name']); ?> + "</b> 的代理价格", btnAlign: 'c', content: $('#show_daili_price_html'),
            <?php if($islogin2 && $userrow['power'] > 0) {?>
            btn: ['关闭'],
            <?php }else{ ?>
            btn: ['提升级别', '关闭'],
            yes: function(index, layero){ window.location.href = "./user/regsite.php"; },
            <?php } ?>
        });
    });

    layer.photos({ photos: '#layer-photos-demo', anim: 5 });
    layer.photos({ photos: '#layer-photos-demo-mb', anim: 5 });
    
    $('#num').on('input', function() { $('#num_mb').val($(this).val()); });
    $('#num_mb').on('input', function() { $('#num').val($(this).val()); });
    $('#num_min, #num_min_mb').click(function(){
        var num_obj = $('#num'); var num = parseInt(num_obj.val()) || 1;
        if (num > 1) { num--; } $('#num, #num_mb').val(num).trigger('change');
    });
    $('#num_add, #num_add_mb').click(function(){
        var num_obj = $('#num'); var num = parseInt(num_obj.val()) || 1;
        num++; $('#num, #num_mb').val(num).trigger('change');
    });
});
</script>

</body>
</html>
