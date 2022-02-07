<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
//use Kit\Origami\Helper\Config;
use /*Kit\Origami\Config\Option,*/ \Bitrix\Main\Localization\Loc;

global $analogProducts;

$APPLICATION->IncludeComponent('bitrix:breadcrumb', 'origami_default',
    [
        "START_FROM" => "0",
        "PATH"       => "",
        "SITE_ID"    => SITE_ID,
    ], false, [
        'HIDE_ICONS' => 'N',
    ]);

$elementId = $APPLICATION->IncludeComponent(
    'bitrix:catalog.element',
    $template,
    $componentElementParams,
    $component
);

//\KitOrigami::setSeoOffer();

$GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $elementId;

if ($elementId > 0)
{
    //////analog/////

    ////////recommend////////
    if(true) {
        ?>
		<div class='puzzle_block'>
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "",
                [
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    'SECTION_NAME'              => Loc::getMessage('CATALOG_RECOMMEND'),
                    'FILTER_IDS'                => [$elementId],
                    'USE_VOTE_RATING' => $arParams["USE_VOTE_RATING"],
                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "FILTER_NAME" => '',
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                    "PAGE_ELEMENT_COUNT" => 10,
                    "PRICE_CODE" => $arParams["~PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                    'SHOW_ALL_WO_SECTION'        => 'Y',
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",

                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"])
                        ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"])
                        ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                    "SECTION_URL" => $arResult["FOLDER"]
                        . $arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"]
                        . $arResult["URL_TEMPLATES"]["element"],
                    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                    'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                    'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                    'LINE_ELEMENT_COUNT' => "5",
                    'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':true}]",
                    'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                    'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP'])
                        ? $arParams['LIST_ENLARGE_PROP'] : '',
                    'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                    'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL'])
                        ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                    'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS'])
                        ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                    "DISPLAY_TOP_PAGER" => 'N',
                    "DISPLAY_BOTTOM_PAGER" => 'N',
                    "HIDE_SECTION_DESCRIPTION" => "Y",

                    "RCM_TYPE" => isset($arParams['BIG_DATA_RCM_TYPE'])
                        ? $arParams['BIG_DATA_RCM_TYPE'] : '',
                    "RCM_PROD_ID" => $arResult['ID'],
                    "SHOW_FROM_SECTION" => 'N',

                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                    'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY'])
                        ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                    'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR'])
                        ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                    'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY'])
                        ? $arParams['~MESS_RELATIVE_QUANTITY_MANY']
                        : ''),
                    'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW'])
                        ? $arParams['~MESS_RELATIVE_QUANTITY_FEW']
                        : ''),
                    'MESS_RELATIVE_QUANTITY_NO' => (isset($arParams['~MESS_RELATIVE_QUANTITY_NO'])
                        ? $arParams['~MESS_RELATIVE_QUANTITY_NO']
                        : ''),
                    'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY'])
                        ? $arParams['~MESS_BTN_BUY'] : ''),
                    'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
                        ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                    'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE'])
                        ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                    'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL'])
                        ? $arParams['~MESS_BTN_DETAIL'] : ''),
                    'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE'])
                        ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                    'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE'])
                        ? $arParams['~MESS_BTN_COMPARE'] : ''),

                    'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE'])
                        ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                    'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME'])
                        ? $arParams['DATA_LAYER_NAME'] : ''),
                    'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY'])
                        ? $arParams['BRAND_PROPERTY'] : ''),
                    'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME'])
                        ? $arParams['TEMPLATE_THEME'] : ''),
                    'ADD_TO_BASKET_ACTION' => $basketAction,
                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP'])
                        ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                    'COMPARE_PATH' => $arResult['FOLDER']
                        . $arResult['URL_TEMPLATES']['compare'],
                    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                    'USE_COMPARE_LIST' => 'Y',
                    'BACKGROUND_IMAGE' => '',
                    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT'])
                        ? $arParams['DISABLE_INIT_JS_IN_COMPONENT']
                        : ''),
                    'VARIANT_LIST_VIEW' => 'template_1',
                    'ACTION_PRODUCTS' => array("ADMIN"),

                ],
                $component
            );
            ?>
		</div>
        <?
    }

    ////buy with/////
