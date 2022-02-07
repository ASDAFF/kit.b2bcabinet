<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$discounts = array();
if(\Bitrix\Main\Loader::includeModule('catalog'))
{
	$rs = \Bitrix\Catalog\DiscountTable::getList(
		array(
			'filter' => array(
				'ACTIVE' => 'Y'
			),
			'select' => array(
				'ID',
				'NAME'
			)
		)
	);
	while ($discount = $rs->fetch())
	{
		$discounts[$discount['ID']] = $discount['NAME'];
	}
}

$arParameters = Array(
	"PARAMETERS" => Array(
		"ID_DISCOUNT" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_DISCOUNT_ID_DISCOUNT"),
			"TYPE" => "LIST",
			"VALUES" => $discounts
		),
		"PATH_TO_PAGE" => array(
			"NAME" => GetMessage("GD_SOTBIT_CABINET_DISCOUNT_PATH_TO_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		)
	)
);
?>
