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


	Asset::getInstance()->addJs($arGadget['PATH_SITEROOT'].'/script.js');
//	Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
	$idUser = intval($USER->GetID());

	if(Loader::includeModule('kit.b2bcabinet') && Loader::includeModule('sale') && $idUser > 0) {
		if($deleteElementId > 0) {
            $buyer = new Kit\B2bCabinet\Personal\Buyer();
            $buyer->setId($deleteElementId);
            $buyer->delete($idUser);
            LocalRedirect();
		}

        $buyers = new \Kit\B2bCabinet\Personal\Buyers();
		$listBuyers = $buyers->findBuyersForUser($idUser);

		foreach ($listBuyers as $idBuyer => $buyer) { ?>
            <div class="widget_content widget_links widget-organizations-content" id="buyer-<?=$idBuyer?>">
                <a href="#"><?=$buyer->getName()?></a>
                <div class="widget-organizations-change_icons">
                    <a href="<?=$buyer->genEditUrl($arParams['G_BUYERS_PATH_TO_BUYER_DETAIL'])?>">
                        <i class="icon-arrow-right13 mr-2"></i>
                    </a>
                </div>
            </div>
            <?
        }
    ?>
    <div class="widget_content widget_links_btns">
        <?
            $url = explode('?', $arParams['G_BUYERS_PATH_TO_BUYER_DETAIL']);
        ?>
        <a href="<?=($url[0]) ? $url[0] : '#'?>">
            <?= Loc::getMessage('GD_KIT_CABINET_BUYERS_EDIT') ?>
        </a>
    </div>
    <?php
}
?>