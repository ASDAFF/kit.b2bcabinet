<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

?>

<div>
    <!-- <h4>
		<? /*=GetMessage("SOA_TEMPL_BUYER_INFO")*/ ?>
	</h4> -->
    <div class="card-header header-elements-inline checkout_form-title_inner">
        <h6 class="card-title"><span><?= (!empty($arResult['arForSecondColumn'])
                    ? GetMessage("SOA_TEMPL_PAY_SYSTEM_BLOCK_TITLE") : GetMessage("SOA_TEMPL_BUYER_INFO")) ?></span>
        </h6>
    </div>

    <div id="sale_order_props">
        <?
        if (is_array($arParams['BUYER_PERSONAL_TYPE'])
            && in_array($arResult['ORDER_DATA']['PERSON_TYPE_ID'], $arParams['BUYER_PERSONAL_TYPE'])
            && is_array($arResult["ORDER_PROP"]["USER_PROFILES"])
            && sizeof($arResult["ORDER_PROP"]["USER_PROFILES"]) > 0
        ):

            ?>
            <?
        endif;

        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"],
            $arResult['arForMainColumn']);
        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"],
            $arResult['arForMainColumn']);

        ?>
    </div>
</div>
<div>
    <div class="card-header header-elements-inline checkout_form-title_inner">
        <h6 class="card-title"><span><?= (!empty($arResult['arForSecondColumn']) ? GetMessage("SOA_TEMPL_BUYER_INFO")
                    : GetMessage("SOA_TEMPL_TITLE_COMMENTS")) ?></span></h6>
    </div>
    <?
    if ($arResult['arForSecondColumn']) {
        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"],
            $arResult['arForSecondColumn']);
        PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"],
            $arResult['arForSecondColumn']);
    }
    ?>

    <div class="form-group row">
        <? if (!empty($arResult['arForSecondColumn'])): ?><label
                class="col-lg-3 col-form-label"><?= GetMessage("SOA_TEMPL_SUM_COMMENTS") ?></label><? endif; ?>
        <div class="col-lg-<?= (!empty($arResult['arForSecondColumn']) ? 9 : 12) ?>">
            <textarea rows="5" cols="5" class="form-control" name="ORDER_DESCRIPTION"
                      id="ORDER_DESCRIPTION"><?= $arResult["USER_VALS"]["ORDER_DESCRIPTION"] ?></textarea>
            <input type="hidden" name="" value="">


            <?
            if(is_array($arResult["ORDER_PROP"]["USER_PROPS_N"]))
            {
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $arProperties)
                {
                    if ($arProperties['CODE'] == 'CONFIDENTIAL') {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label for="<?= $arProperties["FIELD_NAME"] ?>" class="form-check-label">
                                            <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="1">
                                            <input required type="checkbox"
                                                   class="form-input-styled"
                                                   checked
                                                   data-fouc
                                                   name="<?= $arProperties["FIELD_NAME"] ?>"
                                                   id="<?= $arProperties["FIELD_NAME"] ?>" value="Y">
                                            <?= Loc::getMessage('SOA_TEMPL_SALE_CONFIDENTIAL') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }

            if(is_array($arResult["ORDER_PROP"]["USER_PROPS_N"]))
            {
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties)
                {
                    if ($arProperties['CODE'] == 'CONFIDENTIAL')
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label for="<?= $arProperties["FIELD_NAME"] ?>" class="form-check-label">
                                            <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="1">
                                            <input required type="checkbox"
                                                   class="form-input-styled"
                                                   checked
                                                   data-fouc
                                                   name="<?= $arProperties["FIELD_NAME"] ?>"
                                                   id="<?= $arProperties["FIELD_NAME"] ?>" value="Y">
                                            <?= Loc::getMessage('SOA_TEMPL_SALE_CONFIDENTIAL') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            } ?>


        </div>
    </div>
</div>

<? if (!CSaleLocation::isLocationProEnabled()): ?>
    <div style="display:none;">

        <? $APPLICATION->IncludeComponent(
            "bitrix:sale.ajax.locations",
            $arParams["TEMPLATE_LOCATION"],
            [
                "AJAX_CALL"          => "N",
                "COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
                "REGION_INPUT_NAME"  => "REGION_tmp",
                "CITY_INPUT_NAME"    => "tmp",
                "CITY_OUT_LOCATION"  => "Y",
                "LOCATION_VALUE"     => "",
                "ONCITYCHANGE"       => "submitForm()",
            ],
            null,
            ['HIDE_ICONS' => 'Y']
        ); ?>

    </div>
<? endif ?>
