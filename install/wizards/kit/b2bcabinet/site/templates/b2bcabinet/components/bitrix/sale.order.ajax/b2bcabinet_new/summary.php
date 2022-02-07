<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column

?>
<!-- summary info1-->
<div class="index_checkout-table_total">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th rowspan="2" class="text-center"><h4><?=GetMessage("SOA_TEMPL_SUMMARY_TITLE")?></h4></th>

                <th class="text-center"><?=GetMessage("SOA_TEMPL_SUMMARY_COUNT")?></th>
                <th class="text-center"><?=GetMessage("SOA_TEMPL_SUMMARY_SUM")?></th>
                <?if(doubleval($arResult["DISCOUNT_PRICE"]) > 0):?><th class="text-center"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?></th><?endif;?>
                <?if(!empty($arResult['TOTAL_VAT'])):?><th class="text-center"><?=GetMessage("SOA_TEMPL_SUMMARY_VAT")?></th><?endif;?>
                <th class="text-center"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT")?></th>
                <th class="text-center"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY_NOT_PIG")?></th>
            </tr>
            <tr>
                <td class="text-center"><?=$arResult["TOTAL_QUANTITY"]?></td>
                <td class="text-center"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
                <?if(doubleval($arResult["DISCOUNT_PRICE"]) > 0):?><td class="text-center"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?></td><?endif;?>
                <?if(!empty($arResult['TOTAL_VAT'])):?><td class="text-center"><?=$arResult["TOTAL_VAT"]?></td><?endif;?>
                <td class="text-center"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
                <td class="text-center"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
            </tr>
        </table>
    </div>

    <div class="index_checkout-total">
        <div class="index_checkout-total_title"><span><?=GetMessage("SOA_TEMPL_SUM_IT")?></span>
            <h3><span
                        class="index_checkout-promocode-total_amount"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></span>
            </h3>
        </div>
        <div class="form-group row">
            <button type="button"
                    class="btn btn-light index_checkout-formalize_button"
                    onclick="submitForm('Y'); return false;"
                    id="ORDER_CONFIRM_BUTTON"
            >
                <?=GetMessage("SOA_TEMPL_BUTTON")?>
            </button>
        </div>
    </div>
</div>
