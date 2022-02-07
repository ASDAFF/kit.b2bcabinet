<?php
namespace Kit\B2bCabinet;

use Kit\B2bCabinet\Helper\Menu;

class EventHandlers {
    public function onBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu){
        Menu::getAdminMenu($arGlobalMenu, $arModuleMenu);
    }
    
    public function onPageStart() {
//        define("NEED_AUTH", true);
        global $APPLICATION;
        
        if(strpos($APPLICATION->GetCurPage(false), '/bitrix') !== false) {
            return;
        }
        
        if(!\Bitrix\Main\Loader::includeModule('kit.b2bcabinet')) {
            return;
        }
        
        $access_mode = \COption::GetOptionString( \KitB2bCabinet::MODULE_ID, 'OPT_ACCESS_GROUPS', false, SITE_ID );
        
        if($access_mode == "S") {
            define("NEED_AUTH", true);
        }
    }
}
?>