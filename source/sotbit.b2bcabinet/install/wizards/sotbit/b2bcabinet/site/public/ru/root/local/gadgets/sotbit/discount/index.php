<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

//Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
$idUser = intval($USER->GetID());

if(Loader::includeModule('sotbit.b2bcabinet') && $idUser > 0)
{
    $discount = new \Sotbit\B2BCabinet\Shop\Discount(
        array('ID' => $arParams['G_DISCOUNT_ID_DISCOUNT'])
    );
	?>
    <div class="widget_content widget_links widget_discount">
        <div class="widget_discount-img">
            <img src="<?=$arGadget['PATH_SITEROOT']?>/img/gift.png">
        </div>
        <div class="test_menu">
            <span><?=$discount->getName()?></span>
        </div>
    </div>
	<?
}
?>