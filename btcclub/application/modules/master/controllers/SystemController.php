<?php

class Master_SystemController extends Master_AppController {

	protected $_filename;

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
	public function addresourceAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$front = $this->getFrontController();
		$acl = array();
		foreach ($front->getControllerDirectory() as $module => $path) {
			if(!is_dir($path)) {
				continue;
			}

			foreach (@scandir($path) as $file) {
				if (strstr($file, "Controller.php") !== false) {

					include_once $path . DIRECTORY_SEPARATOR . $file;

					foreach (get_declared_classes() as $class) {
						if (is_subclass_of($class, 'Zend_Controller_Action')) {

							$controller = strtolower(substr($class, 0, strpos($class, "Controller")));
							if (strstr($controller, $module) !== false) {
								$actions = array();

								foreach (get_class_methods($class) as $action) {

									if (strstr($action, "Action") !== false) {
										$actions[] = strtolower(str_replace("Action","",$action));
									}
								}
							}
						}
					}
					if (strstr($controller, $module) !== false) {
						$acl[$module][$controller] = $actions;
					}
				}
			}
		}
		$acl_array = array();
		foreach ($acl as $module=>$controllers) {
			foreach ($controllers as $controller => $actions) {
				$controller = strtolower(str_replace($module . "_","",$controller));
				if($controller == 'app'){
					continue;
				}
				$acl_array[$module][$controller] = $actions;
			}
		}

		foreach ($acl_array as $module=>$controllers) {
			foreach ($controllers as $controller => $actions) {
				foreach ($actions as  $action) {
					$rsp = $this->db->callProcedurePrepare('system_add_resource', array($module, $controller, $action));
				}
			}
		}
		Zend_Debug::dump($acl_array);
		die;
		//
	}

	public function getroleAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rsp = $this->db->callProcedurePrepare('get_roles');
		$this->setFileName('role.jon');
		$this->putData($rsp);
		Zend_Debug::dump($rsp);die;
	}
	public function getaclAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$rsp = $this->db->callProcedurePrepare('get_acls');
		$this->setFileName('acl.jon');
		$this->putData($rsp);
		Zend_Debug::dump($rsp);die;
	}

	protected function setFileName($filename = '') {
		$this->_filename = APPLICATION_PATH . DS . 'configs'. DS . $filename;
		return $this->_filename;
	}
	protected function putData($data = array()) {
		return file_put_contents($this->_filename, json_encode($data));
	}
	protected function getData() {
		return json_decode(file_get_contents($this->_filename));
	}


}
