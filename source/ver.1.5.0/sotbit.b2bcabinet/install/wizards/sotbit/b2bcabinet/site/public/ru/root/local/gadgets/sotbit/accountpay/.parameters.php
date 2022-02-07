<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$personTypes = array();
$rs = \Bitrix\Sale\Internals\PersonTypeTable::getList(array('filter' => array('ACTIVE' => 'Y')));
while($personType = $rs->fetch())
{
	$personTypes[$personType['ID']] = $personType['NAME'];
}
$arParameters = Array(
		"PARAMETERS"=> Array(
				"PATH_TO_BASKET" => array(
						"NAME" => GetMessage("GD_SOTBIT_CABINET_ACCOUNTPAY_PATH_TO_BASKET"),
						"TYPE" => "STRING",
						"DEFAULT" => "/personal/cart/"
				),
				"PATH_TO_PAYMENT" => array(
						"NAME" => GetMessage("GD_SOTBIT_CABINET_ACCOUNTPAY_PATH_TO_PAYMENT"),
						"TYPE" => "STRING",
						"DEFAULT" => "/personal/order/payment"
				),
				"PERSON_TYPE_ID" => Array(
					"NAME" => GetMessage("GD_SOTBIT_CABINET_ACCOUNTPAY_PERSON_TYPE_ID"),
					"TYPE" => "LIST",
					"MULTIPLE" => "N",
					"DEFAULT" => "ALL",
					"VALUES" => $personTypes,
				),
		));
?>
