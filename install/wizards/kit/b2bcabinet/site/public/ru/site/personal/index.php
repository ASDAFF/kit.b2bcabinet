<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(!Loader::includeModule('kit.b2bcabinet'))
{
    LocalRedirect(is_dir($_SERVER["DOCUMENT_ROOT"].'/b2bcabinet/') ? SITE_DIR.'b2bcabinet/' : SITE_DIR);
}

if(!$USER->IsAuthorized())
{
    $APPLICATION->AuthForm('', false, false, 'N', false);
}
else
{
    $APPLICATION->SetTitle(Loc::getMessage('B2B_CABINET_PERSONAL_PERSONAL_INFO'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage('B2B_CABINET_PERSONAL_PERSONAL_DATA') . '</span> - ');
    $APPLICATION->AddChainItem(Loc::getMessage('B2B_CABINET_PERSONAL_PERSONAL_INFO'));

    $APPLICATION->IncludeComponent(
        "bitrix:main.profile",
        "b2b_personal_data",
        array(
            "SET_TITLE" => "Y",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "USER_PROPERTY" => array(),
            "SEND_INFO" => "N",
            "CHECK_RIGHTS" => "N",
            "USER_PROPERTY_NAME" => "",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPONENT_TEMPLATE" => "b2b_personal_data",
            "BUYER_PERSONAL_TYPE" => unserialize(COption::GetOptionString("kit.b2bcabinet","BUYER_PERSONAL_TYPE","a:0:{}",
                SITE_ID)),
            "USER_PROPERTY_GENERAL_DATA" => array(
                0 => "TITLE",
                1 => "NAME",
                2 => "LAST_NAME",
                3 => "SECOND_NAME",
                4 => "EMAIL",
            ),
        ),
        false
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>