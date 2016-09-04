<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends ZQD_Controller 
{
	protected function initialize()
	{
		parent::initialize();
	}
	
	/**
	 * 
	 * 跳转到主页
	 */
	public function index()
	{
		redirect(formatUrl('oa_admin/home'));
	}
	
}