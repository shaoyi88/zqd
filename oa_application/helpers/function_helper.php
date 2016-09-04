<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 重定向
 * @param unknown_type $uri
 */
function redirect($uri = '')
{
	header("Location: ".$uri, TRUE, 302);
	exit;
}

/**
 * 
 * 获取当前链接
 * @param unknown_type $noparam
 * @param unknown_type $stripParams
 */
function get_current_uri()
{
	$s = !isset($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on' ? 's' : '');
	$protocol = strtolower($_SERVER['SERVER_PROTOCOL']);
	$protocol = substr($protocol, 0, strpos($protocol, '/')) . $s . '://';
	$port = $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];
	$server_name = !empty($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) :
		(!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'].$port : getenv('SERVER_NAME').$port);

	$request_uri = $_SERVER['REQUEST_URI'];
	return $protocol.$server_name.$request_uri;
}

/**
 *
 * 格式化url
 * @param unknown_type $uri
 */
function formatUrl($uri = '')
{
	$ci =& get_instance();
	return base_url().config_item('index_page').$ci->rtrDir.$uri;
}

/**
 *
 * 检测后台登录
 */
function checkLogin()
{
	$ci =& get_instance();
	$ci->userId = $ci->session->userdata('admin_id');
	$ci->userName = $ci->session->userdata('admin_name');
	$ci->userRights = $ci->session->userdata('admin_rights');
	$ci->userRole = $ci->session->userdata('admin_role');
	if($ci->userId == ''){
		if(strtolower($ci->rtrClass) !== 'login'){
			redirect(formatUrl('login/index'));
		}
	}else{
		if(strtolower($ci->rtrClass) === 'login'){
			redirect(formatUrl('home/index'));
		}
	}
}

/**
 * Format size in special unit
 *
 * @param int $size
 * @param int $unit
 * @param boolean $onlyNum
 * @param int $precision
 * @return mixed
 */
function format_size_str($size, $unit = 0, $onlyNum = FALSE, $precision = 2)
{
	$sizeDef = array(1=>'B', 2=>'K', 3=>'M', 4=>'G', 5=>'T');
	if($size <= 0)
		return $onlyNum ? 0 : (isset($sizeDef[$unit]) ? '0'.$sizeDef[$unit] : '0B');
	if(isset($sizeDef[$unit])){
		$key = $unit;
	}else{
		$key = floor(log($size, 1024)) + 1;
	}

	$key = max(1, min($key, count($sizeDef)));

	return round($size/pow(1024, $key-1), $precision).(!$onlyNum ? $sizeDef[$key] : '');
}

/**
 *
 * 检测权限
 * @param unknown_type $key
 */
function checkRight($key)
{
	$ci =& get_instance();
	if($ci->userRights == 'all'){
		return TRUE;
	}else{
		$rightsArr = explode(',', $ci->userRights);
		if(is_string($key) && in_array($key, $rightsArr)){
			return TRUE;
		}
		if(is_array($key)){
			foreach($key as $item){
				if(in_array($item, $rightsArr)){
					return TRUE;
				}
			}
		}
	}
	return FALSE;
}

/**
 *
 * 分页帮助类
 * @param unknown_type $baseUrl
 * @param unknown_type $totalNum
 * @param unknown_type $perNum
 * @param unknown_type $offset
 * @param unknown_type $pageUrl
 */
function page($baseUrl, $totalNum, $perNum, &$offset, &$pageUrl, $query_string_segment=NULL)
{
	$ci =& get_instance();
	$ci->load->library('pagination');
	$config['base_url'] = $baseUrl;
	$config['total_rows'] = $totalNum;
	$config['per_page'] = $perNum;
	$config['page_query_string'] = TRUE;
	$config['use_page_numbers'] = TRUE;
	$config['num_links'] = 5;
	$config['full_tag_open'] = '<div class="page">';
	$config['full_tag_close'] = '</div>';
	$config['prev_link'] = '&lt;上一页';
	$config['next_link'] = '下一页&gt;';
	$config['first_link'] = '首页';
	$config['last_link'] = '末页';
	if($query_string_segment !== NULL){
		$config['query_string_segment'] = $query_string_segment;
	}
	$ci->pagination->initialize($config);
	$pageUrl = $ci->pagination->create_links();
	
	$curPage = 1;
	if($ci->input->get('per_page')){
		$curPage = $ci->input->get('per_page');
	}
	$offset = ($curPage-1)*$perNum;
}

//object转化为array
function object_array($array)
{
	if(is_object($array))
	{
		$array = (array)$array;
	}
	if(is_array($array))
	{
		foreach($array as $key=>$value)
		{
			$array[$key] = object_array($value);
		}
	}
	return $array;
}

//调用腾讯地图api获取详细地址
function getlocation($pos=array()){
	if(empty($pos)){
		return 0;
	}
	$key = 'CDYBZ-CP4R4-GGZU5-X7OCO-AVVT2-K2F4F';
	$posarr = $pos[0].','.$pos[1];
	$url = 'http://apis.map.qq.com/ws/geocoder/v1?location='.$posarr.'&key='.$key.'&coord_type=1';
	$context = array();
	$context['http'] = array(
			'method'=>"GET",
			'header' => "Content-type: application/x-www-form-urlencoded ",
	);
	$con = stream_context_create($context);
	$rs = file_get_contents($url,false,$con);
	$arr = json_decode($rs,true);
	if($arr['status']==0){
		return $arr['result']['address'].$arr['result']['formatted_addresses']['recommend'];
	}else{
		return 0;
	}
}