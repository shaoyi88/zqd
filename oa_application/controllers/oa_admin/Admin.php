<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends ZQD_Controller 
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
		$this->load->helper('qyh_helper');
	}
	
	/**
	 * 
	 * 修改密码
	 */
	public function changePassword()
	{
		$data = $this->input->post();
		$data['admin_password'] = md5($data['admin_password']);
		$this->load->model('OA_Admin');
		$data['info'] = $this->OA_Admin->update($data);
		$this->session->sess_destroy();
		redirect(formatUrl('login/index?msg='.urlencode('请使用新密码重新登录')));
	}
	
	/**
	 * 
	 * 系统用户首页
	 */
	public function index()
	{
		$data = array();
		if(checkRight('admin_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] = $this->input->get('msg');
		}
		$this->load->model('OA_Admin');
		$keyword = '';
		if($this->input->get('keyword')){
			$keyword = $this->input->get('keyword');
		}
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('admin/index').'?', $this->OA_Admin->getCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Admin->getList($keyword, $offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$data['adminList'] = $dataList;
		$data['keyword'] = $keyword;
		$this->showView('adminList', $data);
	}
	
	/**
	 * 
	 * 增加/编辑用户
	 */
	public function add()
	{
		$data = array();
		$this->load->model('OA_Role');
		$data['roleList'] = $this->OA_Role->getAll();
		if($this->input->get('id')){
			if(checkRight('admin_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$id = $this->input->get('id');
			$this->load->model('OA_Admin');
			$data['info'] = $this->OA_Admin->getInfo($id);
			$data['typeMsg'] = '编辑';
		}else{
			if(checkRight('admin_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data['typeMsg'] = '新增';
		}
		$this->showView('adminAdd', $data);
	}
	
	/**
	 * 
	 * 增加/编辑逻辑
	 */
	public function doAdd()
	{
		$data = array();
		if($this->input->post('admin_id')){
			if(checkRight('admin_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data = $this->input->post();
			if($data['admin_password']){
        		$data['admin_password'] = md5($data['admin_password']);
        	}else{
        		unset($data['admin_password']);
        	}
			$this->load->model('OA_Admin');
			$this->OA_Admin->update($data);
			//企业号更新成员
			$admininfo = $this->OA_Admin->getInfo($data['admin_id']);
			$func = $content = array();
			$func[0] = 'user';
			$func[1] = 'update';
			$content['name'] = urlencode($data['admin_name']);
			$content['userid'] = $admininfo['admin_qyh'];
			$this->load->model('OA_Role');
			$rinfo = $this->OA_Role->getInfo($data['admin_role']);
			$content['department'] = isset($rinfo['qyh_id'])?$rinfo['qyh_id']:1;
			$content['position'] = urlencode($data['admin_post']);
			$content['mobile'] = $data['admin_phone'];
			qyh_operate($func,$content);
			redirect(formatUrl('admin/index'));
		}else{
			if(checkRight('admin_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data = $this->input->post();
			$data['admin_password'] = md5($data['admin_password']);
			$data['reg_time'] = time();
			$data['admin_qyh'] = 'z'.time().rand(10000,99999);
			$this->load->model('OA_Admin');
			$msg = '';
			$adminInfo = $this->OA_Admin->queryAdminByAccount($data['admin_account']);
			if(empty($adminInfo)){
				if($this->OA_Admin->add($data) === FALSE){
					$msg = '&msg='.urlencode('创建失败');
				}else{
					//企业号新增成员
					$func = $content = array();
					$func[0] = 'user';
					$func[1] = 'create';
					$content['name'] = urlencode($data['admin_name']);
					$content['userid'] = $data['admin_qyh'];
					$this->load->model('OA_Role');
			        $rinfo = $this->OA_Role->getInfo($data['admin_role']);
					$content['department'] = isset($rinfo['qyh_id'])?$rinfo['qyh_id']:1;
					$content['position'] = urlencode($data['admin_post']);
					$content['mobile'] = $data['admin_phone'];
					qyh_operate($func,$content);
				}
			}else{
				$msg = '&msg='.urlencode('该系统用户已存在，请勿重复新增');
			}
			redirect(formatUrl('admin/index?'.$msg));
		}
	}
	
	/**
	 * 
	 * 删除
	 */
	public function doDel()
	{
		$data = array();
		if(checkRight('admin_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('id');
		$this->load->model('OA_Admin');
		$admininfo = $this->OA_Admin->getInfo($id);
		$this->OA_Admin->del($id);
		//企业号删除成员
		$func = $content = array();
		$func[0] = 'user';
		$func[1] = 'delete';
		$func[2] = 'GET';
		$func[3] = 'userid='.$admininfo['admin_qyh'];
		qyh_operate($func,$content);
		redirect(formatUrl('admin/index'));
	}
	
}