<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * 订单模型类
 * @author Administrator
 *
 */
class OA_Order extends CI_Model
{
	private $_table = 'oa_order';
	
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
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
	 * 
	 * 编辑
	 * @param unknown_type $data
	 */
	public function update($data)
	{
        $this->db->where('order_no', $data['order_no']);
		$this->db->update($this->_table, $data); 
	}
	
	
	/**
	 * 获取信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getInfo($id)
	{
		$this->db->select('a.*,b.sku_info,b.sku_price,b.sku_cost,c.good_name,c.good_main_img,c.good_id as gid,d.province,d.city,d.area,d.address,d.username,d.userphone,e.user_nickname,e.user_name');		
		$this->db->join('oa_good_sku as b', 'b.sku_id = a.good_sku_id');
		$this->db->join('oa_good as c', 'c.good_id = b.sku_good_id');
		$this->db->join('oa_address as d', 'd.address_id = a.address_id');
		$this->db->join('oa_user as e', 'e.user_id = a.user_id');
		$this->db->where('order_no', $id);
		$this->db->order_by('order_time','DESC');
		$query = $this->db->get('oa_order as a');
		$info = array();
		if($query){
			$info = $query->row_array();
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
		$this->db->select('a.*,b.sku_info,b.sku_price,c.good_name,c.good_main_img,c.good_id as gid,d.province,d.city,d.area,d.address,d.username,d.userphone,e.user_nickname,e.user_name');
		$this->db->join('oa_good_sku as b', 'b.sku_id = a.good_sku_id');
		$this->db->join('oa_good as c', 'c.good_id = b.sku_good_id');
		$this->db->join('oa_address as d', 'd.address_id = a.address_id');
		$this->db->join('oa_user as e', 'e.user_id = a.user_id');
	    if(isset($keyword['user_name'])&&$keyword['user_name']){
			$this->db->like('user_name', $keyword['user_name'], 'both');
		}
		$this->db->order_by('order_time','DESC');
		$query = $this->db->get('oa_order as a', $limit, $offset);
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
	    if(isset($keyword['user_name'])&&$keyword['user_name']){
			$this->db->like('user_name', $keyword['user_name'], 'both');
		}
		return $this->db->count_all_results($this->_table);
	}
	
	/**
	 *
	 * 订单删除
	 */
	public function del($id)
	{
		$this->db->where('order_no', $id);
		return $this->db->delete($this->_table);
	}
	
	/*
	 * 
	 * 检查商品是否存在于订单中
	 */
	public function checkGood($id)
	{
		$this->db->select('a.*,b.sku_info,b.sku_price,b.sku_cost,c.good_name,c.good_main_img,c.good_id as gid');
		$this->db->join('oa_good_sku as b', 'b.sku_id = a.good_sku_id');
		$this->db->join('oa_good as c', 'c.good_id = b.sku_good_id');
		$this->db->where('c.good_id', $id);
		$query = $this->db->get('oa_order as a');
		$info = array();
		if($query){
			$info = $query->row_array();
			if(!empty($info)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
}