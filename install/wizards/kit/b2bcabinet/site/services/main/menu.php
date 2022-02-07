<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

CModule::IncludeModule('fileman');
$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);

SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
COption::SetOptionInt("fileman", "num_menu_param", 2, false ,WIZARD_SITE_ID);


CModule::IncludeModule('fileman');
$arRes = array();

$menuTypes=array(
    'bottom'=>GetMessage("WIZ_MENU_bottom"),
    'b2bcabinet_menu'=>'b2bcabinet_menu',
    'b2bcabinet_menu_inner'=>'b2bcabinet_menu_inner'
);

$armt=array();
$armt = GetMenuTypes();

foreach($menuTypes as $key=>$name)
{
    if(!key_exists($key,$armt))
    {
        $tmp=array();
        $tmp[$key]=$name;
        $armt=array_merge($armt,$tmp);
    }
}

SetMenuTypes($armt,WIZARD_SITE_ID);
?>