<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$APPLICATION->IncludeComponent(
    'bitrix:main.calendar',
    'b2b_data_picker',
    array(
        'INPUT_NAME' => 'PERSONAL_BIRTHDAY',
        'VALUE' => $arParams['VALUE'],
        'PLACEHOLDER' => $arParams['PLACEHOLDER'],
        'CLASS' => $arParams['CLASS'],
    ),
    null,
    array('HIDE_ICONS' => 'Y')
);

//=CalendarDate("PERSONAL_BIRTHDAY", $arResult["arUser"]["PERSONAL_BIRTHDAY"], "form1", "15")
?>