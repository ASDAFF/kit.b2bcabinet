<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$methodIstall = Option::get('kit.b2bcabinet', 'method_install', '',
    SITE_ID) == 'AS_TEMPLATE' ? SITE_DIR . 'b2bcabinet/' : SITE_DIR;

$this->setFrameMode(true);

if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1) {
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$generalParams = array(
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
    'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
    'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
    'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    'LABEL_POSITION_CLASS' => $labelPositionClass,
    'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
    'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
    '~BASKET_URL' => $arParams['~BASKET_URL'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
    '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
    'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
    'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
    'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
    'LIST_SHOW_MEASURE_RATIO' => $arParams['~LIST_SHOW_MEASURE_RATIO'],
);

$obName = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-' . $navParams['NavNum'];

if (false && $arParams['HIDE_SECTION_DESCRIPTION'] !== 'Y') {
    ?>
    <div class="bx-section-desc bx-<?= $arParams['TEMPLATE_THEME'] ?>">
        <p class="bx-section-desc-post"><?= $arResult['DESCRIPTION'] ?></p>
    </div>
    <?
}
?>
<!-- table_header -->
<div class="table_header">
    <!-- total products -->
    <div id="block_filter_js" class="block_form_filter blank_filter" data-site-url="<?= $APPLICATION->GetCurPage() ?>"
         data-site-dir="<?= $_SERVER['DOCUMENT_ROOT'] . SITE_DIR ?>" style="display: none"></div>
    <div class="total_products-table_header">
        <div class="sorting-checkboxes">
            <div class="form-check">
                <form method="POST">
                    <?= bitrix_sessid_post() ?>
                    <label class="form-check-label" for="only_available_filter">
                        <input type="checkbox"
                               class="form-input-styled"
                               id="only_available_filter"
                               onclick="$(this).closest('form').submit(); return false;"
                            <?= ($_SESSION["only_available"] == 'Y' ? 'checked="checked"' : "") ?>
                               data-fouc>
                        <?= Loc::getMessage('ONLY_PRODUCTS_AVAILEBLE') ?>
                    </label>
                    <input
                            style="display: none;"
                            name="only_available"
                            value="<?= ($_SESSION["only_available"] == 'Y' ? 'N' : 'Y') ?>"
                    >
                </form>
            </div>
            <div class="form-check">
                <form method="POST">
                    <?= bitrix_sessid_post() ?>
                    <label class="form-check-label" for="only_checked_filter">
                        <input type="checkbox"
                               class="form-input-styled"
                               id="only_checked_filter"
                               onclick="$(this).closest('form').submit(); return false;"
                            <?= ($_SESSION["only_checked"] == 'Y' ? 'checked="checked"' : "") ?>
                               data-fouc>
                        <?= Loc::getMessage('ONLY_PRODUCTS_SELECTED') ?>
                    </label>
                    <input
                            style="display: none;"
                            name="only_checked"
                            value="<?= ($_SESSION["only_checked"] == 'Y' ? 'N' : 'Y') ?>"
                    >
                </form>
            </div>
        </div>
    </div>
    <!-- /total products-->
    <div>
        <div class="blank_detail-search row index_blank-sorting">
            <?
            $APPLICATION->IncludeFile(SITE_DIR . "include/b2b_page_sort_catalog.php", Array(
                $arResult,
                $arParams,
                "top"
            ), Array(
                "MODE" => "php"
            ));
            ?>
        </div>
    </div>

    <div class="index_blank-sorting_pagination">
        <? if ($showTopPager): ?>
            <div class="index_blank-sorting_title-pagination" data-pagination-num="<?= $navParams['NavNum'] ?>">
                <?= $arResult['NAV_STRING'] ?>
            </div>
        <? endif; ?>
    </div>

</div>
<!--/ table_header -->
<div class="total_products">
    <span><?= Loc::getMessage('PRODUCTS_COUNT') ?></span><span
            class="index_blank-total"><?= (isset($arResult['NAV_RESULT']) && !empty($arResult['NAV_RESULT']->NavRecordCount) ? " " . $arResult['NAV_RESULT']->NavRecordCount : "-") ?></span>
</div>
<? if (!empty($arResult['ITEMS'])): ?>
    <div class="dataTables_wrapper no-footer bx-<?= $arParams['TEMPLATE_THEME'] ?>" data-entity="<?= $containerName ?>">
        <div class="table-responsive">
            <div class="main-grid-ear-left scroll-ears"></div>
            <div class="main-grid-ear-right scroll-ears"></div>
            <div class="datatable-scroll">
                <!-- table header -->
                <div class="index_blank-thead_fixed-wrapper">
                    <div id="index_blank-thead_fixed">
                    </div>
                </div>
                <div class="anchor_header"></div>
                <!-- table header -->
                <table class="table dataTable no-footer">
                    <thead id="index_blank-thead">
                    <tr role="row">
                        <th></th>

                        <?

                        foreach ($arParams['TABLE_HEADER'] as $item) {
                            if (is_array($item)) {
                                foreach ($item as $it) {

                                    echo '<th><div class="thead-item">' . $it["NAME"] . '</div></th>';
                                }
                            } else {
                                if (stristr($item, $arParams['TABLE_HEADER']['NAME'])) {
                                    echo '<th><div class="thead-item thead-item_name">' . $item . '</div></th>';
                                } else {
                                    if (stristr($item, $arParams['TABLE_HEADER']['QUANTITY'])) {
                                        echo '<th><div class="thead-item thead-item_quantity">' . $item . '</div></th>';
                                    } else {
                                        echo '<th><div class="thead-item">' . $item . '</div></th>';
                                    }
                                }
                            }

                        }
                        ?>
                    </tr>
                    </thead>
                    <?
                    array_shift($arParams['TABLE_HEADER']);
                    if (!empty($arResult['ITEMS'])) {
                    $areaIds = array();
                    ?>
                    <!-- items-container -->
                    <?
                    foreach ($arResult['ITEMS'] as $rowData) {
                    $rowItems = array_splice($arResult['ITEMS'], 0, 1);
                    $item = reset($rowItems);
                    $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                    $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                    ?>
                    <tbody class="index_blank-table-tbody" id="<?= $areaIds[$item['ID']] ?>">
                    <?

                    $APPLICATION->IncludeComponent(
                        'bitrix:catalog.item',
                        '',
                        array(
                            'RESULT' => array(
                                'ITEM' => $item,
                                'AREA_ID' => $areaIds[$item['ID']],
                                'TYPE' => 'line',
                                /*'TYPE' => $rowData['TYPE'],*/
                                'BIG_LABEL' => 'N',
                                'BIG_DISCOUNT_PERCENT' => 'N',
                                'BIG_BUTTONS' => 'N',
                                'SCALABLE' => 'N',
                                'TABLE_HEADER' => $arParams['TABLE_HEADER']
                            ),
                            'PARAMS' => $generalParams
                                + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );
                    ?>
                    <?
                    }
                    unset($generalParams, $rowItems);
                    ?>
                    <!-- items-container -->
                    <?
                    } else {
                        // load css for bigData/deferred load
                        $APPLICATION->IncludeComponent(
                            'bitrix:catalog.item',
                            '',
                            array(),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
<? else: ?>
    <div class="nothing_to_show text-muted"><?= Loc::getMessage('PRODUCTS_NOTHING_TO_SHOW') ?></div>
<? endif; ?>
<div>
    <div class="anchor"></div>
    <div class="row-under-modifications row-under-modifications-fixed">
        <div class="add_to_cart">
            <div>
                <span class="index_blank-add_cart-number-title"><?= Loc::getMessage('CT_BCS_TPL_MESS_TOTAL_PRODUCTS') ?></span>
                <span class="index_blank-add_cart-number"><?= (!empty($_SESSION['BLANK_IDS']) && is_array($_SESSION['BLANK_IDS']) ? count($_SESSION['BLANK_IDS']) : '0') ?></span>
            </div>
            <div>
                <span class="index_blank-add_cart-total-title"><?= Loc::getMessage('CT_BCS_TPL_MESS_TOTAL') ?></span>
                <span class="index_blank-add_cart-total"><?= $totalPrice . ' ' . $currency ?></span>
            </div>
        </div>
        <button type="button" class="btn btn-light add_to_cart btn_b2b" data-toggle="modal"
                data-target="#modal_add_to_bascket">
            <i class="icon-cart-add2 mr-2"></i>
            <?= Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET') ?>
        </button>
    </div>
    <? if ($showBottomPager): ?>
        <div class="datatable-footer_table_blank">
            <div class="dataTables_info"
                 role="status"
                 aria-live="polite"
                 data-pagination-num="<?= $navParams['NavNum'] ?>"
            >
                <?= $arResult['NAV_STRING'] ?>
            </div>
        </div>
    <? endif; ?>
</div>

<!-- Basic modal -->
<div id="modal_add_to_bascket" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Loc::getMessage('POPUP_WAS_ADDED_HEADER') ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="modal_add_to_bascket-preloader">
                    <i class="icon-spinner2 spinner"></i>
                </div>
                <div class="modal_add_to_bascket-on_success">
                    <h6 class="font-weight-semibold"><?= Loc::getMessage('POPUP_WAS_ADDED') ?></h6>
                </div>
                <div class="modal_add_to_bascket-on_error">
                    <h6 class="font-weight-semibold"><?= Loc::getMessage('POPUP_WAS_ADDED_ERROR') ?></h6>
                </div>
                <hr>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link"
                        data-dismiss="modal"><?= Loc::getMessage('POPUP_WAS_ADDED_CLOSE') ?></button>
                <a href="<?= $methodIstall ?>orders/make/index.php">
                    <button type="button"
                            class="btn btn_b2b"><?= Loc::getMessage('POPUP_WAS_ADDED_PROCESSED') ?></button>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->

<?

$headerCol = [
    'NAME' => Loc::getMessage('HEAD_NAME'),
    'ID' => 'ID'
];
$arParams['TABLE_HEADER'] = array_merge($headerCol, $arParams['TABLE_HEADER']);

$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
        BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
    });
    var <?=$obName?> =
    new JCCatalogSectionComponent({
        siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
        componentPath: '<?=CUtil::JSEscape($componentPath)?>',
        navParams: <?=CUtil::PhpToJSObject($navParams)?>,
        deferredLoad: false, // enable it for deferred load
        initiallyShowHeader: '<?=!empty($arResult['ITEMS'])?>',
        bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
        lazyLoad: !!'<?=$showLazyLoad?>',
        loadOnScroll: !!'<?=($arParams['LOAD_ON_SCROLL'] === 'Y')?>',
        template: '<?=CUtil::JSEscape($signedTemplate)?>',
        ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'])?>',
        parameters: '<?=CUtil::JSEscape($signedParams)?>',
        container: '<?=$containerName?>'
    });

    var site_path = '<?=SITE_DIR?>';
    var tableHeader = <?=CUtil::PhpToJSObject($arParams['TABLE_HEADER'])?>;

    <?
    $filterProps = array();
    if (!empty($GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID']) && is_array($GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'])) {
        $filterProps = $GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'];
    } else {
        if (!empty($GLOBALS[$arParams['FILTER_NAME']]) && is_array($GLOBALS[$arParams['FILTER_NAME']])) {
            $filterProps = $GLOBALS[$arParams['FILTER_NAME']];
        } else {
            if (!empty($GLOBALS["kitFilterResult"]['ITEMS']['SECTION_ID']['VALUES']) && is_array($GLOBALS["kitFilterResult"]['ITEMS']['SECTION_ID']['VALUES'])) {
                $filterProps = array_keys($GLOBALS["kitFilterResult"]['ITEMS']['SECTION_ID']['VALUES']);
            }
        }
    }

    if ($arParams['CONVERT_CURRENCY'] == 'Y' && !empty($arParams['CURRENCY_ID'])) {
        $baseCyrrency = $arParams['CURRENCY_ID'];
    } else {
        $arCurrency = CCurrency::GetList(($by = "name"), ($order = "asc"), LANGUAGE_ID);
        while ($resCurrency = $arCurrency->Fetch()) {
            if ($resCurrency['BASE'] == 'Y') {
                $baseCyrrency = $resCurrency["CURRENCY"];
                break;
            }
        }
    }
    ?>
    var filterProps = <?=CUtil::PhpToJSObject($filterProps)?>;
    var priceCodes = <?=CUtil::PhpToJSObject($arParams['PRICE_CODE'])?>;
    var baseCurrency = '<?=$baseCyrrency?>';
</script>