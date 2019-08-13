<?php

class BeezupOMOrderChangeMetaInfo {
	
	protected $sCSharpType = null;
	protected $sName = null;
	protected $sValue = null;
	
	
	/**
	 * @return the $sCode
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * @param NULL $sCode
	 */
	public function setName($sName)
	{
		$this->sName = (string)$sName;
		return $this;
	}

	/**
	 * @return the $sCSharpType
	 */
	public function getCSharpType()
	{
		return $this->sCSharpType;
	}

	/**
	 * @param NULL $sCSharpType
	 */
	public function setCSharpType($sCSharpType)
	{
		$this->sCSharpType = $sCSharpType;
		return $this;
	}

	/**
	 * @return the $sCode
	 */
	public function getValue()
	{
		return $this->sValue;
	}
	
	/**
	 * @param NULL $sCode
	 */
	public function setValue($sValue)
	{
		$this->sValue = (string)sValue;
		return $this;
	}
	
	
	public static function fromArray(array $aData = array()){
		$oValue = new BeezupOMExpectedOrderChangeMetaInfo();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if (!method_exists($oValue,$sSetterMethod)){
				continue;
			}
			$cCallback = array($oValue,$sSetterMethod);
			if (is_scalar($mValue) && !is_null($mValue)){
				call_user_func($cCallback, $mValue);
			} // if
		} // foreach
		return $oValue;		
	} 
	
	public function toArray(){
		return array(
			"cSharpType" => $this->getCSharpType(),
			"value" => $this->getValue(),
			"name" => $this->getName(),		
		);		
	}
	
}