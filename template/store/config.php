<?php
$template_info = [
	'name' => 'H5商城首页模板',
	'version' => 1.5,
];
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
//PC用户中心模板文件
$template_route = [
	'userreg' => TEMPLATE_ROOT.'store/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'store/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'store/user/findpwd.php',
];

//手机用户中心模板文件
$template_route_m = [
	'userhead' => TEMPLATE_ROOT.'store/user/head.php',
	'userfoot' => TEMPLATE_ROOT.'store/user/foot.php',
	'userindex' => TEMPLATE_ROOT.'store/user/index.php',
	'userreg' => TEMPLATE_ROOT.'store/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'store/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'store/user/findpwd.php',
];

$template_settings = [
	'banner' => ['name'=>'首页轮播图', 'type'=>'textarea', 'note'=>'填写格式：图片链接*跳转链接|图片链接*跳转链接'],
	'defaultcid' => ['name'=>'默认显示分类ID', 'type'=>'input', 'note'=>'首页默认显示商品的分类ID，不填写则显示所有'],
	'template_showprice' => [
		'name'=>'商品页面显示代理价格',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'template_virtualdata' => [
		'name'=>'首页是否显示虚拟数据',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'template_showsales' => [
		'name'=>'是否显示商品销量',
		'type'=>'select',
		'options'=> [
			'0'=>'关闭',
			'1'=>'开启',
		]
	],
	'index_class_num_style' => ['name'=>'首页分类展示几行', 'type'=>'input', 'note'=>'默认不填写为2'],
	'anounce_brief' => ['name'=>'首页滚动公告(简短)', 'type'=>'input', 'note'=>'用于首页横向滚动的简短公告，不填则显示默认文字'],
];
