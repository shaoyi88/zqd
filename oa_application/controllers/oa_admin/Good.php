<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Good extends ZQD_Controller
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
	}

	/**
	 *
	 * 商品列表
	 */
	public function index()
	{
		$data = $ids = $sku = array();
		if(checkRight('good_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$this->load->model('OA_Good');
		$keyword = $this->input->get();
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('good/index').'?', $this->OA_Good->getCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Good->getList($keyword, $offset, PER_COUNT);
		if(!empty($dataList)){
			foreach($dataList as $v){
				$ids[] = $v['good_id'];
			}
			$skuinfo = $this->OA_Good->getSku($ids);
			foreach($skuinfo as $s){
				$sku[$s['sku_good_id']][] = $s;
			}
		}
		$data['pageUrl'] = $pageUrl;
		$data['dataList'] = $dataList;
		$data['keyword'] = $keyword;
		$data['good_type'] = $this->config->item('good_type');
		$data['shelfStatus'] = $this->config->item('shelf_status');
		$data['cat'] = $this->OA_Good->getCatNameList();
		$data['sku'] = $sku;
		$this->showView('goodList', $data);
	}

    /*
     * 
     * 添加商品
     */	
	public function add(){
		$data = array();
		if(checkRight('good_add') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$this->load->model('OA_Good');
		if($this->input->get('gid')){
			if(checkRight('good_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$id = $this->input->get('gid');			
			$data['info'] = $this->OA_Good->getInfo($id);
			$mainImg = $sku = array();
			if($data['info']['good_main_img']){
				$mainImg = explode('|',$data['info']['good_main_img']);
			}
			$data['mainImg'] = $mainImg;
			$ids[] = $id;
			$skuinfo = $this->OA_Good->getSku($ids);
			foreach($skuinfo as $s){
				$sku[$s['sku_good_id']][] = $s;
			}
			$data['sku'] = $sku;
			$data['typeMsg'] = '编辑';
		}else{
			if(checkRight('good_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data['typeMsg'] = '新增';
		}
		$data['shipping'] = $this->OA_Good->getShippingList(0,0);
		$data['good_type'] = $this->config->item('good_type');
		$data['catTree'] = $this->OA_Good->getListTree(0);
		$this->showView('goodAdd', $data);
	}
	
	/*
	 * 
	 * 删除商品
	 */
	public function doDel(){
		$data = array();
		if(checkRight('good_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}				
		if($this->input->get('id')){
			$id = $this->input->get('id');
		$this->load->model('OA_Order');
			if($this->OA_Order->checkGood($id)){
				$msg = '?msg='.urlencode('商品存在于订单表中，无法删除');
				redirect(formatUrl('good/index'.$msg));
			}
			
		}
		redirect(formatUrl('good/index'.$msg));
	}
	
	/*
	 *
	 * 检查商品是否存在于订单中
	 */
	public function checkGood(){
		$data = array();
		$data['result'] = 0;
		if(checkRight('good_del') === FALSE){
			$data['result'] = -1;
		}
		if($this->input->get('id')){
			$id = $this->input->get('id');
			$this->load->model('OA_Order');
			if($this->OA_Order->checkGood($id)){
				$data['result'] = 1;
			}
		}
		$this->send_json($data);
	}
	
	/**
	 * 商品详情
	 */
	public function view(){
		$data = $shiparr = $mainImg = $sku = array();
	    if(checkRight('good_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('gid');
		if(!is_numeric($id)){
			redirect(formatUrl('good/index'));
		}
		$this->load->model('OA_Good');
		$data['info'] = $this->OA_Good->getInfo($id);
		$shipping = $this->OA_Good->getShippingList(0,0);
		foreach($shipping as $v){
			$shiparr[$v['shipping_id']] = $v;
		}
		if($data['info']['good_main_img']){
			$mainImg = explode('|',$data['info']['good_main_img']);
		}
		$ids[] = $id;
		$skuinfo = $this->OA_Good->getSku($ids);
		foreach($skuinfo as $s){
			$sku[$s['sku_good_id']][] = $s;
		}
		$data['sku'] = $sku;
		$data['shipping'] = $shiparr;
		$data['good_type'] = $this->config->item('good_type');
		$data['category'] = $this->OA_Good->getCatNameList();
		$data['mainImg'] = $mainImg ;
		$data['shelfStatus'] = $this->config->item('shelf_status');
		$this->showView('goodView', $data);
	}
	
	/**
	 * 上传主图
	 */
	public function uploadMainPic(){
		if(checkRight('good_add') === FALSE){
			$result  = array('status'=>0,'msg'=>'登录超时');
			$this->send_json($result);
			exit();
		}
		if($_FILES["image"]["error"]!=0){
			$result = array('status'=>0,'msg'=>'上传错误');
			$this->send_json($result);
			exit();
		}
		
		if( !in_array($_FILES["image"]["type"], array('image/jpg','image/png','image/gif','image/jpeg','image/bmp')) ){
			$result = array('status'=>0,'msg'=>'图片格式错误');
			$this->send_json($result);
			exit();
		}
		
		if($_FILES["image"]["size"] > 2000000){//判断是否大于2M
			$result = array('status'=>0,'msg'=>'图片大小超过限制');
			$this->send_json($result);
			exit();
		}
		
		$filename = substr(md5(time()),0,10).mt_rand(1,10000);
		$ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
		
		$localName = "./upload/good/main/".$filename.'.'.$ext;
		
		if(move_uploaded_file($_FILES["image"]["tmp_name"], $localName) == true) {
			$lurl = '/'.$localName;
			$result  = array('status'=>1,'msg'=>$lurl);
		}else{
			$result  = array('status'=>0,'msg'=>'error');
		}
		$this->send_json($result);
	}
	
	public function doAdd(){
		$data = $good = $skulist = array();
		$this->load->model('OA_Good');
		$data = $this->input->post();
		unset($data['image']);
		$good = $data;
		$good['good_main_img'] = implode('|',$good['imgs']);
		unset($good['imgs']);
		unset($good['gskuno']);
		unset($good['gskunum']);
		unset($good['gskuinfo']);
		unset($good['gskuprice']);
		unset($good['gskucost']);
		unset($good['gskushow']);
		$msg = '';
		if($this->input->post('good_id')){
			if(checkRight('good_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$this->OA_Good->update($good);
			if(!empty($data['gskushow'])){
			    if(isset($data['gskuno'])){
					foreach($data['gskuno'] as $k=>$v){
						$sku = array();
						$sku['sku_no'] = $v;
						if(isset($data['gskunum'][$k])){
							$sku['sku_num'] = $data['gskunum'][$k];
						}
						if(isset($data['gskuinfo'][$k])){
							$sku['sku_info'] = $data['gskuinfo'][$k];
						}
						if(isset($data['gskuprice'][$k])){
							$sku['sku_price'] = $data['gskuprice'][$k];
						}
						if(isset($data['gskucost'][$k])){
							$sku['sku_cost'] = $data['gskucost'][$k];
						}
						$sku['sku_show'] = $data['gskushow'][$k];
						$this->OA_Good->updateSKU($sku);
					}
			    }
			}
			if(!empty($skulist)){
				$this->OA_Good->addSKU($skulist);
			}
		}else{
			if(checkRight('good_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$good['add_time'] = time();
			$result = $this->OA_Good->add($good);
			if($result === FALSE){
				$msg = '?msg='.urlencode('创建失败');
			}else{				
				if(isset($data['gskuno'])){
					foreach($data['gskuno'] as $k=>$v){
				        $sku = array();
				        $sku['sku_no'] = $v;
				        if(isset($data['gskunum'][$k])){
				         	$sku['sku_num'] = $data['gskunum'][$k];
				        }
				        if(isset($data['gskuinfo'][$k])){
				        	$sku['sku_info'] = $data['gskuinfo'][$k];
				        }
				        if(isset($data['gskuprice'][$k])){
				        	$sku['sku_price'] = $data['gskuprice'][$k];
				        }
				        if(isset($data['gskucost'][$k])){
				        	$sku['sku_cost'] = $data['gskucost'][$k];
				        }
				        $show = date('ymdhis').$result;
				        $sku['sku_show'] = $show;
				        $show++;
				        $sku['sku_good_id'] = $result;
				        $skulist[] = $sku;
					}
				}
				if(!empty($skulist)){
					$this->OA_Good->addSKU($skulist);
				}
			}
		}
		redirect(formatUrl('good/index'.$msg));
	}
	
	/**
	 *
	 * 邮费模板
	 */
	public function shipping()
	{
		$data = array();
		if(checkRight('shipping_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$this->load->model('OA_Good');
		$keyword = $this->input->get();
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('good/shipping').'?', $this->OA_Good->getShippingCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Good->getShippingList($keyword, $offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$data['dataList'] = $dataList;
		$data['keyword'] = $keyword;
		$data['type'] = $this->config->item('shipping_type');
		$data['postage'] = $this->config->item('shipping_postage');
		$this->showView('shippingList', $data);
	}
	
	/*
	 *
	 * 添加邮费模板
	 */
	public function addShipping(){
		$data = array();
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		if($this->input->get('shipping_id')){
			if(checkRight('shipping_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$id = $this->input->get('shipping_id');
			$this->load->model('OA_Good');
			$data['info'] = $this->MIS_Good->getShippingInfo($id);
			$data['typeMsg'] = '编辑';
		}else{
			if(checkRight('shipping_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data['typeMsg'] = '新增';
		}
		$data['province'] = $this->config->item('shipping_province');
		$data['shipping_type'] = $this->config->item('shipping_type');
		$data['shipping_postage'] = $this->config->item('shipping_postage');
		$this->showView('shippingAdd', $data);
	}
	
	public function doAddShipping(){
		$this->load->model('OA_Good');
		$data = $this->input->post();
		$msg = '';
		if($data['shipping_type']==1||$data['shipping_type']==2){
			$content = array();
			foreach($data['pr'] as $k=>$v){
				$arr = array();
				$arr[0] = $v;
				$arr[1] = $data['fgp'][$k]?$data['fgp'][$k]:0;
				$arr[2] = $data['agp'][$k]?$data['agp'][$k]:0;
				$content[] = $arr;
			}
			unset($data['pr']);
			unset($data['fgp']);
			unset($data['agp']);
			$data['shipping_content'] = json_encode($content);
		}		
		if($this->input->post('sid')){
			if(checkRight('shipping_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$this->OA_Good->updateShipping($data);
		}else{
			if(checkRight('shipping_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}			
			$result = $this->OA_Good->addShipping($data);
			if($result === FALSE){
				$msg = '?msg='.urlencode('创建失败');
			}			
		}
		redirect(formatUrl('good/shipping'.$msg));
	}
	
	public function doDelShipping()
	{
		$data = $idsarr = array();
		if(checkRight('shipping_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('did');
		$this->load->model('OA_Good');
		$this->OA_Good->delShipping($id);
		redirect(formatUrl('good/shipping'));
	}
	
	public function checkShipping(){
		$id = 0;
		if($this->input->get('id')){
			$id = $this->input->get('id');
		}
		$data = array();
		$data['status'] = 0;
		$idsarr = $this->OA_Good->checkShippingByid($id);
		if(in_array($id,$idsarr)){
			$data['status'] = 1;
		}
		$this->send_json($data);
	}
	
	/**
	 *
	 * 商品分类列表
	 */
	public function category()
	{
		$data = array();
		if(checkRight('good_cat_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$this->load->model('OA_Good');
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('good/category').'?', $this->OA_Good->getCatCount(), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Good->getCatList($offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$ninfo 			= array();
		$cat_info	=	array();
		foreach($dataList as $v){
			if($v['parent_id']==0){
				$ninfo[$v['cat_id']] = $this->OA_Good->queryCatByPid($v['cat_id']);
				$cat_info[$v['cat_id']]	= $this->OA_Good->getCatInfo($v['cat_id']);
			}else{
				$ninfo[$v['parent_id']] = $this->OA_Good->queryCatByPid($v['parent_id']);
				$cat_info[$v['parent_id']]	= $this->OA_Good->getCatInfo($v['parent_id']);
			}
		}
		$data['category'] = $this->OA_Good->getCatNameList();
		$data['dataList'] = $ninfo;
		$data['cat_info'] = $cat_info;
		$this->showView('goodCatList', $data);
	}
	
	/**
	 *
	 * 获取二级分类
	 */
	public function getScategory()
	{
		$pid = 0;
		if($this->input->get('pid')){
			$pid = $this->input->get('pid');
		}
		$catInfo = $this->OA_Good->queryCatByPid($pid);
		$this->send_json($catInfo);
	}
	
	/**
	 *
	 * 添加分类
	 */
	public function addCat()
	{
		$data = $nInfo = array();	
		$this->load->model('OA_Good');
		$fCat = $this->OA_Good->queryCatByPid(0);
		if($this->input->get('cid')){
			if(checkRight('good_cat_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$cid = $this->input->get('cid');
			$data['typeMsg'] = '编辑';
			$data['info'] = $this->OA_Good->getCatInfo($cid);
			$data['nInfo'] = $this->OA_Good->queryCatByPid($cid);
			if($data['info']['parent_id']>0){
				$data['nInfo'] = $this->OA_good->queryCatByPid($data['info']['parent_id']);
				$data['info'] = $this->OA_good->getCatInfo($data['info']['parent_id']);
			}
			//已经使用过的分类id
		    $data['ids'] = $this->OA_Good->checkCatByCid($cid);
		}else{
			if(checkRight('good_cat_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data['typeMsg'] = '新增';
		}
		$data['catInfo'] = $fCat;
		$this->showView('goodCatAdd', $data);
	}
	
	public function doCatAdd()
	{
		$data = $scatarr = $scatid = $idsarr = array();
		$this->load->model('OA_Good');
		if($this->input->post('cat_id')){
			if(checkRight('good_cat_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}			
			$data = $this->input->post();
			$cat['cat_id'] = $data['cat_id'];
			$cat['cat_name'] = $data['cat_name'];
			$this->OA_Good->updateCat($cat);
			if(isset($data['scatid'])){
				$scatid = $data['scatid'];
			}
			//排除已存在于商品表
			$idsarr = $this->OA_Good->checkCatByCid($data['cat_id']);
			$scatid = array_merge($scatid,$idsarr);
			$scatid = array_unique($scatid);
			//根据编辑删除的二级分类情况删除相应二级分类
			$this->OA_Good->delscat($scatid,$data['cat_id']);
			if(isset($data['scat'])&&!empty($data['scat'])){
				$scatarr = $data['scat'];
				foreach($scatarr as $k=>$v){
					$upsta = array();
					if(isset($scatid[$k])){
						//根据编辑科室情况更新相应科室
						$upsta['cat_id'] = $scatid[$k];
						$upsta['cat_name'] = $v;
						$this->OA_Good->updateCat($upsta);
					}else{
						$upsta['cat_name'] = $v;
						$upsta['parent_id'] = $data['cat_id'];
						$this->OA_Good->addCat($upsta);
					}
				}
			}
			redirect(formatUrl('good/category'));
		}else{
			if(checkRight('good_cat_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data = $this->input->post();
			$msg = '';
				
			$result = $this->OA_Good->addCat($data);
			if($result === FALSE){
				$msg = '?msg='.urlencode('创建失败');
				redirect(formatUrl('good/category'.$msg));
			}else{
				redirect(formatUrl('good/addCat?cid='.$result));
			}
		}
	}
	
	public function doCatDel()
	{
		$data = $idsarr = array();
		if(checkRight('good_cat_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('did');
		$this->load->model('OA_Good');
		$idsarr = $this->OA_Good->checkCatByCid($data['cat_id']);
		if(in_array($id,$idsarr)){
			$msg = '?msg='.urlencode('该分类已被商品使用，无法删除');
			redirect(formatUrl('good/category'.$msg));
		}else{
			//删除分类（连同二级分类一起删除）	    
			$this->OA_Good->delCat($id);
			redirect(formatUrl('good/category'));
		}
	}
	
	public function checkCat(){
		$id = 0;
		if($this->input->get('id')){
			$id = $this->input->get('id');
		}
		$data = array();
		$data['status'] = 0;
		$idsarr = $this->OA_Good->checkCatByCid($id);
		if(in_array($id,$idsarr)){
			$data['status'] = 1;
		}
		$this->send_json($data);
	}
}