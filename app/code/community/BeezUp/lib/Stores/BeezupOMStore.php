<?php
	
	class BeezupOMStore {
	
		protected  $sBeezupStoreId = null;
		protected  $sBeezUPStoreName = null;
	
		/**
	 * @return the $sBeezupStoreId
	 */
	public function getBeezupStoreId()
	{
		return $this->sBeezupStoreId;
	}

		/**
	 * @param NULL $sBeezupStoreId
	 */
	public function setBeezupStoreId($sBeezupStoreId)
	{
		$this->sBeezupStoreId = $sBeezupStoreId;
		return $this;
	}

		/**
	 * @return the $sBeezUPStoreName
	 */
	public function getBeezUPStoreName()
	{
		return $this->sBeezUPStoreName;
	}	

		/**
	 * @param NULL $sBeezUPStoreName
	 */
	public function setBeezUPStoreName($sBeezUPStoreName)
	{
		$this->sBeezUPStoreName = $sBeezUPStoreName;
		return $this;
	}

		public static function fromArray(array $aData = array()){
			$oValue = new BeezupOMStore();
			foreach ($aData as $sKey=>$mValue){
				$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
				$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
				if (!method_exists($oValue,$sSetterMethod)){
					continue;
				}
				$cCallback = array($oValue,$sSetterMethod);
				if (is_scalar($mValue)){
					call_user_func($cCallback, $mValue);
				} // if
			} // foreach
			return $oValue;
		}
	}