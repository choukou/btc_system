<?php
abstract class ExportData {
	protected $exportTo;
	protected $stringData;
	protected $tempFile;
	protected $tempFilename;

	public $filename;

	public function __construct($exportTo = "browser", $filename = "exportdata") {
		if(!in_array($exportTo, array('browser','file','string') )) {
			throw new Exception("$exportTo is not a valid ExportData export type");
		}
		$this->exportTo = $exportTo;
		$this->filename = $filename;
	}

	public function initialize() {

		switch($this->exportTo) {
			case 'browser':
				$this->sendHttpHeaders();
				break;
			case 'string':
				$this->stringData = '';
				break;
			case 'file':
				$this->tempFilename = tempnam(sys_get_temp_dir(), 'exportdata');
				$this->tempFile = fopen($this->tempFilename, "w");
				break;
		}

		$this->write($this->generateHeader());
	}

	public function addRow($row) {
		$this->write($this->generateRow($row));
	}

	public function finalize() {

		$this->write($this->generateFooter());

		switch($this->exportTo) {
			case 'browser':
				flush();
				break;
			case 'string':
				// do nothing
				break;
			case 'file':
				// close temp file and move it to correct location
				fclose($this->tempFile);
				rename($this->tempFilename, $this->filename);
				break;
		}
	}

	public function getString() {
		return $this->stringData;
	}

	abstract public function sendHttpHeaders();

	protected function write($data) {
		switch($this->exportTo) {
			case 'browser':
				echo $data;
				break;
			case 'string':
				$this->stringData .= $data;
				break;
			case 'file':
				fwrite($this->tempFile, $data);
				break;
		}
	}

	protected function generateHeader() {
	}

	protected function generateFooter() {
	}

	abstract protected function generateRow($row);

}

/**
 * ExportDataCSV
 */
class ExportDataCSV extends ExportData {

	function generateRow($row) {
		foreach ($row as $key => $value) {
			$row[$key] = '"'. str_replace('"', '\"', $value) .'"';
		}
		return implode(",", $row) . "\n";
	}

	function sendHttpHeaders() {
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=" . basename($this->filename));
	}
}
