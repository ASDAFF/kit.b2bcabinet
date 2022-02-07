<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
$idUser = intval($USER->GetID());
if(Loader::includeModule('kit.b2bcabinet') && Loader::includeModule('sale') && $idUser > 0) {
    $limit = 2;
    if($arParams['GU_ORDERS_LIMIT'] > 0) {
        $limit = $arParams['GU_ORDERS_LIMIT'];
    }
    if($arGadgetParams['LIMIT'] > 0) {
        $limit = $arGadgetParams['LIMIT'];
    }
    $listOrders = new \Kit\B2BCabinet\Shop\OrderCollection();
    $listOrders->setLimit($limit);
    $filter = ["USER_ID" => $idUser, "LID" => SITE_ID];
    if($arParams['STATUS'] && $arParams['STATUS'] != 'ALL') {
        $filter['STATUS_ID'] = $arParams['STATUS'];
    }
    if($arGadgetParams['STATUS'] && $arGadgetParams['STATUS'] != 'ALL') {
        $filter['STATUS_ID'] = $arGadgetParams['STATUS'];
    }
    $orders = $listOrders->getOrders($filter);
    foreach($orders as $order) {
        ?>
        <div class="widget_content widget_links orders">
            <div class="widget_order_content">

                <div class="widget_order_header">
                    <a href="<?=$order->getUrl($arParams['G_ORDERS_PATH_TO_ORDER_DETAIL'])?>" title="<?=Loc::getMessage('GD_KIT_CABINET_ORDER_ORDER')?> <?=$order->getId()?>">
                        <h6><?=Loc::getMessage('GD_KIT_CABINET_ORDER_ORDER')?> <?=$order->getId()?></h6> <i class="icon-arrow-right13 mr-2"></i>
                    </a>
                </div>
                <div class="widget_order_information">
                    <span><?=Loc::getMessage('GD_KIT_CABINET_ORDER_FROM')?> <?=$order->getDate()->format("d.m.Y")?></span>
                    <span><?=Loc::getMessage('GD_KIT_CABINET_ORDER_SUM')?>:  <?=$order->getPrice()?></span>
                </div>
            </div>
            <div class="widget_button_wrapper">
                <button type="button" class="btn btn-light widget_button">
                    <?=reset($order->getStatus())?>
                </button>
            </div>
        </div>
        <?
    }
    if($arParams['G_ORDERS_PATH_TO_ORDERS']) {
        ?>
        <div class="b2b-gadget b2b-gadget-orders">
            <div class="b2b-gadget-profile__link">
                <a href="<?php echo $arParams['G_ORDERS_PATH_TO_ORDERS']; ?>">
                    <?=Loc::getMessage('GD_KIT_CABINET_ORDER_HISTORY')?>
                </a>
            </div>
        </div>
        <?php
    }
}
?>
