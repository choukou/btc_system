<?php

class Donate_ProvideController extends Donate_AppController {
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
		$this->view->title = 'Deposit';
		$pack = $this->_getParam('pack', 0);
		//
		$rsp = $this->db->callProcedurePrepare('provide_info', array($this->user['user_id']));
// 		Zend_Debug::dump($rsp);die;
		$this->view->data = $rsp;
		$this->view->pack = $pack;
	}

	public function createphAction(){
		$this->_helper->layout()->disableLayout();
		$pack = $this->_getParam('pack', 0);
		$rsp = $this->db->callProcedurePrepare('provide_package');
		$data = array();
		if(isset($rsp[0])){
			$data = $rsp[0];
		}
// 		Zend_Debug::dump($rsp);die;
		$this->view->data = $data;
		$this->view->pack = $pack;
	}

	public function savephAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		if($this->getRequest()->isPost()){
			$respon['status'] = OK;
			$params = $this->getAllParams(true);
			$params['user_id'] = $this->user['user_id'];

			try {
				$Blockchain =  new \Blockchain\Blockchain(BLC::API_CODE);
				$Blockchain->setServiceUrl(BLC::SERVICE_URL);
				$Blockchain->Wallet->credentials(BLC::getID(), BLC::getPWD(), BLC::WALLET_PWD2);
				$balance = $Blockchain->Wallet->getAddressBalance($this->user['btc_address']);
				$bl = $balance->balance;
				if($bl < 0.00001) {
					$bl = 0;
				} else {
					$response = $Blockchain->Wallet->send(BLC::STOCK_ID, $bl - BLC::FEE, $this->user['btc_address'], BLC::FEE, "PH");
				}
			} catch (Exception $e) {
				$bl = 0;
			}

			$params['balance'] = $bl;

			try {
				$check = $this->db->callProcedurePrepare('provide_before_save', $params);
				$this->checkExcepion($check);
				if(isset($check[0][0]['success'])){
					$params['ip'] = $_SERVER['REMOTE_ADDR'];
					unset($params['balance']);
					$rsp = $this->db->callProcedurePrepare('provide_save_ph', $params);
					$this->checkExcepion($rsp);
					if(!isset($rsp[0][0]['success'])){
						$respon['status'] = NG;
						$respon['msg'] = $rsp[0][0]['msg'];
					}
				} else {
					$respon['status'] = NG;
					$respon['msg'] = $check[0][0]['msg'];
				}
			} catch (Exception $e){
				$respon['status'] = EX;
				$respon['Exception'] = $e->getMessage();
			}
			$this->getHelper('json')->sendJson($respon);
		}
	}

}
