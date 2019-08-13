<?php

	abstract class BeezupOMSummary {
		private $sCode = null;
		private $sMessage = null;
		public function __construct($sCode = '', $sMessage = ''){
			$this->sCode = (string)$sCode;
			$this->sMessage = (string)$sMessage;
		}
		public function getCode(){
			return $this->sCode;
		}
		public function getMessage(){
			return $this->sMessage;
		}
		public function setCode($sCode){
			$this->sCode = $sCode;
			return $this;
		}
		public function setMessage($sMessage){
			$this->sMessage = $sMessage;
			return $this;
		}
		public function toArray(){
			return array(
				'code' 		=> $this->getCode(),
				'message'	=> $this->getMessage()
			);
		}
		public static function fromArray(array $aData = array()){
			// @todo PHP <  5.3
			$oResult = new static();
			if (isset($aData['code'])){
				$oResult->setCode($aData['code']);
			}
			if (isset($aData['message'])){
				$oResult->setMessage($aData['message']);
			}
			return $oResult;
		}
	}