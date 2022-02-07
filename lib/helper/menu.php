<?php

namespace Kit\B2bCabinet\Helper;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu(&$arGlobalMenu, &$arModuleMenu) {
        $moduleInclude =  Loader::includeModule('kit.b2bcabinet');
        $sites = Config::getSites();
        $settings = [];
        
        foreach ($sites as $lid => $name) {
            $settings[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/kit.b2bcabinet_settings.php?lang='.LANGUAGE_ID.'&site='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
        }
        
        if (!isset($arGlobalMenu['global_menu_kit'])) {
            $arGlobalMenu['global_menu_kit'] = [
                'menu_id'   => 'kit',
                'text'      => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_GLOBAL_MENU'),
                'title'     => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_GLOBAL_MENU'),
                'sort'      => 1000,
                'items_id'  => 'global_menu_kit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }
        
        $menu = [];
        
        if ($moduleInclude) {
            if ($GLOBALS['APPLICATION']->GetGroupRight(\KitB2bCabinet::MODULE_ID) >= 'R') {
                $menu = [
                    "section"   => "kit_b2bcabinet",
                    "menu_id"   => "kit_b2bcabinet",
                    "sort"      => 75,
                    'id'        => 'b2bcabinet',
                    "text"      => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_GLOBAL_MENU_B2BCABINET'),
                    "title"     => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_GLOBAL_MENU_B2BCABINET'),
                    "icon"      => "kit_b2bcabinet_menu_icon",
                    "page_icon" => "",
                    "items_id"  => "global_menu_kit_b2bcabinet",
                    "items"     => [
                        [
                            'text'      => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_SETTINGS'),
                            'title'     => Loc::getMessage(\KitB2bCabinet::MODULE_ID.'_SETTINGS'),
                            'sort'      => 10,
                            'icon'      => '',
                            'page_icon' => '',
                            "items_id"  => "settings",
                            'items'     => $settings,
                        ]
                    ],
                    "more_url" => array(
                        "kit.b2bcabinet_settings.php",
                    ),
                ];
            }
        }
        
        $arGlobalMenu['global_menu_kit']['items']['kit.b2bcabinet'] = $menu;
    }
}
?>