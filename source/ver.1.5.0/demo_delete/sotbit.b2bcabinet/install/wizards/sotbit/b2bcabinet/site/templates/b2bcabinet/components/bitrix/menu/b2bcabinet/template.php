<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
    <ul class="nav nav-sidebar" data-nav-type="accordion">
        <?
        $previousLevel = 0;
//        $userType = $USER->isAdmin();
        foreach($arResult as $key => $arItem):
            ?>
            <?
            if($key === 'PERSONAL_MANAGER_ID')
            {
                continue;
            }
            ?>
            <?if ($arItem["IS_PARENT"] || $arItem["PARAMS"]["IS_PARENT"]):?>
            <li class="nav-item-header">
                <div class="text-uppercase font-size-xs line-height-xs"><?=$arItem["TEXT"]?></div>
                <i class="icon-menu" title="Forms"></i>
            </li>
        <?else:?>
            <?if ($arItem["PERMISSION"] > "D"):?>
                <li class="nav-item">
                    <a href="<?=$arItem["LINK"]?>" class="nav-link<?if($arItem["SELECTED"] == true):?> active<?endif?>">
                        <? if(isset($arItem['PARAMS']['ICON_CLASS'])): ?>
                            <i class="<?=$arItem['PARAMS']['ICON_CLASS']?>"></i>
                        <?else:?>
                                <i class="icon-menu6"></i>
                        <?endif;?>
                        <span><?=$arItem["TEXT"]?></span>
                    </a>
                </li>
            <?endif?>
        <?endif?>
            <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
        <?endforeach?>
        <?
        if(!empty($arResult['PERSONAL_MANAGER_ID']))
        {
            $APPLICATION->IncludeComponent(
                "sotbit:sotbit.personal.manager",
                "b2bcabinet_manager",
                array(
                    "MANAGER_ID" => $arResult['PERSONAL_MANAGER_ID'],
                    "COMPONENT_TEMPLATE" => "b2bcabinet_manager",
                    "SHOW_FIELDS" => array(
                        0 => "NAME",
                        1 => "PERSONAL_PHOTO",
                        2 => "WORK_PHONE",
                    ),
                    "USER_PROPERTY" => array(
                        0 => "UF_ORGANIZATION",
                        1 => "UF_P_MANAGER_ID",
                    )
                ),
                false
            );
        }
        ?>
    </ul>
<?endif?>
