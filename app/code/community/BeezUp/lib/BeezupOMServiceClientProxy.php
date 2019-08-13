<?php

	/**
	 * Main class used to connect with BeezUP API
	 */
	class BeezupOMServiceClientProxy {

	# CLASS CONSTANTS

		/**
		 * @var string Library version
		 */
		const VERSION		= '0.1';

		// @todo define methods in one place
		const METHOD_GET 	= 'GET';
		const METHOD_POST	= 'POST';
		const METHOD_PATCH	= 'PATCH';

	# PRIVATE VARIABLES

		/**
		 * API base - host
		 * @var string Host (with protocol, without trailing slash)
		 */
		private $sBaseHost = 'https://api.beezup.com';

		/**
		 * API base - path
		 * @var string Path part of URL, with trailing slashes
		 */
		private $sBasePath = '/orders/v1/';

		/**
		 * API credential
		 * @var BeezupOMCredential
		 */
		private $oCredential = null;

		/**
		 * cURL handler
		 * @var resource cURL handler
		 */
		private $rCurl = null;

		/**
		 * cURL initial options
		 * @var array
		 */
		private $aCurlOptions = array(
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_TIMEOUT			=> 30,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_SSL_VERIFYPEER  => false
		);

		/**
		 * Debug mode. See BeezupOMServiceClientProxy::debug
		 * @var boolean
		 */
		private $bDebugMode = false;

		/**
		 * Current curl call headers
		 * @var array
		 */
		private $aCurrentHeader = array();
	# MAGIC METHODS

		/**
		 * Class constructor
		 * @param BeezupOMCredential $oCredential
		 */
		public function __construct(BeezupOMCredential $oCredential){
			$this->setCredential($oCredential);
		}

	# SETTERS / GETTERS

		/**
		 * Returns library version
		 * @return string Version string
		 */
		public function getVersion(){
			return self::VERSION;
		}

		/**
		 * Sets API credential
		 * @param BeezupOMCredential $oCredential
		 * @return BeezupOMServiceClientProxy Self
		 */
		public function setCredential(BeezupOMCredential $oCredential){
			$this->oCredential = $oCredential;
			return $this;
		}

		/**
		 * Gets API credential
		 * @return BeezupOMCredential API credential
		 */
		public function getCredential(){
			return $this->oCredential;
		}

		/**
		 */
		public function setBaseHost($sBaseHost){
			$this->sBaseHost = (string)$sBaseHost;
			return $this;
		}

		/**
		 *
		 * @return string		 */
		public function getBaseHost(){
			return $this->sBaseHost;
		}

		/**
		 * Sets API's relative path
		 * @param string $sBasePath API's relative path, starting and ending with "/"
		 * @return BeezupOMServiceClientProxy
		 */
		public function setBasePath($sBasePath){
			$this->sBasePath = (string)$sBasePath;
			return $this;
		}

		/**
		 * Gets API's relative path
		 * @return string Api relative path, starting and ending with "/"
		 */
		public function getBasePath(){
			return $this->sBasePath;
		}

		/**
		 * Returns API's base URL
		 * @return string Base url
		 */
		public function getBaseUrl(){
			return $this->getBaseHost() . $this->getBasePath();
		}

		/**
		 * Sets debug mode
		 * @param boolean $bDebugMode
		 * @return BeezupOMServiceClientProxy
		 */
		public function setDebugMode($bDebugMode){
			$this->bDebugMode = (bool)$bDebugMode;
			return $this;
		}

		/**
		 * Returns debug mode
		 * @return boolean True if debug mode activated
		 */
		public function getDebugMode(){
			return $this->bDebugMode;
		}

		/**
		 * tests whether debug mode is activated
		 * @return boolean  True if debug mode activated
		 */
		public function isDebugModeActivated(){
			return $this->bDebugMode;
		}

	# PUBLIC API

		/**
		 * Those methods are primary methods of this library. They should be used to all operations
		 * One can use hand-crafted request (instance of BeezupOM*Request) or one of links sent by API (BeezupOMLink), transition or normal link
		 */

		/**
		 * Simple call to verify if credentials and connection are ok
		 * @return BeezupOMResponse
		 */
		public function validate(){
			$oRequestData = $this->createRequestData()
				->setHeaders(array('Content-type: application/json'))
				->setUrl($this->getUrl(array('validate')));
			$oResponse = $this->doRequest($oRequestData, new BeezupOMResponse());
			return $oResponse;
		} // validate

		/**
		 * Returns list of all stores associated with account
		 * @return BeezupOMStoresResponse
		 */
		public function stores(){
			$oRequestData = $this->createRequestData()
				->setHeaders(array('Content-type: application/json'))
				->setUrl($this->getUrl(array('stores')));
			$oResponse = $this->doRequest($oRequestData, new BeezupOMStoresResponse());
			return $oResponse;
		}

		/**
		 * Sets merchant order id
		 * @param BeezupOMSetOrderIdRequest $oRequest
		 * @return BeezupOMSetOrderIdResponse
		 */
		public function setOrderMerchantId(BeezupOMSetOrderIdRequest $oRequest){
			$aUrlPath = array(
				'marketPlace' 		=> $oRequest->getOrderIdentifier()->getMarketplaceTechnicalCode(),
				'accountId' 		=> $oRequest->getOrderIdentifier()->getAccountId(),
				'beezUPOrderUUID' 	=> $oRequest->getOrderIdentifier()->getBeezupOrderUUID(),
				'SetMerchantOrderId'=> 'SetMerchantOrderId'
			);
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrl($aUrlPath))
				->setMethod($oRequest->getMethod())
				->setHeaders(array('Content-type: application/json'))
				->setBody(json_encode($oRequest->getValues()->toArray()));
			$oResponse = $this->doRequest($oRequestData, new BeezupOMSetOrderIdResponse());
			return $oResponse;
		}

		/**
		 * Sets merchant order id
		 * @param BeezupOMLink $oLink
		 * @param BeezupOMSetOrderIdValues $oValues
		 * @return BeezupOMSetOrderIdResponse
		 */
		public function setOrderMerchantIdByLink(BeezupOMLink $oLink, BeezupOMSetOrderIdValues $oValues){
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrlFromLink($oLink))
				->setMethod($oLink->getMethod())
				->setHeaders(array('Content-type: application/json'))
				->setBody(json_encode($oValues->toArray()));
			$oResponse = $this->doRequest($oRequestData, new BeezupOMSetOrderIdResponse());
			return $oResponse;
		}

		/**
		 * Returns list of values (LOV). There are many LOVs possible, differentiated by name and culture
		 * @param BeezupOMLOVRequest $oRequest
		 * @return BeezupOMLOVResponse
		 */
		public function getLOV(BeezupOMLOVRequest $oRequest){
			$oRequestData = $this->createRequestData()
				->setHeaders(array('Content-type: application/json'))
				->setUrl($this->getUrl(array('lov', $oRequest->getListName()), array('cultureName' => $oRequest->getCultureName())));
			$oResponse = $this->doRequest($oRequestData, new BeezupOMLOVResponse());
			return $oResponse;
		} // getLOV

		/**
		 * Returns list of values (LOV). There are many LOVs possible, differentiated by name and culture
		 * @param BeezupOMLink $oLink
		 * @return BeezupOMLOVResponse
		 */
		public function getLOVByLink(BeezupOMLink $oLink){
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrlFromLink($oLink))
				->setHeaders(array('Content-type: application/json'))
				->setMethod($oLink->getMethod());
			$oResponse = $this->doRequest($oRequestData, new BeezupOMLOVResponse());
			return $oResponse;
		} // getLOV

		/**
		 * Returns list of orders for given params
		 * @param BeezupOMOrderListRequest $oRequest
		 * @return BeezupOMOrderListResponse
		 */
		public function getOrderList(BeezupOMOrderListRequest $oRequest){
			$aRequest = $oRequest->toArray();
			$aRequest['beginPeriodUtcDate'] = $oRequest->getBeginPeriodUtcDate()->sub(new DateInterval("PT2H"))->format('Y-m-d\TH:i:s\Z');
			
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrl(array(), $aRequest))
				->setHeaders(array('Content-type: application/json'))
				->setMethod($oRequest->getMethod());
			$oResponse = $this->doRequest($oRequestData, new BeezupOMOrderListResponse());
			return $oResponse;
		} // getOrderList

		/**
		 * Returns list of orders for given params
		 * @param BeezupOMLink $oLink
		 * @return BeezupOMOrderListResponse
		 */
		public function getOrderListByLink(BeezupOMLink $oLink){
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrlFromLink($oLink))
				->setHeaders(array('Content-type: application/json'))
				->setMethod($oLink->getMethod());
			return $this->doRequest($oRequestData, new BeezupOMOrderListResponse());

		}

		/**
		 * Fetches order via link
		 * @param BeezupOMLink $oLink
		 * @return BeezupOMOrderResponse
		 */
		public function getOrderByLink(BeezupOMLink $oLink){
			$aHeaders = array();
			$aHeaders[] = 'Content-type: application/json';
			foreach ($oLink->getHeaders() as $mKey=>$sHeader){
				$aHeaders[] = is_numeric($mKey) ? $sHeader : $mKey . ': ' . $sHeader;
			}

			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrlFromLink($oLink))
				->setHeaders($aHeaders)
				->setMethod($oLink->getMethod());
			return $this->doRequest($oRequestData, new BeezupOMOrderResponse());
		} // getOrderByLink

		/**
		 * Fetches order
		 * @param BeezupOMOrderRequest $oRequest
		 * @return BeezupOMOrderResponse
		 */
		public function getOrder(BeezupOMOrderRequest $oRequest){
			$aUrlPath = array(
				'marketPlace' 		=> $oRequest->getOrderIdentifier()->getMarketplaceTechnicalCode(),
				'accountId' 		=> $oRequest->getOrderIdentifier()->getAccountId(),
				'beezUPOrderUUID' 	=> $oRequest->getOrderIdentifier()->getBeezupOrderUUID(),
			);
			$aUrlParams = array(
				'ignoreCurrentActivity' => $oRequest->ignoreCurrentActivity()
			);
			$aHeaders = array();
			$aHeaders[] = 'Content-type: application/json';
			if ($oRequest->getETagIfNoneMatch()){
				$aHeaders[] = 'If-None-Match: '.$oRequest->getETagIfNoneMatch();
			}

			$oRequestData = $this
							->createRequestData()
							->setUrl($this->getUrl($aUrlPath, $aUrlParams))
							->setHeaders($aHeaders);

			return $this->doRequest($oRequestData, new  BeezupOMOrderResponse());
		} // getOrder

		/**
		 * Returns order history
		 * @param BeezupOMOrderHistoryRequest $oRequest
		 * @throws BadMethodCallException
		 * @stub
		 */
		public function getOrderHistory(BeezupOMOrderHistoryRequest $oRequest){
			throw new BadMethodCallException('Not implemented yet');
		} // getOrderHistory

		/**
		 * Changes order using transition link
		 * @param BeezupOMLink $oLink Link to execute
		 * @param array $aParams Url params to add (notably, userName and testMode)
		 * @param array $aPostData Post data to send
		 * @return BeezupOMOrderChangeResponse
		 */
		public function changeOrderByLink(BeezupOMLink $oLink, array $aParams = array(), array $aPostData = array()){
			$oRequestData = $this->createRequestData()
				->setUrl($this->getUrlFromLink($oLink, $aParams))
				->setMethod($oLink->getMethod())
				->setHeaders(array('Content-type: application/json'))
				->setBody(json_encode($aPostData));
			return $this->doRequest($oRequestData, new  BeezupOMOrderChangeResponse());
		} // changeOrderByLink

		/**
		 * Changes order
		 * @param BeezupOMOrderChangeRequest $oRequest
		 * @throws BadMethodCallException
		 * @stub
		 */
		public function changeOrder(BeezupOMOrderChangeRequest $oRequest){
			throw new BadMethodCallException('Not implemented yet');
		} // changeOrder

	# URL MANAGEMENT

		/**
		 * Creates url for API call. Adds credentials.
		 * @param array $aParams
		 * @return string Well formed API url
		 */
		protected function getUrl(array $aPath = array(), array $aParams = array()) {
			if (!isset($aParams['subscription-key'])){
				$aParams['subscription-key'] = $this->getCredential()->getBeezupApiToken();
			} // if
			array_unshift($aPath, $this->getCredential()->getBeezupUserId());
			return $this->getBaseUrl() . implode('/', $aPath) . '?' . http_build_query($aParams);
		} // getUrl

		/**
		 * Creates url from BeezupOMLink object. Adds credentials.
		 * @param BeezupOMLink $oLink
		 * @param array $aParams
		 * @return string Well formed API url
		 */
		protected function getUrlFromLink(BeezupOMLink $oLink,  array $aParams = array()){
			$sUrl = $this->getBaseHost() . $oLink->getHref();
			if (stristr($sUrl, '?') !== false){
				$aUrl = explode('?', $sUrl, 2);
				$sUrl = $aUrl[0];
				parse_str($aUrl[1], $aQuery);
			} else {
				$aQuery = array();
			}
			$aQuery = $aParams + $aQuery;
			if (!isset($aQuery['subscription-key'])){
				$aQuery['subscription-key'] = $this->getCredential()->getBeezupApiToken();
			}
			return $sUrl . '?' . http_build_query($aQuery);
		} // getUrlFromLink

	# REQUEST HANDLING

		/**
		 * Returns container for all request data (such as url or method)
		 * @return BeezupOMRequestData
		 */
		protected function createRequestData(){
			return new BeezupOMRequestData();
		} // createRequestData

		/**
		 * Doing request
		 * This function return always object BeezupOM*Response, which has 3 properties : info, request and result
		 * Info (instance of BeezupOMInfoSummaries) is a container for all messages, (errors, warnings, sucesses, infos) from API and from network part
		 * of this library (ie. curl errors)
		 * Request (descendant of BeezupOMRequest) is a request object. IT IS NOT original request, but request rebuild from response (because for links we do not have
		 * such object)
		 * Result (descendant of BeezupOMResult) is probably the result you want to handle
		 * Request and Result are created by calling $oResponse::createResult and $oResponse::createRequest
		 * @param BeezupOMRequestData $oRequestData
		 * @param BeezupOMResponse $oResponse
		 * @return BeezupOMResponse
		 * @todo Etag handling
		 *
		 */
		protected function doRequest(BeezupOMRequestData $oRequestData, BeezupOMResponse $oResponse){

			$this->debug(PHP_EOL . str_repeat('=', 70) . PHP_EOL);
			$this->debug(var_export($oRequestData, true));

			$aResult = array();
			$aReturnedRequest = array();
			$oInfo = new BeezupOMInfoSummaries();
			$oCurlResponse = $this->doCurlRequest($oRequestData);

			$this->debug(var_export($oCurlResponse, true));

			$aParsedResponse = $this->parseResponse($oCurlResponse->getResponse());
			if ($aParsedResponse && is_array($aParsedResponse)){
				if (isset($aParsedResponse['request']) && is_array($aParsedResponse['request'])){
					$aReturnedRequest = (array)$aParsedResponse['request'];
				} // if
				if (isset($aParsedResponse['result']) && is_array($aParsedResponse['result'])){
					$aResult = (array)$aParsedResponse['result'];
				} // if
				// any infos codes sent by Beezup API
				if (isset($aParsedResponse['info']) && is_array($aParsedResponse['info'])){
					// @todo: rather merge than overwrite
					$oInfo = $oInfo->merge($aParsedResponse['info']);
				} // if
				// azure error codes, notably identification error
				if (isset($aParsedResponse['message']) && isset($aParsedResponse['statusCode'])){
					$oInfo->addError(new BeezupOMErrorSummary($aParsedResponse['statusCode'], $aParsedResponse['message']));
				} // if
				$oResponse->parseRawResponse($aParsedResponse);
			}

			// curl errors
			$aCurlError = $oCurlResponse->getCurlError();
			if ($aCurlError && isset($aCurlError['code']) && (int)$aCurlError['code'] != 0){
				$oInfo->addError(new BeezupOMErrorSummary($aCurlError['code'], $aCurlError['message']));
			}

			$aCurlInfo = $oCurlResponse->getCurlInfo();
			// HTTP codes others than 200, 204, 304 (0 means http request failed and have curl error, it is probably in $aCurlError)
			if ($aCurlInfo && is_array($aCurlInfo) && isset($aCurlInfo['http_code']) &&  (!in_array((int)$aCurlInfo['http_code'], array(0, 200, 204, 304)) )){
				$oInfo->addError(new BeezupOMErrorSummary($aCurlInfo['http_code'], 'HTTP Error'));
			} // if

			$oReturnedRequest = $oResponse->createRequest($aReturnedRequest);
			$oResult = $oResponse->createResult($aResult);

			// @todo See if we can stick it at one level
			if (method_exists($oResponse, 'setHttpStatus')){
				call_user_func(array($oResponse, 'setHttpStatus'), (int)$aCurlInfo['http_code']);
			}
			if (method_exists($oResult, 'setHttpStatus')){
				call_user_func(array($oResult, 'setHttpStatus'), (int)$aCurlInfo['http_code']);
			}

			$aHeaders = $oCurlResponse->getHeaders();

			foreach (array('ETag' => 'setEtag', 'BeezUPHttpExecutionUUID' => 'setExecutionId') as $sHeaderName=>$sSetter)
				foreach (array($oResponse, $oResult) as $oTarget){
    				if (method_exists($oTarget, $sSetter) && isset($aHeaders[$sHeaderName])){
		    			call_user_func(array($oTarget, $sSetter), $aHeaders[$sHeaderName]);
	    			}
			}

			/**
			 * Code  304 => not modified
			 */
			if ($aCurlInfo && is_array($aCurlInfo) && isset($aCurlInfo['http_code']) && (int)$aCurlInfo['http_code'] === 304){
				$oResponse->setNotModified(true);
			}

			$oResponse
						->setInfo($oInfo)
						->setRequest($oReturnedRequest)
						->setResult($oResult);

			$this->debug(var_export($oResponse, true));

			return $oResponse;
		} // doRequest

		/**
		 * Parses raw response into array
		 * @param string $sResponse Valid JSON
		 * @return array|null Parsed response or null
		 */
		protected function parseResponse($sResponse){
			return json_decode($sResponse, true);
		} // parseResponse

	# CURL MANAGEMENT

		/**
		 * Returns curl handle. Creates and sets new handle if necessary
		 * @return resource Curl handle
		 */
		protected function getCurlHandle(){
			if ($this->rCurl === null) {
				$this->rCurl = $this->createCurlHandle();
			} // if
			return $this->rCurl;
		} // getCurlHandle

		/**
		 * Creates new curl handle, using options from self::aCurlOptions
		 * @return resource cUrl handle
		 */
		protected function createCurlHandle(){
			if (extension_loaded('curl')){
				$rCurl = curl_init();
				if (!is_resource($rCurl)){
					throw new RuntimeException('BeezupOM: Unable to create curl handle.');
				}
				curl_setopt_array($rCurl, $this->aCurlOptions);
				curl_setopt($rCurl, CURLOPT_HEADERFUNCTION, array($this, 'curlHeaderCallback'));
				return $rCurl;
			} else {
				throw new RuntimeException('BeezupOM: You need to activate curl extension!');
			}
		} // createCurlHandle

		/**
		 * makes actual curl call
		 * @param BeezupOMRequestData $oRequestData Shiny little object
		 * @return BeezupOMResponseData
		 */
		protected function doCurlRequest(BeezupOMRequestData $oRequestData) { // $sUrl, $sMethod = self::METHOD_GET, array $aHeaders = array(), array $aParams = array()) {

			if ($this->isDebugModeActivated()){
				if (PHP_SAPI === 'cli') {
					print PHP_EOL . $oRequestData->getMethod() .' ' . $oRequestData->getUrl();
					print PHP_EOL . implode(' | ', $oRequestData->getHeaders());
				} else {
					print PHP_EOL . sprintf('<div style="padding:10px; margin:10px; background-color: #cfc">%1$s <a href="%2$s " target="_blank">%2$s </a> <br />%3$s</div>',$oRequestData->getMethod(),$oRequestData->getUrl(),  implode(' | ', $oRequestData->getHeaders()));
				}
			}

			$this->aCurrentHeader = array();
			$sMethod =  $oRequestData->getMethod() ?  $oRequestData->getMethod() : self::METHOD_GET;
			// @todo We are forced to recreate handle because CURLOPT_HTTPGET do not reset CURLOPT_CUSTOMREQUEST; there is need to do more testing
			$rCurl = $this->createCurlHandle();
			if ($sMethod !== self::METHOD_GET) {
				if ($sMethod === self::METHOD_POST){
					curl_setopt($rCurl, CURLOPT_POST, true);
				} else {
					curl_setopt($rCurl, CURLOPT_CUSTOMREQUEST, $sMethod);
				}
				if ($oRequestData->getBody()){
					curl_setopt($rCurl, CURLOPT_POSTFIELDS, $oRequestData->getBody());
				} else if ($oRequestData->getParams()){
					curl_setopt($rCurl, CURLOPT_POSTFIELDS, http_build_query($oRequestData->getParams()));
				} // if
			} else {
				curl_setopt($rCurl, CURLOPT_HTTPGET, true);
				if ($oRequestData->getParams()){
					// @todo Do it more elegant way
					$sUrl = $sUrl . (stristr($sUrl, '?') ? '&' : '?') . http_build_query($oRequestData->getParams());
				} // if
			} // if
			if ($oRequestData->getHeaders()) {
				curl_setopt($rCurl, CURLOPT_HTTPHEADER, $oRequestData->getHeaders());
			}

			curl_setopt($rCurl, CURLOPT_URL, $oRequestData->getUrl());
			$mCurlResponse = curl_exec($rCurl);

			$this->debug(var_export($mCurlResponse, true));

			$oResponse = new BeezupOMResponseData();
			$oResponse
				->setResponse($mCurlResponse)
				->setHeaders($this->aCurrentHeader)
				->setCurlInfo(curl_getinfo($rCurl))
				->setCurlError(array('code' => curl_errno($rCurl), 'message' => curl_error($rCurl)));

			if ($this->isDebugModeActivated()){
				$aCurlInfo = $oResponse->getCurlInfo();
				if (PHP_SAPI === 'cli') {

					print PHP_EOL . ($aCurlInfo && isset($aCurlInfo['http_code']) ? $aCurlInfo['http_code'] : 'XXX ' . var_export($aCurlInfo, true));
				} else {
					print PHP_EOL . sprintf('<div style="padding:10px; margin:10px; background-color: #ccf">%1$s %2$d bytes</div>', ($aCurlInfo && isset($aCurlInfo['http_code']) ? $aCurlInfo['http_code'] : 'XXX'), strlen($mCurlResponse));
				}
			}


			return $oResponse;
		} // doCurlRequest

		/**
		 * Parse curl headers
		 * @param resource $rCurl
		 * @param string $sHeaderLine
		 * @return number
		 */
		public function curlHeaderCallback($rCurl, $sHeaderLine){
			if (stristr($sHeaderLine, ':') !== false){
				list($sName, $sContent) = explode(':', $sHeaderLine, 2);
				$sName = trim($sName);
				$sContent = trim($sContent);
				if ($sName && $sContent){
					if (isset($this->aCurrentHeader[$sName])){
						if (!is_array($this->aCurrentHeader[$sName])){
							$this->aCurrentHeader[$sName] = array($this->aCurrentHeader[$sName]);
						}
						$this->aCurrentHeader[$sName][] = $sContent;
					} else {
						$this->aCurrentHeader[$sName] = $sContent;
					}
				}
			}
			return strlen($sHeaderLine);
		}

	# DEBUG

		/**
		 * Writing log messages to file beezup-om-log-<Ymd>.log in the same dir
		 * @param string $sMessage
		 * @return int|bool|null Writing result as in file_put_contents or null if debug mode not activated
		 */
		protected function debug($sMessage) {
			if ($this->isDebugModeActivated() ){
				return file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'beezup-om-log-' . date('Ymd') . '.log', PHP_EOL. $sMessage, FILE_APPEND);
			} // if
			return null;
		} // debug

	}