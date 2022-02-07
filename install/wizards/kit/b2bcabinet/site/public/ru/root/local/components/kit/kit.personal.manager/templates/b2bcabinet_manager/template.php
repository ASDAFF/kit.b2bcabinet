<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
?>

<li class="nav-item-header">
    <div class="text-uppercase font-size-xs line-height-xs">
        <?=Loc::getMessage('PERSONAL_MANAGER_TITLE')?>
    </div>
    <i class="icon-menu" title="Forms"></i>
</li>

<li class="nav-item modal_manager_icon">
    <div class="nav-link">
        <div data-toggle="modal" data-target="#modal_manager" >
            <i class="icon-user-tie"></i>
        </div>
    </div>
</li>

<li class="nav-item your_manager">
    <div class="your_manager-icon_wrapper">
        <div class="your_manager-icon"
             style="background-image: url(<?=!empty($arResult['PERSONAL_PHOTO']['src']) ? $arResult['PERSONAL_PHOTO']['src'] : '/local/templates/b2bcabinet/assets/images/placeholders/user.gif'?>);"
        >
            <i class="icon-user-tie"></i>
        </div>
    </div>
    <div class="your_manager-icon_title">
        <span><?=Loc::getMessage('PERSONAL_MANAGER_TYPE')?></span>
    </div>
    <div class="your_manager-title">
        <span><?=$arResult['NAME']?></span>
    </div>
    <?if(!empty($arResult['WORK_PHONE'])):?>
    <div class="your_manager-contact">
        <span><?=Loc::getMessage('PERSONAL_MANAGER_PHONE')?></span>
        <span class="your_manager-phone_number"><?=$arResult['WORK_PHONE']?></span>
    </div>
    <?endif;?>
    <div class="your_manager-call_back_button">
        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal_manager" >
            <?=Loc::getMessage('PERSONAL_MANAGER_REQUEST_CALL')?>
        </button>
    </div>
</li>