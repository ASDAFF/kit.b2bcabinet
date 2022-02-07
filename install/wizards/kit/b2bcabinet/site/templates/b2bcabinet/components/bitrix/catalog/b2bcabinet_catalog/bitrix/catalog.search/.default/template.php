<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$arSections = $arElements = array();

$arElementsFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arElementsSelect = array("ID", "IBLOCK_SECTION_ID");

$cacheId = md5(serialize($arElementsFilter));

$cache = \Bitrix\Main\Data\Cache::createInstance();

if ($cache->initCache(36000000, $cacheId, '/kit.b2bcabinet'))
{
    $arData = $cache->getVars();
}
elseif ($cache->startDataCache())
{

    $res = CIBlockElement::GetList(Array(), $arElementsFilter, false, false, $arElementsSelect);

    while($arRes = $res->Fetch())
    {
        $arElements[] = $arRes["ID"];
    }

    if($arElements)
    {
        $arData["arElements"] = $arElements;
    }
    $cache->endDataCache($arData);
}



$arSectionsFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "<DEPTH_LEVEL" => "3");
$arSectionsSelect = array("ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL");

$cacheId = md5(serialize($arSectionsFilter));

$cache = \Bitrix\Main\Data\Cache::createInstance();

if ($cache->initCache(36000000, $cacheId, '/kit.b2bcabinet'))
{
    $arData = $cache->getVars();
}
elseif ($cache->startDataCache())
{

    $res = CIBlockSection::GetList(Array(), $arSectionsFilter, false, $arSectionsSelect);

    while($arRes = $res->Fetch())
    {
        $tmpSections['ID'] = $arRes["ID"];
        $tmpSections['DEPTH_LEVEL'] = $arRes["DEPTH_LEVEL"];
        $tmpSections['IBLOCK_SECTION_ID'] = $arRes["IBLOCK_SECTION_ID"];

        $arSections[$arRes['ID']] = $tmpSections;
    }

    if($arSections)
    {
        $arData["arSections"] = $arSections;
    }
    $cache->endDataCache($arData);
}


$arElements = $arData["arElements"];
$arSections = $arData["arSections"];

