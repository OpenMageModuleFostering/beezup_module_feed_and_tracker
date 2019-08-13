<?php

	class BeezupOMSetOrderIdRequest extends BeezupOMRequest {

		protected $sMethod = self::METHOD_PATCH;

		protected $oOrderIdentifier = null;
		protected $oValues          = null;

		public static function fromArray(array $aData = array()){
			$aData = array_change_key_case($aData, CASE_LOWER);
			$oResult = new self();
			if (isset($aData['beezuporderuuid'])) {
				$oResult
					->setOrderIdentifier(BeezupOMOrderIdentifier::fromArray($aData ? $aData : array()))
					->setValues(BeezupOMSetOrderIdValues::fromArray(isset($aData['values']) ? $aData['values'] : array()));
			}
			return $oResult;
		}

		public function toArray(){
			return array(
				'orderIdentifier' => $this->getOrderIdentifier()->toArray(),
				'values'		  => $this->getValues()->toArray()
			);
		}

		public function setOrderIdentifier(BeezupOMOrderIdentifier $oOrderIdentifier) {
			$this->oOrderIdentifier = $oOrderIdentifier;
			return $this;
		}

		/**
		 *
		 * @return BeezupOMOrderIdentifier
		 */
		public function getOrderIdentifier() {
			return $this->oOrderIdentifier;
		}

		public function setValues(BeezupOMSetOrderIdValues $oValues) {
			$this->oValues = $oValues;
			return $this;
		}

		/**
		 *
		 * @return BeezupOMSetOrderIdValues
		 */
		public function getValues() {
			return $this->oValues;
		}

	}