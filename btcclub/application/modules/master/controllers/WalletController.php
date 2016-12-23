<?php

class Master_WalletController extends Master_AppController {
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
		$this->view->title = 'Wallet';
	}

	public function mywalletAction(){
		$this->view->title = 'My Wallet';
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
		$rsp = $this->db->callProcedurePrepare('wallet_info', array($this->user['user_id'], $bl));
// 				Zend_Debug::dump($rsp);die;
		$this->view->data = $rsp;
	}

}
