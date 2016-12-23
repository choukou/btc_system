<?php
class Master_MessageController extends Master_AppController {
	public function init() {
		parent::init();
	}
	public function indexAction(){
		$this->view->title ="Message";
		$page		=	$this->_request->getParam('page',1);
		$pageLimit	=	10;
		$strWhere		=	'm006.del_flag = 0 and (m006.user_to_id = -1 OR m006.user_to_id = '.$this->user['user_id'] .')';
		$strOrder		=	array('create_date desc');
		$userJoin	= array(
					'join_typ' 	=> 'left'
				,	'join_tbl' 	=> 'm001'
				,	'join_cond' => 'm006.user_from_id = m001.user_id'
				,	'join_cols' => array('user_name')
		);
		$array_join		=	array($userJoin);
		$msg			=	$this->model->joinDB('m006',array('*'),$array_join,$strWhere,$strOrder,false,null,$page,$pageLimit);
		$recordCount	=	$this->model->getCount('m006','msg_id', $strWhere);
		if(isset($msg) && count($msg) >0){
			$this->view->arrmsg = $msg;
			if ($pageLimit!=0)
			{
				$this->view->paging	=	$this->paging->pagination('' , $page , $recordCount , $pageLimit, NUM_OF_PAGE);
			}
		}else{
			$this->view->arrmsg = array();
		}
	}
	public function tableAction(){
		$this->_helper->_layout->disableLayout();
		$page		=	$this->_request->getParam('page',1);
		$pageLimit	=	$this->_request->getParam('page_size',10);
		$strWhere	=	'm006.del_flag = 0 and m006.user_from_id = '.$this->user['user_id'];
		$strOrder	=	array('create_date desc');
		$userJoin	= array(
					'join_typ' 	=> 'left'
				,	'join_tbl' 	=> 'm001'
				,	'join_cond' => 'm006.user_to_id = m001.user_id'
				,	'join_cols' => array('user_name')
		);
		$array_join		=	array($userJoin);
		$msg			=	$this->model->joinDB('m006',array('*'),$array_join,$strWhere,$strOrder,false,null,$page,$pageLimit);
		$recordCount	=	$this->model->getCount('m006','msg_id', $strWhere);
		if(isset($msg) && count($msg) >0){
			$this->view->arrmsg = $msg;
			if ($pageLimit!=0)
			{
				$this->view->paging	=	$this->paging->pagination('' , $page , $recordCount , $pageLimit, NUM_OF_PAGE);
			}
		}else{
			$this->view->arrmsg = array();
		}
	}
	public function saveAction(){
	try {
			$msg_id = $this->_request->getParam('msg_id',0);
			$user_to_id	= $this->_request->getParam('user_to_id','');
			$content = $this->_request->getParam('content','');
			$data		=	array(
					'user_from_id'=>		$this->user['user_id'],
					'user_to_id'=>			$user_to_id,
					'msg_content'=>			$content,
					'status'=>			1,
					'del_flag'=>			0,
					'ip_create'	=>			$_SERVER['REMOTE_ADDR'],
					'create_date' =>		date('Y-m-d H:i:s'),
					'user_created_id'=>		$this->user['user_id']
			);
			$count		=	0;
			if($msg_id != 0){
				$strWhere	=	'msg_id='.$msg_id;
				$count		=	$this->model->updateDB('m006', $data,'msg_id ='.$msg_id);
			}else{
				$count		=	$this->model->insertDB('m006', $data);
			}
			exit($count);
		} catch (Exception $e) {
			exit($count);
		}
	}

	public function msgviewAction() {
		$this->view->title = 'Message detail';
		$params['msg_id'] = $this->getParam('id', '0');;
		$params['user_id'] = $this->user['user_id'];

		$data = $this->db->callProcedurePrepare('msg_detail', $params);
		$this->view->data = $data[0][0];
	}
	public function deleteAction(){
		try {
			$msg_id = $this->_request->getParam('msg_id',0);
			$data		=	array(
					'del_flag'=>			1,
					'ip_create'	=>			$_SERVER['REMOTE_ADDR'],
					'update_date' =>		date('Y-m-d H:i:s'),
					'user_updated_id'=>		$this->user['user_id']
			);
			$count		=	0;
			if($msg_id != 0){
				$strWhere	=	'msg_id='.$msg_id;
				$count		=	$this->model->updateDB('m006', $data,'msg_id ='.$msg_id);
			}
			exit($count);
		} catch (Exception $e) {
			exit($count);
		}
	}
	public function createAction(){
		$this->view->title ="Create Message";
// 		$data = $this->model->selectDB('m001',array('user_id','user_name'),'del_flag = 0');
		$other_user = $this->db->callProcedurePrepare('msg_get_child', array($this->user['user_id']));
		$data = isset($other_user[0]) ? $other_user[0] : array();
		$msg_id = $this->_request->getParam('msg_id',0);
		if($msg_id !=0){
			$strWhere = 'msg_id = '.$msg_id;
			$msg			=	$this->model->selectDB('m006',array('*'),$strWhere);
			if(isset($msg) && count($msg) >0){
				$this->view->arrmsg = $msg[0];
			}else{
				$this->view->arrmsg = array();
			}
		}else{
			$this->view->arrmsg = array();
		}
		$this->view->listuser = $data;
	}
}