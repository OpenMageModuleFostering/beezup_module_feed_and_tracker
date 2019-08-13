<?php

	/**
	 * BeezUP API credentials storage
	 */
	class BeezupOMCredential {
		
		/**
		 * BeezUP API token
		 * @var string
		 */
		private $sBeezupApiToken = null;
		
		/**
		 * Beezup User Id
		 * @var string
		 */
		private $sBeezupUserId	 = null;
		
		public function __construct($sBeezupUserId = null, $sBeezupApiToken = null){
			$this
				->setBeezupApiToken($sBeezupApiToken)
				->setBeezupUserId($sBeezupUserId);
		}
		
		/**
		 * Returns BeezUP API token
		 * @return string
		 */
		public function getBeezupApiToken(){
			return $this->sBeezupApiToken;
		}
		
		/**
		 * Sets BeezUP API token
		 * @param string $sBeezupApiToken BeezUP API token
		 * @return BeezupOMCredential Self
		 */
		public function setBeezupApiToken($sBeezupApiToken){
			$this->sBeezupApiToken = (string)$sBeezupApiToken;
			return $this;
		}
		
		/**
		 * Returns BeezUP API token
		 * @return string
		 */
		public function getBeezupUserId(){
			return $this->sBeezupUserId;
		}
		
		/**
		 * Sets BeezUP User Id
		 * @param string $sBeezupUserId BeezUP User Id
		 * @return BeezupOMCredential Self
		 */
		public function setBeezupUserId($sBeezupUserId){
			$this->sBeezupUserId = (string)$sBeezupUserId;
			return $this;
		}		
		
	}