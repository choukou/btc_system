<?php

class Donate_PinController extends Donate_AppController {
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
		$this->view->title = 'Tokens';
		$rsp = $this->db->callProcedurePrepare('pin_info', array($this->user['user_id']));
// 		Zend_Debug::dump($rsp);die;
		$this->view->data = $rsp;
	}

	public function sendpinAction() {
		$this->view->title = 'Send Tokens';
		$this->_helper->layout()->disableLayout();
		$rsp = $this->db->callProcedurePrepare('get_all_users', array($this->user['user_id']));
		$data = array();
		if(isset($rsp[0])){
			$data = $rsp[0];
		}
		$this->view->data = $data;

	}

	public function requestpinAction() {
		$this->view->title = 'Request Tokens';
		$this->_helper->layout()->disableLayout();

		try {
			$Blockchain =  new \Blockchain\Blockchain(BLC::API_CODE);
			$Blockchain->setServiceUrl(BLC::SERVICE_URL);
			$rates = $Blockchain->Rates->get();
			$sell = $rates['USD']->sell;
		} catch (Exception $e) {
			$sell = 0;
		}

		$this->view->sell = $sell;
	}

	public function savesendpinAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if($this->getRequest()->isPost()){
			$respon['status'] = OK;
			$params = $this->getAllParams(true);
			$params['user_id_to'] = $this->user['user_id'];
			$params['ip'] = $_SERVER['REMOTE_ADDR'];
			try {
				$rsp = $this->db->callProcedurePrepare('pin_send_token', $params);
				if(!isset($rsp[0][0]['success'])){
					$respon['status'] = NG;
				}
			} catch (Exception $e){
				$respon['status'] = EX;
				$respon['Exception'] = $e->getMessage();
			}
			$this->getHelper('json')->sendJson($respon);
		}
	}

	public function saverequestpinAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$PIN_COST_RQ = new CFG();
		if(Zend_Registry::isRegistered(SYS::SYS_PIN_COST)) {
			$PIN_COST_RQ->request_pin_cost = Zend_Registry::get(SYS::SYS_PIN_COST)->request_pin_cost;
		}

		if($this->getRequest()->isPost()){
			$respon['status'] = OK;
			$params = $this->getAllParams(true);
			$params['pin_cost'] = $PIN_COST_RQ->request_pin_cost;
			$params['user_id'] = $this->user['user_id'];
			$params['ip'] = $_SERVER['REMOTE_ADDR'];
			try {
// 				$btc = $params['pin'] * SYS::PIN_COST;
// 				$Blockchain =  new \Blockchain\Blockchain(BLC::API_CODE);
// 				$Blockchain->setServiceUrl(BLC::SERVICE_URL);
// 				$Blockchain->Wallet->credentials(BLC::WALLET_ID, BLC::getPWD());
// 				$balance = $Blockchain->Wallet->getAddressBalance($this->user['btc_address']);

// 				if($balance->balance < $btc + BLC::FEE ){
// 					throw new Exception('Insufficient funds', 1);
// 				}
// 				$response = $Blockchain->Wallet->send(BLC::WALLET_PIN_ADDRESS, $btc, $this->user['btc_address'], BLC::FEE, "request pin");

				$rsp = $this->db->callProcedurePrepare('pin_request_token', $params);
				if(!isset($rsp[0][0]['success'])){
					$respon['status'] = NG;
				}
			} catch (Exception $e){
				$respon['status'] = EX;
				$respon['Exception'] = $e->getMessage();
			}
			$this->getHelper('json')->sendJson($respon);
		}
	}
}
