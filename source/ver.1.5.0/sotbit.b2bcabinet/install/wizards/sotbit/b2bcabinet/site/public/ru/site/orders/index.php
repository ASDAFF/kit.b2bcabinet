<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sotbit\B2bCabinet\Helper\Config;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if(!Loader::includeModule('sotbit.b2bcabinet')){
    LocalRedirect(is_dir($_SERVER["DOCUMENT_ROOT"].'/b2bcabinet/') ? SITE_DIR.'b2bcabinet/' : SITE_DIR);
}

if(!$USER->IsAuthorized())
{
    $APPLICATION->AuthForm('', false, false, 'N', false);
}
else
{
    $APPLICATION->SetTitle(Loc::getMessage('B2B_CABINET_ORDERS_ORDER_STATUS'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage('B2B_CABINET_ORDERS_ORDERS') . '</span> - ');
    $APPLICATION->AddChainItem(Loc::getMessage('B2B_CABINET_ORDERS_ORDER_STATUS'));
    $_REQUEST['show_all']='Y';

    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.order",
        "b2bcabinet",
        array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ALLOW_INNER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CUSTOM_SELECT_PROPS" => array(
                0 => "PROPERTY_CML2_ARTICLE",
                1 => "PROPERTY_RAZMER",
                2 => "",
            ),
            "DETAIL_HIDE_USER_INFO" => array(
                0 => "0",
            ),
            "DISALLOW_CANCEL" => "N",
            "HISTORIC_STATUSES" => array(
                0 => "F",
            ),
            "NAV_TEMPLATE" => "",
            "ONLY_INNER_FULL" => "N",
            "ORDERS_PER_PAGE" => "20",
            "ORDER_DEFAULT_SORT" => "STATUS",
            "PATH_TO_BASKET" => SITE_DIR . "orders/make/",
            "PATH_TO_CATALOG" => SITE_DIR . "catalog/",
            "PATH_TO_PAYMENT" => SITE_DIR . "orders/payment/",
            "PROP_1" => array(),
            "PROP_2" => array(),
            "PROP_3" => array(),
            "REFRESH_PRICES" => "N",
            "RESTRICT_CHANGE_PAYSYSTEM" => array(
                0 => "0",
            ),
            "SAVE_IN_SESSION" => "Y",
            "SEF_MODE" => "Y",
            "SET_TITLE" => "Y",
            "STATUS_COLOR_F" => "gray",
            "STATUS_COLOR_N" => "green",
            "STATUS_COLOR_P" => "yellow",
            "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
            "COMPONENT_TEMPLATE" => "b2bcabinet",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "",
            "OFFER_TREE_PROPS" => array(),
            "OFFER_COLOR_PROP" => "",
            "MANUFACTURER_ELEMENT_PROPS" => "",
            "MANUFACTURER_LIST_PROPS" => "",
            "PICTURE_FROM_OFFER" => "N",
            "MORE_PHOTO_PRODUCT_PROPS" => "",
            "IMG_WIDTH" => "80",
            "IMG_HEIGHT" => "120",
            "SEF_FOLDER" => SITE_DIR ."order/",
            "SEF_URL_TEMPLATES" => array(
                "list" => "index.php",
                "detail" => "detail/#ID#/",
                "cancel" => "cancel/#ID#/",
            )
        ),
        false
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>