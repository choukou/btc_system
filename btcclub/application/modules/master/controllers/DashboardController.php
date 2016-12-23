<?php

class Master_DashboardController extends Master_AppController {
	protected $respon;

	/**
	 * @author
	 * @package   Base
	 * @return    user, model
	 */
	public function init() {
		parent::init();
		$this->respon['status'] = OK;
	}
	/**
	 * index home
	 */
	public function indexAction(){
		$this->view->title = 'Dashboard';
		$rsp = $this->db->callProcedurePrepare('dashboard_info', array($this->user['user_id']));
// 		Zend_Debug::dump($rsp);die;
		$this->view->data = $rsp;
	}

}