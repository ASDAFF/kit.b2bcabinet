<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<!-- birthday picker -->
<div class="input-group">
    <span class="input-group-prepend">
        <span class="input-group-text">
            <i class="icon-calendar5"></i>
        </span>
    </span>

    <input type="text" class="form-control" id="anytime-month-numeric" <?=( !empty($arParams['CLASS']) ? $arParams['CLASS'] : '' )?>"
    <?=( !empty($arParams['VALUE']) ? "value='".$arParams['VALUE']."'" : "" )?>
    <?=( !empty($arParams['ID']) ? "id='".$arParams['ID']."'" : "" )?>
    <?=( !empty($arParams['INPUT_NAME']) ? "name='".$arParams['INPUT_NAME']."'" : "" )?>
    <?=( !empty($arParams['PLACEHOLDER']) ? "placeholder='". $arParams['PLACEHOLDER'] ."&hellip;'" : "" )?>
    >
</div>