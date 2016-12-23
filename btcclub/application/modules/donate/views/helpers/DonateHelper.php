<?php

class Donate_View_Helper_DonateHelper extends Zend_View_Helper_Abstract {

	public function DonateHelper() {
		return $this;
	}

	public function prefixPin($type = 0) {
		$prefix = '';
		if(in_array($type, array(2,5)) ) {
			$prefix = "+";
		}
		if(in_array($type, array(1,3,4)) ) {
			$prefix = "-";
		}
		return $prefix;
	}
}
?>
