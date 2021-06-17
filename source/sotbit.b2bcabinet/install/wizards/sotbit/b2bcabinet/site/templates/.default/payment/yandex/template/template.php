<?
	use Bitrix\Main\Localization\Loc;
//\Bitrix\Main\Page\Asset::getInstance()->addCss("/bitrix/themes/.default/sale.css");
	Loc::loadMessages(__FILE__);

	$sum = roundEx($params['PAYMENT_SHOULD_PAY'], 2);
?>
    <form name="ShopForm" action="<?=$params['URL'];?>" method="post">
        <input name="ShopID" value="<?=htmlspecialcharsbx($params['YANDEX_SHOP_ID']);?>" type="hidden">
        <input name="scid" value="<?=htmlspecialcharsbx($params['YANDEX_SCID']);?>" type="hidden">
        <input name="customerNumber" value="<?=htmlspecialcharsbx($params['PAYMENT_BUYER_ID']);?>" type="hidden">
        <input name="orderNumber" value="<?=htmlspecialcharsbx($params['PAYMENT_ID']);?>" type="hidden">
        <input name="Sum" value="<?=number_format($sum, 2, '.', '')?>" type="hidden">
        <input name="paymentType" value="<?=htmlspecialcharsbx($params['PS_MODE'])?>" type="hidden">
        <input name="cms_name" value="1C-Bitrix" type="hidden">
        <input name="BX_HANDLER" value="YANDEX" type="hidden">
        <input name="BX_PAYSYSTEM_CODE" value="<?=$params['BX_PAYSYSTEM_CODE']?>" type="hidden">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_PAYMENT_SHOULD_PAY')?><?=date('d.m.Y')?></h5>
        </div>
        <div class="card-body">
    
            <div class="form-group blank_personal_payment_description">
                <span><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_YANDEX_DESCRIPTION');?></span><span><?=SaleFormatCurrency($params['PAYMENT_SHOULD_PAY'], $payment->getField('CURRENCY'));?></span><br><br>
    
                <div class="text-left">
                    <input name="BuyButton"  type="submit" class="btn btn-light blank_invoices_payment-pay_button" value="<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_YANDEX_BUTTON_PAID')?>"/>
                </div>
                <br>
                <span><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_YANDEX_REDIRECT_MESS');?></span><br>
                <span>
                    <?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_YANDEX_WARNING_RETURN');?>
                </span><br>
            </div>
        </div>
    </form>