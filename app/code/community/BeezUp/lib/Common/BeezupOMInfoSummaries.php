<?php

	class BeezupOMInfoSummaries {

		private static $aDefinitions = array(
			'errors'		=> array('name' => 'error', 'class' => 'BeezupOMErrorSummary'),
			'warnings'		=> array('name' => 'warning', 'class' => 'BeezupOMWarningSummary'),
			'informations'	=> array('name' => 'information', 'class' => 'BeezupOMInfoSummary'),
			'successes'		=> array('name' => 'success', 'class' => 'BeezupOMSuccessSummary'),
		);

		private $aErrors = array();
		private $aWarnings = array();
		private $aInformations = array();
		private $aSuccesses = array();

		public static function fromArray(array $aData = array()) {
			$oInfo = new BeezupOMInfoSummaries();
			return $oInfo->merge($aData);
		}

		public function toArray() {
			$aResult = array(
				'errors'		=> $this->getErrors(),
				'warnings'		=> $this->getWarnings(),
				'informations'	=> $this->getInformations(),
				'successes'		=> $this->getSuccesses(),
			);
			foreach ($aResult as $sType => $aList){
				foreach ($aList as $nIndex=>$oItem){
					$aResult[$sType][$nIndex] = $oItem->toArray();
				}
			}
			return $aResult;

		}

		public function merge(array $aData  = array()) {
			foreach (self::$aDefinitions as $sProperty => $aDefinition) {
				if (!isset($aData[$sProperty])){
					continue;
				} // if
				$sClassName = $aDefinition['class'];
				$sCodePropertyName = $aDefinition['name'] . 'Code';
				$sMessagePropertyName = $aDefinition['name'] . 'Message';
				$cAddCallback = array($this, 'add' . ucfirst($aDefinition['name']));

				foreach ($aData[$sProperty] as $mAtom){

					if (is_object($mAtom) && $mAtom instanceof BeezupOMSummary){
						$oAtom = $mAtom;
					} else if (is_array($mAtom)){
						$aAtom = $mAtom;

						if (array_key_exists('code', $aAtom)){
							$oAtom = call_user_func(array($sClassName, 'fromArray'), $aAtom);
						} else if (array_key_exists($sCodePropertyName, $aAtom) && array_key_exists($sMessagePropertyName, $aAtom)){
							$oAtom = new $sClassName((string)$aAtom[$sCodePropertyName], (string)$aAtom[$sMessagePropertyName]);
						}
					} else {
						continue;
					}
					if ($oAtom){
						call_user_func($cAddCallback, $oAtom);
					}
				} // foreach
			} // foreach
			return $this;
		}

		public function getErrors(){
			return $this->aErrors;
		}

		public function addError(BeezupOMSummary $oAtom){
			$this->aErrors[] = $oAtom;
			return $this;

		}

		public function getWarnings(){
			return $this->aWarnings;
		}

		public function addWarning(BeezupOMSummary $oAtom){
			$this->aWarnings[] = $oAtom;
			return $this;
		}

		/**
		 *
		 * @return array:
		 */
		public function getInformations(){
			return $this->aInformations;
		}

		public function addInformation(BeezupOMSummary $oAtom){
			$this->aInformations[] = $oAtom;
			return $this;
		}

		public function getSuccesses(){
			return $this->aSuccesses;
		}

		public function addSuccess(BeezupOMSummary $oAtom){
			$this->aSuccesses[] = $oAtom;
			return $this;
		}
}