//    if(true) {
//        $productIds = [];
//        $orders = [];
//
//
//
//
//
//        $ids = [$elementId];
//        $offers = CCatalogSKU::getOffersList($elementId);
//        if($offers){
//            foreach ($offers[$elementId] as $sku){
//                if($sku['ID']){
//                    $ids[] = $sku['ID'];
//                }
//            }
//        }
//
//        $rs = \Bitrix\Sale\Internals\BasketTable::getList(
//            [
//                'select' => ['ORDER_ID'],
//                'filter' => [
//                    '>ORDER_ID' => 0,
//                    'PRODUCT_ID' => $ids
//                ],
//                'cache'  => [
//                    'ttl' => 36000000,
//                ],
//            ]
//        );
//        while($order = $rs->fetch()){
//            $orders[] = $order['ORDER_ID'];
//        }
//
//        if($orders){
//            $rs = \Bitrix\Sale\Internals\BasketTable::getList(
//                [
//                    'select' => ['PRODUCT_ID'],
//                    'filter' => [
//                        'ORDER_ID' => $orders,
//                        '!PRODUCT_ID' => $elementId
//                    ],
//                ]
//            );
//            while($el = $rs->fetch()){
//                if(!in_array($el['PRODUCT_ID'],$ids)){
//                    $productIds[] = $el['PRODUCT_ID'];
//                }
//            }
//        }
//
//
//        global $productFilter;
//        $productFilter = ['=ID' => $productIds];
//        $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
//        if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
//            $productFilter['PROPERTY_REGIONS'] = [
//                false,
//                $_SESSION['KIT_REGIONS']['ID']
//            ];
//        }
//
//        //with buy
//        if($productIds) {
//            $APPLICATION->IncludeComponent(
//                'bitrix:catalog.section',
//                'origami_section_small',
//                [
//                    'IBLOCK_TYPE'                => $arParams['IBLOCK_TYPE'],
//                    'IBLOCK_ID'                  => $arParams['IBLOCK_ID'],
//                    'SECTION_NAME'              => Loc::getMessage('CATALOG_WITH_BUY'),
//                    'ELEMENT_SORT_FIELD'         => 'rand',
//                    'ELEMENT_SORT_ORDER'         => 'rand',
//                    'ELEMENT_SORT_FIELD2'        => 'sort',
//                    'ELEMENT_SORT_ORDER2'        => 'asc',
//                    'FILTER_NAME'                => 'productFilter',
//                    'SHOW_ALL_WO_SECTION'        => 'Y',
//                    'FILTER_IDS'                 => [$elementId],
//                    'PROPERTY_CODE'             => $arParams['LIST_PROPERTY_CODE'],
//                    'PROPERTY_CODE_MOBILE'      => $arParams['LIST_PROPERTY_CODE_MOBILE'],
//                    'INCLUDE_SUBSECTIONS'       => $arParams['INCLUDE_SUBSECTIONS'],
//                    'BASKET_URL'                => $arParams['BASKET_URL'],
//                    'ACTION_VARIABLE'           => $arParams['ACTION_VARIABLE'],
//                    'PRODUCT_ID_VARIABLE'       => $arParams['PRODUCT_ID_VARIABLE'],
//                    'SECTION_ID_VARIABLE'       => $arParams['SECTION_ID_VARIABLE'],
//                    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
//                    'PRODUCT_PROPS_VARIABLE'    => $arParams['PRODUCT_PROPS_VARIABLE'],
//                    'CACHE_TYPE'                => $arParams['CACHE_TYPE'],
//                    'CACHE_TIME'                => $arParams['CACHE_TIME'],
//                    'CACHE_FILTER'              => $arParams['CACHE_FILTER'],
//                    'CACHE_GROUPS'              => $arParams['CACHE_GROUPS'],
//                    'DISPLAY_COMPARE'           => $arParams['USE_COMPARE'],
//                    'PRICE_CODE'                => $arParams['~PRICE_CODE'],
//                    'USE_PRICE_COUNT'           => $arParams['USE_PRICE_COUNT'],
//                    'SHOW_PRICE_COUNT'          => $arParams['SHOW_PRICE_COUNT'],
//                    'PAGE_ELEMENT_COUNT'        => 20,
//                    "SET_TITLE"            => "N",
//                    "SET_BROWSER_TITLE"    => "N",
//                    "SET_META_KEYWORDS"    => "N",
//                    "SET_META_DESCRIPTION" => "N",
//                    "SET_LAST_MODIFIED"    => "N",
//                    "ADD_SECTIONS_CHAIN"   => "N",
//                    'SHOW_ALL_WO_SECTION' => 'Y',
//                    //'PRICE_VAT_INCLUDE'          => $arParams['PRICE_VAT_INCLUDE'],
//                    //'USE_PRODUCT_QUANTITY'       => $arParams['USE_PRODUCT_QUANTITY'],
//                    /*'ADD_PROPERTIES_TO_BASKET'   => (isset($arParams['ADD_PROPERTIES_TO_BASKET'])
//                        ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
//                    'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES'])
//                        ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
//                    'PRODUCT_PROPERTIES'         => $arParams['PRODUCT_PROPERTIES'],*/
//
//                    /*'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
//                    'OFFERS_FIELD_CODE'      => $arParams['LIST_OFFERS_FIELD_CODE'],
//                    'OFFERS_PROPERTY_CODE'   => $arParams['LIST_OFFERS_PROPERTY_CODE'],
//                    'OFFERS_SORT_FIELD'      => $arParams['OFFERS_SORT_FIELD'],
//                    'OFFERS_SORT_ORDER'      => $arParams['OFFERS_SORT_ORDER'],
//                    'OFFERS_SORT_FIELD2'     => $arParams['OFFERS_SORT_FIELD2'],
//                    'OFFERS_SORT_ORDER2'     => $arParams['OFFERS_SORT_ORDER2'],
//                    'OFFERS_LIMIT'           => $arParams['LIST_OFFERS_LIMIT'],*/
//
//                    'SECTION_URL'   => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
//                    'DETAIL_URL'    => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
//                    'USE_MAIN_ELEMENT_SECTION'  => $arParams['USE_MAIN_ELEMENT_SECTION'],
//                    'CONVERT_CURRENCY'          => $arParams['CONVERT_CURRENCY'],
//                    'CURRENCY_ID'               => $arParams['CURRENCY_ID'],
//                    'HIDE_NOT_AVAILABLE'        => $arParams['HIDE_NOT_AVAILABLE'],
//                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
//
//                    /*'LABEL_PROP'           => $arParams['LABEL_PROP'],
//                    'LABEL_PROP_MOBILE'    => $arParams['LABEL_PROP_MOBILE'],
//                    'LABEL_PROP_POSITION'  => $arParams['LABEL_PROP_POSITION'],
//                    'ADD_PICT_PROP'        => $arParams['ADD_PICT_PROP'],
//                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
//                    'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
//                    'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':false}]",
//                    'ENLARGE_PRODUCT'      => $arParams['LIST_ENLARGE_PRODUCT'],
//                    'ENLARGE_PROP'         => isset($arParams['LIST_ENLARGE_PROP'])
//                        ? $arParams['LIST_ENLARGE_PROP'] : '',
//                    'SHOW_SLIDER'          => $arParams['LIST_SHOW_SLIDER'],
//                    'SLIDER_INTERVAL'      => isset($arParams['LIST_SLIDER_INTERVAL'])
//                        ? $arParams['LIST_SLIDER_INTERVAL'] : '',
//                    'SLIDER_PROGRESS'      => isset($arParams['LIST_SLIDER_PROGRESS'])
//                        ? $arParams['LIST_SLIDER_PROGRESS'] : '',*/
//
//                    'DISPLAY_TOP_PAGER'        => 'N',
//                    'DISPLAY_BOTTOM_PAGER'     => 'N',
//                    'HIDE_SECTION_DESCRIPTION' => 'Y',
//
//                    'RCM_TYPE'          => isset($arParams['BIG_DATA_RCM_TYPE'])
//                        ? $arParams['BIG_DATA_RCM_TYPE'] : '',
//                    'RCM_PROD_ID'       => $elementId,
//                    'SHOW_FROM_SECTION' => 'N',
//
//                    //'OFFER_ADD_PICT_PROP'         => $arParams['OFFER_ADD_PICT_PROP'],
//                    //'OFFER_TREE_PROPS'            => $arParams['OFFER_TREE_PROPS'],
//                    //'PRODUCT_SUBSCRIPTION'        => $arParams['PRODUCT_SUBSCRIPTION'],
//                    'SHOW_DISCOUNT_PERCENT'       => $arParams['SHOW_DISCOUNT_PERCENT'],
//                    'DISCOUNT_PERCENT_POSITION'   => $arParams['DISCOUNT_PERCENT_POSITION'],
//                    'SHOW_OLD_PRICE'              => $arParams['SHOW_OLD_PRICE'],
//                    //'SHOW_MAX_QUANTITY'           => $arParams['SHOW_MAX_QUANTITY'],
//                    /*'MESS_SHOW_MAX_QUANTITY'      => (isset($arParams['~MESS_SHOW_MAX_QUANTITY'])
//                        ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
//                    'RELATIVE_QUANTITY_FACTOR'    => (isset($arParams['RELATIVE_QUANTITY_FACTOR'])
//                        ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
//                    'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY'])
//                        ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
//                    'MESS_RELATIVE_QUANTITY_FEW'  => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW'])
//                        ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
//                    'MESS_BTN_BUY'                => (isset($arParams['~MESS_BTN_BUY'])
//                        ? $arParams['~MESS_BTN_BUY'] : ''),
//                    'MESS_BTN_ADD_TO_BASKET'      => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
//                        ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
//                    'MESS_BTN_SUBSCRIBE'          => (isset($arParams['~MESS_BTN_SUBSCRIBE'])
//                        ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
//                    'MESS_BTN_DETAIL'             => (isset($arParams['~MESS_BTN_DETAIL'])
//                        ? $arParams['~MESS_BTN_DETAIL'] : ''),
//                    'MESS_NOT_AVAILABLE'          => (isset($arParams['~MESS_NOT_AVAILABLE'])
//                        ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
//                    'MESS_BTN_COMPARE'            => (isset($arParams['~MESS_BTN_COMPARE'])
//                        ? $arParams['~MESS_BTN_COMPARE'] : ''),
//
//                    'USE_ENHANCED_ECOMMERCE'       => (isset($arParams['USE_ENHANCED_ECOMMERCE'])
//                        ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
//                    'DATA_LAYER_NAME'              => (isset($arParams['DATA_LAYER_NAME'])
//                        ? $arParams['DATA_LAYER_NAME'] : ''),
//                    'BRAND_PROPERTY'               => (isset($arParams['BRAND_PROPERTY'])
//                        ? $arParams['BRAND_PROPERTY'] : ''),
//                    'USE_VOTE_RATING'              => $arParams['DETAIL_USE_VOTE_RATING'],
//                    'TEMPLATE_THEME'               => (isset($arParams['TEMPLATE_THEME'])
//                        ? $arParams['TEMPLATE_THEME'] : ''),
//                    'ADD_TO_BASKET_ACTION'         => $basketAction,
//                    'SHOW_CLOSE_POPUP'             => isset($arParams['COMMON_SHOW_CLOSE_POPUP'])
//                        ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
//                    'COMPARE_PATH'                 => $arResult['FOLDER']
//                        .$arResult['URL_TEMPLATES']['compare'],
//                    'COMPARE_NAME'                 => $arParams['COMPARE_NAME'],
//                    'BACKGROUND_IMAGE'             => '',
//                    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT'])
//                        ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),*/
//                ],
//                $component
//            );
//        }
//    }

    //////bestsellers/////
