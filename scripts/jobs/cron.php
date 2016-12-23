<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
require_once 'init.php';

Class Console {
	protected $db;

	public function __construct() {
		$this->db = Zend_Db_Table::getDefaultAdapter();
	}

	public function updateBonus() {
		try {
			echo date("Y-m-d H:i:s");
			$rsp = $this->db->callProcedurePrepare('job_update_daily');
			print_r($rsp);
			if(!isset($rsp[0][0]['success'])){
			}
		} catch (Exception $e){
			$columnMapping = array('lvl' => 'priority', 'msg' => 'message', 'timestampFormat' => 'timestamp');
			$writer = new Zend_Log_Writer_Db($this->db, 'log_table_name', $columnMapping);
			$logger = new Zend_Log($writer);
			$logger->info(json_encode($e));
		}
	}

	public function sendGetHelp() {
		try {
			$Blockchain =  new \Blockchain\Blockchain(BLC::API_CODE);
			$Blockchain->setServiceUrl(BLC::SERVICE_URL);
			$Blockchain->Wallet->credentials(BLC::getID(), BLC::getPWD(), BLC::WALLET_PWD2);
			$balance = $Blockchain->Wallet->getAddressBalance(BLC::STOCK_ID);
			$bl = $balance->balance;
		} catch (Exception $e) {
			$bl = 0;

			$columnMapping = array('lvl' => 'priority', 'msg' => 'message', 'timestampFormat' => 'timestamp');
			$writer = new Zend_Log_Writer_Db($this->db, 'log_table_name', $columnMapping);
			$logger = new Zend_Log($writer);
			$logger->info(json_encode($e));
		}

		$rsp_total = $this->db->callProcedurePrepare('admin_get_total_gh_bit');
		$total_bit = 0;
		if(isset($rsp_total[0][0]['all_bit'])) {
			$total_bit = $rsp_total[0][0]['all_bit'];
		}

		if($bl >= $total_bit) {
			$recipients = array();
			$list_user = array();
			$rsp = $this->db->callProcedurePrepare('job_get_send_daily');
			if(isset($rsp[0])) {
				foreach ($rsp[0] as $key => $persion) {
					$recipients[$persion['btc_address']] = $persion['amount_total'];
					$list_user[] = $persion['user_id'];
				}

			}

// 			print_r(json_encode($list_user));
			if(!empty($list_user)) {
				try {
					$response = $Blockchain->Wallet->sendMany($recipients, BLC::STOCK_ID, BLC::FEE, "Send");
					$this->db->callProcedurePrepare('job_update_wallet_daily', array(json_encode($list_user)));
				} catch (Exception $e) {
					$columnMapping = array('lvl' => 'priority', 'msg' => 'message', 'timestampFormat' => 'timestamp');
					$writer = new Zend_Log_Writer_Db($this->db, 'log_table_name', $columnMapping);
					$logger = new Zend_Log($writer);
					$logger->info(json_encode($e));
				}
			}
		}
	}
}

$job = new Console();
$job->updateBonus();
$job->sendGetHelp();

