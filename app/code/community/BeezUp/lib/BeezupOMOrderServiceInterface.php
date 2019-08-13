<?php

	interface BeezupOMOrderServiceInterface {
		
		public function changeOrder();
		public function checkSynchronizationAlreadyInProgress();
		public function synchronizeOrders();
		public function synchronizeOrder();
		
	}