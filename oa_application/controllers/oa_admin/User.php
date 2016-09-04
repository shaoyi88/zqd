<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends ZQD_Controller 
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
	}
	
	/**
	 * 
	 * 会员首页
	 */
	public function index()
	{
		$data = array();
		if(checkRight('user_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] = $this->input->get('msg');
			unset($_GET['msg']);
		}
		$this->load->model('OA_User');
		$keyword = $this->input->get();
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('user/index').'?', $this->OA_User->getCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_User->getList($keyword, $offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$data['dataList'] = $dataList;
		$data['keyword'] = $keyword;		
		$this->showView('userList', $data);
	}
	
	/**
	 * 
	 * 现金充值
	 */
	public function add()
	{
		$data = array();
		$data['user_type'] = $this->config->item('user_type');
		if($this->input->get('id')){
			if(checkRight('user_edit') === FALSE || checkRight('cash_return') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$id = $this->input->get('id');
			$this->load->model('OA_User');
			$data['info'] = $this->OA_User->getInfo($id);
			$data['typeMsg'] = '现金充值';
		}else{
			redirect(formatUrl('user/index?'));
		}
		$this->showView('userAdd', $data);
	}
	
	/**
	 * 
	 * 现金充值逻辑
	 */
	public function doAdd()
	{
		$data = array();
		$data = $this->input->post();
		if($data['user_id']){
			if(checkRight('user_edit') === FALSE || checkRight('cash_return') === FALSE){
				$this->showView('denied', $data);
				exit;
			}		
			$this->load->model('OA_User');
			$info = $this->OA_User->getInfo($id);
			$updata['user_id'] = $data['user_id'];
			$updata['user_account'] = $data['add_account']+$info['user_account'];
			$this->OA_User->update($updata);
		}
		redirect(formatUrl('user/index'));
	}
	
	/**
	 * 
	 * 删除
	 */
	public function doDel()
	{
		$data = array();
		if(checkRight('user_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('id');
		$this->load->model('OA_User');
		$info = $this->OA_User->getInfo($id);
		$msg = '';
		if($info['focus_status']>0){
			$msg = '?msg='.urlencode('该会员已关注微信公众号，无法删除');
		}else{
		    $this->OA_User->del($id);
		}
		redirect(formatUrl('user/index').$msg);
	}
	
	/**
	 * 
	 * 推广管理
	 */
	public function campaign(){
		$data = array();
		if(checkRight('campaign_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$data['dataList'] = array();
		$this->showView('campaignList', $data);
	}
	
	/**
	 *
	 * 提现管理
	 */
	public function cash(){
		$data = array();
		if(checkRight('cash_apply') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$data['dataList'] = array();
		$this->showView('cashList', $data);
	}
}