$this->setFrameMode(true);
?>
<div class="index_blank">
    <div class="d-flex align-items-start flex-column flex-md-row">
    <!-- SMART FILTER -->
        <div class="index_blank-filter <?=(isset($arParams['FILTER_HIDE_ON_MOBILE']) && $arParams['FILTER_HIDE_ON_MOBILE'] === 'Y' ? ' hidden-xs' : '')?>">
            <div class="card">
                <?
                $APPLICATION->IncludeComponent(
                    "kit:b2bcabinet.catalog.smart.filter",
                    "b2b_smart_filter",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => '',
                        "SECTIONS_ID" => array_keys($arSections),
                        "ELEMENTS_ID" => $arElements,
                        "ARR_SECTIONS" => $arSections,
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "PRICE_CODE" => $arParams["~PRICE_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SAVE_IN_SESSION" => "N",
                        "FILTER_VIEW_MODE" => "",
                        "XML_EXPORT" => "N",
                        "SECTION_TITLE" => "NAME",
                        "SECTION_DESCRIPTION" => "DESCRIPTION",
                        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                        "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                        "SEF_MODE" => $arParams["SEF_MODE"],
                        "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                        "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                        "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                        "SHOW_SECTIONS" => "Y"
                    ),
                    $component,
                    array('HIDE_ICONS' => 'Y')
                );
                ?>
            </div>
        </div>
    <!-- /SMART FILTER -->
    <?
    if (!empty($arElements) && is_array($arElements))
    {
        ?>
        <div class="w-100 overflow-auto order-2 order-md-1">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"><?=Loc::getMessage('CATALOG_SECTION_TITLE')?></h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-excel-button">
                        <button type="button" class="btn btn-light btn-ladda btn-ladda-spinner"
                                data-spinner-color="#333" data-style="slide-right"
                        >
                            <span class="ladda-label">
                                <i class="icon-download mr-2"></i>
                                <?=Loc::getMessage('IMPORT_EXCEL')?>
                            </span>
                        </button>
                        <button type="button" class="btn btn-light btn-ladda btn-ladda-spinner"
                                data-spinner-color="#333" data-style="slide-right"
                        >
                            <span class="ladda-label">
                                <i class="icon-upload mr-2"></i>
                                <?=Loc::getMessage('EXPORT_EXCEL')?>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="card-body index_blank-search">
                    <?
                    $arElements = $APPLICATION->IncludeComponent(
                        "bitrix:search.page",
                        "b2b_search",
                        Array(
                            "RESTART" => $arParams["RESTART"],
                            "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
                            "USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
                            "CHECK_DATES" => $arParams["CHECK_DATES"],
                            "arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
                            "arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
                            "USE_TITLE_RANK" => "N",
                            "DEFAULT_SORT" => "rank",
                            "FILTER_NAME" => "",
                            "SHOW_WHERE" => "N",
                            "arrWHERE" => array(),
                            "SHOW_WHEN" => "N",
                            "PAGE_RESULT_COUNT" => $arParams["PAGE_RESULT_COUNT"],
                            "DISPLAY_TOP_PAGER" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "N",
                            "PAGER_TITLE" => "",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_TEMPLATE" => "N",
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );

//                    $APPLICATION->IncludeFile(SITE_DIR."include/blank_search.php",
//                        Array($params, $arParams),
//                        Array("MODE"=>"php")
//                    );
                    ?>
                </div>

                <div class="index_blank-table">
                    <?
                    if(!empty($arElements))
                    {
                        global $searchFilter;
                        $searchFilter = array(
                            "=ID" => $arElements,
                        );
                    }

                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.section",
                        ".default",
                        array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                            "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                            "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                            "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                            "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                            "PROPERTY_CODE_MOBILE" => $arParams["PROPERTY_CODE_MOBILE"],
                            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                            "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                            "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                            "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                            "SECTION_URL" => $arParams["SECTION_URL"],
                            "DETAIL_URL" => $arParams["DETAIL_URL"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                            "PRICE_CODE" => $arParams["~PRICE_CODE"],
                            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                            "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
                            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                            "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                            "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                            "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],
                            "FILTER_NAME" => "searchFilter",
                            "SECTION_ID" => "",
                            "SECTION_CODE" => "",
                            "SECTION_USER_FIELDS" => array(),
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "SHOW_ALL_WO_SECTION" => "Y",
                            "META_KEYWORDS" => "",
                            "META_DESCRIPTION" => "",
                            "BROWSER_TITLE" => "",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "SET_TITLE" => "N",
                            "SET_STATUS_404" => "N",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "N",

                            'LABEL_PROP' => $arParams['LABEL_PROP'],
                            'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                            'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                            'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                            'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
                            'PRODUCT_ROW_VARIANTS' => $arParams['PRODUCT_ROW_VARIANTS'],
                            'ENLARGE_PRODUCT' => $arParams['ENLARGE_PRODUCT'],
                            'ENLARGE_PROP' => $arParams['ENLARGE_PROP'],
                            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
                            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
                            'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],

                            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                            'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
                            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                            'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
                            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
                            'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
                            'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
                            'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
                            'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
                            'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                            'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
                            'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
                            'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],

                            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                            'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],

                            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                            'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                            'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                            'COMPARE_PATH' => $arParams['COMPARE_PATH'],
                            'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                            'USE_COMPARE_LIST' => $arParams['USE_COMPARE_LIST']
                        ),
                        $arResult["THEME_COMPONENT"],
                        array('HIDE_ICONS' => 'Y')
                    );
                    ?>
                </div>
                <?
                $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
                ?>
            </div>
        </div>


    <?
    }
    elseif (is_array($arElements))
    {
        echo GetMessage("CT_BCSE_NOT_FOUND");
    }
    ?>
</div>
</div>
