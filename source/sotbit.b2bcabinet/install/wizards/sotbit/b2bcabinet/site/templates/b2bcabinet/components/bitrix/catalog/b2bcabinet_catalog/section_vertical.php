<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

$arAvailableSort = array(
    "default" => Array("default", ""),
    "name_0" => Array("name", "desc"),
    "name_1" => Array("name", "asc"),
    "price_0" => Array("property_minimum_price", "desc"),
    "price_1" => Array("property_minimum_price", "asc"),
    "date_0" => Array("date_create", "desc"),
    "date_1" => Array('date_create', "asc"),
    "articul_0" => Array("articul", "desc"),
    "articul_1" => Array("articul", "asc"),
);

if($_POST['only_available'] == 'Y')
{
    $_SESSION['only_available'] = 'Y';
}
else if($_POST['only_available'] == 'N')
{
    $_SESSION['only_available'] = 'N';
}

if($_POST['only_checked'] == 'Y')
{
    $_SESSION['only_checked'] = 'Y';
}
else if($_POST['only_checked'] == 'N')
{
    $_SESSION['only_checked'] = 'N';
}

if(isset($_POST["sort"]))
    $_SESSION["B2B_SORT"] = $_POST["sort"];

if(Loader::includeModule('iblock') && Loader::includeModule('catalog') && $_SESSION['only_checked'] == 'Y' && !empty($_SESSION['BLANK_IDS']) && is_array($_SESSION['BLANK_IDS']))
{
    global ${$arParams["FILTER_NAME"]};

    $arProducts = array();
    if($_SESSION['BLANK_IDS'])
    {
        $ids = array_keys($_SESSION['BLANK_IDS']);
    }
    else
    {
        $ids = array();
    }
    $arProducts = array( 'ID' => array());
    if($ids)
    {
        $offersIds = array();
        $ofIblock = 0;
        $result = \Bitrix\Iblock\ElementTable::getList(array(
            'select' => array('ID','IBLOCK_ID'),
            'filter' => array('ID' => $ids)
        ));
        while ($Element = $result->fetch())
        {
            if($Element['IBLOCK_ID'] == $arParams['IBLOCK_ID'])
            {
                $arProducts['ID'][] = intval($Element['ID']);
            }
            else
            {
                $offersIds[] = $Element['ID'];
                $ofIblock = $Element['IBLOCK_ID'];
            }
        }


        if($offersIds)
        {
            $prods = CCatalogSKU::getProductList($offersIds, $ofIblock);
            if($prods)
            {
                foreach($prods as $prod)
                {
                    $arProducts['ID'][] = $prod['ID'];
                }
            }
        }
    }

    if($arProducts['ID'])
    {
        if(${$arParams["FILTER_NAME"]}['ID'])
        {
            ${$arParams["FILTER_NAME"]}['ID'] = array_intersect(${$arParams["FILTER_NAME"]}['ID'], $arProducts['ID']);
        }
        else
        {
            ${$arParams["FILTER_NAME"]}['ID'] = $arProducts['ID'];
        }
    }
    else
    {
        ${$arParams["FILTER_NAME"]}['ID'] = array(0);
    }
}

$arSections = $arElements = array();

if(empty($arProducts['ID']) || !is_array($arProducts['ID']))
{
    $arElementsFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
    $arElementsSelect = array("ID", "IBLOCK_SECTION_ID");

    $cacheId = md5(serialize($arElementsFilter));

    $cache = \Bitrix\Main\Data\Cache::createInstance();

    if ($cache->initCache(36000000, $cacheId, '/sotbit.b2bcabinet')) {
        $arData = $cache->getVars();
    } elseif ($cache->startDataCache()) {

        $res = CIBlockElement::GetList(Array(), $arElementsFilter, false, false, $arElementsSelect);

        while ($arRes = $res->Fetch()) {
            $arElements[] = $arRes["ID"];
        }

        if ($arElements) {
            $arData["arElements"] = $arElements;
        }
        $cache->endDataCache($arData);
    }
}
else
    $arData["arElements"] = $arProducts['ID'];


$arSectionsFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "<DEPTH_LEVEL" => "3");
$arSectionsSelect = array("ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL");

$cacheId = md5(serialize($arSectionsFilter));

$cache = \Bitrix\Main\Data\Cache::createInstance();

if ($cache->initCache(36000000, $cacheId, '/sotbit.b2bcabinet'))
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

// -------------- sort
if(isset($_SESSION["B2B_SORT"]))
{
    $arSort = $arAvailableSort[$_SESSION["B2B_SORT"]];

    if($arSort[0] == 'ARTICUL')
    {
        $sort_field = 'PROPERTY_'.$arParams['OPT_ARTICUL_PROPERTY'];
        $sort_order = $arSort[1];
        if($arParams['OPT_ARTICUL_PROPERTY_OFFER'])
        {
            $sort_field_offer = 'PROPERTY_'.$arParams['OPT_ARTICUL_PROPERTY_OFFER'];
            $sort_order_offer = $arSort[1];
        }
    }
    else
    {
        $sort_field = $arSort[0];
        $sort_order = $arSort[1];
    }
}
elseif(empty($_SESSION["B2B_SORT"]) && $arAvailableSort[$arParams["ELEMENT_SORT_FIELD"]])
{
    $arSort = $arAvailableSort[$arParams["ELEMENT_SORT_FIELD"]];
    $sort_field = $arSort[0];
    $sort_order = $arSort[1];
}
// -------------- /sort

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}

