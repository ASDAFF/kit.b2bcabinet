<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="form-check form-check-inline disabled">
    <label class="form-check-label">
        <input
            <?=(!empty($arParams['ID']) ? "id='".$arParams['ID']."'" : "")?>
            type='radio'
            class="form-input-styled <?=(!empty($arParams['CLASS']) ? $arParams['CLASS'] : "")?>"
            <?=(!empty($arParams['NAME']) ? "name='".$arParams['NAME']."'" : "")?>
            <?if(is_array($arParams['ATTR']) && !empty($arParams['ATTR'])) {
                foreach ($arParams['ATTR'] as $attr) {
                    if(!empty($attr))
                        echo $attr ." ";
                }
            }?>
            <?=(!empty($arParams['JS_ACTION']) ? $arParams['JS_ACTION']['NAME']."='".$arParams['JS_ACTION']['FUNCTION']."'" : "")?>
            <?=(!empty($arParams['VALUE']) ? "value='".$arParams['VALUE']."'" : "")?>
        >
        <?=(!empty($arParams['R_BUTTOM_CONTENT']) ? $arParams['R_BUTTOM_CONTENT'] : "")?>
    </label>
</div>