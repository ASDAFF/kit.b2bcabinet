<?
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
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
    $APPLICATION->SetTitle(Loc::getMessage('ADD_ORGANIZATION'));
    $APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">' . Loc::getMessage('PERSONAL_DATA_ORGANIZATION') . '</span> - ');

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/uniform.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/selects/select2.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/extensions/jquery_ui/interactions.min.js");

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/components_dropdowns.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_select2.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/uniform_init.js");

    //Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_checkboxes_radios.js");
    //Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_inputs.js");

    $needProfiles = unserialize(Option::get('sotbit.b2bcabinet', 'BUYER_PERSONAL_TYPE', ''));
    if (!is_array($needProfiles)) {
        $needProfiles = [];
    }

    $APPLICATION->IncludeComponent(
        "sotbit:sale.profile.add",
        "b2bcabinet",
        Array(
            "COMPATIBLE_LOCATION_MODE" => "N",
            "PATH_TO_LIST" => SITE_DIR . "b2bcabinet/personal/buyer/",
            "SET_TITLE" => "N",
            "USE_AJAX_LOCATIONS" => "Y",
            "PERSONAL_TYPES" => $needProfiles
        )
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>