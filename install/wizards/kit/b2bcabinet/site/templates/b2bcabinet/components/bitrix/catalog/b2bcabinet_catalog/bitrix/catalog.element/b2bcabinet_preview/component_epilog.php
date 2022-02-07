<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/fonts/share/share.css');

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

// get products in the current user's basket
$inBasket = array();
$arResult['WISHES'] = $arIDs = array();
//if($arResult["SHOW_DELAY"])
//{
//    $wish = new \Kit\Origami\Sale\Basket\Wish();
//    $arResult['WISHES'] = $wish->findWishes();
//}

if($arResult["SHOW_BUY"])
{
    $basketRes = \Bitrix\Sale\Internals\BasketTable::getList(array(
        'filter' => array(
            'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
            'ORDER_ID' => null,
            'LID' => SITE_ID,
            'CAN_BUY' => 'Y',
            'DELAY' => 'N',
        ),
        'select' => array('PRODUCT_ID')
    ));
    while ($item = $basketRes->fetch()) {
        $inBasket[] = (int)$item["PRODUCT_ID"];
    }
}

$template = $this->__template->__name;
if (!$template) {
    $template = '';
}
if ($template == '.default') {
    $template = '';
}

$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);

global $APPLICATION;

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



if (isset($templateData['JS_OBJ']))
{
    if($arResult["SHOW_BUY"])
    {
        ?>
        <script>
            BX.ready(BX.defer(function(){

                if (!!window.<?=$templateData['JS_OBJ']?>)
                {
                    //if(typeof(arBasketID) != "undefined")
                        //alert('test');
                        window.<?=$templateData['JS_OBJ']?>.setBasket(<?=$templateData['ITEM']['ID']?>, <?=json_encode($templateData['OFFERS_ID'])?>, <?=json_encode($inBasket)?>);

                }
            }));
        </script>

        <?
    }?>


    <script>
        BX.ready(BX.defer(function(){
            if (!!window.<?=$templateData['JS_OBJ']?>)
            {
                window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
            }
        }));
    </script>

    <?
    $item = $templateData['ITEM'];
    // check compared state
    if ($arResult["SHOW_COMPARE"] && $arParams['DISPLAY_COMPARE'])
    {
        $compared = false;
        $comparedIds = array();


        if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
        {
            if (!empty($item['JS_OFFERS']))
            {
                foreach ($item['JS_OFFERS'] as $key => $offer)
                {
                    if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
                    {
                        if ($key == $item['OFFERS_SELECTED'])
                        {
                            $compared = true;
                        }

                        $comparedIds[] = $offer['ID'];
                    }
                }
            }
            elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
            {
                $compared = true;
            }
        }

        if ($templateData['JS_OBJ'])
        {
            ?>
            <script>
                BX.ready(BX.defer(function(){
                    if (!!window.<?=$templateData['JS_OBJ']?>)
                    {
                        window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

                        <? if (!empty($comparedIds)): ?>
                        window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
                        <? endif ?>
                    }
                }));
            </script>
            <?
        }
    }

    $wished = false;
    if($arResult["SHOW_DELAY"] && $templateData['JS_OBJ'] && $arResult['WISHES'])
    {
        if (!empty($item['JS_OFFERS']))
        {
            foreach ($item['JS_OFFERS'] as $key => $offer)
            {
                if (in_array($offer['ID'], $arResult['WISHES']))
                {
                    if ($key == $item['OFFERS_SELECTED'])
                    {
                        $wished = true;
                    }
                }
            }
        }elseif (in_array($item['ID'], $arResult['WISHES']))
        {
            $wished = true;
        }
        ?>
        <script>
            BX.ready(BX.defer(function(){

                if (!!window.<?=$templateData['JS_OBJ']?>)
                {
                    window.<?=$templateData['JS_OBJ']?>.setWish('<?=$wished?>');

                    window.<?=$templateData['JS_OBJ']?>.setWishes(<?=CUtil::PhpToJSObject($arResult['WISHES'], false, true)?>);
                }
            }));
        </script>
        <?
    }

    // select target offer
    $request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $offerNum = false;
    $offerId = (int)$this->request->get('OFFER_ID');
    $offerCode = $this->request->get('OFFER_CODE');

    if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
    {
        $offerNum = array_search($offerId, $templateData['OFFER_IDS']);
    }
    elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
    {
        $offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
    }

    if (!empty($offerNum))
    {
        ?>
        <script>
            BX.ready(function(){
                if (!!window.<?=$templateData['JS_OBJ']?>)
                {
                    window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
                }
            });
        </script>
        <?
    }
}?>
