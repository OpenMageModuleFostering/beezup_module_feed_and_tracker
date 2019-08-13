<?php
	
	class BeezupOMLOVRequest extends BeezupOMRequest {
	
		protected $sCultureName = 'en';
		protected $sListName = null;
		/**
	 * @return the $sCultureName
	 */
	public function getCultureName()
	{
		return $this->sCultureName;
	}

		/**
	 * @param string $sCultureName
	 */
	public function setCultureName($sCultureName)
	{
		$this->sCultureName = $sCultureName;
		return $this;
	}

		/**
	 * @return the $sListName
	 */
	public function getListName()
	{
		return $this->sListName;
	}

		/**
	 * @param NULL $sListName
	 */
	public function setListName($sListName)
	{
		$this->sListName = $sListName;
		return $this;
	}

	
	
	}