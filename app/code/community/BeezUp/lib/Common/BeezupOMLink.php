<?php 

	class BeezupOMLink {
		
		private $aHeaders = array();
		private $sHref	  = null;
		private $oInfo 	  = null;
		private $sMethod  = 'GET';
		private $sRel	  = null; 
		private $aParameters  = array();

	public static function fromArray(array $aData = array()){

		$oLink = new BeezupOMLink();
		
		$aData = array_combine(array_map('strtolower', array_keys($aData)), array_values($aData));

		$oLink
			->setRel($aData['rel'])
			->setHref($aData['href'])
			->setMethod($aData['method'])
			->setHeaders((array)$aData['headers']);
		
		if (isset($aData['info'])){
			
			$oLink->setInfo(BeezupOMInfoSummaries::fromArray($aData['info']));
			
		}
		
		if (isset($aData['parameters']) && is_array($aData['parameters'])){
			foreach ($aData['parameters'] as $aParameter){
				$oLink->addParameter(BeezupOMExpectedOrderChangeMetaInfo::fromArray($aParameter));
			}
		}
				
		return $oLink;
		
	}	

	public function __toString(){
		return $this->getHref();
	}
	
	
	public function toArray(){
		
		return array(
			'rel' 		=> $this->getRel(),
			'href'		=> $this->getHref(),
			'method'	=> $this->getMethod(),
			'headers'	=> $this->getHeaders(),
			'parameters'=> $this->getParametersAsArrays()
		);
	
	}
	
	/**
	 * @return the $aHeaders
	 */
	public function getHeaders()
	{
		return $this->aHeaders;
	}

	/**
	 * @param multitype: $aHeaders
	 */
	public function setHeaders($aHeaders)
	{
		$this->aHeaders = $aHeaders;
		return $this;
		
	}

	public function addHeader($sHeader){
		$this->aHeaders[] = $sHeader;
	}
	
	/**
	 * @return the $sHref
	 */
	public function getHref()
	{
		return $this->sHref;
	}

		/**
	 * @param NULL $sHref
	 */
	public function setHref($sHref)
	{
		$this->sHref = $sHref;
		return $this;
		
	}

		/**
	 * @return the $oInfo
	 */
	public function getInfo()
	{
		return $this->oInfo;
	}

		/**
	 * @param NULL $oInfo
	 */
	public function setInfo($oInfo)
	{
		$this->oInfo = $oInfo;
		return $this;
		
	}

		/**
	 * @return the $sMethod
	 */
	public function getMethod()
	{
		return $this->sMethod;
	}

		/**
	 * @param string $sMethod
	 */
	public function setMethod($sMethod)
	{
		$this->sMethod = $sMethod;
		return $this;
		
	}

		/**
	 * @return the $sRel
	 */
	public function getRel()
	{
		return $this->sRel;
	}

		/**
	 * @param NULL $sRel
	 */
	public function setRel($sRel)
	{
		$this->sRel = $sRel;
		return $this;
		
	}

		/**
	 * @return the $aValues
	 */
	public function getParameters()
	{
		return $this->aParameters;
	}

	public function getParametersAsArrays()
	{
		$aResult = array();
		foreach ($this->aParameters as $oParameter){
			$aResult[] = $oParameter->toArray();
		}
		return $aResult;
	}

	/**
	 * @param multitype: $aValues
	 */
	public function setParameters($aParameters)
	{
		$this->aParameters = $aParameters;
		return $this;
	}

	public function addParameter(BeezupOMExpectedOrderChangeMetaInfo $oParameter){
		
		$this->aParameters[] = $oParameter;
		return $this;
	}	
		
		}