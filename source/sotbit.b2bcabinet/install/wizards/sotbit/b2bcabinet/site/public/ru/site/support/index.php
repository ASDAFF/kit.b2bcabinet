<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(!Loader::includeModule('sotbit.b2bcabinet'))
{
    header('Location: '.SITE_DIR);
}

if(!$USER->IsAuthorized())
{
    $APPLICATION->AuthForm('', false, false, 'N', false);
}
else
{
    $APPLICATION->SetTitle(Loc::getMessage('SUPPORT_LIST'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage('TECH_SUPPORT') . '</span> - ');
    $APPLICATION->AddChainItem(Loc::getMessage('SUPPORT_LIST'));

    $APPLICATION->IncludeComponent(
        "bitrix:support.wizard",
        "",
        Array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "IBLOCK_ID" => 0,
            "IBLOCK_TYPE" => "-",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
            "MESSAGES_PER_PAGE" => "20",
            "MESSAGE_MAX_LENGTH" => "70",
            "MESSAGE_SORT_ORDER" => "asc",
            "PROPERTY_FIELD_TYPE" => "",
            "PROPERTY_FIELD_VALUES" => "",
            "SECTIONS_TO_CATEGORIES" => "N",
            "SET_PAGE_TITLE" => "Y",
            "SET_SHOW_USER_FIELD" => array(),
            "SHOW_COUPON_FIELD" => "N",
            "SHOW_RESULT" => "Y",
            "TEMPLATE_TYPE" => "",
            "TICKETS_PER_PAGE" => "50",
            "VARIABLE_ALIASES_ID" => "ID"
        )
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>