//    if(Config::get('SHOW_BESTSELLER_'.$template) == 'Y') {
//        $productIds = [];
//        $date = new \Bitrix\Main\Type\DateTime();
//        $date->add('-14 days');
//
//        $rs = \Bitrix\Sale\Internals\BasketTable::getList(
//            [
//                'select' => ['PRODUCT_ID','COUNT','PRODUCT.*'],
//                'filter' => [
//                    '>ORDER_ID' => 0,
//                    '>=DATE_UPDATE' => $date
//                ],
//                'order' => ['COUNT' => 'DESC'],
//                'group' => ['PRODUCT_ID'],
//                'runtime' => [
//                    'COUNT' => [
//                        'data_type' => 'integer',
//                        'expression' => ['COUNT(*)']
//                    ]
//                ],
//                'limit' => 10,
//                'cache'  => [
//                    'ttl' => 36000000,
//                ],
//            ]
//        );
//        while($product = $rs->fetch()){
//            $productIds[$product['PRODUCT_ID']] = $product['PRODUCT_ID'];
//        }
//        $cache = \Bitrix\Main\Data\Cache::createInstance();
//        if ($cache->initCache(36000000, implode('|',$productIds))) {
//            $productIds = $cache->getVars();
//        }
//		elseif ($cache->startDataCache()) {
//            if($productIds){
//                foreach ($productIds as $productId){
//                    $sku = CCatalogSku::GetProductInfo($productId);
//                    if($sku['ID']){
//                        $productIds[$productId] = $sku['ID'];
//                    }
//                }
//            }
//            $cache->endDataCache($productIds);
//        }
//
//
//        global $productFilter;
//        $productFilter = ['=ID' => $productIds];
//        $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
//        if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
//            $productFilter['PROPERTY_REGIONS'] = [
//                false,
//                $_SESSION['KIT_REGIONS']['ID']
//            ];
//        }
//        //bestsellers
//        if($productIds) {
//            $APPLICATION->IncludeComponent(
//                'bitrix:catalog.section',
//                'origami_section_small',
//                [
//                    'IBLOCK_TYPE'                => $arParams['IBLOCK_TYPE'],
//                    'IBLOCK_ID'                  => $arParams['IBLOCK_ID'],
//                    'SECTION_NAME'              => Loc::getMessage('CATALOG_BESTSELLERS'),
//                    'ELEMENT_SORT_FIELD'         => 'rand',
//                    'ELEMENT_SORT_ORDER'         => 'rand',
//                    'ELEMENT_SORT_FIELD2'        => 'sort',
//                    'ELEMENT_SORT_ORDER2'        => 'asc',
//                    'FILTER_NAME'                => 'productFilter',
//                    'SHOW_ALL_WO_SECTION'        => 'Y',
//                    'FILTER_IDS'                 => [$elementId],
//                    'PROPERTY_CODE'             => $arParams['LIST_PROPERTY_CODE'],
//                    'PROPERTY_CODE_MOBILE'      => $arParams['LIST_PROPERTY_CODE_MOBILE'],
//                    'INCLUDE_SUBSECTIONS'       => $arParams['INCLUDE_SUBSECTIONS'],
//                    'BASKET_URL'                => $arParams['BASKET_URL'],
//                    'ACTION_VARIABLE'           => $arParams['ACTION_VARIABLE'],
//                    'PRODUCT_ID_VARIABLE'       => $arParams['PRODUCT_ID_VARIABLE'],
//                    'SECTION_ID_VARIABLE'       => $arParams['SECTION_ID_VARIABLE'],
//                    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
//                    'PRODUCT_PROPS_VARIABLE'    => $arParams['PRODUCT_PROPS_VARIABLE'],
//                    'CACHE_TYPE'                => $arParams['CACHE_TYPE'],
//                    'CACHE_TIME'                => $arParams['CACHE_TIME'],
//                    'CACHE_FILTER'              => $arParams['CACHE_FILTER'],
//                    'CACHE_GROUPS'              => $arParams['CACHE_GROUPS'],
//                    'DISPLAY_COMPARE'           => $arParams['USE_COMPARE'],
//                    'PRICE_CODE'                => $arParams['~PRICE_CODE'],
//                    'USE_PRICE_COUNT'           => $arParams['USE_PRICE_COUNT'],
//                    'SHOW_PRICE_COUNT'          => $arParams['SHOW_PRICE_COUNT'],
//                    'PAGE_ELEMENT_COUNT'        => 20,
//                    "SET_TITLE"            => "N",
//                    "SET_BROWSER_TITLE"    => "N",
//                    "SET_META_KEYWORDS"    => "N",
//                    "SET_META_DESCRIPTION" => "N",
//                    "SET_LAST_MODIFIED"    => "N",
//                    "ADD_SECTIONS_CHAIN"   => "N",
//                    'SHOW_ALL_WO_SECTION' => 'Y',
//                    //'PRICE_VAT_INCLUDE'          => $arParams['PRICE_VAT_INCLUDE'],
//                    //'USE_PRODUCT_QUANTITY'       => $arParams['USE_PRODUCT_QUANTITY'],
//                    /*'ADD_PROPERTIES_TO_BASKET'   => (isset($arParams['ADD_PROPERTIES_TO_BASKET'])
//                        ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
//                    'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES'])
//                        ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
//                    'PRODUCT_PROPERTIES'         => $arParams['PRODUCT_PROPERTIES'],*/
//
//                    /*'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
//                    'OFFERS_FIELD_CODE'      => $arParams['LIST_OFFERS_FIELD_CODE'],
//                    'OFFERS_PROPERTY_CODE'   => $arParams['LIST_OFFERS_PROPERTY_CODE'],
//                    'OFFERS_SORT_FIELD'      => $arParams['OFFERS_SORT_FIELD'],
//                    'OFFERS_SORT_ORDER'      => $arParams['OFFERS_SORT_ORDER'],
//                    'OFFERS_SORT_FIELD2'     => $arParams['OFFERS_SORT_FIELD2'],
//                    'OFFERS_SORT_ORDER2'     => $arParams['OFFERS_SORT_ORDER2'],
//                    'OFFERS_LIMIT'           => $arParams['LIST_OFFERS_LIMIT'],*/
//
//                    'SECTION_URL'   => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
//                    'DETAIL_URL'    => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
//                    'USE_MAIN_ELEMENT_SECTION'  => $arParams['USE_MAIN_ELEMENT_SECTION'],
//                    'CONVERT_CURRENCY'          => $arParams['CONVERT_CURRENCY'],
//                    'CURRENCY_ID'               => $arParams['CURRENCY_ID'],
//                    'HIDE_NOT_AVAILABLE'        => $arParams['HIDE_NOT_AVAILABLE'],
//                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
//
//                    'LABEL_PROP'           => $arParams['LABEL_PROP'],
//                    'LABEL_PROP_MOBILE'    => $arParams['LABEL_PROP_MOBILE'],
//                    'LABEL_PROP_POSITION'  => $arParams['LABEL_PROP_POSITION'],
//                    /*'ADD_PICT_PROP'        => $arParams['ADD_PICT_PROP'],
//                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
//                    'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
//                    'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':false}]",
//                    'ENLARGE_PRODUCT'      => $arParams['LIST_ENLARGE_PRODUCT'],
//                    'ENLARGE_PROP'         => isset($arParams['LIST_ENLARGE_PROP'])
//                        ? $arParams['LIST_ENLARGE_PROP'] : '',
//                    'SHOW_SLIDER'          => $arParams['LIST_SHOW_SLIDER'],
//                    'SLIDER_INTERVAL'      => isset($arParams['LIST_SLIDER_INTERVAL'])
//                        ? $arParams['LIST_SLIDER_INTERVAL'] : '',
//                    'SLIDER_PROGRESS'      => isset($arParams['LIST_SLIDER_PROGRESS'])
//                        ? $arParams['LIST_SLIDER_PROGRESS'] : '',*/
//
//                    'DISPLAY_TOP_PAGER'        => 'N',
//                    'DISPLAY_BOTTOM_PAGER'     => 'N',
//                    'HIDE_SECTION_DESCRIPTION' => 'Y',
//
//                    'RCM_TYPE'          => isset($arParams['BIG_DATA_RCM_TYPE'])
//                        ? $arParams['BIG_DATA_RCM_TYPE'] : '',
//                    'RCM_PROD_ID'       => $elementId,
//                    'SHOW_FROM_SECTION' => 'N',
//
//                    //'OFFER_ADD_PICT_PROP'         => $arParams['OFFER_ADD_PICT_PROP'],
//                    //'OFFER_TREE_PROPS'            => $arParams['OFFER_TREE_PROPS'],
//                    //'PRODUCT_SUBSCRIPTION'        => $arParams['PRODUCT_SUBSCRIPTION'],
//                    'SHOW_DISCOUNT_PERCENT'       => $arParams['SHOW_DISCOUNT_PERCENT'],
//                    'DISCOUNT_PERCENT_POSITION'   => $arParams['DISCOUNT_PERCENT_POSITION'],
//                    'SHOW_OLD_PRICE'              => $arParams['SHOW_OLD_PRICE'],
//                    //'SHOW_MAX_QUANTITY'           => $arParams['SHOW_MAX_QUANTITY'],
//                    /*'MESS_SHOW_MAX_QUANTITY'      => (isset($arParams['~MESS_SHOW_MAX_QUANTITY'])
//                        ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
//                    'RELATIVE_QUANTITY_FACTOR'    => (isset($arParams['RELATIVE_QUANTITY_FACTOR'])
//                        ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
//                    'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY'])
//                        ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
//                    'MESS_RELATIVE_QUANTITY_FEW'  => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW'])
//                        ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
//                    'MESS_BTN_BUY'                => (isset($arParams['~MESS_BTN_BUY'])
//                        ? $arParams['~MESS_BTN_BUY'] : ''),
//                    'MESS_BTN_ADD_TO_BASKET'      => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
//                        ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
//                    'MESS_BTN_SUBSCRIBE'          => (isset($arParams['~MESS_BTN_SUBSCRIBE'])
//                        ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
//                    'MESS_BTN_DETAIL'             => (isset($arParams['~MESS_BTN_DETAIL'])
//                        ? $arParams['~MESS_BTN_DETAIL'] : ''),
//                    'MESS_NOT_AVAILABLE'          => (isset($arParams['~MESS_NOT_AVAILABLE'])
//                        ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
//                    'MESS_BTN_COMPARE'            => (isset($arParams['~MESS_BTN_COMPARE'])
//                        ? $arParams['~MESS_BTN_COMPARE'] : ''),
//
//                    'USE_ENHANCED_ECOMMERCE'       => (isset($arParams['USE_ENHANCED_ECOMMERCE'])
//                        ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
//                    'DATA_LAYER_NAME'              => (isset($arParams['DATA_LAYER_NAME'])
//                        ? $arParams['DATA_LAYER_NAME'] : ''),
//                    'BRAND_PROPERTY'               => (isset($arParams['BRAND_PROPERTY'])
//                        ? $arParams['BRAND_PROPERTY'] : ''),
//                    'USE_VOTE_RATING'              => $arParams['DETAIL_USE_VOTE_RATING'],
//                    'TEMPLATE_THEME'               => (isset($arParams['TEMPLATE_THEME'])
//                        ? $arParams['TEMPLATE_THEME'] : ''),
//                    'ADD_TO_BASKET_ACTION'         => $basketAction,
//                    'SHOW_CLOSE_POPUP'             => isset($arParams['COMMON_SHOW_CLOSE_POPUP'])
//                        ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
//                    'COMPARE_PATH'                 => $arResult['FOLDER']
//                        .$arResult['URL_TEMPLATES']['compare'],
//                    'COMPARE_NAME'                 => $arParams['COMPARE_NAME'],
//                    'BACKGROUND_IMAGE'             => '',
//                    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT'])
//                        ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),*/
//                ],
//                $component
//            );
//        }
//    }

    //////popular/////
    /*if(Config::get('SHOW_SECTION_POPULAR_'.$template) == 'Y') {
    if (!isset($arParams['DETAIL_SHOW_POPULAR']) || $arParams['DETAIL_SHOW_POPULAR'] != 'N') {
        ?>
        <div class='puzzle_block'>
	        <p class="puzzle_block__title fonts__middle_title"><?= GetMessage('CATALOG_POPULAR_IN_SECTION') ?></p>
            <?
			global $productFilter;
            $productFilter = ['ACTIVE' => 'Y'];
            $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
            if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
                $productFilter['PROPERTY_REGIONS'] = [
                    false,
                    $_SESSION['KIT_REGIONS']['ID']
                ];
            }

            $APPLICATION->IncludeComponent(
                'bitrix:catalog.section',
                '',
                [
                    'IBLOCK_TYPE'               => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID'                 => $arParams['IBLOCK_ID'],
                    'SECTION_ID'                => $arResult['VARIABLES']['SECTION_ID'],
                    'SECTION_CODE'              => $arResult['VARIABLES']['SECTION_CODE'],
                    'ELEMENT_SORT_FIELD'        => 'shows',
                    'ELEMENT_SORT_ORDER'        => 'desc',
                    'ELEMENT_SORT_FIELD2'       => 'sort',
                    'ELEMENT_SORT_ORDER2'       => 'asc',
                    'FILTER_NAME' => 'productFilter',
                    'PROPERTY_CODE'             => $arParams['LIST_PROPERTY_CODE'],
                    'PROPERTY_CODE_MOBILE'      => $arParams['LIST_PROPERTY_CODE_MOBILE'],
                    'INCLUDE_SUBSECTIONS'       => $arParams['INCLUDE_SUBSECTIONS'],
                    'BASKET_URL'                => $arParams['BASKET_URL'],
                    'ACTION_VARIABLE'           => $arParams['ACTION_VARIABLE'],
                    'PRODUCT_ID_VARIABLE'       => $arParams['PRODUCT_ID_VARIABLE'],
                    'SECTION_ID_VARIABLE'       => $arParams['SECTION_ID_VARIABLE'],
                    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PRODUCT_PROPS_VARIABLE'    => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'CACHE_TYPE'                => $arParams['CACHE_TYPE'],
                    'CACHE_TIME'                => $arParams['CACHE_TIME'],
                    'CACHE_FILTER'              => $arParams['CACHE_FILTER'],
                    'CACHE_GROUPS'              => $arParams['CACHE_GROUPS'],
                    'DISPLAY_COMPARE'           => $arParams['USE_COMPARE'],
                    'PRICE_CODE'                => $arParams['~PRICE_CODE'],
                    'USE_PRICE_COUNT'           => $arParams['USE_PRICE_COUNT'],
                    'SHOW_PRICE_COUNT'          => $arParams['SHOW_PRICE_COUNT'],
                    'PAGE_ELEMENT_COUNT'        => 4,
                    'FILTER_IDS'                => [$elementId],

                    "SET_TITLE"            => "N",
                    "SET_BROWSER_TITLE"    => "N",
                    "SET_META_KEYWORDS"    => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED"    => "N",
                    "ADD_SECTIONS_CHAIN"   => "N",

                    'PRICE_VAT_INCLUDE'          => $arParams['PRICE_VAT_INCLUDE'],
                    'USE_PRODUCT_QUANTITY'       => $arParams['USE_PRODUCT_QUANTITY'],
                    'ADD_PROPERTIES_TO_BASKET'   => (isset($arParams['ADD_PROPERTIES_TO_BASKET'])
                        ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
                    'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES'])
                        ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
                    'PRODUCT_PROPERTIES'         => $arParams['PRODUCT_PROPERTIES'],

                    'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                    'OFFERS_FIELD_CODE'      => $arParams['LIST_OFFERS_FIELD_CODE'],
                    'OFFERS_PROPERTY_CODE'   => $arParams['LIST_OFFERS_PROPERTY_CODE'],
                    'OFFERS_SORT_FIELD'      => $arParams['OFFERS_SORT_FIELD'],
                    'OFFERS_SORT_ORDER'      => $arParams['OFFERS_SORT_ORDER'],
                    'OFFERS_SORT_FIELD2'     => $arParams['OFFERS_SORT_FIELD2'],
                    'OFFERS_SORT_ORDER2'     => $arParams['OFFERS_SORT_ORDER2'],
                    'OFFERS_LIMIT'           => $arParams['LIST_OFFERS_LIMIT'],

                    'SECTION_URL'               => $arResult['FOLDER']
                        .$arResult['URL_TEMPLATES']['section'],
                    'DETAIL_URL'                => $arResult['FOLDER']
                        .$arResult['URL_TEMPLATES']['element'],
                    'USE_MAIN_ELEMENT_SECTION'  => $arParams['USE_MAIN_ELEMENT_SECTION'],
                    'CONVERT_CURRENCY'          => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID'               => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE'        => $arParams['HIDE_NOT_AVAILABLE'],
                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],

                    'LABEL_PROP'           => $arParams['LABEL_PROP'],
                    'LABEL_PROP_MOBILE'    => $arParams['LABEL_PROP_MOBILE'],
                    'LABEL_PROP_POSITION'  => $arParams['LABEL_PROP_POSITION'],
                    'ADD_PICT_PROP'        => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                    'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                    'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':false}]",
                    'ENLARGE_PRODUCT'      => $arParams['LIST_ENLARGE_PRODUCT'],
                    'ENLARGE_PROP'         => isset($arParams['LIST_ENLARGE_PROP'])
                        ? $arParams['LIST_ENLARGE_PROP'] : '',
                    'SHOW_SLIDER'          => $arParams['LIST_SHOW_SLIDER'],
                    'SLIDER_INTERVAL'      => isset($arParams['LIST_SLIDER_INTERVAL'])
                        ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                    'SLIDER_PROGRESS'      => isset($arParams['LIST_SLIDER_PROGRESS'])
                        ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                    'DISPLAY_TOP_PAGER'        => 'N',
                    'DISPLAY_BOTTOM_PAGER'     => 'N',
                    'HIDE_SECTION_DESCRIPTION' => 'Y',

                    'RCM_TYPE'          => isset($arParams['BIG_DATA_RCM_TYPE'])
                        ? $arParams['BIG_DATA_RCM_TYPE'] : '',
                    'RCM_PROD_ID'       => $elementId,
                    'SHOW_FROM_SECTION' => 'Y',

                    'OFFER_ADD_PICT_PROP'         => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS'            => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION'        => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT'       => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'DISCOUNT_PERCENT_POSITION'   => $arParams['DISCOUNT_PERCENT_POSITION'],
                    'SHOW_OLD_PRICE'              => $arParams['SHOW_OLD_PRICE'],
                    'SHOW_MAX_QUANTITY'           => $arParams['SHOW_MAX_QUANTITY'],
                    'MESS_SHOW_MAX_QUANTITY'      => (isset($arParams['~MESS_SHOW_MAX_QUANTITY'])
                        ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                    'RELATIVE_QUANTITY_FACTOR'    => (isset($arParams['RELATIVE_QUANTITY_FACTOR'])
                        ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                    'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY'])
                        ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                    'MESS_RELATIVE_QUANTITY_FEW'  => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW'])
                        ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                    'MESS_BTN_BUY'                => (isset($arParams['~MESS_BTN_BUY'])
                        ? $arParams['~MESS_BTN_BUY'] : ''),
                    'MESS_BTN_ADD_TO_BASKET'      => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
                        ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                    'MESS_BTN_SUBSCRIBE'          => (isset($arParams['~MESS_BTN_SUBSCRIBE'])
                        ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                    'MESS_BTN_DETAIL'             => (isset($arParams['~MESS_BTN_DETAIL'])
                        ? $arParams['~MESS_BTN_DETAIL'] : ''),
                    'MESS_NOT_AVAILABLE'          => (isset($arParams['~MESS_NOT_AVAILABLE'])
                        ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                    'MESS_BTN_COMPARE'            => (isset($arParams['~MESS_BTN_COMPARE'])
                        ? $arParams['~MESS_BTN_COMPARE'] : ''),

                    'USE_ENHANCED_ECOMMERCE'       => (isset($arParams['USE_ENHANCED_ECOMMERCE'])
                        ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                    'DATA_LAYER_NAME'              => (isset($arParams['DATA_LAYER_NAME'])
                        ? $arParams['DATA_LAYER_NAME'] : ''),
                    'BRAND_PROPERTY'               => (isset($arParams['BRAND_PROPERTY'])
                        ? $arParams['BRAND_PROPERTY'] : ''),
                    'USE_VOTE_RATING'              => $arParams['DETAIL_USE_VOTE_RATING'],
                    'TEMPLATE_THEME'               => (isset($arParams['TEMPLATE_THEME'])
                        ? $arParams['TEMPLATE_THEME'] : ''),
                    'ADD_TO_BASKET_ACTION'         => $basketAction,
                    'SHOW_CLOSE_POPUP'             => isset($arParams['COMMON_SHOW_CLOSE_POPUP'])
                        ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                    'COMPARE_PATH'                 => $arResult['FOLDER']
                        .$arResult['URL_TEMPLATES']['compare'],
                    'COMPARE_NAME'                 => $arParams['COMPARE_NAME'],
                    'BACKGROUND_IMAGE'             => '',
                    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT'])
                        ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
                ],
                $component
            );
            ?>
        </div>
        <?
        }
    }*/

    //////viewed///
