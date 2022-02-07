<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<textarea
    <?=(!empty($arParams['NAME']) ? "name='".$arParams['NAME']."'" : "")?>
    <?=(!empty($arParams['ID']) ? "id='".$arParams['ID']."'" : "")?>
    <?=(!empty($arParams['CLASS']) ? "class='".$arParams['CLASS']."'" : "")?>
    <?if(is_array($arParams['ATTR']) && !empty($arParams['ATTR'])) {
        foreach ($arParams['ATTR'] as $attr) {
            if(!empty($attr))
                echo $attr ." ";
       }
    }?>
    <?=(!empty($arParams['PLACEHOLDER']) ? "placeholder='".$arParams['PLACEHOLDER']."'" : "")?>
    <?=(!empty($arParams['JS_ACTION']) ? $arParams['JS_ACTION']['NAME']."='".$arParams['JS_ACTION']['FUNCTION']."'" : "")?>
><?=(!empty($arParams['VALUE']) ? $arParams['VALUE'] : "")?></textarea>