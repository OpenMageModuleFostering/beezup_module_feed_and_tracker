<?php

class BeezupOMExpectedOrderChangeMetaInfo {
	
	protected $sCSharpType = null;
	protected $bIsLOVRequired = false;
	protected $bIsMandatory = false;
	protected $oLovLink = null;
	protected $sName = null;
	
	
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
	 * @return the $bIsLOVRequired
	 */
	public function getIsLOVRequired()
	{
		return $this->bIsLOVRequired;
	}
	
	public function isLOVRequired()
	{
		return $this->bIsLOVRequired;
	}
	
	/**
	 * @param boolean $bIsLOVRequired
	 */
	public function setIsLOVRequired($bIsLOVRequired)
	{
		$this->bIsLOVRequired = (boolean)$bIsLOVRequired;
	}

	/**
	 * @return the $bISMandatory
	 */
	public function getIsMandatory()
	{
		return $this->bIsMandatory;
	}
	
	public function isMandatory()
	{
		return $this->bIsMandatory;
	}
	/**
	 * @param boolean $bISMandatory
	 */
	public function setIsMandatory($bIsMandatory)
	{
		$this->bIsMandatory = (boolean)$bIsMandatory;
		return $this;
	}

	/**
	 * @return the $oLovLink
	 */
	public function getLovLink()
	{
		return $this->oLovLink;
	}

	/**
	 * @param NULL $oLovLink
	 */
	public function setLovLink(BeezupOMLink $oLovLink)
	{
		$this->oLovLink = $oLovLink;
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
			if (strtolower($sKey)==='lovlink' && !is_null($mValue)){
				call_user_func($cCallback, BeezupOMLink::fromArray($mValue));			
			} else if (is_scalar($mValue) && !is_null($mValue)){
				call_user_func($cCallback, $mValue);
			} // if
		} // foreach
		return $oValue;		
	} 
	
	public function toArray(){
		return array(
			"cSharpType" => $this->getCSharpType(),
			"isMandatory" => $this->getIsMandatory(),
			"islovRequired" => $this->getIsLOVRequired(),
			"lovLink" => $this->getLovLink() ? $this->getLovLink()->toArray() : null,
			"name" => $this->getName(),		
		);		
	}
	
}