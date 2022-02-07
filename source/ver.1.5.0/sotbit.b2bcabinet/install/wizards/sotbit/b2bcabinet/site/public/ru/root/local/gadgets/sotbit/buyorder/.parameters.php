<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$props = array();
$rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(array(
	'filter' => array(
		'ACTIVE' => 'Y',
	),
	'select' => array(
		'ID',
		'CODE',
		'NAME'
	)
));
while ($property = $rs->fetch())
{
	$props[$property['ID']] = "[" . $property['CODE'] . "] " . $property['NAME'];
}

$arParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_ORDER_DETAIL" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_BUYORDER_PATH_TO_ORDER_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/order/detail/#ID#/"
		),
		"PATH_TO_PAY" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_BUYORDER_PATH_TO_PAY"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/order/payment/"
		),
		"ORG_PROP" => Array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_BUYORDER_ORG_PROP"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $props,
		),
	)
);
?>
