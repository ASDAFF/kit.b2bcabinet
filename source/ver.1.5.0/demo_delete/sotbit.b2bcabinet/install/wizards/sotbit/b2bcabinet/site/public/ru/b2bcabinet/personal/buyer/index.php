<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

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
    $APPLICATION->SetTitle(Loc::getMessage('ORGANIZATIONS'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage("PERSONAL_DATA_ORGANIZATION") . '</span> - ');

    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.profile",
        "b2bcabinet",
        array(
            "PER_PAGE" => "20",
            "SEF_MODE" => "Y",
            "SET_TITLE" => "N",
            "USE_AJAX_LOCATIONS" => "N",
            "COMPONENT_TEMPLATE" => "b2bcabinet",
            "SEF_FOLDER" => SITE_DIR . "b2bcabinet/personal/buyer/",
            "SEF_URL_TEMPLATES" => array(
                "list" => "profile_list.php",
                "detail" => "profile_detail.php?ID=#ID#",
            ),
            "VARIABLE_ALIASES" => array(
                "detail" => array(
                    "ID" => "ID",
                ),
            ),
            "BUYER_PERSONAL_TYPE" => unserialize(COption::GetOptionString("sotbit.b2bcabinet","BUYER_PERSONAL_TYPE","a:0:{}",
                SITE_ID)),
        ),
        false
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>