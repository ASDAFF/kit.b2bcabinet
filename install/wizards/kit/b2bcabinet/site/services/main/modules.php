<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
    return;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$moduleName = 'kit.b2bcabinet';

//START
if (!IsModuleInstalled("kit.b2bcabinet") && file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.b2bcabinet/"))
{
    $installFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.b2bcabinet/install/index.php";
    if (!file_exists($installFile))
        return false;

    include_once($installFile);

    $moduleIdTmp = str_replace(".", "_", "kit.b2bcabinet");
    if (!class_exists($moduleIdTmp))
        return false;

    $module = new $moduleIdTmp;

    $module->InstallEvents();
    $module->InstallFiles();
    $module->InstallDB();
    RegisterModule("kit.b2bcabinet");
}
//END

//START



$modulesThear = array(
//    'kit.regions',
    'kit.auth',
    'kit.bill',
    'kit.privateprice',
//    'sns.tools1c',
//    'kit.cabinet',
//    'kit.client',
    'kit.checkcompany',
);

if(IsModuleInstalled('kit.b2bshop') && (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.b2bshop/") || file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/kit/b2bshop/")) ) {
    $modulesB2bShop = array(
        'sns.tools1c',
        'shs.parser',
        'kit.mailing',
        'kit.seometa',
        'kit.postcalc',
        'kit.reviews',
        'kit.regions',
        'kit.opengraph',
        'kit.cabinet',
        'kit.bill',
        'kit.crosssell',
        'kit.crmtools',
        'kit.schemaorg',
        'kit.htmleditoraddition',
        'kit.orderphone',
        'kit.seosearch'
    );

    $modulesThear = array_merge($modulesThear,$modulesB2bShop);
}


//$modulesStrangers = array(
    //'asd.share',
    //'coffeediz.schema'
//);

if (!function_exists("installModuleHands")){

    function installModuleHands($module,$modulesThear) {

        $obModule = CModule::CreateModuleObject($module);
        if(!is_object($obModule)) {
            return false;
        }

        if(!$obModule->IsInstalled()) {

            /*
            if(in_array($module,array('asd.share'))){
                CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module."/install/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix/", true, true);
                RegisterModule($module);
                return true;
            }

            if(in_array($module,array('asd.mailtpl'))){
                $obModule->InstallDB();
                return true;
            }


            if(in_array($module,array('coffeediz.schema'))){
                CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module."/install/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/", true, true);
                RegisterModule($module);
                return true;
            }
            */
            if(in_array($module,$modulesThear)){
                $obModule->InstallFiles();
                $obModule->InstallDB();
                $obModule->InstallEvents();

                if(!$obModule->IsInstalled()) {
                    RegisterModule($module);
                }
                return true;
            }
        }

    }
}

//$modulesNeed =  array_merge($modulesThear,$modulesStrangers);
$modulesNeed = $modulesThear;
foreach($modulesNeed as $module)
{
    $modulesPathDir = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module."/";
    if(!file_exists($modulesPathDir))
    {
        $strError = '';
        CUpdateClientPartner::LoadModuleNoDemand($module,$strError,'Y',false);
    }

    $module_status = CModule::IncludeModuleEx($module);
    if($module_status==2 || $module_status==0 || $module_status==3)
    {
        installModuleHands($module,$modulesThear);
    }
}

//END

?>