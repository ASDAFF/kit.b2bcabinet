<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$statuses = array(
	'ALL' => GetMessage("GD_SOTBIT_CABINET_ORDER_STATUS_ALL")
);

$rsStatuses = \Bitrix\Sale\StatusLangTable::getList(array('filter' => array('LID' => 'ru')));
while($status = $rsStatuses->fetch())
{
	$statuses[$status['STATUS_ID']] = $status['NAME'];
}

$arParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_ORDERS" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_PATH_TO_ORDER"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/order/"
		),
		"PATH_TO_ORDER_DETAIL" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_PATH_TO_ORDER_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/order/detail/#ID#/"
		),
	),
	"USER_PARAMETERS" => Array(
		"STATUS" => Array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_ORDER_STATUS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "ALL",
			"VALUES" => $statuses,
		),
		"LIMIT" => Array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_ORDER_LIMIT"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "2",
			"VALUES" => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5'
			),
		),
	)
);
?>
