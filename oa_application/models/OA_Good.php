<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * 订单模型类
 * @author Administrator
 *
 */
class OA_Good extends CI_Model
{
	private $_table = 'oa_good';
	private $_shipping = 'oa_shipping';
	private $_cat = 'oa_good_cat';
	private $_sku = 'oa_good_sku';
	
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
        $this->db->where('good_id', $data['good_id']);
		$this->db->update($this->_table, $data); 
	}
	
	/**
	 * 
	 * 删除
	 * @param unknown_type $ids
	 */
	public function del($id)
	{
		$this->db->where('good_id', $id);
		$this->db->delete($this->_table); 
		//同时删除属性
		$this->db->where('sku_good_id', $id);
		$this->db->delete($this->_sku);
	}

	/**
	 *
	 * 添加商品属性
	 * @param unknown_type $ids
	 */
	public function addSKU($data)
	{
		$this->db->insert_batch($this->_sku, $data); 
		if($this->db->affected_rows() <= 0){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 *
	 * 编辑商品属性
	 * @param unknown_type $ids
	 */
	public function updateSKU($data)
	{
		$this->db->where('sku_show', $data['sku_show']);
		$this->db->update($this->_sku, $data); 
	}
	
	/**
	 * 获取信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getInfo($id)
	{
		$query = $this->db->get_where($this->_table, array('good_id' => $id));
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
		$this->db->order_by('good_id','DESC');
		if(isset($keyword['good_name'])&&$keyword['good_name']){
			$this->db->like('good_name', $keyword['good_name'], 'both');
		}
		if(isset($keyword['good_type'])&&$keyword['good_type']){
			$this->db->where('good_type', $keyword['good_type']);
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
	    if(isset($keyword['good_name'])&&$keyword['good_name']){
			$this->db->like('good_name', $keyword['good_name'], 'both');
		}
		if(isset($keyword['good_type'])&&$keyword['good_type']){
			$this->db->where('good_type', $keyword['good_type']);
		}
		return $this->db->count_all_results($this->_table);
	}
	
	/**
	 *
	 * 获取SKU By good_ids
	 */
	public function getSku($ids)
	{
		$info = array();
	    $this->db->where_in('sku_good_id', $ids);
		$query = $this->db->get($this->_sku);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 获取邮费模板总数
	 */
	public function getShippingCount()
	{
		return $this->db->count_all_results($this->_shipping);
	}
	
	/**
	 *
	 * 获取邮费模板列表
	 */
	public function getShippingList($offset, $limit)
	{
		$info = array();
		$this->db->order_by('shipping_id','DESC');
		$query = $this->db->get($this->_shipping, $limit, $offset);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 增加邮费模板
	 * @param unknown_type $data
	 */
	public function addShipping($data)
	{
		$this->db->insert($this->_shipping, $data);
		if($this->db->affected_rows() <= 0){
			return FALSE;
		}
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 *
	 * 编辑邮费模板
	 * @param unknown_type $data
	 */
	public function updateShipping($data)
	{
		$this->db->where('shipping_id', $data['shipping_id']);
		$this->db->update($this->_shipping, $data);
	}
	
	/**
	 *
	 * 删除邮费模板
	 * @param unknown_type $id
	 */
	public function delShipping($id)
	{
		$this->db->where('shipping_id', $id);
		$this->db->delete($this->_shipping);
	}
	
	/**
	 * 检查模板是否商品表有用到
	 * @param unknown $cid
	 */
	public function checkShippingByid($id){
		$info = $ids = array();
		$this->db->select('shipping_id');
		$this->db->from('oa_shipping as a');
		$this->db->where('cat_id', $cid);
		$this->db->join('oa_good as b', 'b.good_delivery_info = a.shipping_id');
		$query = $this->db->get();
		if($query){
			$info = $query->result_array();
			foreach($info as $v){
				$ids[] = $v['shipping_id'];
			}
		}
		return array_unique($ids);
	}
	
	/**
	 *
	 * 获取分类总数
	 */
	public function getCatCount()
	{
		return $this->db->count_all_results($this->_cat);
	}
	
	/**
	 *
	 * 获取分类列表
	 */
	public function getCatList($offset, $limit)
	{
		$info = array();
		$this->db->order_by('cat_id','DESC');
		$query = $this->db->get($this->_cat, $limit, $offset);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 获取分类信息
	 */
	public function getCatInfo($cat_id)
	{
		$query = $this->db->get_where($this->_cat, array('cat_id' => $cat_id));
		$info = array();
		if($query){
			$info = $query->row_array();
		}
		return $info;
	}
	
    /**
	 *
	 * 查询二级分类
	 * @param unknown_type $pid
	 */
	public function queryCatByPid($pid)
	{
		$this->db->where('parent_id', $pid);
		$info = array();
		$query = $this->db->get($this->_cat);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 获取分类名列表
	 */
	public function getCatNameList(&$subInfo=array())
	{
		$allList = $this->_getCatAll();
		$result = array();
		foreach($allList as $item){
			$result[$item['cat_id']] = $item['cat_name'];
			$subInfo[$item['parent_id']][] = $item;
		}
		return $result;
	}
	
	/**
	 *
	 * 获取分类全部
	 */
	private function _getCatAll()
	{
		$info = array();
		$query = $this->db->get($this->_cat);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 增加分类
	 * @param unknown_type $data
	 */
	public function addCat($data)
	{
		$this->db->insert($this->_cat, $data);
		if($this->db->affected_rows() <= 0){
			return FALSE;
		}
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 *
	 * 编辑分类
	 * @param unknown_type $data
	 */
	public function updateCat($data)
	{
		$this->db->where('cat_id', $data['cat_id']);
		$this->db->update($this->_cat, $data);
	}
	
	/**
	 *
	 * 删除二级分类
	 *
	 */
	public function delscat($scatid,$cat_id)
	{
		$msg = '';
		$this->db->where('parent_id', $cat_id);
		if(!empty($scatid)){
			$this->db->where_not_in('cat_id', $scatid);
		}
		$this->db->delete($this->_cat);
	}
	
	/**
	 *
	 * 删除分类
	 *
	 */
	public function delCat($cat_id)
	{
		$this->db->where('cat_id', $cat_id);
		$this->db->or_where('parent_id', $cat_id);
		$this->db->delete($this->_cat);
	}
	
	
	/**
	 * 检查分类是否商品表有用到
	 * @param unknown $cid
	 */
	public function checkCatByCid($cid){
		$info = $ids = array();	
		$this->db->select('cat_id');
		$this->db->from('oa_good_cat as a');
		//检查一级分类
		$this->db->where('cat_id', $cid);
		//检查二级分类
		$this->db->or_where('parent_id', $cid);
		$this->db->join('oa_good as b', 'b.good_category = a.cat_id');
		$query = $this->db->get();
		if($query){
			$info = $query->result_array();
			foreach($info as $v){
				$ids[] = $v['cat_id'];
			}
		}
		return array_unique($ids);
	}
	
	/**
	 *
	 * 获取分类树
	 * @param unknown_type $parent_id
	 */
	public function getListTree($pid, $allList = NULL)
	{
		if($allList === NULL){
			$allList = $this->_getCatAll();
		}
		$reuslt = array();
		$this->_getSubList($reuslt, $allList, $pid, 0);
		return $reuslt;
	}
	
	/**
	 *
	 * 获取分类子树
	 * @param unknown_type $result
	 * @param unknown_type $allList
	 * @param unknown_type $parent_id
	 * @param unknown_type $level
	 */
	private function _getSubList(&$result, $allList, $pid, $level)
	{
		foreach($allList as $item){
			if($item['parent_id'] == $pid){
				$item['level'] = $level;
				$result[] = $item;
				$this->_getSubList($result,$allList,$item['cat_id'],$level+1);
			}
		}
	}
}