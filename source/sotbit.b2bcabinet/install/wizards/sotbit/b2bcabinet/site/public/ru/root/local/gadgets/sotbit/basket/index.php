<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
Loc::loadMessages(__FILE__);

Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
$idUser = intval($USER->GetID());

if(Loader::includeModule('sotbit.b2bcabinet') && $idUser > 0)
{
    $Items = new \Sotbit\B2BCabinet\Shop\BasketItems(array(
		'CAN_BUY' => 'Y',
		'DELAY' => 'N',
		'SUBSCRIBE' => 'N'
	), array(
		'width' => 70,
		'height' => 70,
		'resize' => BX_RESIZE_IMAGE_PROPORTIONAL,
        'noPhoto' => '/upload/no_photo_small.jpg'
	));
    ?>

    <div class="widget_content widget_links widget-pending">
        <span><?= $Items->getQnt() ?></span>
        <span><?= \Sotbit\B2bCabinet\Element::num2word(
                $Items->getQnt(),
                array(
                    Loc::getMessage('GD_SOTBIT_CABINET_BASKET_PRODUCTS_1'),
                    Loc::getMessage('GD_SOTBIT_CABINET_BASKET_PRODUCTS_2'),
                    Loc::getMessage('GD_SOTBIT_CABINET_BASKET_PRODUCTS_3')
                ));
            ?></span>
        <span><?= $Items->getSum() ?></span>
        <div class="widget-pending-goods">
            <? foreach ($Items->getItems() as $item)
            {
                $img = $item->getElement()->getImg();
                ?>
                <div class="block-cart-img"  style="background-image: url('<?=( !empty($img['src']) ? $img['src'] : '' )?>');"></div>
            <? } ?>
        </div>
    </div>
	<?php
	if($arParams['G_BASKET_PATH_TO_BASKET'])
	{
		?>
		<div class="widget_content widget_links_btns">
			<a href="<?= $arParams['G_BASKET_PATH_TO_BASKET'] ?>">
				<?= Loc::getMessage('GD_SOTBIT_CABINET_BASKET_MORE') ?>
			</a>
		</div>
		<?
	}
}
?>
