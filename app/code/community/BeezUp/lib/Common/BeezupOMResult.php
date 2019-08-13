<?php 
	class BeezupOMResult extends BeezupOMDataHandler {
		
		public function __call($sMethod, $aArgs) {
			throw new BadMethodCallException(sprintf('Unimplemented method %s::%s', get_class($this), $sMethod));
		}
	}