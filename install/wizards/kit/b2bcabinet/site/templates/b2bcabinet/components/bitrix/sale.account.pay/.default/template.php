<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

// arr_key == pay_system_id
$styleClassesForPaySystemIcons = [
    "1" => "icon-cash4",
    "4" => "icon-credit-card",
    "10" => "icon-file-text2",
];

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
		<div class="bx-sap blank_personal" id="bx-sap<?=$wrapperId?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title"><?=Loc::getMessage("SAP_MAIN_DATA")?></h5>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="#">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label"><?=Loc::getMessage('SAP_STATE_OF_INVOICE', array(
                                            '#DATE#' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
                                        ))?></label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control blank_invoices-amount"
                                               readonly
                                               disabled
                                               placeholder="<?= (!empty($arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET']) ? $arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET'] : Loc::getMessage('SAP_WALLET_IS_EMPTY') ) ?>">
                                    </div>
                                </div>
                                <?
                                if ($arParams['SELL_VALUES_FROM_VAR'] != 'Y')
                                {
                                    if ($arParams['SELL_SHOW_FIXED_VALUES'] === 'Y')
                                    {
                                        ?>
                                        <div class="form-group row js-fixedpay-container">
                                            <label class="col-lg-3 col-form-label"><?= Loc::getMessage("SAP_FIXED_PAYMENT") ?></label>
                                            <div class="col-lg-9">
                                                <?
                                                foreach ($arParams["SELL_TOTAL"] as $valueChanging) {
                                                    ?>
                                                    <button type="button" class="btn btn-light js-fixedpay-item">
                                                        <?=CUtil::JSEscape(htmlspecialcharsbx($valueChanging))?>
                                                    </button>
                                                    <?
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label"><?=Loc::getMessage("SAP_SUM")?></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control blank_invoices-amount js-sale-accountpay-input"
                                                       placeholder="0, 00"
                                                       name="<?=$arParams["VAR"]?>"
                                                        <?=( $arParams['SELL_USER_INPUT'] === 'N' ? "disabled" :"" )?>
                                                >
                                            </div>
                                        </div>
                                <?
                                }
                                else
                                {
                                    if ($arParams['SELL_SHOW_RESULT_SUM'] === 'Y')
                                    {
                                        ?>
                                        <h3 class="sale-acountpay-title"><?=Loc::getMessage("SAP_SUM")?></h3>
                                        <h2><?=SaleFormatCurrency($arResult["SELL_VAR_PRICE_VALUE"], $arParams['SELL_CURRENCY'])?></h2>
                                        <?
                                    }
                                    ?>
                                    <div class="row">
                                        <input type="hidden" name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>"
                                            class="form-control input-lg sale-acountpay-input"
                                            value="<?=CUtil::JSEscape(htmlspecialcharsbx($arResult["SELL_VAR_PRICE_VALUE"]))?>">
                                    </div>
                                    <?
                                }
                                ?>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">
                                        <?=Loc::getMessage("SAP_TYPE_PAYMENT_TITLE")?>
                                    </label>
                                    <div class="col-lg-9">
                                        <div class="card-body blank_invoices-payment_method">
                                            <ul class="nav nav-pills nav-pills-bordered flex-column">
                                                <?
                                                foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem) {
                                                    ?>
                                                    <li class="nav-item">
                                                        <a href="#"
                                                           class="nav-link blank_personal-payment_method-button <?= ($key == 0 ? 'active show' : '') ?>"
                                                           data-toggle="tab"
                                                           data-id="<?=$paySystem['ID']?>"
                                                        >
                                                            <span class="payment_method-icon">
                                                            <i class="<?= (!empty($styleClassesForPaySystemIcons[$paySystem['ID']]) ? $styleClassesForPaySystemIcons[$paySystem['ID']] : "") ?> mr-2"></i>
                                                                </span>
                                                            <?=$paySystem['NAME']?>
                                                        </a>
                                                    </li>
                                                    <?
                                                }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <button type="button" class="btn btn-light blank_invoices-pay_button">
                                        <?=Loc::getMessage("SAP_BUTTON")?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
	else
	{
		?>
		<h3><?=Loc::getMessage("SAP_BUY_MONEY")?></h3>
		<form method="post" name="buyMoney" action="">
			<?
			foreach($arResult["AMOUNT_TO_SHOW"] as $value)
			{
				?>
				<input type="radio" name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>"
					value="<?=$value["ID"]?>" id="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"])).$value["ID"]?>">
				<label for="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"])).$value["ID"]?>"><?=$value["NAME"]?></label>
				<br />
				<?
			}
			?>
			<input type="submit" name="button" value="<?=GetMessage("SAP_BUTTON")?>">
		</form>
		<?
	}
}

