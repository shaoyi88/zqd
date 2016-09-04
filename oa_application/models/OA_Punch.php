<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * 外出打卡签到模型类
 * @author Administrator
 *
 */
class OA_Punch extends CI_Model
{
	private $_table = 'oa_punch';
	
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取信息
	 * Enter description here ...
	 */
	public function getInfoById($id)
	{
		$query = $this->db->get_where($this->_table, array('id' => $id));
		$info = array();
		if($query){
			$info = $query->row_array();
		}
		return $info;
	}
	
	/**
	 * 
	 * 获取全部数据
	 */
	public function getAll()
	{
		$info = array();
		$query = $this->db->get($this->_table);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
/**
	 * 
	 * 获取列表
	 */
	public function getList($keyword, $offset, $limit)
	{
		$info = array();
		$this->db->order_by('id','DESC');
		if($keyword != ''){
			$this->db->where('admin_name', $keyword);
		}
		$query = $this->db->get($this->_table, $limit, $offset);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 * 
	 * 获取总数
	 */
	public function getCount($keyword)
	{
		if($keyword != ''){
			$this->db->where('admin_name', $keyword);
		}
		return $this->db->count_all_results($this->_table);
	}
	
	/**
	 * 
	 * 增加
	 * @param unknown_type $data
	 */
	public function add($data)
	{
		$this->db->insert($this->_table, $data); 
		if($this->db->affected_rows() <= 0){
			return FALSE;
		}
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 获取信息
	 * Enter description here ...
	 */
	public function getInfo($data)
	{
		$this->db->select('*');
		if(isset($data['name'])){
			$this->db->where('admin_name',$data['name']);
		}
		if(isset($data['day'])){
			$d1 = strtotime(date('Y-m-d',$data['day']));
			$d2 = $d1+86400;
		}else{
			$d1 = strtotime(date('Y-m-d'));
			$d2 = $d1+86400;
		}
		$this->db->where('punch_time between '.$d1.' and '.$d2);
		$query = $this->db->get($this->_table);
		$info = array();
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
}
