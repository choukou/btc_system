<?php

class Master_TreeController extends Master_AppController {
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
	public function referralAction(){
		$this->view->title = 'Referral';
		$rsp = $this->db->callProcedurePrepare('map_referral_get', array($this->user['user_id']));
		// set view
		$this->view->rows = $rsp[0];
	}

	public function downlinetreeAction() {
		$this->view->title = 'Downlinetree';
		$rsp = $this->db->callProcedurePrepare('map_downlinetree_get', array($this->user['user_id']));
// 		Zend_Debug::dump($rsp);die;
		// set view
// 		$this->getHelper('json')->sendJson($rsp[0]);
		$this->view->rows = $rsp[0];

	}

	public function addtreeAction() {
		$this->_helper->layout()->disableLayout();

		$rsp = $this->db->callProcedurePrepare('get_add_tree_info', array($this->user['user_id']));
		$data = array();
		if(isset($rsp[0])){
			$data = $rsp[0];
		}
// 				Zend_Debug::dump($rsp);die;
		$this->view->data = $data;

	}

	public function saveaddtreeAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if($this->getRequest()->isPost()){
			$respon['status'] = OK;
			$params = $this->getAllParams(true);
			$params['user_id'] = $this->user['user_id'];
			$params['ip'] = $_SERVER['REMOTE_ADDR'];
			try {
				$rsp = $this->db->callProcedurePrepare('referral_add_to_tree', $params);
				if(isset($rsp[0][0]['Data']) && $rsp[0][0]['Data']== 'Exception'){
					throw new Exception($rsp[0][0]['Message'], 1);
				}

				if(!isset($rsp[0][0]['tree_id'])){
					$respon['status'] = NG;
					$respon['msg'] = $rsp[0][0]['msg'];
				}
			} catch (Exception $e){
				$respon['status'] = EX;
				$respon['Exception'] = $e->getMessage();
			}
			$this->getHelper('json')->sendJson($respon);
		}
	}
}




