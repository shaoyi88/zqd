<?php
/*
 * 菜单
 */
$config['menus'] = array(
	array(
		'module' => '系统用户管理',
		'menu' => array(
				array('分组权限管理', formatUrl('role/index'), 'role_list'),
				array('系统用户管理', formatUrl('admin/index'), 'admin_list'),
		),
		'right' => 'admin'
	),
	array(
		'module' => '会员管理',
		'menu' => array(
			array('会员管理', formatUrl('user/index'), 'user_list'),
			array('推广管理', formatUrl('user/campaign'), 'campaign_list'),
			array('提现管理', formatUrl('user/cash'), 'cash_apply'),
		),
		'right' => 'user'
	),
	array(
		'module' => '订单管理',
		'menu' => array(
			array('订单管理', formatUrl('order/index'), 'order_list'),
		),
		'right' => 'order'
	),
	array(
		'module' => '商品管理',
		'menu' => array(
			array('商品管理', formatUrl('good/index'), 'good_list'),
			array('商品分类管理', formatUrl('good/category'), 'good_cat_list'),
			array('邮费模板管理', formatUrl('good/shipping'), 'shipping_list'),
		),
		'right' => 'user'
	),
);

/*
 * 权限
 */
$config['rights'] = array(
	array(
		'module' => '系统用户管理',
		'roles' => array(
			array('系统分组列表', 'role_list'),
			array('系统分组增加', 'role_add'),
			array('系统分组编辑', 'role_edit'),
			array('系统分组删除', 'role_del', TRUE),
			array('系统用户列表', 'admin_list'),
			array('系统用户增加', 'admin_add'),
			array('系统用户编辑', 'admin_edit'),
			array('系统用户删除', 'admin_del', TRUE),
			
		),
		'right' => 'admin'
	),
	array(
		'module' => '会员管理',
		'roles' => array(
			array('会员列表', 'user_list'),
			array('会员增加', 'user_add'),
			array('会员编辑', 'user_edit'),
			array('会员删除', 'user_del', TRUE),
			array('推广列表', 'campaign_list'),
			array('结算管理', 'accounts_list'),
			array('提现申请', 'cash_apply'),
			array('返现操作', 'cash_return', TRUE),
		),
		'right' => 'user'
	),
	array(
		'module' => '订单管理',
		'roles' => array(
			array('订单列表', 'order_list'),
			array('订单增加', 'order_add'),
			array('订单编辑', 'order_edit'),
			array('订单删除', 'order_del'),
			array('订单发货', 'order_ship'),
			array('订单支付', 'order_pay', TRUE),
		),
		'right' => 'order'
	),
	array(
		'module' => '商品管理',
		'roles' => array(
				array('商品列表', 'good_list'),
				array('商品增加', 'good_add'),
				array('商品编辑', 'good_edit'),
				array('商品删除', 'good_del', TRUE),
				array('商品分类列表', 'good_cat_list'),
				array('商品分类增加', 'good_cat_add'),
				array('商品分类编辑', 'good_cat_edit'),
				array('商品分类删除', 'good_cat_del', TRUE),
				array('邮费模板列表', 'shipping_list'),
				array('邮费模板增加', 'shipping_add'),
				array('邮费模板编辑', 'shipping_edit'),
				array('邮费模板删除', 'shipping_del', TRUE),
		),
		'right' => 'good'
	),	
);

//商品类型
$config['good_type'] = array(
	'0' => '商品',
	'1' => '服务',
	'2' => '赠品',
);

//邮费模板类型
$config['shipping_type'] = array(
		'0' => '全场包邮',
		'1' => '满金额包邮',
		'2' => '常规',
);

//上下架
$config['shelf_status'] = array(
		'0' => '上架',
		'1' => '下架',
);


//运送省份
$config['shipping_province'] = array(
		'广东',
		//'东莞',
		'上海',
		'江苏',
		'浙江',
		'安徽',
		'江西',
		'湖南',
		'湖北',
		'福建',
		'广西',
		'云南',
		'北京',
		'天津',
		'河北',
		'河南',
		'山东',
		'贵州',
		'重庆',
		'四川',
		'辽宁',
		'黑龙江',
		'陕西',
		'山西',
		'吉林',
		'内蒙古',
		'海南',
		'甘肃',
		'青海',
		'宁夏',
		'新疆',
		'西藏',
		'香港',
		'澳门',
		'台湾'
);

//快递公司
$config['shipping_postage'] = array(
		'1' => array('顺丰快递','sf'),
		'2' => array('申通快递','st'),
		'3' => array('圆通快递','yt'),
		'4' => array('中通快递','zt'),
		'5' => array('百世汇通','htky'),
		'6' => array('韵达快递','yd'),
		'7' => array('天天快递','tt'),
		'8' => array('全峰快递','qfkd'),
		'9' => array('EMS','ems'),
		'10' => array('宅急送','zjs'),
);

//退款状态
$config['refund_status'] = array(
		'0' => '已提交',
		'1' => '已退款',
		'2' => '已取消',
);

//订单状态
$config['order_status'] = array(
	'0' => '待付款',
	'1' => '已取消',
	'2' => '已付款',
	'3' => '已发货',
	'4' => '已完成',
	'5' => '退款中',
	'6' => '已退款',
);

//积分方式
$config['credit_message'] = array(
		'1' => '购物获得',
		'2' => '推荐奖励',
		'3' => '兑换礼品',
		'4' => '积分抵现',
		'5' => '提现',
);
