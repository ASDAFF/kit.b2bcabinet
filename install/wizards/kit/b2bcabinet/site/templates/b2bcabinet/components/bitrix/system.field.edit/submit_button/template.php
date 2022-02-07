<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input required type="checkbox" class="form-input-styled"
                           checked
                           data-fouc
                    >
                    <?=html_entity_decode(html_entity_decode($arParams['CONFIRM_LABEL']))?>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="text-left">
    <button
            type="submit"
            name="save"
            value="<?=$arParams['NAME']?>"
            class="btn btn_b2b <?=(!empty($arParams['CLASS']) ? $arParams['CLASS'] : "")?>"
    ><?=$arParams['NAME']?>
        <i class="icon-paperplane ml-2"></i>
    </button>
</div>