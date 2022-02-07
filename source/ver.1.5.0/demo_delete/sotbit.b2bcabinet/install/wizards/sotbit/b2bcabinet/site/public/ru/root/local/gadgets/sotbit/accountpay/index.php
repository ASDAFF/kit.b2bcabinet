<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$APPLICATION->IncludeComponent(
	"bitrix:sale.account.pay",
	"sotbit_cabinet_widget",
	Array(
		"REFRESHED_COMPONENT_MODE" => "Y",
		"ELIMINATED_PAY_SYSTEMS" => array("0"),
		"PATH_TO_BASKET" => $arParams['G_ACCOUNTPAY_PATH_TO_BASKET'],
		"PATH_TO_PAYMENT" => $arParams['G_ACCOUNTPAY_PATH_TO_PAYMENT'],
		"PERSON_TYPE" => $arParams['G_ACCOUNTPAY_PERSON_TYPE_ID'],
		"REDIRECT_TO_CURRENT_PAGE" => "N",
		"SELL_AMOUNT" => array(""),
		"SELL_CURRENCY" => '',
		"SELL_SHOW_FIXED_VALUES" => 'Y',
		"SELL_SHOW_RESULT_SUM" =>  '',
		"SELL_TOTAL" => array(""),
		"SELL_USER_INPUT" => 'Y',
		"SELL_VALUES_FROM_VAR" => "N",
		"SELL_VAR_PRICE_VALUE" => "",
		"SET_TITLE" => "N",
	),
	''
);
?>