<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$switchId = microtime(true);
$switchId = preg_replace('/\./', '_', $switchId);
?>
<div class="form-group row">
    <?if(!empty($arParams['LABEL'])):?>
        <label class="col-lg-3 col-form-label">
            <?=$arParams['LABEL']?>
            <?if(in_array('required', $arParams['ATTR'])) {
                echo '<span class="req">*</span>';
            }?>
        </label>
    <?endif;?>
    <div class="form-check form-check-switchery">
        <label class="form-check-label">
            <input
                    type="checkbox"
                    class="form-check-input-switchery inp-switch-<?=$switchId?>"
                <?if(is_array($arParams['ATTR']) && !empty($arParams['ATTR'])) {
                    foreach ($arParams['ATTR'] as $attr) {
                        if(!empty($attr))
                            echo $attr ." ";
                    }
                }?>
            >
            <input
                    type="hidden"
                    class="checkbox-switchery-data inp-switch-data-<?=$switchId?>"
                <?=(!empty($arParams['NAME']) ? "name='".$arParams['NAME']."'" : "")?>
                <?=(!empty($arParams['VALUE']) ? "value='".$arParams['VALUE']."'" : "")?>
            >
        </label>
    </div>
</div>

<script>
    var changeCheckbox = document.querySelector('.inp-switch-<?=$switchId?>');


    changeCheckbox.onchange = function() {
        var checkboxValue = document.querySelector('.inp-switch-data-<?=$switchId?>');

        if(this.checked)
            checkboxValue.value = 'Y';
        else
            checkboxValue.value = 'N';
    };
</script>