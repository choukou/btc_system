<?php
// require LIBRARY_PATH .'/PHPMailer/PHPMailerAutoload.php';

class Master_HomeController extends Master_AppController {

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
		$homeData = $this->db->callProcedurePrepare('home_default');
		$this->view->homeData = $homeData[0];
		$this->view->captcha = $this->getRandom();
// 		Zend_Debug::dump($this->view->homeData);die;
	}
	/**
	 * index home
	 */
	public function indexAction(){
		$this->view->title = 'Home';
		$this->_helper->layout->setLayout('home');
		$rsp = $this->db->callProcedurePrepare('home_info');
// 		Zend_Debug::dump($rsp[2]);die;
		$this->view->data = $rsp;
	}

	public function tableAction(){
		$this->_helper->_layout->disableLayout();
		$page		=	$this->_request->getParam('page',1);
		$pageLimit	= $this->_request->getParam('page_size',10);

		$strWhere		=	'del_flag = 0 ';
		$strOrder		=	array('create_date desc');
		$news			=	$this->model->selectDB('m008',array('*'),$strWhere,$strOrder,false,$page,$pageLimit);
		$recordCount	=	$this->model->getCount('m008','news_id', $strWhere);
		if(isset($news) && count($news) >0){
			$this->view->arrnews = $news;
			if ($pageLimit!=0)
			{
				$this->view->paging			=	$this->paging->pagination('' , $page , $recordCount , $pageLimit, NUM_OF_PAGE);
			}
		}else{
			$this->view->arrnews = array();
		}
	}
	public function howitworksAction(){
		$this->view->title ="How it works";
		$lang = $this->getParam('lang', 'en');
		$this->_helper->layout->setLayout('home');
		$this->view->lang = $lang;
// 		$strWhere = 'del_flag = 2';
// 		$news			=	$this->model->selectDB('m008',array('*'),$strWhere);
// 		if(isset($news) && count($news) >0){
// 			$this->view->arrnews = $news[0];
// 		}else{
// 			$this->view->arrnews = array();
// 		}
	}
	public function contactAction(){
		$this->view->title ="Contact US";
		$this->_helper->layout->setLayout('home');

	}
	/**
	 * index home
	 */
	public function registerAction(){
		$sharecode = $this->getParam('sharecode', '');
		$this->view->title = 'Register';
		$this->_helper->layout->setLayout('home');
		$data = $this->model->selectDB('m001',array('user_id','user_name'),'del_flag = 0');
		$this->view->listuser = $data;
		$this->view->parent_name = $this->getUserNameFromShareCode($sharecode);
	}
	/**
	 * index home
	 */
	public function saveregisterAction() {
		$respon['status'] = OK;
		try {
			$params = $this->getAllParams(true);
			$params['role_id'] = 1;
			$params['rank'] = 0;
			$params['status'] = 1;
			$params['ip_create'] = $_SERVER['REMOTE_ADDR'];

			if(empty($params['user_name']) || empty($params['password'])){
				throw new Exception('Empty username or password.');
			}
			if(!$this->checkSame($params['password'], $params['confirm_pwd'])){
				throw new Exception('error_pwd_match');
			}

// 			if(!$this->checkSame($params['t_password'], $params['t_password_2'])){
// 				throw new Exception('error_tpwd_match');
// 			}

			unset($params['confirm_pwd']);
// 			unset($params['t_password_2']);

			$params['password'] = $this->getPassword($params['password']);
// 			$params['t_password'] = $this->getPassword($params['t_password']);

			$Blockchain =  new \Blockchain\Blockchain(BLC::API_CODE);
			$Blockchain->setServiceUrl(BLC::SERVICE_URL);
			$Blockchain->Wallet->credentials(BLC::getID(), BLC::getPWD(), BLC::WALLET_PWD2);
			$address = $Blockchain->Wallet->getNewAddress($params['user_name']);
// 			$address = new stdClass();
// 			$address->address = '1LtRFgWGyL2RrfXVNiTazgej9LZSdHsrjZ';
			if(isset($address->address)) {
				$params['wallet_address'] = $address->address;
				$rsp = $this->db->callProcedurePrepare('register_user', array(json_encode($params)));
				$this->checkExcepion($rsp);
				if(isset($rsp[0][0]['code_exp'])) {
					$respon['status'] = NG;
					$respon['msg'] = $rsp[0][0]['msg'];
				}
			}
		} catch (Exception $e) {
			$respon['status'] = EX;
			$respon['msg'] = $e->getMessage();
		}
		$this->getHelper('json')->sendJson($respon);
	}

	private function getUserNameFromShareCode($shareCode) {
		return str_replace(SECRET,"",base64_decode($shareCode));
	}

	public function uploadimgAction(){
		$this->_helper->layout->disablelayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->rsp = new stdClass();
		$this->rsp->status = NG;

		if ($this->_request->isPost ()) {
			$upload = new Zend_File_Transfer();
			$upload->setDestination(UPLOAD_PATH);
			$upload->addValidator('Count', false, array('min' =>1, 'max' => 1));
			$upload->addValidator('FilesSize', false, array('min' => '1', 'max' => '5MB'));
			// 			$upload->addFilter('LowerCase');
			$upload->addValidator('IsImage', false);


			$files = $upload->getFileInfo();
			$File_Ext           = substr($files['fileUpload']['name'], strrpos($files['fileUpload']['name'], '.')); //get file extention
			$NewFileName 		= time().$File_Ext; //new file name
			$upload->addFilter('Rename', UPLOAD_PATH . DIRECTORY_SEPARATOR . $NewFileName);

			if (!$upload->receive()) {
				$uploadErrors = $upload->getErrors();
				$result = array();
				foreach ($uploadErrors as $uploadError){
					switch ($uploadError){
						case 'fileFilesSizeTooSmall':
							$error = array('Code'=>'43','Data'=>'1','Message'=>$uploadError);
							break;
						case 'fileExtensionFalse':
							$error = array('Code'=>'51','Data'=>'1','Message'=>$uploadError);
							break;
						default:
							$error = array('Code'=>'20','Data'=>'1','Message'=>$uploadError);
							break;
					}
					$result[] = $error;
				}
				$this->rsp->status = NG;
				$this->rsp->msg  = $result;
			} else {

				$this->rsp->status = OK;
				$this->rsp->path ='/upload/' . $NewFileName;
			}

		}

		$this->getHelper('json')->sendJson($this->rsp);
	}
}
