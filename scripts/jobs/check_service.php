<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
require_once 'init.php';

Class CheckService {
	protected $db;

	public function __construct() {
		$this->db = Zend_Db_Table::getDefaultAdapter();
	}

	public function checkBlockchainService() {
		try {
			$search_sv = shell_exec('netstat -tulapn|grep 45.32.255.50:3000|grep LISTEN');
			$columnMapping = array('lvl' => 'priority', 'msg' => 'message', 'timestampFormat' => 'timestamp');
			$writer = new Zend_Log_Writer_Db($this->db, 'log_table_name', $columnMapping);
			$logger = new Zend_Log($writer);
			if(empty($search_sv)) {
				// echo 'blockchain-wallet-service is stop';
				$shell_txt = shell_exec('nohup blockchain-wallet-service start --bind 45.32.255.50 --port 3000 &');
				$logger->info(json_encode("blockchain-wallet-service"));
			}

		} catch (Exception $e){
			return;
		}
	}

}

$job_chk = new CheckService();
$job_chk->checkBlockchainService();

