<?php
class Master_NewsController extends Master_AppController {
	public function init() {
		parent::init();
	}
	public function indexAction(){
		$this->view->title ="News list";
		$page		=	$this->_request->getParam('page',1);
		$pageLimit	=	10;
		
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
	public function deleteAction(){
		try {
			$news_id = $this->_request->getParam('news_id',0);
			$data		=	array(
					'del_flag'=>			1,
					'ip_create'	=>			$_SERVER['REMOTE_ADDR'],
					'update_date' =>		date('Y-m-d H:i:s'),
					'user_updated_id'=>		$this->user['user_id']
			);
			$count		=	0;
			if($news_id != 0){
				$strWhere	=	'news_id='.$news_id;
				$count		=	$this->model->updateDB('m008', $data,'news_id ='.$news_id);
			}
			exit($count);
		} catch (Exception $e) {
			exit($count);
		}
	}
	public function saveAction(){
		try {
			$news_id = $this->_request->getParam('news_id',0);
			$title = $this->_request->getParam('title','');
			$image = $this->_request->getParam('image','');
			$description = $this->_request->getParam('description','');
			
			$data		=	array(
					'news_id'=>				$news_id,
					'image'=>				$image,
					'title'=>				$title,
					'description' => 		$description,
					'del_flag'=>0,
			);
			$count = 0;
			if($news_id !=0){
				$data['ip_update'] = $_SERVER['REMOTE_ADDR'];
				$data['update_date'] = date('Y-m-d H:i:s');
				$data['user_updated_id'] = 1;
				$strWhere = 'news_id = '.$news_id;
				$count	=	$this->model->getCount('m008', 'news_id',$strWhere);
				if($count > 0){
					$this->model->updateDB('m008', $data,$strWhere);
				}
			}else{
				$data['ip_create'] 			= $_SERVER['REMOTE_ADDR'];
				$data['create_date'] 		= date('Y-m-d H:i:s');
				$data['user_created_id'] 	= 1;
				$count		=	$this->model->insertDB('m008', $data);
			}
			exit($count);
		} catch (Exception $e) {
			exit($count);
		}
	}
	public function createAction(){
		$this->view->title ="Create news";
		$news_id = $this->_request->getParam('news_id',0);
		if($news_id !=0){
			$strWhere = 'news_id = '.$news_id;
			$news			=	$this->model->selectDB('m008',array('*'),$strWhere);
			if(isset($news) && count($news) >0){
				$this->view->arrnews = $news[0];
			}else{
				$this->view->arrnews = array();
			}
		}else{
			$this->view->arrnews = array();
		}
	}
	public function howitworksAction(){
		$this->view->title ="How it works";
		$strWhere = 'del_flag = 2';
		$news			=	$this->model->selectDB('m008',array('*'),$strWhere);
		if(isset($news) && count($news) >0){
			$this->view->arrnews = $news[0];
		}else{
			$this->view->arrnews = array();
		}
	}
	public function savehowitworksAction(){
		try {
			$news_id = $this->_request->getParam('news_id',0);
			$title = $this->_request->getParam('title','');
			$image = $this->_request->getParam('image','');
			$description = $this->_request->getParam('description','');
			$data		=	array(
					'description' => 		$description,
			);
			$count = 0;
			if($news_id !=0){
				$data['ip_update'] = $_SERVER['REMOTE_ADDR'];
				$data['update_date'] = date('Y-m-d H:i:s');
				$data['user_updated_id'] = 1;
				$strWhere = 'news_id = '.$news_id;
				$count	=	$this->model->getCount('m008', 'news_id',$strWhere);
				if($count > 0){
					$this->model->updateDB('m008', $data,$strWhere);
				}
			}else{
				$data['ip_create'] 			= $_SERVER['REMOTE_ADDR'];
				$data['create_date'] 		= date('Y-m-d H:i:s');
				$data['user_created_id'] 	= 1;
				$count		=	$this->model->insertDB('m008', $data);
			}
			exit($count);
		} catch (Exception $e) {
			exit($count);
		}
	}
}