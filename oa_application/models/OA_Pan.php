<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * 
 * @author Administrator
 *
 */
class OA_Pan extends CI_Model
{
	private $_asia = 'pan_asia';
	private $_asiaC = 'pan_asia_company';
	private $_euro = 'pan_euro';
	private $_euroC = 'pan_euro_company';
	
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCompany($type=0)
	{
		$info = array();
		if($type==0){
		    $query = $this->db->get($this->_asiaC);
		}else{
			$query = $this->db->get($this->_euroC);
		}
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	
	/**
	 * 
	 * 增加
	 * @param unknown_type $data
	 */
	public function add($data,$type=0)
	{ 
		if($type==0){
		    $this->db->insert_batch($this->_asia, $data);
		}else{
			$this->db->insert_batch($this->_euro, $data);
		} 
		if($this->db->affected_rows() <= 0){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 
	 * 编辑
	 * @param unknown_type $data
	 */
	public function update($data)
	{
        $this->db->where('id', $data['id']);
		$this->db->update($this->_table, $data); 
	}
	
	/**
	 * 
	 * 删除
	 * @param unknown_type $ids
	 */
	public function del($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->_table); 
	} 
	
	public function getAsiaPan($data){
		$info = array();
		if(isset($data['apan_company'])){
			$this->db->where('apan_company', $data['apan_company']);
		}
		if(isset($data['lpan_home_w'])){
			$this->db->where('lpan_home_w', $data['lpan_home_w']);
		}
		if(isset($data['lpan_pan'])){
			$this->db->where('lpan_pan', $data['lpan_pan']);
		}
		if(isset($data['lpan_away_w'])){
			$this->db->where('lpan_away_w', $data['lpan_away_w']);
		}
		if(isset($data['ipan_home_w'])){
			$this->db->where('ipan_home_w', $data['ipan_home_w']);
		}
		if(isset($data['ipan_pan'])){
			$this->db->where('ipan_pan', $data['ipan_pan']);
		}
		if(isset($data['ipan_away_w'])){
			$this->db->where('ipan_away_w', $data['ipan_away_w']);
		}
		$query = $this->db->get($this->_asia);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	public function getAsiaPanByW($data){
		$sql = "SELECT * FROM (`$this->_asia`) WHERE apan_company=".$data['apan_company'].' AND ';
		if($data['lpan_home_w']>$data['lpan_away_w']){
			$sql .= 'lpan_home_w>lpan_away_w';
		}else{
			$sql .= 'lpan_home_w<=lpan_away_w';
		}
		$info = array();
		$query = $this->db->query($sql);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	public function getEuroPan($data){
		$info = array();
		if(isset($data['epan_company'])){
			$this->db->where('epan_company', $data['epan_company']);
		}
		if(isset($data['lpan_home_w'])){
			$this->db->where('lpan_home_w', $data['lpan_home_w']);
		}
		if(isset($data['lpan_draw_w'])){
			$this->db->where('lpan_draw_w', $data['lpan_draw_w']);
		}
		if(isset($data['lpan_away_w'])){
			$this->db->where('lpan_away_w', $data['lpan_away_w']);
		}
		if(isset($data['ipan_home_w'])){
			$this->db->where('ipan_home_w', $data['ipan_home_w']);
		}
		if(isset($data['ipan_draw_w'])){
			$this->db->where('ipan_draw_w', $data['ipan_draw_w']);
		}
		if(isset($data['ipan_away_w'])){
			$this->db->where('ipan_away_w', $data['ipan_away_w']);
		}
		$query = $this->db->get($this->_euro);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
	
	public function getEuroPanByW($data){
		$sql = "SELECT * FROM (`$this->_euro`) WHERE epan_company=".$data['epan_company'].' AND ';
		if($data['lpan_home_w']>$data['lpan_away_w']){
			$sql .= 'lpan_home_w>lpan_away_w';
		}else{
			$sql .= 'lpan_home_w<lpan_away_w';
		}
		$info = array();
		$query = $this->db->query($sql);
		if($query){
			$info = $query->result_array();
		}
		return $info;
	}
}
