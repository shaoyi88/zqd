<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pan extends ZQD_Controller 
{
	protected function initialize()
	{
		parent::initialize();
		$this->panw = array(
			'1' => '平手',
			'2' => '平手/半球','3' => '半球','4' => '半球/一球','5' => '一球','6' => '一球/球半','7' => '球半','8' => '球半/两球','9' => '两球','10' => '两球/两球半','11' => '两球半','12' => '两球半/三球','13' => '三球','14' => '三球/三球半','15' => '三球半','16' => '三球半/四球','17' => '四球','18' => '四球/四球半','19' => '四球半','20' => '四球半/五球','21' => '五球','22' => '五球/五球半','23' => '五球半','24' => '五球半/六球',
			'25' => '受平手/半球','26' => '受半球','27' => '受半球/一球','28' => '受一球','29' => '受一球/球半','30' => '受球半','31' => '受球半/两球','32' => '受两球','33' => '受两球/两球半','34' => '受两球半','35' => '受两球半/三球','36' => '受三球','37' => '受三球/三球半','38' => '受三球半','39' => '受三球半/四球','40' => '受四球','41' => '受四球/四球半','42' => '受四球半','43' => '受四球半/五球','44' => '受五球','45' => '受五球/五球半','46' => '受五球半','47' => '受五球半/六球',				
		);
		$this->panint = array(
			'1' => '0',
			'2' => '0|0.5','3' => '0.5','4' => '0.5|1','5' => '1','6' => '1|1.5','7' => '1.5','8' => '1.5|2','9' => '2','10' => '2|2.5','11' => '2.5','12' => '2.5|3','13' => '3','14' => '3|3.5','15' => '3.5','16' => '3.5|4','17' => '4','18' => '4|4.5','19' => '4.5','20' => '4.5|5','21' => '5','22' => '5|5.5','23' => '5.5','24' => '5.5|6',
			'25' => '0|-0.5','26' => '-0.5','27' => '-0.5|-1','28' => '-1','29' => '-1|-1.5','30' => '-1.5','31' => '-1.5|-2','32' => '-2','33' => '-2|-2.5','34' => '-2.5','35' => '-2.5|-3','36' => '-3','37' => '-3|-3.5','38' => '-3.5','39' => '-3.5|-4','40' => '-4','41' => '-4|-4.5','42' => '-4.5','43' => '-4.5|-5','44' => '-5','45' => '-5|-5.5','46' => '-5.5','47' => '-5.5|-6'
		);
	}
	
	public function index(){
		$data = array();
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$data['layout'] = false;
		$this->showView('/pan/pan', $data);
	}
	
	public function gameCalculate(){
		$data = array();
		$data['layout'] = false;
		$this->load->model('OA_Pan');
		$game = $this->input->get('g');
		//亚盘分析
		$url = "http://odds.500.com/fenxi/yazhi-".$game.".shtml";
		$ch = curl_init();
		// 设置浏览器的特定header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Host: odds.500.com",
		"charset: UTF-8",
		"Connection: keep-alive",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Upgrade-Insecure-Requests: 1",
		"DNT:1",
		"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
		));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
		// 在HTTP请求头中"Referer: "的内容。
		curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
		curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,120);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
		$html = '';
		$html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
		curl_close($ch);
		//比赛
		$matchInfo = array();
		preg_match_all('/<a class="hd_name"([\s\S]*?)<\/a>/',$html,$matchInfo);
		$data['homeTeam'] = substr($matchInfo[1][0],strpos($matchInfo[1][0],'"_blank">')+9);
		$data['awayTeam'] = substr($matchInfo[1][2],strpos($matchInfo[1][2],'"_blank">')+9);
		//公司
		$cinfo = array();
		preg_match_all('/<tr class="tr[1,2]" xls="row" id="([\s\S]*?)"/',$html,$cinfo);
		//如果有开盘
		if(!empty($cinfo)){			
			//盘口
			$paninfo = array();
			preg_match_all('/class="pl_table_data">([\s\S]*?)<\/tr>/',$html,$paninfo);
			$aclist = $this->OA_Pan->getCompany();
			foreach($aclist as $v){
				$acname[$v['link_id']] = $v['company_name'];
			}
			$aclist = array_column($aclist,'link_id');
			$panw = $this->panw;
			$panwKey = array_flip($panw);
			$addlist = array();
			$len = count($cinfo[1])>12?12:(count($cinfo[1])-1);
			for($i=0;$i<=$len;$i++){
				if(in_array($cinfo[1][$i],$aclist)){
					$ac = $cinfo[1][$i];
					$amap['apan_company'] = $ac;
					//末盘
					$panl = array();
					preg_match_all('/">([\s\S]*?)<\/td>/',$paninfo[1][2*$i+2],$panl);
					$amap['lpan_home_w'] = substr($panl[1][0],0,5);
					$panl[1][1] = str_replace('<font color="red"> 升</font>','',$panl[1][1]);
					$panl[1][1] = str_replace('<font color="blue"> 降</font>','',$panl[1][1]);
					$amap['lpan_pan'] = $panwKey[trim($panl[1][1])];
					$amap['lpan_away_w'] = substr($panl[1][2],0,5);
					//初盘
					$pani = array();
					preg_match_all('/">([\s\S]*?)<\/td>/',$paninfo[1][2*$i+3],$pani);
					$amap['ipan_home_w'] = $pani[1][0];
					$txti = explode(" ",$pani[1][1]);
					$amap['ipan_pan'] = $panwKey[$txti[0]];
					$amap['ipan_away_w'] = $pani[1][2];
					$acal[$ac] = $this->calculatePan($amap,1);
				}
			}
		}
		//欧指分析
		$url = "http://odds.500.com/fenxi/ouzhi-".$game.".shtml";
		$ch = curl_init();
		// 设置浏览器的特定header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Host: odds.500.com",
		"charset: UTF-8",
		"Connection: keep-alive",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Upgrade-Insecure-Requests: 1",
		"DNT:1",
		"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
		));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
		// 在HTTP请求头中"Referer: "的内容。
		curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
		curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,120);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
		$html = '';
		$html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
		curl_close($ch);
		//公司
		$cinfo = array();
		preg_match_all('/<tr class="tr[1,2]" id="([\s\S]*?)" ttl="zy"/',$html,$cinfo);
		//如果有开盘
		if(!empty($cinfo)){
			$paninfo = array();
			preg_match_all('/class="pl_table_data">([\s\S]*?)<\/tbody>/',$html,$paninfo);
			$eclist = $this->OA_Pan->getCompany(1);
			foreach($eclist as $v){
				$ecname[$v['link_id']] = $v['company_name'];
			}
			$eclist = array_column($eclist,'link_id');			
			$addlist = array();
			$len = count($cinfo[1])>12?12:(count($cinfo[1])-1);
			for($i=0;$i<=$len;$i++){
				if(in_array($cinfo[1][$i],$eclist)){
					$ec = $cinfo[1][$i];
					$emap['epan_company'] = $ec;
					//指数
					$panl = array();
					preg_match_all('/style="cursor:pointer" >([\s\S]*?)<\/td>/',$paninfo[1][4*$i+3],$panl);
					//初盘
					$emap['ipan_home_w'] = substr($panl[1][0],0,4);
					$emap['ipan_draw_w'] = substr($panl[1][1],0,4);
					$emap['ipan_away_w'] = substr($panl[1][2],0,4);
					//末盘
					$emap['lpan_home_w'] = substr($panl[1][3],0,4);
					$emap['lpan_draw_w'] = substr($panl[1][4],0,4);
					$emap['lpan_away_w'] = substr($panl[1][5],0,4);
					$ecal[$ec] = $this->calculatePan($emap,2);
				}
			}
		}
		$data['acal'] = $acal;
		$data['ecal'] = $ecal;
		$data['ac'] = $acname;
		$data['ec'] = $ecname;
		$this->showView('/pan/calculate', $data);
	}
	
	/**
	 * 
	 * 亚盘
	 */
	public function asia()
	{
	    header("content-type:text/html;charset=utf-8");
	    $day = $this->input->get('d');
		$url = "http://odds.500.com/index_jczq_".$day.".shtml";
		$ch = curl_init();
	    // 设置浏览器的特定header
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        "Host: odds.500.com",
	        "charset: UTF-8",
	        "Connection: keep-alive",
	        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
	        "Upgrade-Insecure-Requests: 1",
	        "DNT:1",
	        "Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
			));
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
	    // 在HTTP请求头中"Referer: "的内容。
	    curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
	    curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT,120);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
		
	    $html = curl_exec($ch);
	    $html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
	    curl_close($ch);
	    if($html === false){
	        $msg = curl_error($ch);
	    }else{
	    	$gameinfo = array();
	    	preg_match_all('/yazhi-([\s\S]*?)\.shtml/',$html,$gameinfo);
	    	$done = $fail = $none = 0;
	    	foreach($gameinfo[1] as $game){
		    	$url = "http://odds.500.com/fenxi/yazhi-".$game.".shtml";
		    	$ch = curl_init();
		    	// 设置浏览器的特定header
		    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    	"Host: odds.500.com",
		    	"charset: UTF-8",
		    	"Connection: keep-alive",
		    	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		    	"Upgrade-Insecure-Requests: 1",
		    	"DNT:1",
		    	"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
		    	));
		    	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
		    	// 在HTTP请求头中"Referer: "的内容。
		    	curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
		    	curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    	curl_setopt($ch, CURLOPT_URL, $url);
		    	curl_setopt($ch, CURLOPT_TIMEOUT,120);
		    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
		    	$html = '';
		    	$html = curl_exec($ch);
		    	$html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
		    	curl_close($ch);		    	
		    	//公司
		    	$cinfo = array();
		    	preg_match_all('/<tr class="tr[1,2]" xls="row" id="([\s\S]*?)"/',$html,$cinfo);
		    	//如果有开盘		    	
		    	if(!empty($cinfo)){
		    		$this->load->model('OA_Pan');
		    		$score = array();
		    		//比分
		    		$partten = '/<p class="odds_hd_bf"><strong>(.*)\<\/strong><\/p>/';
		    		preg_match($partten,$html,$score);
		    		if(!empty($score[1])){
		    			$scorearr = array();
		    			$scorearr = explode(":",$score[1]);
		    		}
		    		//比赛正常完场，有比分
		    		if(isset($scorearr[1])&&is_numeric($scorearr[1])){
			    		//盘口
			    		$paninfo = array();
			    		preg_match_all('/class="pl_table_data">([\s\S]*?)<\/tr>/',$html,$paninfo);
			    		$clist = $this->OA_Pan->getCompany();
			    		$clist = array_column($clist,'link_id');
			    		$panw = $this->panw;
			    		$panwKey = array_flip($panw);
			    		$addlist = array();
				    	$len = count($cinfo[1])>12?12:(count($cinfo[1])-1);
				    	for($i=0;$i<=$len;$i++){
					    	$data = array();
					    	if(in_array($cinfo[1][$i],$clist)){
					    		$data['apan_game'] = $game;
					    		$data['apan_company'] = $cinfo[1][$i];
					    		//末盘
					    		$panl = array();
					    		preg_match_all('/">([\s\S]*?)<\/td>/',$paninfo[1][2*$i+2],$panl);
					    		$data['lpan_home_w'] = substr($panl[1][0],0,5);
					    		$panl[1][1] = str_replace('<font color="red"> 升</font>','',$panl[1][1]);
					    		$panl[1][1] = str_replace('<font color="blue"> 降</font>','',$panl[1][1]);
					    		$data['lpan_pan'] = $panwKey[trim($panl[1][1])];
					    		$data['lpan_away_w'] = substr($panl[1][2],0,5);
					    		//初盘
					    		$pani = array();
					    		preg_match_all('/">([\s\S]*?)<\/td>/',$paninfo[1][2*$i+3],$pani);
					    		$data['ipan_home_w'] = $pani[1][0];
					    		$txti = explode(" ",$pani[1][1]);
					    		$data['ipan_pan'] = $panwKey[$txti[0]];
					    		$data['ipan_away_w'] = $pani[1][2];
					    		//比分
					    		$data['home_score'] = $scorearr[0];
					    		$data['away_score'] = $scorearr[1];
					    		$addlist[] = $data;
				    		}
				    	}
				    	if(!empty($addlist)){
				    		if($this->OA_Pan->add($addlist)===TRUE){
				    			$done++;
				    		}else{
				    			$fail++;
				    		}
				    	}else{
				    		echo $none++;
				    	}
		    		}
		    	}
	    	}
	    	$msg = '';
	    	if($done>0){
	    		$msg .= '成功：'.$done.'场；';
	    	}
	        if($fail>0){
	    		$msg .= '失败：'.$fail.'场；';
	    	}
	    	if($none>0){
	    		$msg .= '无数据：'.$none.'场；';
	    	}
		}
		$msg = '?msg='.urlencode($msg);
		redirect(formatUrl('pan/index'.$msg));
	}
	
	/**
	 *
	 * 欧指
	 */
	public function euro()
	{
		
		header("content-type:text/html;charset=utf-8");
		$day = $this->input->get('d');
		$url = "http://odds.500.com/index_jczq_".$day.".shtml";
		$ch = curl_init();
		// 设置浏览器的特定header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Host: odds.500.com",
		"charset: UTF-8",
		"Connection: keep-alive",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Upgrade-Insecure-Requests: 1",
		"DNT:1",
		"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
		));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
		// 在HTTP请求头中"Referer: "的内容。
		curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
		curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,120);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
	
		$html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
		curl_close($ch);
		if($html === false){
			$msg = curl_error($ch);
		}else{
			$gameinfo = array();
			preg_match_all('/ouzhi-([\s\S]*?)\.shtml/',$html,$gameinfo);
			$done = $fail = $none = 0;
			foreach($gameinfo[1] as $game){
				$url = "http://odds.500.com/fenxi/ouzhi-".$game.".shtml";
				$ch = curl_init();
				// 设置浏览器的特定header
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Host: odds.500.com",
				"charset: UTF-8",
				"Connection: keep-alive",
				"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
				"Upgrade-Insecure-Requests: 1",
				"DNT:1",
				"Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
				));
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
				// 在HTTP请求头中"Referer: "的内容。
				curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com");
				curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_TIMEOUT,120);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
				$html = '';
				$html = curl_exec($ch);
				$html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
				curl_close($ch);
				//公司
				$cinfo = array();
				preg_match_all('/<tr class="tr[1,2]" id="([\s\S]*?)" ttl="zy"/',$html,$cinfo);
				//如果有开盘
				if(!empty($cinfo)){
					$this->load->model('OA_Pan');
					$score = array();
					//比分
					$partten = '/<p class="odds_hd_bf"><strong>(.*)\<\/strong><\/p>/';
					preg_match($partten,$html,$score);
					if(!empty($score[1])){
						$scorearr = array();
						$scorearr = explode(":",$score[1]);
					}
					//比赛正常完场，有比分
					if(isset($scorearr[1])&&is_numeric($scorearr[1])){
						//盘口
						$paninfo = array();
						preg_match_all('/class="pl_table_data">([\s\S]*?)<\/tbody>/',$html,$paninfo);
						$clist = $this->OA_Pan->getCompany(1);
						$clist = array_column($clist,'link_id');
						$addlist = array();
						$len = count($cinfo[1])>12?12:(count($cinfo[1])-1);
						for($i=0;$i<=$len;$i++){
							$data = array();
							if(in_array($cinfo[1][$i],$clist)){
								$data['epan_game'] = $game;
								$data['epan_company'] = $cinfo[1][$i];
								//指数
								$panl = array();
								preg_match_all('/style="cursor:pointer" >([\s\S]*?)<\/td>/',$paninfo[1][4*$i+3],$panl);
								//初盘
								$data['ipan_home_w'] = substr($panl[1][0],0,4);
								$data['ipan_draw_w'] = substr($panl[1][1],0,4);
								$data['ipan_away_w'] = substr($panl[1][2],0,4);
								//末盘
								$data['lpan_home_w'] = substr($panl[1][3],0,4);
								$data['lpan_draw_w'] = substr($panl[1][4],0,4);
								$data['lpan_away_w'] = substr($panl[1][5],0,4);						
								//比分
								$data['home_score'] = $scorearr[0];
								$data['away_score'] = $scorearr[1];
								$addlist[] = $data;
							}
						}
						if(!empty($addlist)){
							if($this->OA_Pan->add($addlist,1)===TRUE){
								$done++;
							}else{
								$fail++;
							}
						}else{
							$none++;
						}
					}
				}
			}
			$msg = '';
			if($done>0){
				$msg .= '成功：'.$done.'场；';
			}
			if($fail>0){
				$msg .= '失败：'.$fail.'场；';
			}
			if($none>0){
				$msg .= '无数据：'.$none.'场；';
			}
		}
		$msg = '?msg='.urlencode($msg);
		redirect(formatUrl('pan/index'.$msg));
	}
	
	private function calculatePan($data,$type){
		$this->load->model('OA_Pan');		
		$info = array();
		if($type==1){
			//精确匹配
			$pan1 = $this->OA_Pan->getAsiaPan($data);			
			$info[] = $this->stat($pan1); 
			//匹配初盘末盘水位
			$data2 = $data;
			unset($data2['lpan_pan']);
			unset($data2['ipan_pan']);
			$pan2 = $this->OA_Pan->getAsiaPan($data2);
			$info[] = $this->stat($pan2);
			//匹配初盘末盘
			$data3 = $data;
			unset($data3['lpan_home_w']);
			unset($data3['lpan_away_w']);
			unset($data3['ipan_home_w']);
			unset($data3['ipan_away_w']);
			$pan3 = $this->OA_Pan->getAsiaPan($data3);
			$info[] = $this->stat($pan3);
			//匹配末盘和水位
			$data4 = $data;
			unset($data4['ipan_home_w']);
			unset($data4['ipan_away_w']);
			unset($data4['ipan_pan']);
			$pan4 = $this->OA_Pan->getAsiaPan($data4);
			$info[] = $this->stat($pan4);
			//匹配末盘水位
			$data5['lpan_home_w'] = $data['lpan_home_w'];
			$data5['lpan_away_w'] = $data['lpan_away_w'];
			$data5['apan_company'] = $data['apan_company'];
			$pan5 = $this->OA_Pan->getAsiaPan($data5);
			$info[] = $this->stat($pan5);
			//匹配末盘
			$data6['lpan_pan'] = $data['lpan_pan'];
			$data6['apan_company'] = $data['apan_company'];
			$pan6 = $this->OA_Pan->getAsiaPan($data6);
			$info[] = $this->stat($pan6);
			//匹配末盘水位大小情况
			$pan7 = $this->OA_Pan->getAsiaPanByW($data5);
			$info[] = $this->stat($pan7,0,1);			
		}else if($type==2){
			//精确匹配
			$pan1 = $this->OA_Pan->getEuroPan($data);
			$info[] = $this->stat($pan1,1); 
			//匹配末盘指数
			$data2 = $data;
			unset($data2['ipan_home_w']);
			unset($data2['ipan_draw_w']);
			unset($data2['ipan_away_w']);
			$pan2 = $this->OA_Pan->getEuroPan($data2);
			$info[] = $this->stat($pan2,1);
			//匹配末盘水位大小情况
			$pan3 = $this->OA_Pan->getEuroPanByW($data2);
			$info[] = $this->stat($pan3,1,1);
		}
		return $info;
	}
	
	private function stat($data,$type=0,$bs=0){
		$info = array();
		$info['total'] = count($data);
		$info['win'] = $info['draw'] = $info['lose'] = 0;
		if($type==0){			
			foreach($data as $v){
				$panInt = $this->panint;
				if(isset($panInt[$v['lpan_pan']])){
					if($bs==1){					
						//按冷热区分
						if($v['lpan_home_w']>$v['lpan_away_w']){						
							if($v['home_score']-$panInt[$v['lpan_pan']]>$v['away_score']){
								$info['lose']++;
							}else if($v['home_score']-$panInt[$v['lpan_pan']]==$v['away_score']){
								$info['draw']++;
							}else{
								$info['win']++;
							}
						}else{
							if($v['home_score']-$panInt[$v['lpan_pan']]>$v['away_score']){
								$info['win']++;
							}else if($v['home_score']-$panInt[$v['lpan_pan']]==$v['away_score']){
								$info['draw']++;
							}else{
								$info['lose']++;
							}
						}					
					}else{
						if($v['home_score']-$panInt[$v['lpan_pan']]>$v['away_score']){
							$info['win']++;
						}else if($v['home_score']-$panInt[$v['lpan_pan']]==$v['away_score']){
							$info['draw']++;
						}else{
							$info['lose']++;
						}
					}
				}
			}
		}else{
			foreach($data as $v){
				if($bs==1){
					//按冷热区分
					if($v['lpan_home_w']>$v['lpan_away_w']){
						if($v['home_score']>$v['away_score']){
							$info['lose']++;
						}else if($v['home_score']==$v['away_score']){
							$info['draw']++;
						}else{
							$info['lose']++;
						}
					}else{
						if($v['home_score']>$v['away_score']){
							$info['win']++;
						}else if($v['home_score']==$v['away_score']){
							$info['draw']++;
						}else{
							$info['lose']++;
						}
					}
				}else{
					if($v['home_score']>$v['away_score']){
						$info['win']++;
					}else if($v['home_score']==$v['away_score']){
						$info['draw']++;
					}else{
						$info['lose']++;
					}
				}
			}
		}
		return $info;
	}
	
}