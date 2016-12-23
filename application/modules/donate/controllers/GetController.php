<?php

class Donate_GetController extends Donate_AppController {

	protected $model;
	protected $user;
	protected $auth;

	/**
	 * @author
	 * @package   Base
	 * @return    user, model
	 */
	public function init() {
		parent::init();
	}
	/**
	 * index home
	 */
	public function indexAction(){
		$this->view->title = 'Withdraw';
		//
		$rsp = $this->db->callProcedurePrepare('gethelp_info', array($this->user['user_id']));
// 		Zend_Debug::dump($rsp);die;
		$this->view->data = $rsp;
	}

	public function createghAction(){
		$this->_helper->layout()->disableLayout();
		$rsp = $this->db->callProcedurePrepare('get_help_info', array($this->user['user_id']));
		$data = array();
		if(isset($rsp[0][0])){
			$data = $rsp[0][0];
		}
// 				Zend_Debug::dump($data);die;
		$this->view->data = $data;
	}

	public function saveghAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		if($this->getRequest()->isPost()){
			$respon['status'] = OK;
			try {
				$params['user_id'] = $this->user['user_id'];
				$params['ip'] = $_SERVER['REMOTE_ADDR'];
				$rsp = $this->db->callProcedurePrepare('get_save_gh', $params);
				if(!isset($rsp[0][0]['success'])){
					$respon['status'] = NG;
					$respon['msg'] = isset($rsp[0][0]['msg'])? $rsp[0][0]['msg']: $rsp[0][0]['Exception'];
				}
			} catch (Exception $e){
				$respon['status'] = EX;
				$respon['Exception'] = $e->getMessage();
			}
			$this->getHelper('json')->sendJson($respon);
		}
	}
}