//    if (Config::get('SHOW_VIEWED_'.$template) == 'Y' && Loader::includeModule('catalog')
//        && (!isset($arParams['DETAIL_SHOW_VIEWED']) || $arParams['DETAIL_SHOW_VIEWED'] != 'N')) {
//
//        global $productFilter;
//        $productFilter = ['ACTIVE' => 'Y'];
//        $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
//        if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
//            $productFilter['PROPERTY_REGIONS'] = [
//                false,
//                $_SESSION['KIT_REGIONS']['ID']
//            ];
//        }
//        $APPLICATION->IncludeComponent(
//            'bitrix:catalog.products.viewed',
//            'origami_products_viewed',
//            [
//                'IBLOCK_MODE'                        => 'single',
//                'IBLOCK_TYPE'                        => $arParams['IBLOCK_TYPE'],
//                'IBLOCK_ID'                          => $arParams['IBLOCK_ID'],
//                'SECTION_NAME'              => Loc::getMessage('CATALOG_PRODUCTS_VIEWED'),
//                'ELEMENT_SORT_FIELD'                 => $arParams['ELEMENT_SORT_FIELD'],
//                'ELEMENT_SORT_ORDER'                 => $arParams['ELEMENT_SORT_ORDER'],
//                'ELEMENT_SORT_FIELD2'                => $arParams['ELEMENT_SORT_FIELD2'],
//                'ELEMENT_SORT_ORDER2'                => $arParams['ELEMENT_SORT_ORDER2'],
//                'FILTER_NAME'   => 'productFilter',
//                'PROPERTY_CODE_'.$arParams['IBLOCK_ID']  => $arParams['LIST_PROPERTY_CODE'],
//                'PROPERTY_CODE_'
//                .$recommendedData['OFFER_IBLOCK_ID'] => $arParams['LIST_OFFERS_PROPERTY_CODE'],
//                'PROPERTY_CODE_MOBILE'
//                .$arParams['IBLOCK_ID']              => $arParams['LIST_PROPERTY_CODE_MOBILE'],
//                'BASKET_URL'                         => $arParams['BASKET_URL'],
//                'ACTION_VARIABLE'                    => $arParams['ACTION_VARIABLE'],
//                'PRODUCT_ID_VARIABLE'                => $arParams['PRODUCT_ID_VARIABLE'],
//                'PRODUCT_QUANTITY_VARIABLE'          => $arParams['PRODUCT_QUANTITY_VARIABLE'],
//                'PRODUCT_PROPS_VARIABLE'             => $arParams['PRODUCT_PROPS_VARIABLE'],
//                'CACHE_TYPE'                         => $arParams['CACHE_TYPE'],
//                'CACHE_TIME'                         => $arParams['CACHE_TIME'],
//                'CACHE_FILTER'                       => $arParams['CACHE_FILTER'],
//                'CACHE_GROUPS'                       => $arParams['CACHE_GROUPS'],
//                'DISPLAY_COMPARE'                    => 'N',
//                'PRICE_CODE'                         => $arParams['~PRICE_CODE'],
//                'USE_PRICE_COUNT'                    => $arParams['USE_PRICE_COUNT'],
//                'SHOW_PRICE_COUNT'                   => $arParams['SHOW_PRICE_COUNT'],
//                'PAGE_ELEMENT_COUNT'                 => 10,
//                'SECTION_ELEMENT_ID'                 => $elementId,
//
//                "SET_TITLE"            => "N",
//                "SET_BROWSER_TITLE"    => "N",
//                "SET_META_KEYWORDS"    => "N",
//                "SET_META_DESCRIPTION" => "N",
//                "SET_LAST_MODIFIED"    => "N",
//                "ADD_SECTIONS_CHAIN"   => "N",
//
//                'PRICE_VAT_INCLUDE'                  => $arParams['PRICE_VAT_INCLUDE'],
//                /*'USE_PRODUCT_QUANTITY'               => $arParams['USE_PRODUCT_QUANTITY'],
//                'ADD_PROPERTIES_TO_BASKET'           => (isset($arParams['ADD_PROPERTIES_TO_BASKET'])
//                    ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
//                'PARTIAL_PRODUCT_PROPERTIES'         => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES'])
//                    ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
//                'CART_PROPERTIES_'
//                .$arParams['IBLOCK_ID']              => $arParams['PRODUCT_PROPERTIES'],
//                'CART_PROPERTIES_'
//                .$recommendedData['OFFER_IBLOCK_ID'] => $arParams['OFFERS_CART_PROPERTIES'],
//                'ADDITIONAL_PICT_PROP_'
//                .$arParams['IBLOCK_ID']              => $arParams['ADD_PICT_PROP'],
//                'ADDITIONAL_PICT_PROP_'
//                .$recommendedData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],*/
//
//                'SHOW_FROM_SECTION'         => 'N',
//                'DETAIL_URL'                => $arResult['FOLDER']
//                    .$arResult['URL_TEMPLATES']['element'],
//                'CONVERT_CURRENCY'          => $arParams['CONVERT_CURRENCY'],
//                'CURRENCY_ID'               => $arParams['CURRENCY_ID'],
//                'HIDE_NOT_AVAILABLE'        => $arParams['HIDE_NOT_AVAILABLE'],
//                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
//
//                'LABEL_PROP_'
//                .$arParams['IBLOCK_ID'] => $arParams['LABEL_PROP'],
//                'LABEL_PROP_MOBILE_'
//                .$arParams['IBLOCK_ID'] => $arParams['LABEL_PROP_MOBILE'],
//                'LABEL_PROP_POSITION'   => $arParams['LABEL_PROP_POSITION'],
//                'PRODUCT_BLOCKS_ORDER'  => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
//                'PRODUCT_ROW_VARIANTS'  => "[{'VARIANT':'3','BIG_DATA':false}]",
//                'ENLARGE_PRODUCT'       => $arParams['LIST_ENLARGE_PRODUCT'],
//                'ENLARGE_PROP_'
//                .$arParams['IBLOCK_ID'] => isset($arParams['LIST_ENLARGE_PROP'])
//                    ? $arParams['LIST_ENLARGE_PROP'] : '',
//                /*'SHOW_SLIDER'           => $arParams['LIST_SHOW_SLIDER'],
//                'SLIDER_INTERVAL'       => isset($arParams['LIST_SLIDER_INTERVAL'])
//                    ? $arParams['LIST_SLIDER_INTERVAL'] : '',
//                'SLIDER_PROGRESS'       => isset($arParams['LIST_SLIDER_PROGRESS'])
//                    ? $arParams['LIST_SLIDER_PROGRESS'] : '',
//
//                'OFFER_TREE_PROPS_'
//                .$recommendedData['OFFER_IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],*/
//                'PRODUCT_SUBSCRIPTION'               => $arParams['PRODUCT_SUBSCRIPTION'],
//                'SHOW_DISCOUNT_PERCENT'              => $arParams['SHOW_DISCOUNT_PERCENT'],
//                'DISCOUNT_PERCENT_POSITION'          => $arParams['DISCOUNT_PERCENT_POSITION'],
//                'SHOW_OLD_PRICE'                     => $arParams['SHOW_OLD_PRICE'],
//                'PRODUCT_COUNT_ROW' => 4,
//                /*'SHOW_MAX_QUANTITY'                  => $arParams['SHOW_MAX_QUANTITY'],
//                'MESS_SHOW_MAX_QUANTITY'             => (isset($arParams['~MESS_SHOW_MAX_QUANTITY'])
//                    ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
//                'RELATIVE_QUANTITY_FACTOR'           => (isset($arParams['RELATIVE_QUANTITY_FACTOR'])
//                    ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
//                'MESS_RELATIVE_QUANTITY_MANY'        => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY'])
//                    ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
//                'MESS_RELATIVE_QUANTITY_FEW'         => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW'])
//                    ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
//                'MESS_BTN_BUY'                       => (isset($arParams['~MESS_BTN_BUY'])
//                    ? $arParams['~MESS_BTN_BUY'] : ''),
//                'MESS_BTN_ADD_TO_BASKET'             => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
//                    ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
//                'MESS_BTN_SUBSCRIBE'                 => (isset($arParams['~MESS_BTN_SUBSCRIBE'])
//                    ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
//                'MESS_BTN_DETAIL'                    => (isset($arParams['~MESS_BTN_DETAIL'])
//                    ? $arParams['~MESS_BTN_DETAIL'] : ''),
//                'MESS_NOT_AVAILABLE'                 => (isset($arParams['~MESS_NOT_AVAILABLE'])
//                    ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
//                'MESS_BTN_COMPARE'                   => (isset($arParams['~MESS_BTN_COMPARE'])
//                    ? $arParams['~MESS_BTN_COMPARE'] : ''),
//
//                'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE'])
//                    ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
//                'DATA_LAYER_NAME'        => (isset($arParams['DATA_LAYER_NAME'])
//                    ? $arParams['DATA_LAYER_NAME'] : ''),
//                'BRAND_PROPERTY'         => (isset($arParams['BRAND_PROPERTY'])
//                    ? $arParams['BRAND_PROPERTY'] : ''),
//
//                'TEMPLATE_THEME'       => (isset($arParams['TEMPLATE_THEME'])
//                    ? $arParams['TEMPLATE_THEME'] : ''),
//                'ADD_TO_BASKET_ACTION' => $basketAction,
//                'SHOW_CLOSE_POPUP'     => isset($arParams['COMMON_SHOW_CLOSE_POPUP'])
//                    ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
//                'COMPARE_PATH'         => $arResult['FOLDER']
//                    .$arResult['URL_TEMPLATES']['compare'],
//                'COMPARE_NAME'         => $arParams['COMPARE_NAME'],
//                'USE_COMPARE_LIST'     => 'N',*/
//            ],
//            $component
//        );
//
//    }
}
?>