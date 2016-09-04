<?php

//企业号操作    
/**
 * $type 企业号管理组
 * $content 操作内容
 * $co 不同企业号（当有多个企业号时使用）
 */
    function qyh_operate($func=array(),$content=array(),$type='oa',$co=1){
    	if(empty($func)){
    		exit;
    	}
    	//$func[0]操作模块；$func[1]具体操作；$func[2]方式（为空时默认post）；$func[3]为参数
    	$token = weixin_token($type,$co);
    	$url = 'https://qyapi.weixin.qq.com/cgi-bin/'.$func[0].'/'.$func[1].'?access_token='.$token;
    	if(isset($func[3])){
    		$url .= "&".$func[3];
    	}
    	$context = array();
    	$method = 'POST';
    	if(isset($func[2])){
    		$method = $func[2];
    	} 
    	$content = json_encode($content);
    	$content = urldecode($content);
    	$context['http'] = array(
    			'method' => $method,
    			'header' => "Content-type: application/x-www-form-urlencoded ",
    			'content' => $content,
    	);
    	return file_get_contents($url, false, stream_context_create($context));
    }
    
    function weixin_token($type,$co){
    	if(isset($_COOKIE['qyhkinfo'])){
    		return $_COOKIE['qyhkinfo'];
    	}else{    		
    		$token = get_weixin_token($type,$co);
    		//企业号的token有效时间为7200秒
    		setcookie('qyhkinfo',$token,time()+7200);
    		return $token;
    	}    	    	
    }
    
    function get_weixin_token($type,$co){
    	set_time_limit(0);
    	switch($co){
    		case 1:
    			$corpid = 'wxc9ac8745bb2c7279';
    			break;
    		default:
    			$corpid = 'wxc9ac8745bb2c7279';
    	}
    	switch($type){
    		case 'oa':
    			$secret = 'ndl-g9mom-5q-BkxGKX2gQsZWYg4k7bBkKQ9-7A1isRMRL7B1vxnp2KtmAohCe4c';
    			break;
    		default:
    			$secret = 'ndl-g9mom-5q-BkxGKX2gQsZWYg4k7bBkKQ9-7A1isRMRL7B1vxnp2KtmAohCe4c';
    	}
    	$url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$corpid.'&corpsecret='.$secret.'';
    	$context = array();
    	$context['http'] = array(
    			'method' => 'GET',
    			'header' => "Content-type: application/x-www-form-urlencoded ",
    	);
    	$rs = file_get_contents($url, false, stream_context_create($context));
    	$arr = json_decode($rs,true);
    	return $arr['access_token'];
    }
    
    //企业号推送信息
    /**
     * $msg 推送的信息
     * $uid 信息接收人
     * $agentid 推送信息的应用
     */
    function wxsend($msg,$uid,$agentid){
    	$text = array();
    	$text['touser'] = $uid;
    	$text['msgtype'] = 'text';
    	$text['agentid'] = $agentid;
    	$arr = urlencode($msg);
    	$text['text'] = array('content'=>$arr);
    	$text['safe'] = '0';
    	$func[0] = 'message';
    	$func[1] = 'send';
    	qyh_operate($func,$text);
    }
	
    //重写分页类
    function cellpage($baseUrl, $totalNum, $perNum, &$offset, &$pageUrl)
    {
    	$ci =& get_instance();
    	$ci->load->library('pagination');
    	$config['base_url'] = $baseUrl;
    	$config['total_rows'] = $totalNum;
    	$config['per_page'] = $perNum;
    	$config['page_query_string'] = TRUE;
    	$config['use_page_numbers'] = TRUE;
    	$config['num_links'] = 1;
    	$config['full_tag_open'] = '<div class="page">';
    	$config['full_tag_close'] = '</div>';
    	$config['prev_link'] = '&lt;';
    	$config['next_link'] = '&gt;';
    	$config['first_link'] = '首页';
    	$config['last_link'] = '末页';
    	$ci->pagination->initialize($config);
    	$pageUrl = $ci->pagination->create_links();
    
    	$curPage = 1;
    	if($ci->input->get('per_page')){
    		$curPage = $ci->input->get('per_page');
    	}
    	$offset = ($curPage-1)*$perNum;
    }


