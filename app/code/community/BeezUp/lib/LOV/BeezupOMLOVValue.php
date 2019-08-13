<?php
	
	class BeezupOMLOVValue {
	
		protected $nPosition = null;
		protected $sCodeIdentifier = null;
		protected $sTranslationText = null;
		protected $nIntIdentifier = null;
		
		/**
	 * @return the $nPosition
	 */
	public function getPosition()
	{
		return $this->nPosition;
	}

		/**
	 * @param NULL $nPosition
	 */
	public function setPosition($nPosition)
	{
		$this->nPosition = (int)$nPosition;
		return $this;
	}

		/**
	 * @return the $sCodeIdentifier
	 */
	public function getCodeIdentifier()
	{
		return $this->sCodeIdentifier;
	}

		/**
	 * @param NULL $sCodeIdentifier
	 */
	public function setCodeIdentifier($sCodeIdentifier)
	{
		$this->sCodeIdentifier = (string)$sCodeIdentifier;
		return $this;
	}

		/**
	 * @return the $sTranslationText
	 */
	public function getTranslationText()
	{
		return $this->sTranslationText;
	}

		/**
	 * @param NULL $sTranslationText
	 */
	public function setTranslationText($sTranslationText)
	{
		$this->sTranslationText = (string)$sTranslationText;
		return $this;
	}

		/**
	 * @return the $nIntIdentifier
	 */
	public function getIntIdentifier()
	{
		return $this->nIntIdentifier;
	}

		/**
	 * @param NULL $nIntIdentifier
	 */
	public function setIntIdentifier($nIntIdentifier)
	{
		$this->nIntIdentifier = (int)$nIntIdentifier;
		return $this;
	}

	public static function fromArray(array $aData = array()){
		$oValue = new BeezupOMLOVValue();
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

	public function toArray(){
		return array(
			'Position' => $this->getPosition(),
			'CodeIdentifier'	=> $this->getCodeIdentifier(),
			'TranslationText'	=> $this->getTranslationText(),
			'IntIdentifier'	=> $this->getIntIdentifier()
		);
	}
	
		
	}