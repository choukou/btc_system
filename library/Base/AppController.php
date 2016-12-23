<?php
/**
 *
 * @author        HiepNV
 * @package       Base
 *
 */

class Base_AppController extends Zend_Controller_Action {
	protected $model;
	protected $db;
	protected $user;
	protected $auth;
	protected $paging;
	private   $_moduleAllow = array('common', 'auth', 'tmp');

	public function init() {
		try {

			$this->model =	new Model_DBCommon();
			$this->db = $this->model->getDb();
			$this->auth = Zend_Auth::getInstance();
			$this->user = $this->auth->getIdentity();
			$this->view->user = $this->user;
			$this->paging		= 	new Model_Paging();

			$layout = Zend_Layout::getMvcInstance();
			$view = $layout->getView();
			$new_msg= 0;
			if($this->auth->hasIdentity()) {
				$rsp = $this->db->callProcedurePrepare('msg_count_unread', array($this->user['user_id']));
				$new_msg = $rsp[0][0]['count_msg'];
			}
			$view->count_msg       = $new_msg;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function getPassword($pass = NULL) {
		return md5(sha1(SECURITYKEY.$pass));
	}

	public function checkExcepion($result){
		if(isset($result[0][0]['Data']) && $result[0][0]['Data'] == 'Exception' ){
			throw new Exception('System error!', 1);
		}
		return true;
	}

	public function checkSame($a, $b) {
		return strcmp($a, $b)== 0 ? true : false;
	}

	public function sendMail($send_to_email, $subject, $body) {
			//Initialize needed variables
			$your_name = 'BTCCLUB SYSTEM';
			$your_email = 'btcclub.us@gmail.com'; //Or your_email@gmail.com for Gmail
			$your_password = 'Btc123$$$';
			$send_to_name = 'member';

			//SMTP server configuration
			$smtpHost = 'smtp.gmail.com';
			$smtpConf = array(
					'auth' => 'login',
					'ssl' => 'tls',
					'port' => '587', // or 587, 25
// 					'ssl' => 'ssl',
// 					'port' => '465',
					'username' => $your_email,
					'password' => $your_password
			);
			$transport = new Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);

			//Create email
			$mail = new Zend_Mail('UTF-8');
			$mail->setBodyText($body);
			$mail->setFrom($your_email, $your_name);
			$mail->addTo($send_to_email, $send_to_name);
			$mail->setSubject($subject);
			return $mail->send($transport);
	}

	/**
	 * get random
	 */
	protected function getRandom(){
		return substr(str_shuffle(str_repeat('abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOP1234567890',5)),0,8);
	}

	protected function isAdmin(){
		if(!empty($this->user['role_id']) && $this->user['role_id'] == 3) {
			return true;
		}
		return false;
	}

	public function encrypt($value){
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SECRET, $value, MCRYPT_MODE_ECB, $iv);
	}

	public function decrypt($value){
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SECRET, $value, MCRYPT_MODE_ECB, $iv));
	}
}
