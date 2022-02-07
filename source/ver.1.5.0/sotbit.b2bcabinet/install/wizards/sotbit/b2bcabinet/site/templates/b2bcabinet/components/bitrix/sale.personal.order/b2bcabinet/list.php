<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/main/utils.js');

$arChildParams = array(
    "PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
    "PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
    "PATH_TO_COPY" => $arResult["PATH_TO_LIST"] . '?ID=#ID#',
    "PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
    "SAVE_IN_SESSION" => $arParams["SAVE_IN_SESSION"],
    "ORDERS_PER_PAGE" => $arParams["ORDERS_PER_PAGE"],
    "SET_TITLE" => $arParams["SET_TITLE"],
    "ID" => $arResult["VARIABLES"]["ID"],
    "NAV_TEMPLATE" => $arParams["NAV_TEMPLATE"],
    "ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
    // "HISTORIC_STATUSES" => $arParams["HISTORIC_STATUSES"],
    "HISTORIC_STATUSES" => array(
        'O'
    ),

    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "DEFAULT_FILTER_FIELDS" => array(
        'date_to',
        'date_from',
        'status',
        'id',
        'payed',
        'find'
    ),
    "ALLOW_COLUMNS_SORT" => array(
        'ID',
        'DATE_INSERT',
        'STATUS',
        'PRICE',
        'PAYED',
        'PAYMENT_METHOD',
        'SHIPMENT_METHOD',
        'PAY_SYSTEM_ID',
        'PAY_SYSTEM_ID'
    )
);

foreach( $arParams as $key => $val )
    if( strpos( $key, "STATUS_COLOR_" ) !== false && strpos( $key, "~" ) !== 0 )
        $arChildParams[$key] = $val;

$_REQUEST['by'] = isset( $_GET['by'] ) ? $_GET['by'] : 'ID';
$_REQUEST['order'] = isset( $_GET['order'] ) ? strtoupper( $_GET['order'] ) : 'DESC';

$filter = [];
$filterOption = new Bitrix\Main\UI\Filter\Options( 'ORDER_LIST' );
$filterData = $filterOption->getFilter( [] );

foreach( $filterData as $key => $value )
{
    if( in_array( strtolower( $key ), $arChildParams['DEFAULT_FILTER_FIELDS'] ) )
        $_REQUEST['filter_' . strtolower( $key )] = $value;
}

$APPLICATION->IncludeComponent( "bitrix:sale.personal.order.list", "b2bcabinet", $arChildParams, $component );
?>