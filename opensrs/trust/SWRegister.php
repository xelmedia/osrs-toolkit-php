<?php

namespace opensrs\trust;

use OpenSRS\Base;
use OpenSRS\Exception;

class SWRegister extends Base {
	public $action = "sw_register";
	public $object = "trust_service";

	public $_formatHolder = "";
	public $resultFullRaw;
	public $resultRaw;
	public $resultFullFormatted;
	public $resultFormatted;

	public $requiredFields = array(
		'cookie',

		'attributes' => array(
			'reg_type',
			'product_type'
			),
		);

	public function __construct( $formatString, $dataObject, $returnFullResponse = true ) {
		parent::__construct();

		$this->_formatHolder = $formatString;

		$this->_validateObject( $dataObject );

		$this->send( $dataObject, $returnFullResponse );
	}

	public function __destruct () {
		parent::__destruct();
	}
}
