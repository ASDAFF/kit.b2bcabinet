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
		<div class="bx-sap person_account_content" id="bx-sap<?=$wrapperId?>">
			<div class="sale-acountpay-block">
				<span class="sale-acountpay-title">
					  <?= Loc::getMessage("SAP_BUY_MONEY") ?>
				</span>
				<div class="bx-selected">
					<div class="sale-acountpay-pp-company-wrapper sale-acountpay-pp">
						<?
						foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem)
						{
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
						}
						?>
					</div>
				</div>
			</div>
			<div style="clear:both"></div>
			<div class="sale-acountpay-pay">
				<div class="form-group" style="margin-bottom: 0;">
					<span><?=Loc::getMessage("SAP_SUM")?></span>
					<input type="text" placeholder="0.00" class="form-control input-lg sale-acountpay-input" value="" name="buyMoney">
					<label class="control-label input-lg input-lg"><?=$arResult['CURRENCY']?></label>
				</div>
				<div class="pay-button">
					<span class="btn_add_basket btn btn-default btn-lg sale-account-pay-button"><?=Loc::getMessage("SAP_BUTTON")?></span>
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
