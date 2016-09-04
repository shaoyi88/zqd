<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//加载微信PHP库
define('WX_PATH', THIRD_PATH.'weixin/');

class Wxqyh extends ZQD_Controller
{

    protected function initialize()//     初始化变量
    {
        parent::initialize();
        require_once WX_PATH.'WXBizMsgCrypt.php';
    }

    private $corpId;
    private $qyhApp;
    private $wxUrl;
    public function __construct(){
        parent::__construct();
        $this->config->load('qyh_conf', TRUE);
        $this->qyh_conf = $this->config->item('qyh_conf');
        $this->corpId        = $this->qyh_conf['corpId']; 
        $this->qyhApp        = $this->qyh_conf['qyh_app'];
        $this->wxUrl = 'http://oa.gd-zqd.com/oa_admin/wxqyh/';
    }
    
    //oa消息提示
    public function index(){
    	$qyhapp = $this->qyhApp;
    	$encodingAesKey = $qyhapp[0]['encodingAesKey'];
    	$token = $qyhapp[0]['token'];
    	$corpId = $this->corpId;
    	/*
    	//回调模式验证
    	$sVerifyMsgSig = $_GET['msg_signature'];
    	$sVerifyTimeStamp = $_GET['timestamp'];
    	$sVerifyNonce = $_GET['nonce'];
    	$sVerifyEchoStr = $_GET['echostr'];
    	
    	// 需要返回的明文
    	$sEchoStr = "";
    	
    	$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
    	$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
    	if($errCode==0){
    	  // 验证URL成功，将sEchoStr返回
    	  echo $sEchoStr;
    	}else{
    	  print("ERR: " . $errCode . "\n\n");
    	  return;
    	}
    	*/
    }
    
