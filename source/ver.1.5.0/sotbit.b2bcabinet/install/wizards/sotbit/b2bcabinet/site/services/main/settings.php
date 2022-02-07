<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

$module = 'sotbit.b2bcabinet';

Loader::IncludeModule('sotbit.b2bcabinet');
Loader::IncludeModule('fileman');

Option::set("sale", "SHOP_SITE_".WIZARD_SITE_ID, WIZARD_SITE_ID);

Option::set("fileman", "propstypes",
    serialize(["description"    => GetMessage("MAIN_OPT_DESCRIPTION"), "keywords" => GetMessage("MAIN_OPT_KEYWORDS"), "title" => GetMessage("MAIN_OPT_TITLE"),
            "keywords_inner" => GetMessage("MAIN_OPT_KEYWORDS_INNER")]
    ), false, $siteID
);
Option::set("search", "suggest_save_days", 250);
Option::set("search", "use_tf_cache", "Y");
Option::set("search", "use_word_distance", "Y");
Option::set("search", "use_social_rating", "Y");
Option::set("iblock", "use_htmledit", "Y");
Option::set("main", "captcha_registration", "N");
Option::set("main", "optimize_css_files", "Y");
Option::set("main", "optimize_js_files", "Y");
Option::set("main", "use_minified_assets", "Y");
Option::set("main", "move_js_to_body", "Y");
Option::set("main", "compres_css_js_files", "Y");
$subscribes = unserialize(Option::get('sale', 'subscribe_prod'));
if(is_array($subscribes)) {
    $subscribes[WIZARD_SITE_ID] = ['use' => 'Y', 'del_after' => 100];
    Option::set("sale", "subscribe_prod", serialize($subscribes));
}

if(file_exists(WIZARD_ABSOLUTE_PATH ."/images/test_manager.png"))
{
    $managerPhoto = CFile::MakeFileArray(WIZARD_ABSOLUTE_PATH . "/images/test_manager.png");
    $managerPhoto['MODULE_ID'] = 'main';
}

$rndPassword = md5(time() * rand(1, time()));

$arFields = array(
    "NAME"              => Loc::getMessage('SETTINGS_B2B_MANAGER_FIRST_NAME'),
    "LAST_NAME"         => Loc::getMessage('SETTINGS_B2B_MANAGER_SECOND_NAME'),
    "EMAIL"             => "manager@mail.ru",
    "LOGIN"             => "manager",
    "LID"               => "ru",
    "ACTIVE"            => "Y",
    "GROUP_ID"          => array(3,6,4),
    "PASSWORD"          => $rndPassword,
    "CONFIRM_PASSWORD"  => $rndPassword,
    "PERSONAL_PHOTO"    => $managerPhoto,
    "WORK_PHONE"        => "+7 (795) 111-11-11"
);

$manager = new CUser();
$managerID = $manager->Add($arFields);

unset($arFields);
unset($manager);

if(file_exists(WIZARD_ABSOLUTE_PATH ."/images/test_user.jpg"))
{
    $testUserPhoto = CFile::MakeFileArray(WIZARD_ABSOLUTE_PATH . "/images/test_user.jpg");
    $testUserPhoto['MODULE_ID'] = 'main';
}

$arFields = array(
    "NAME"              => Loc::getMessage('SETTINGS_B2B_USER_FIRST_NAME'),
    "LAST_NAME"         => Loc::getMessage('SETTINGS_B2B_USER_SECOND_NAME'),
    "EMAIL"             => "b2b@sotbit.ru",
    "LOGIN"             => "b2b@sotbit.ru",
    "LID"               => "ru",
    "ACTIVE"            => "Y",
    "GROUP_ID"          => array(3, 6, 4, 9, 10),
    "PASSWORD"          => '123456',
    "CONFIRM_PASSWORD"  => '123456',
    "PERSONAL_PHOTO"    => $testUserPhoto,
    "PERSONAL_CITY"    => Loc::getMessage('SETTINGS_B2B_USER_PERSONAL_CITY'),
    "PERSONAL_ZIP"    => '101000',
    "WORK_PHONE"        => "+7 (495) 111-01-54",
    "UF_P_MANAGER_ID" => $managerID
);
$testUser = new CUser();
$testUserId = $testUser->Add($arFields);

$agreement = new \Bitrix\Main\UserConsent\Agreement('');
$agreement->setData([
    'NAME' => Loc::getMessage('SETTINGS_B2B_AGREEMENT_NAME'),
    'TYPE' => 'C',
    'ACTIVE' => 'Y',
    'AGREEMENT_TEXT' => Loc::getMessage('SETTINGS_B2B_AGREEMENT_TEXT'),
    'LABEL_TEXT' => Loc::getMessage('SETTINGS_B2B_AGREEMENT_LABEL'),
    'FIELDS' => [
        'DEMO' => 'b2b@sotbit.ru'
    ]
]);
$agreement->save();
if(!$agreement->hasErrors()) {
    Option::set($module,'AGREEMENT_ID',$agreement->getId(), WIZARD_SITE_ID);
}

if(!empty($managerID))
    Option::set($module,'MANAGER_ID',$managerID, WIZARD_SITE_ID);
if(!empty($testUserId))
    Option::set($module,'TEST_USER_ID',$testUserId, WIZARD_SITE_ID);
?>