if ($isFilter || $isSidebar): ?>
    <!-- Right sidebar component 5-->
    <div class="sidebar sidebar-light bg-transparent sidebar-component
                sidebar-component-right border-0 shadow-0 sidebar-expand-md smartfilter_wrapper">
        <div class="sidebar-content bx_filter<?= (isset($arParams['FILTER_HIDE_ON_MOBILE']) && $arParams['FILTER_HIDE_ON_MOBILE'] === 'Y' ? ' hidden-xs' : '') ?>">
            <? if ($isFilter): ?>
                    <?
                    $APPLICATION->IncludeComponent(
                        "sotbit:b2bcabinet.catalog.smart.filter",
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
                            'HIDE_NOT_AVAILABLE' => ($_SESSION['only_available'] == 'Y' ? 'Y' : ( $_SESSION['only_available'] == 'N' ? 'N' : 'L') ),
                            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                            "SEF_MODE" => $arParams["SEF_MODE"],
                            "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                            "SHOW_SECTIONS" => "Y",
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );
                    ?>
            <? endif ?>
        </div>
	</div>
<?endif?>
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
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.file.input",
                    "b2bcabinet_file_input",
                    array(
                        "INPUT_NAME"=>"EXCEL_FILE",
                        "MULTIPLE"=>"N",
                        "MODULE_ID"=>"main",
                        "MAX_FILE_SIZE"=>"",
                        "ALLOW_UPLOAD"=>"F",
                        "ALLOW_UPLOAD_EXT"=>"",
                        "INPUT_VALUE" => $_POST["DOPFILE"]
                    ),
                    false
                );
                ?>
                <button type="button"
                        class="btn btn-light btn-ladda btn-ladda-spinner"
                        data-spinner-color="#333"
                        data-style="slide-right"
                        id="blank-export-in-excel"
                >
                    <span class="ladda-label export_excel_preloader">
                        <i class="icon-upload mr-2"></i>
                        <?=Loc::getMessage('EXPORT_EXCEL')?>
                    </span>
                </button>
            </div>
        </div>

        <div class="card-body index_blank-search">
            <?
            $APPLICATION->IncludeFile(SITE_DIR."include/blank_search.php",
                Array($params, $arParams),
                Array("MODE" => "php")
            ); ?>
        </div>

		<div class="index_blank-table">
			<?

			$intSectionID = $APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"",
				array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_SORT_FIELD" => $sort_field?$sort_field:"name",
                    "ELEMENT_SORT_ORDER" => $sort_order?$sort_order:"desc",
					"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
					"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
					"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
					"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
					"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
					"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
					"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
					"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
					"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
					"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_FILTER" => $arParams["CACHE_FILTER"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SET_TITLE" => $arParams["SET_TITLE"],
					"MESSAGE_404" => $arParams["~MESSAGE_404"],
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"SHOW_404" => $arParams["SHOW_404"],
					"FILE_404" => $arParams["FILE_404"],
					"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
					"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
					"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
					"PRICE_CODE" => $arParams["~PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
					"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
					"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
					"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

					"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
					"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
					"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
					"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
					"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
					"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
					"LAZY_LOAD" => $arParams["LAZY_LOAD"],
					"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
					"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

					"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
					"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
					"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

					"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
					"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
					'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
					'CURRENCY_ID' => $arParams['CURRENCY_ID'],
					'HIDE_NOT_AVAILABLE' => ($_SESSION['only_available'] == 'Y' ? 'Y' : ( $_SESSION['only_available'] == 'N' ? 'N' : 'L') ),
					'HIDE_NOT_AVAILABLE_OFFERS' => ($_SESSION['only_available'] == 'Y' ? 'Y' : ( $_SESSION['only_available'] == 'N' ? 'N' : 'L') ),

					'LABEL_PROP' => $arParams['LABEL_PROP'],
					'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
					'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
					'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
					'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
					'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
					'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'0','BIG_DATA':false}]",
					'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
					'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
					'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
					'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
					'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

					'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
					'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
					'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
					'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
					'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
					'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
					'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
					'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
					'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
					'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
					'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
					'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
					'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
					'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
					'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
					'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
					'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

					'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
					'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
					'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

					'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
					"ADD_SECTIONS_CHAIN" => "N",
					'ADD_TO_BASKET_ACTION' => $basketAction,
					'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
					'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
					'COMPARE_NAME' => $arParams['COMPARE_NAME'],
					'USE_COMPARE_LIST' => 'Y',
					'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
					'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
					'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
                    'SHOW_ALL_WO_SECTION' => 'Y',
                    "BY_LINK" => "Y",
                    'LIST_SHOW_MEASURE_RATIO'    => (isset($arParams['LIST_SHOW_MEASURE_RATIO'])
                        ? $arParams['LIST_SHOW_MEASURE_RATIO'] : ''),
                ),
                $component
            );

            ?>
        </div>
        <? $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID; ?>
    </div>
</div>