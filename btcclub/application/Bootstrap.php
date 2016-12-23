<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	protected function _initAutoload() {
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
			'basePath' => APPLICATION_PATH,
			'namespace' => '',
			'resourceTypes' => array(
				'form' => array(
					'path' => 'forms/',
					'namespace' => 'Form_'
				),
				'model' => array(
					'path' => 'models/',
					'namespace' => 'Model_'
				)
			)
		));
	}

	protected function _initDb() {
		try {
			$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/database.ini', 'database');
			$config = new Zend_Config(
					array(
							'database' => array(
									'adapter' => $this->_config->database->adapter,
									'params'  => $this->_config->database->params->toArray()
							)
					)
					);

			// Instantiate the DB factory
			$dbAdapter = Zend_Db::factory($config->database);
			Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);
		} catch (PDOException $e) {
			echo "There was an error querying the database <br />" . $e->getMessage();
		} catch (Zend_Db_Adapter_Exception $e) {
			echo "Unable to connect to the database <br />" . $e->getMessage();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	protected function _initSession() {
		$options = $this->getOptions();
		Zend_Session::setOptions($options['resources']['session']);

// 		$config = array(
// 				'name'           => 'session',
// 				'primary'        => 'id',
// 				'modifiedColumn' => 'modified',
// 				'dataColumn'     => 'data',
// 				'lifetimeColumn' => 'lifetime'
// 		);
// 		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
		Zend_Session::start();
// 		session_regenerate_id();
// 		@Zend_Session::forgetMe();
// 		Zend_Session::rememberMe();
	}

	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
	}

	protected function _initRewrite() {
		$front = Zend_Controller_Front::getInstance();
		$router = $front->getRouter();
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routers');

		if(isset($config->routes)) {
				$router->addConfig($config,'routes');
		}

	}

	protected function _initSetting() {
		$cfg = new CFG();
		$model =	new Model_DBCommon();
		$data = $model->getDb()->callProcedurePrepare('admin_settings');
		if(isset($data[0])) {
			foreach ($data[0] as $obj) {
				if($obj['sys_name'] == 'request_pin_cost') {
					$cfg->request_pin_cost = $obj['sys_value'];
				}
			}
		}
		Zend_Registry::set(SYS::SYS_PIN_COST, $cfg);
	}

}
?>
