<?php

	class BeezupOMPaginationResult extends BeezupOMResult{

		private $nCurrentNumberOfEntries= null;
		private $nTotalNumberOfPages= null;
		private $nTotalNumberOfEntries= null;
		private $aLinks = array();

		public function addLink(BeezupOMLink $oLink){
			$this->aLinks[] = $oLink;
			return false;
			
		}
		
		public function getLinks(){
			return $this->aLinks;
				
		}
		
		public function getLinkByRel($sRel){
			foreach ($this->getLinks() as $oLink){
				if ($oLink->getRel() === $sRel){
					return $oLink;
				}
			}
			return null;
		}
		/**
		 * @return the $nCurrentNumberOfEntries
		 */
		public function getCurrentNumberOfEntries() {
			return $this->nCurrentNumberOfEntries;
		}

		/**
		 * @return the $nTotalNumberOfPages
		 */
		public function getTotalNumberOfPages() {
			return $this->nTotalNumberOfPages;
		}

			/**
		 * @return the $nTotalNumberOfEntries
		 */
		public function getTotalNumberOfEntries() {
			return $this->nTotalNumberOfEntries;
		}

			/**
		 * @param NULL $nCurrentNumberOfEntries
		 */
		public function setCurrentNumberOfEntries($nCurrentNumberOfEntries) {
			$this->nCurrentNumberOfEntries = (int)$nCurrentNumberOfEntries;
			return $this;
		}

			/**
		 * @param NULL $nTotalNumberOfPages
		 */
		public function setTotalNumberOfPages($nTotalNumberOfPages) {
			$this->nTotalNumberOfPages = (int)$nTotalNumberOfPages;
			return $this;
		}

			/**
		 * @param NULL $nTotalNumberOfEntries
		 */
		public function setTotalNumberOfEntries($nTotalNumberOfEntries) {
			$this->nTotalNumberOfEntries = (int)$nTotalNumberOfEntries;
			return $this;
		}

		public static function fromArray(array $aData = array()){

			$oPaginationResult = new BeezupOMPaginationResult();

			$oPaginationResult
				->setCurrentNumberOfEntries($aData['currentNumberOfEntries'])
				->setTotalNumberOfEntries($aData['totalNumberOfEntries'])
				->setTotalNumberOfPages($aData['totalNumberOfPages']);

			foreach ($aData['links'] as $aLink){
				$oPaginationResult->addLink(BeezupOMLink::fromArray($aLink));
			}

			return $oPaginationResult;

		}

	}