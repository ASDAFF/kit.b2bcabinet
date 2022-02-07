<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
foreach($arResult["ACCOUNT_LIST"] as $accountValue)
{
    ?>
    <div class="sale-personal-account-wallet-container">
        <span><?=Bitrix\Main\Localization\Loc::getMessage('SPA_BILL_AT')?> <?=$arResult["DATE"];?></span>
        <span><?=$accountValue['SUM']?></span>
    </div>
    <?
}
?>