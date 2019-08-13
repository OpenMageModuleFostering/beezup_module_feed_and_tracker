<?php
	
	/**
	 * Container class for request data 
	 */
	class BeezupOMRequestData {

	/**
	 * Request method
	 * @var string GET or POST
	 */
	protected $sMethod = 'GET';
	
	/**
	 * Additional headers
	 * @var array
	 */
	protected $aHeaders = array();
	
	/**
	 * Any additional query params
	 * @var array
	 */
	protected $aParams  = array();
	
	/**
	 * Request url
	 * @var string
	 */
	protected $sUrl 	= null;
	
	protected $sBody    = null;
	
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

		/**
	 * @return the $aParams
	 */
	public function getParams()
	{
		return $this->aParams;
	}

		/**
	 * @param multitype: $aParams
	 */
	public function setParams($aParams)
	{
		$this->aParams = $aParams;
		return $this;
	}

		/**
	 * @return the $sUrl
	 */
	public function getUrl()
	{
		return $this->sUrl;
	}

		/**
	 * @param NULL $sUrl
	 */
	public function setUrl($sUrl)
	{
		$this->sUrl = $sUrl;
		return $this;
	}
		
	public function setBody($sBody){
		$this->sBody = $sBody;
		return $this;
	}
	
	public function getBody(){
		return $this->sBody;
	}
	
	
}