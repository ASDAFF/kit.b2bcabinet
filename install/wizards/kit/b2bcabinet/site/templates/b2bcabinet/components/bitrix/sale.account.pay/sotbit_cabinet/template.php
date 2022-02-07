<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if (!empty($arResult["errorMessage"]))
{
	if (!is_array($arResult["errorMessage"]))
	{
		ShowError($arResult["errorMessage"]);
	}
	else
	{
		foreach ($arResult["errorMessage"] as $errorMessage)
		{
			ShowError($errorMessage);
		}
	}
}
else
{
	if ($arParams['REFRESHED_COMPONENT_MODE'] === 'Y')
	{
		$wrapperId = str_shuffle(substr($arResult['SIGNED_PARAMS'],0,10));
		?>
            <!--/widget header -->

            <div class="widget_content widget-private_invoices_header">
                <span><?=Loc::getMessage('SAP_STATE_OF_INVOICE', array(
                        '#DATE#' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
                    ))?></span><h4><b><?= (!empty($arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET']) ? $arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET'] : "0 ".$arResult['CURRENCY'] ) ?></b></h4>
            </div>

            <div class="widget_content widget-private_invoices_content">
                <span class="display_block"><?= Loc::getMessage("SAP_BUY_MONEY") ?></span>
                <div class="widget-private_invoices_icons">
                    <? foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem) {
                        ?>
                        <div class="sale-acountpay-pp-company <?= ($key == 0) ? 'bx-selected' :""?>">
                            <div class="sale-acountpay-pp-company-graf-container" title="<?=CUtil::JSEscape(htmlspecialcharsbx($paySystem['NAME']))?>">
                                <input type="checkbox"
                                       class="sale-acountpay-pp-company-checkbox"
                                       name="PAY_SYSTEM_ID"
                                       value="<?=$paySystem['ID']?>"
                                    <?= ($key == 0) ? "checked='checked'" :""?>
                                >
                                <?
                                if (isset($paySystem['LOGOTIP']))
                                {
                                    ?>
                                    <div class="sale-acountpay-pp-company-image"
                                         style="
                                                 background-image: url(<?=$paySystem['LOGOTIP']?>);
                                                 background-image: -webkit-image-set(url(<?=$paySystem['LOGOTIP']?>) 1x, url(<?=$paySystem['LOGOTIP']?>) 2x);">
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    } ?>
                </div>
                <span class="display_block widget-private_invoices-input_description"><?=Loc::getMessage("SAP_SUM")?>, <?=$arResult['CURRENCY']?></span>
                <div class="widget-private_invoices-input">
                    <input type="text" class="form-control" placeholder="0.00">
                    <div class="widget_button_wrapper">
                        <button type="button" class="btn btn-light widget_button">
                            <?=Loc::getMessage("SAP_BUTTON")?>
                        </button>
                    </div>
                </div>
            </div>
		<?
		$javascriptParams = array(
			"alertMessages" => array("wrongInput" => Loc::getMessage('SAP_ERROR_INPUT')),
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"signedParams" => $arResult['SIGNED_PARAMS'],
			"wrapperId" => $wrapperId
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			var sc = new BX.saleAccountPay(<?=$javascriptParams?>);
		</script>
	<?
	}
}
?>