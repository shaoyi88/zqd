<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * 地区类
 * @author Administrator
 *
 */
class OA_Area extends CI_Model
{
	private $_table = 'oa_areas';
	
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *
	 * 获取地域名称
	 */
	public function getAreaName()
	{
		$info = $data = array();
		$query = $this->db->get($this->_table);
		if($query){
			$info = $query->result_array();
		}
		foreach($info as $v){
			$data[$v['area_id']] = $v['area_name'];
		}
		return $data;
	}
	
	/**
	 *
	 * 获取子地区
	 * @param unknown_type $id
	 */
	public function queryAreasByPid($pid)
	{
		$this->db->where('parent_id', $pid);
		$info = array();
		$query = $this->db->get($this->_table);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	/**
	 *
	 * 模糊查询获取地区对应id
	 * param string ;
	 * return int;
	 */
	
	public function getIdByArea($str){
		$this->db->select('area_id');
		$this->db->like('area_name',$str,'after');
		$result	=	$this->db->get($this->_table);
		if($result){
			$area_id	=	$result->row_array();
			return $area_id['area_id'];
		}
	}
	
}