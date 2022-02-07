<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arParams['LABEL'])):?>
    <div
        class="blank_personal-title <?=(!empty($arParams['CLASS']) ? $arParams['CLASS'] : "")?>">
        <span><?=$arParams['LABEL']?></span>
    </div>
<?endif;?>