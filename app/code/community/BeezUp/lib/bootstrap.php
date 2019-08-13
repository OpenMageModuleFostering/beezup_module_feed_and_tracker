<?php

	/**
	 * This is the bootstrap file. Due the lack of proper autoloader, it makes sure that all files are included in good order
	 */

	require_once 'BeezupOMDataHandler.php';
	require_once 'BeezupOMRequestData.php';
	require_once 'BeezupOMResponseData.php';

	require_once 'Common/BeezupOMSummary.php';
	require_once 'Common/BeezupOMInfoSummary.php';
	require_once 'Common/BeezupOMErrorSummary.php';
	require_once 'Common/BeezupOMSuccessSummary.php';
	require_once 'Common/BeezupOMWarningSummary.php';
	require_once 'Common/BeezupOMInfoSummaries.php';
	require_once 'Common/BeezupOMResponse.php';
	require_once 'Common/BeezupOMRequest.php';
	require_once 'Common/BeezupOMResult.php';
	require_once 'Common/BeezupOMCredential.php';
	require_once 'Common/BeezupOMLink.php';
	require_once 'Common/BeezupOMOrderIdentifier.php';
	require_once 'Common/BeezupOMExpectedOrderChangeMetaInfo.php';
	require_once 'Common/BeezupOMProcessingStatus.php';

	require_once 'OrderList/BeezupOMOrderListRequest.php';
	require_once 'OrderList/BeezupOMOrderListResponse.php';
	require_once 'OrderList/BeezupOMOrderListResult.php';
	require_once 'OrderList/BeezupOMPaginationResult.php';
	require_once 'OrderList/BeezupOMOrderHeader.php';

	require_once 'Order/BeezupOMOrderRequest.php';
	require_once 'Order/BeezupOMOrderResponse.php';
	require_once 'Order/BeezupOMOrderResult.php';
	require_once 'Order/BeezupOMOrderItem.php';

	require_once 'SetOrderId/BeezupOMSetOrderIdRequest.php';
	require_once 'SetOrderId/BeezupOMSetOrderIdResponse.php';
	require_once 'SetOrderId/BeezupOMSetOrderIdResult.php';
	require_once 'SetOrderId/BeezupOMSetOrderIdValues.php';

	require_once 'OrderHistory/BeezupOMOrderHistoryRequest.php';
	require_once 'OrderHistory/BeezupOMOrderHistoryResponse.php';
	require_once 'OrderHistory/BeezupOMOrderHistoryResult.php';
	require_once 'OrderHistory/BeezupOMOrderChangeReporting.php';
	require_once 'OrderHistory/BeezupOMOrderHarvestReporting.php';

	require_once 'LOV/BeezupOMLOVRequest.php';
	require_once 'LOV/BeezupOMLOVResponse.php';
	require_once 'LOV/BeezupOMLOVResult.php';
	require_once 'LOV/BeezupOMLOVValue.php';

	require_once 'Stores/BeezupOMStoresRequest.php';
	require_once 'Stores/BeezupOMStoresResponse.php';
	require_once 'Stores/BeezupOMStoresResult.php';
	require_once 'Stores/BeezupOMStore.php';

	require_once 'Marketplaces/BeezupOMMarketplacesRequest.php';
	require_once 'Marketplaces/BeezupOMMarketplacesResponse.php';
	require_once 'Marketplaces/BeezupOMMarketplacesResult.php';
	require_once 'Marketplaces/BeezupOMMarketplace.php';


	require_once 'OrderChange/BeezupOMOrderChangeRequest.php';
	require_once 'OrderChange/BeezupOMOrderChangeResult.php';
	require_once 'OrderChange/BeezupOMOrderChangeResponse.php';
	require_once 'OrderChange/BeezupOMOrderChangeMetaInfo.php';

	require_once 'Harvest/BeezupOMHarvestAbstractReporting.php';
	require_once 'Harvest/BeezupOMHarvestClientReporting.php';
	require_once 'Harvest/BeezupOMHarvestOrderReporting.php';

	require_once 'BeezupOMRepositoryInterface.php';
	// require_once 'BeezupOMOrderServiceInterface.php';
	require_once 'BeezupOMServiceClientProxy.php';
	require_once 'BeezupOMOrderService.php';
