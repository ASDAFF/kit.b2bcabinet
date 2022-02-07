<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <input
        <?=(!empty($arParams['NAME']) ? "name='".$arParams['NAME']."'" : "")?>
        <?=(!empty($arParams['ID']) ? "id='".$arParams['ID']."'" : "")?>
        <?=(!empty($arParams['TYPE']) ? "type='".$arParams['TYPE']."'" : "")?>
        <?=(!empty($arParams['CLASS']) ? "class='".$arParams['CLASS']."'" : "")?>
        <?if(is_array($arParams['ATTR']) && !empty($arParams['ATTR'])) {
            foreach ($arParams['ATTR'] as $attr) {
                if(!empty($attr))
                    echo $attr ." ";
           }
        }?>
        <?=(!empty($arParams['PLACEHOLDER']) ? "placeholder='".$arParams['PLACEHOLDER']."'" : "")?>
        <?=(!empty($arParams['VALUE']) ? "value='".$arParams['VALUE']."''" : "")?>
        <?=(!empty($arParams['JS_ACTION']) ? $arParams['JS_ACTION']['NAME']."='".$arParams['JS_ACTION']['FUNCTION']."'" : "")?>
    >