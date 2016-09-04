<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends ZQD_Controller
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
	}

	/**
	 * 
	 * 订单首页
	 */
	public function index()
	{
	 	$data = array();
	    if(checkRight('order_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] = $this->input->get('msg');
			unset($_GET['msg']);
		}
		$this->load->model('OA_Order');
		$keyword = $this->input->get();
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('order/index').'?', $this->OA_Order->getCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Order->getList($keyword, $offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$data['dataList'] = $dataList;
		$data['keyword'] = $keyword;
		$data['status'] = $this->config->item('order_status');	
		$data['shipping'] = $this->config->item('shipping_postage');
		$this->load->model('OA_Area');
		$data['area'] = $this->OA_Area->getAreaName();
		$this->showView('orderList', $data);
	}

	/**
	 *
	 * 订单详情 
	 */
	public function view()
	{
		$data = array();
		if(checkRight('order_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] = $this->input->get('msg');
			unset($_GET['msg']);
		}
		$this->load->model('OA_Order');
		$id = 0;
		if($this->input->get('id')){
			$id = $this->input->get('id');
			$data['info'] = $this->OA_Order->getInfo($id);
			if(empty($data['info'])){
				redirect(formatUrl('order/index'));
			}
			$data['mainImg'] = explode('|',$data['info']['good_main_img']);
			$data['status'] = $this->config->item('order_status');
			$data['ship'] = $this->config->item('shipping_postage');
			$this->load->model('OA_Area');
			$data['area'] = $this->OA_Area->getAreaName();
		}else{
			redirect(formatUrl('order/index'));
		}
		$this->showView('orderInfo', $data);
	}
	
	/**
	 *
	 * 订单删除
	 */
	public function doDel()
	{
		$data = array();
		if(checkRight('order_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$this->load->model('OA_Order');
		$id = 0;
		if($this->input->get('id')){
			$id = $this->input->get('id');
			$this->OA_Order->del($id);
		}
		redirect(formatUrl('order/index'));
	}
	
	/**
	 * 
	 * 订单发货
	 */
	public function doSend()
	{
		$data = array();
		if(checkRight('order_ship') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$data = $this->input->post();
		$this->load->model('OA_Order');
		$id = 0;
		if($data['soid']){
			$id = $data['soid'];
			$up['order_no'] = $id;
			$up['shipping'] = $data['shipping'];
			$up['shipping_no'] = $data['shipping_no'];
			$up['order_status'] = 3;
			$up['sent_time'] = time();
			$this->OA_Order->update($up);
		}
		redirect(formatUrl('order/index'));
	}
}