<?php
/**
 * Medicare Project
 *
 * Connect database
 *
 * @copyright   :   ANS-ASIA
 * @author      :   GiangNT - 2015/05/08 - create
 *
 */

class Model_DAO {
	private $_config;
	private $_db;
	public  $logger = NULL;

	public function __construct() {
		try {
			if (!Zend_Registry::isRegistered('logger')) {
				throw new Exception("logger not define!", 1);
			}

			$this->logger = Zend_Registry::get('logger');
			$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/database.ini', 'database');
			$config = new Zend_Config(
				array(
					'database' => array(
						'adapter' => $this->_config->db->adapter,
						'params'  => $this->_config->db->params->toArray()
					)
				)
			);

			// Instantiate the DB factory
			$this->_db = Zend_Db::factory($config->database);
		} catch (PDOException $e) {
			echo "There was an error querying the database <br />" . $e->getMessage();
		} catch (Zend_Db_Adapter_Exception $e) {
			echo "Unable to connect to the database <br />" . $e->getMessage();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}


	public function executeSql($nameSPC, $params = array(), $option = FALSE, $struct = array()) {
		try {
			if(!is_array($params)){
				$params = array();
			}

			$params = array_values($params);
			$sql = 'EXECUTE ' . $nameSPC . ' ';
			$sql .= $this->addPrepare($params);

			if($option === TRUE){
				// SPC_GET_MESSAGE_LST
				echo $this->interpolateQuery($sql, $params);
				return;
			}

			if($nameSPC != 'SPC_GET_MESSAGE_LST') {
				$this->logSystem($this->interpolateQuery($sql, $params));
			}

			$statement = $this->_db->getConnection()->prepare($sql);
			$statement->execute($params);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			// $statement = $this->_db->query($sql, $params);
			$result = array();
			switch ($option) {
				case 'group':
					$i = 0;
					do {
						if(isset($struct[$i])){
							while($respon = $statement->fetch(PDO::FETCH_ASSOC)) {
								foreach ($respon as &$res) {
									$res = htmlspecialchars($res);
								}
								$result[$i][$respon[$struct[$i]]][] = $respon;
							}
						} else {
							while($respon = $statement->fetch(PDO::FETCH_ASSOC)) {
								foreach ($respon as &$res) {
									$res = htmlspecialchars($res);
								}
								$result[$i][] = $respon;
							}
						}

						if(!isset($result[$i])) {
							$result[$i] = $this->getColumns($statement, '');
						}

						$i++;
					} while ($statement->nextRowset());
					break;

				default:
					$i = 0;
					do {
						while($respon = $statement->fetch(PDO::FETCH_ASSOC)) {
							foreach ($respon as &$res) {
								$res = htmlspecialchars($res);
							}
							$result[$i][] = $respon;
						}

						if(!isset($result[$i])) {
							$result[$i] = $this->getColumns($statement, $option);
						}

						$i++;
					} while ($statement->nextRowset());
					break;
			}
		} catch (Exception $e) {
			$result[0][0]['Data'] = 'Exception';
			$result[0][0]['Code'] = $e->getCode();
			$result[0][0]['Message'] = $e->getMessage();
			$this->logSystem($e->getMessage());
		}
		return $result;
	}

	public function getDb() {
		return $this->_db;
	}

	protected function addPrepare($params= array()) {
		if(empty($params)){return ' ';}
		return implode(',', array_fill(0, count($params), '?'));
	}

	protected function interpolateQuery($query, $params) {
		$keys = array();
		$values = $params;

		# build a regular expression for each parameter
		foreach ($params as $key => $value) {
			if (is_string($key)) {
				$keys[] = '/:'.$key.'/';
			} else {
				$keys[] = '/[?]/';
			}

			if (is_string($value))
				$values[$key] = "'" . $value . "'";

			if (is_array($value))
				$values[$key] = "'" . implode("','", $value) . "'";

			if (is_null($value))
				$values[$key] = 'NULL';
		}

		$query = preg_replace($keys, $values, $query, 1, $count);

		return $query;
	}


	protected function getColumns($statement, $option) {
		$columns = array();
		if($option == 'default'){
			for ($i = 0; $i < $statement->columnCount(); $i++) {
				$col = $statement->getColumnMeta($i);
				$columns[0][$col['name']] = $this->setDefaultByType($col['sqlsrv:decl_type']);
			}
		}
		return $columns;
	}

	private function setDefaultByType($mssql_type) {
		$default = null;

		switch(strtoupper($mssql_type)) {
			case 'BIT':
				$default = '';
				break;

			case 'TINYINT':
			case 'SMALLINT':
			case 'MEDIUMINT':
			case 'INT':
			case 'INTEGER':
			case 'BIGINT':
				$default = '';
				break;

			case 'FLOAT':
			case 'DOUBLE':
			case 'DECIMAL':
				$default = '';
				break;

			case 'CHAR':
			case 'ENUM':
			case 'SET':
			case 'VARCHAR':
			case 'NVARCHAR':
				$default = '';
				break;

			case 'TINYTEXT':
			case 'TEXT':
			case 'MEDIUMTEXT':
			case 'LONGTEXT':
				$default = '';
				break;

			case 'BINARY':
			case 'VARBINARY':
			case 'BLOB':
			case 'TINYBLOB':
			case 'MEDIUMBLOB':
			case 'LONGBLOB':
				$default = '';
				break;

			case 'DATE':
			case 'TIME':
			case 'DATETIME':
			case 'TIMESTAMP':
			case 'YEAR':
				$default = '';
				break;
			default:
				$default = '';
				break;
		}

		return $default;
	}

	private function logSystem($msg = '') {
		$this->logger->log($msg,7);
	}
}
