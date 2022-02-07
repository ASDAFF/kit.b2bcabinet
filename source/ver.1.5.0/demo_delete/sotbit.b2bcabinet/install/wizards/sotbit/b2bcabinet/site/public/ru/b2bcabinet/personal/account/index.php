<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if(!Loader::includeModule('sotbit.b2bcabinet'))
{
    header('Location: '.SITE_DIR.'b2bcabinet/');
}

if(!$USER->IsAuthorized())
{
    $APPLICATION->AuthForm('', false, false, 'N', false);
}
else
{
    $APPLICATION->SetTitle(Loc::getMessage('PESONAL_TITLE'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage('PESONAL_DATA') . '</span> - ');

    $APPLICATION->IncludeComponent(
        "bitrix:sale.account.pay",
        "",
        Array(
            "COMPONENT_TEMPLATE" => ".default",
            "REFRESHED_COMPONENT_MODE" => "Y",
            "ELIMINATED_PAY_SYSTEMS" => array("0"),
            "PATH_TO_BASKET" => SITE_DIR . 'personal/cart/',
            "PATH_TO_PAYMENT" => SITE_DIR . 'personal/order/payment/',
            "PERSON_TYPE" => '1', //TODO PERSON TYPE??????
            "REDIRECT_TO_CURRENT_PAGE" => "N",
            "SELL_AMOUNT" => array("100", "200", "500", "1000", "5000", ""),
            "SELL_CURRENCY" => '',
            "SELL_SHOW_FIXED_VALUES" => 'Y',
            "SELL_SHOW_RESULT_SUM" => '',
            "SELL_TOTAL" => array("100", "200", "500", "1000", "5000", ""),
            "SELL_USER_INPUT" => 'Y',
            "SELL_VALUES_FROM_VAR" => "N",
            "SELL_VAR_PRICE_VALUE" => "",
            "SET_TITLE" => "N",
        ),
        false
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>