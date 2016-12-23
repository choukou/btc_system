<?php
/**
 * Master Bootstrap
 *
 * @author        GiangNT
 * @package       Auth Module
 *
 */

class Donate_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoload() {
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
					'namespace' => 'Donate',
					'basePath' => APPLICATION_PATH . '/modules/donate'
					));

		$resourceLoader = new Zend_Loader_Autoloader_Resource ( array (
				'basePath' => APPLICATION_PATH . '/modules/donate',
				'namespace' => '',
				'resourceTypes' => array (
						'controller' => array (
								'path' => '/controllers',
								'namespace' => 'Donate_'
						)
				)
		));

		return;
	}

}