    /**
     * 打卡签到
     */
    public function punch(){
    	$qyhapp = $this->qyhApp;
    	$encodingAesKey = $qyhapp[2]['encodingAesKey'];
    	$token = $qyhapp[2]['token'];
    	$corpId = $this->corpId;
    	$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
    	$sReqMsgSig = $_GET["msg_signature"];
    	$sReqTimeStamp = $_GET["timestamp"];
    	$sReqNonce = $_GET["nonce"];
    	$resarr = array();
    	$resarr = $this->geterr($sReqMsgSig,$sReqTimeStamp,$sReqNonce,$token, $encodingAesKey, $corpId);
    	if($resarr['errCode']==0){
    		$etype = $resarr['etype'];
	    	if($etype=='LOCATION'||$etype=='location'){
	    		$pos[0] = $resarr['Latitude'];
	    		$pos[1] = $resarr['Longitude'];
	    		$pos[2] = $resarr['Precision'];
	    		$locinfo = getlocation($pos);
	    		if($locinfo){
		    		$contentStr = "";
	    			$wxuid = $resarr['uid'];
	    			$this->load->model('OA_Admin');
	    			if(($adminInfo = $this->OA_Admin->checkAdminQyh($wxuid)) === FALSE){
	    				exit;
	    			}
	    			$this->load->model('OA_Punch');
	    			$add['punch_time'] = time();
	    			$add['punch_by'] = $adminInfo['admin_name'];
	    			$add['punch_admin_id'] = $adminInfo['admin_id'];
	    			$add['punch_at'] = $locinfo;
	    			$add['punch_lat'] = $pos[0];
	    			$add['punch_lng'] = $pos[1];
	    			$add['punch_pre'] = $pos[2];
	    			$this->OA_Punch->add($add);
	    			$contentStr = '谢谢，打卡成功';
	    		}else{
	    			$contentStr = '当前无法获取您所在的位置，请稍后再试';
	    		}
	    	}else{
	    		$contentStr = '请返回重新打卡';
	    	}
	    	if($contentStr){
	    		$resultStr = sprintf($resarr['textTpl'], $resarr['touid'], $resarr['uid'], $resarr['time'], 'text', $contentStr);
	    		$sEncryptMsg = ""; //xml格式的密文
	    		$errCode = $wxcpt->EncryptMsg($resultStr, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
	    		if($errCode==0){
	    			echo $sEncryptMsg;
	    			return;
	    		}else{
	    			exit;
	    		}
	    	}
    	}
    }
    
    /**
     * 外出情况
     */
    public function outinfo(){
    	$qyhapp = $this->qyhApp;
    	$encodingAesKey = $qyhapp[4]['encodingAesKey'];
    	$token = $qyhapp[4]['token'];
    	$corpId = $this->corpId;    	
    	$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
    	$sReqMsgSig = $_GET["msg_signature"];
    	$sReqTimeStamp = $_GET["timestamp"];
    	$sReqNonce = $_GET["nonce"];   	
    	$resarr = array();
    	$resarr = $this->geterr($sReqMsgSig,$sReqTimeStamp,$sReqNonce,$token, $encodingAesKey, $corpId);
    	if($resarr['errCode']==0){
    		$etype = $resarr['MsgType'];
    		$contentStr = '';
    	    if($etype=='text'){
		      	$content = $resarr['Content'];
		      	$content = trim($content);
		      	$this->load->model('OA_Admin');
		      	$check = $this->OA_Admin->queryAdminByName($content);
		      	if(empty($check)){
		      		$contentStr = '您输入的名字有误，没有这个人';
		      	}else{
		      		$this->load->model('OA_Punch');
		      		$pmap['punch_by'] = $content;
		      		$punchinfo = $this->OA_Punch->getInfo($pmap);
		      		$contentStr = $content."\n";
		      		if(!empty($punchinfo)){	      		
		      		    foreach($punchinfo as $k=>$v){
		      			    $contentStr .= "打卡时间：".date('Y-m-d H:i:s',$v['punch_time'])."\n打卡地点：".$v['punch_at']."附近\n\n";
		      		    }
		      	    }else{
		      	    	$contentStr .= '今天还没有任何打卡签到记录';
		      	    }
		      	}
	        }else if($etype=='event'){
		      	$Event = $resarr['etype'];
		      	if($Event=='enter_agent'){
		      		$contentStr = '直接输入名字就可以查询此人当天的外出及签到情况';
		      	}
	        }		      
		    if($contentStr){
		      	$resultStr = sprintf($resarr['textTpl'], $resarr['touid'], $resarr['uid'], $resarr['time'], 'text', $contentStr);
		      	$sEncryptMsg = ""; //xml格式的密文
		      	$errCode = $wxcpt->EncryptMsg($resultStr, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
		      	if($errCode==0){
		      		echo $sEncryptMsg;
		      		return;
		      	}else{
		      		exit;
		      	}
	        }
    	}
    }
    
    //获取企业号发送的信息
    private function geterr($sReqMsgSig,$sReqTimeStamp,$sReqNonce,$token, $encodingAesKey, $corpId){
    	// post请求的密文数据
    	$sReqData = file_get_contents('php://input');
    	$sMsg = "";  // 解析之后的明文
    	$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
    	$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
    	$resarr = array();
    	$resarr['errCode'] = $errCode;
    	if($errCode==0){
    		$xml = new DOMDocument();
    		$xml->loadXML($sMsg);
    		$resarr['ekey'] = $xml->getElementsByTagName('EventKey')->item(0)->nodeValue;
    		$resarr['uid'] = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
    		$resarr['touid'] = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
    		$resarr['etype'] = $xml->getElementsByTagName('Event')->item(0)->nodeValue;
    		$resarr['Latitude'] = $xml->getElementsByTagName('Latitude')->item(0)->nodeValue;
    		$resarr['Longitude'] = $xml->getElementsByTagName('Longitude')->item(0)->nodeValue;
    		$resarr['Precision'] = $xml->getElementsByTagName('Precision')->item(0)->nodeValue;
    		$resarr['Content'] = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
    		$resarr['MsgType'] = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
    		$resarr['time'] = time();
    		$resarr['textTpl'] = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <AgentID>0</AgentID>
                        </xml>";
    	}
    	return $resarr;
    }
    
    // 针对企业号admin用户的登录验证，页面跳转
    public function urlLogin($url){
    	if($this->userId == ''){
    		$code = $_GET['code'];
    		$state = $_GET['state'];
    		if($code){
    			$token = weixin_token('oa',1);
    			$geturi = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=".$token."&code=".$code;
    			$context = array();
    			$context['http'] = array(
    					'method' => 'GET',
    					'header' => "Content-type: application/x-www-form-urlencoded ",
    			);
    			$rs = file_get_contents($geturi, false, stream_context_create($context));
    			$arr = json_decode($rs,true);
    			$wxuid = $arr['UserId'];
    			$this->load->model('OA_Admin');
    			if(($adminInfo = $this->OA_Admin->checkAdminQyh($wxuid)) === FALSE){
    				redirect(formatUrl('wxqyh/noinfo'));
    			}
    			$info = array(
    					'admin_id' => $adminInfo['admin_id'],
    					'admin_name' => $adminInfo['admin_name']
    			);
    			if($adminInfo['admin_role'] == 0){
    				$info['admin_rights'] = 'all';
    			}else{
    				$this->load->model('OA_Role');
    				$roleInfo = $this->OA_Role->getInfo($adminInfo['admin_role']);
    				$info['admin_rights'] = $roleInfo['role_rights'];
    			}
    			$this->session->set_userdata($info);
    			$reurl = urldecode($state);
    			redirect(formatUrl($reurl));
    		}else{
    			$corpId = $this->corpId;
    			$wxurl = $this->wxUrl;
    			$redirect_uri = $wxurl.'urlLogin';
    			$redirect_uri = urlencode($redirect_uri);
    			$url = urlencode($url);
    			$reuri = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$corpId."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=".$url."#wechat_redirect";
    			redirect($reuri);
    		}
    	}
    }
    
}
?>