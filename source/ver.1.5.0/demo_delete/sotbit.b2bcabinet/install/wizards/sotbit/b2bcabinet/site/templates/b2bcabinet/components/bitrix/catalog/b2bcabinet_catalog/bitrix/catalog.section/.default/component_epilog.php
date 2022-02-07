<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}

if($arParams['CONVERT_CURRENCY'] == 'Y' && !empty($arParams['CURRENCY_ID']))
{
    $baseCyrrency = $arParams['CURRENCY_ID'];
}
else
{
    $arCurrency = CCurrency::GetList(($by = "name"), ($order = "asc"), LANGUAGE_ID);
    while ($resCurrency = $arCurrency->Fetch())
    {
        if($resCurrency['BASE'] == 'Y')
        {
            $baseCyrrency = $resCurrency["CURRENCY"];
            break;
        }
    }
}

$totalPrice = 0;
$totalCount = 0;
if(!empty($_SESSION['BLANK_IDS']) && is_array($_SESSION['BLANK_IDS']))
{
    foreach ($_SESSION['BLANK_IDS'] as $key => $item)
    {
        if(!empty($item['MIN_PRICE']))
        {
            $totalPrice += $item['MIN_PRICE'] * $item['QNT'];
        }

        if($key !== 'TOTAL_PRICE' && $key !== 'TOTAL_COUNT')
        {
            $totalCount++;
        }
    }
}

if($totalCount > 0)
{
    $_SESSION['BLANK_IDS']['TOTAL_COUNT'] = $totalCount;
}

if(
    (isset($_SESSION['only_checked']) && $_SESSION['only_checked'] == 'Y') &&
    (!empty($_SESSION['BLANK_IDS']) && is_array($_SESSION['BLANK_IDS'])))
{
    $itemIds = array_keys($_SESSION['BLANK_IDS']);

    foreach ($arResult['ITEMS'] as &$ITEM)
    {
        if(!empty($ITEM['OFFERS']))
        {
            foreach ($ITEM['OFFERS'] as $key => $offer) {
                if(!in_array($offer['ID'], $itemIds))
                {
                    unset($ITEM['OFFERS'][$key]);
                }
            }
        }
    }
}
?>

<script>
    $(document).ready(function () {
        <?if(!empty($_SESSION['BLANK_IDS']) && is_array($_SESSION['BLANK_IDS'])):?>
            var productIDs = <?= \Bitrix\Main\Web\Json::encode($_SESSION['BLANK_IDS']);?>;
                if(productIDs !== undefined && productIDs !== '')
                {
                    $.each(productIDs, function (key, value) {
                        if(value['QNT'] !== undefined && value['QNT'] >= 0)
                            $('.form-control.touchspin-empty[data-id="'+ key +'"]').val(value['QNT']);
                    })
                }
        <?endif;?>
        $('.index_blank-add_cart-number').html('<?=( !empty($_SESSION['BLANK_IDS']['TOTAL_COUNT']) ? $_SESSION['BLANK_IDS']['TOTAL_COUNT'] : '0' )?>');
        $('.index_blank-add_cart-total').html('<?= CurrencyFormat($totalPrice, $baseCyrrency) ?>');
    });
</script>