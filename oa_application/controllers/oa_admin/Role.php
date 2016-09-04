<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends ZQD_Controller 
{
	protected function initialize()
	{
		parent::initialize();
		checkLogin();
	}
	
	/**
	 * 分组权限首页
	 * Enter description here ...
	 */
	public function index()
	{
		$data = array();
		if(checkRight('role_list') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		if($this->input->get('msg')){
			$data['msg'] = $this->input->get('msg');
		}
		$this->load->model('OA_Role');
		$keyword = '';
		if($this->input->get('keyword')){
			$keyword = $this->input->get('keyword');
		}
		$offset = 0;
		$pageUrl = '';
		page(formatUrl('role/index').'?', $this->OA_Role->getCount($keyword), PER_COUNT, $offset, $pageUrl);
		$dataList = $this->OA_Role->getList($keyword, $offset, PER_COUNT);
		$data['pageUrl'] = $pageUrl;
		$data['roleList'] = $dataList;
		$data['keyword'] = $keyword;
		$this->showView('roleList', $data);
	}
	
	/**
	 * 
	 * 增加/编辑权限
	 */
	public function add()
	{
		$data = array();
		$data['roleList'] = $this->config->item('rights');
		if($this->input->get('id')){
			if(checkRight('role_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$id = $this->input->get('id');
			$this->load->model('OA_Role');
			$data['info'] = $this->OA_Role->getInfo($id);
			$data['roles'] = explode(',', $data['info']['role_rights']);
			$data['typeMsg'] = '编辑';
		}else{
			if(checkRight('role_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data['typeMsg'] = '新增';
		}
		$this->showView('roleAdd', $data);
	}
	
/**
	 * 
	 * 增加/编辑逻辑
	 */
	public function doAdd()
	{
		$data = array();
		if($this->input->post('id')){
			if(checkRight('role_edit') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data = $this->input->post();
			$data['role_rights'] = $this->_formatRoles($data['role_rights']);
			$this->load->model('OA_Role');
			$rinfo = $this->OA_Role->getInfo($data['id']);
			$this->OA_Role->update($data);
			if(isset($rinfo['qyh_id'])){
				//企业号更新部门
				$func = $content = array();
				$func[0] = 'department';
				$func[1] = 'update';
				$content['name'] = urlencode($data['role_name']);
				$content['parentid'] = 1;
				$content['id'] = $rinfo['qyh_id'];
				qyh_operate($func,$content);
			}
			redirect(formatUrl('role/index'));
		}else{
			if(checkRight('role_add') === FALSE){
				$this->showView('denied', $data);
				exit;
			}
			$data = $this->input->post();
			$data['role_rights'] = $this->_formatRoles($data['role_rights']);
			$this->load->model('OA_Role');
			$msg = '';
			if($rid=$this->OA_Role->add($data) === FALSE){
				$msg = '?msg='.urlencode('创建失败');
			}else{
				//企业号新增部门
				$func = $content = array();
				$func[0] = 'department';
				$func[1] = 'create';
				$content['name'] = urlencode($data['role_name']);
				$content['parentid'] = 1;
				$rs = qyh_operate($func,$content);
				$arr = json_decode($rs,true);
				//更新oa分组qyh_id
				$savedata = array();
				$savedata['id'] = $rid;
				$savedata['qyh_id'] = $arr['id'];
				$this->OA_Role->update($savedata);
			}			
			redirect(formatUrl('role/index'.$msg));
		}
	}
	
	/**
	 * 
	 * 删除
	 */
	public function doDel()
	{
		$data = array();
		if(checkRight('role_del') === FALSE){
			$this->showView('denied', $data);
			exit;
		}
		$id = $this->input->get('id');
		$this->load->model('OA_Admin');
		$rinfo = $this->OA_Role->getInfo($id);
		$adminList = $this->OA_Admin->queryAdminByRole($id);		
		$this->load->model('OA_Role');
		if(empty($adminList)){
			$this->OA_Role->del($id);
			//企业号删除部门
			$func = $content = array();
			$func[0] = 'department';
			$func[1] = 'delete';
			$func[2] = 'GET';
			$func[3] = 'id='.$rinfo['qyh_id'];
			qyh_operate($func,$content);
			redirect(formatUrl('role/index'));
		}else{
			redirect(formatUrl('role/index?msg='.urlencode('该分组下存在'.count($adminList).'个用户，暂时不可删除')));
		}
	}
	
	/**
	 * 
	 * 格式化权限
	 * @param unknown_type $roles
	 */
	private function _formatRoles($roles)
	{
		$allRoles = $this->config->item('rights');
		$roleResult = $roles;
		foreach($allRoles as $item){
			foreach($item['roles'] as $role){
				if(in_array($role[1], $roles)){
					$roleResult[] = $item['right'];
					break;
				}
			}
		}
		return implode(',', $roleResult);
	}
}