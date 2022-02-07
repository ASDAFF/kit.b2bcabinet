<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
\Bitrix\Main\UI\Extension::load("ui.fonts.ruble");

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
$documentRoot = Main\Application::getDocumentRoot();

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact')))
{
	$arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
//$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = 'N';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
{
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('bottom');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

\CJSCore::Init(array('fx', 'popup', 'ajax'));

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
	? $arParams['COLUMNS_LIST_MOBILE']
	: $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
    if(pathinfo($jsTemplate->getPath(), PATHINFO_EXTENSION) != "php")
        continue;
	include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';

if (empty($arResult['ERROR_MESSAGE']) && !empty($arResult['ITEMS']['AnDelCanBuy']))
{


	if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED'])
	{
		?>
		<div id="basket-item-message">
			<?=Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', array('#PATH#' => $arParams['PATH_TO_BASKET']))?>
		</div>
		<?
	}
	?>
	<div class="row">
        <div class="col-md-12">
            <div id="basket-root" class="card">

                <? /* title */?>
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"><?=Loc::getMessage('SBB_BASKET_TITLE')?></h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <!--<a class="list-icons-item" data-action="reload"></a>-->
                        </div>
                    </div>
                </div>

                <? /* warning */?>
                <div class="">
                    <div class="col-xs-12">
                        <div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
                            <span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
                            <div data-entity="basket-general-warnings"></div>
                            <div data-entity="basket-item-warnings">
                                <?=Loc::getMessage('SBB_BASKET_ITEM_WARNING')?>
                            </div>
                        </div>
                    </div>
                </div>

                <? /* list products */ ?>
                <div class="card-body index_checkout-table" id="basket-items-list-wrapper">
                    <div class="table-responsive">
                        <div class="datatable-scroll">
                            <div class="anchor_header"></div>
                            <table class="table datatable-save-state" id="basket-item-list">
                                <thead>
                                    <tr>
                                        <th<?=(in_array("PREVIEW_PICTURE", $arParams['COLUMNS_LIST']) ? ' colspan="2"' : '')
                                        ?>><?=GetMessage("SBB_BASKET_NAME")?></th>
                                        <?if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) {

                                            foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
                                                if(trim((string)$blockName) == "props" && in_array('PROPS', $arParams['COLUMNS_LIST'])) {
                                                    foreach ($arResult['headers']['props'] as $props) {
                                                        ?><th><?=$props?></th><?
                                                    }

                                                } else if(trim((string)$blockName) == "sku") {
                                                    foreach ($arResult['headers']['sku_data'] as $sku) {
                                                        ?><th><?=$sku?></th><?
                                                    }
                                                } else if(trim((string)$blockName) == "columns") {
                                                    foreach ($arResult['headers']['column_list'] as $colums) {
                                                        ?><th><?=$colums?></th><?
                                                    }
                                                }
                                            }

                                        }?>

                                        <?foreach($arResult['templateColums'] as $codeColums => $titleColums):?>
                                            <?if(in_array($codeColums, $arParams['COLUMNS_LIST'])):?>
                                                <th<?=(in_array($codeColums, $arParams['COLUMNS_LIST_MOBILE']) ? ' class="hidden-xs"' : '')?>>
                                                    <?=$titleColums?>
                                                </th>
                                            <?endif;?>
                                        <?endforeach;?>
                                    </tr>
                                </thead>

                                <tbody id="basket-item-table"></tbody>

                            </table>
                        </div>
                    </div>
                    <?
                    if ( $arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y' ) {
                        ?>
                        <div class="anchor"></div>
                        <div class="index_checkout-promocode row-under-modifications row-under-modifications-fixed" data-entity="basket-total-block"></div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>
	</div>
	<?
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?
	}

	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
	$messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		BX.Sale.BasketComponent.init({
			result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			template: '<?=CUtil::JSEscape($signedTemplate)?>',
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
			templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
		});
	</script>
	<?
}
elseif ($arResult['EMPTY_BASKET'] || empty($arResult['ITEMS']['AnDelCanBuy']))
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}