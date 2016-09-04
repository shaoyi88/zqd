<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends ZQD_Controller
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
	}

	/**
	 *
	 * 登录页面
	 */
	public function index()
	{
		$data = array();
		if($this->input->get('msg')){
			$data['msg'] =  $this->input->get('msg');
		}
		$data['layout'] = FALSE; //不使用layout文件
		$this->showView('login', $data);
	}

	/**
	 *
	 * 登录处理
	 */
	public function actionLogin()
	{
		if(($userAccount = $this->input->post('userAccount', TRUE)) === FALSE){
			redirect(formatUrl('login/index?msg='.urlencode('请填写账户')));
		}
		if(($userPassword = $this->input->post('userPassword', TRUE)) === FALSE){
			redirect(formatUrl('login/index?msg='.urlencode('请填写密码')));
		}
		$this->load->model('OA_Admin');
		if(($adminInfo = $this->OA_Admin->checkAdmin($userAccount, $userPassword)) === FALSE){
			redirect(formatUrl('login/index?msg='.urlencode('账户或密码错误')));
		}
		
		$info = array(
			'admin_id' => $adminInfo['admin_id'],
			'admin_name' => $adminInfo['admin_name']
		);
		if($adminInfo['admin_role'] == 0){
			$info['admin_role'] = 0;
			$info['admin_rights'] = 'all';
		}else{
			$this->load->model('OA_Admin');
			$roleInfo = $this->OA_Admin->getInfo($adminInfo['admin_role']);
			$info['admin_role'] = $adminInfo['admin_role'];
			$info['admin_rights'] = $roleInfo['role_rights'];
		}
		$this->session->set_userdata($info);
		redirect(formatUrl('home/index'));
	}
}