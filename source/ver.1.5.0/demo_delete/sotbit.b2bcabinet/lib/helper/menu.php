<?php

namespace Sotbit\B2bCabinet\Helper;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu(&$arGlobalMenu, &$arModuleMenu) {
        $moduleInclude =  Loader::includeModule('sotbit.b2bcabinet');
        $sites = Config::getSites();
        $settings = [];
        
        foreach ($sites as $lid => $name) {
            $settings[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/sotbit.b2bcabinet_settings.php?lang='.LANGUAGE_ID.'&site='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
        }
        
        if (!isset($arGlobalMenu['global_menu_sotbit'])) {
            $arGlobalMenu['global_menu_sotbit'] = [
                'menu_id'   => 'sotbit',
                'text'      => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_GLOBAL_MENU'),
                'title'     => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_GLOBAL_MENU'),
                'sort'      => 1000,
                'items_id'  => 'global_menu_sotbit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }
        
        $menu = [];
        
        if ($moduleInclude) {
            if ($GLOBALS['APPLICATION']->GetGroupRight(\SotbitB2bCabinet::MODULE_ID) >= 'R') {
                $menu = [
                    "section"   => "sotbit_b2bcabinet",
                    "menu_id"   => "sotbit_b2bcabinet",
                    "sort"      => 75,
                    'id'        => 'b2bcabinet',
                    "text"      => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_GLOBAL_MENU_B2BCABINET'),
                    "title"     => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_GLOBAL_MENU_B2BCABINET'),
                    "icon"      => "sotbit_b2bcabinet_menu_icon",
                    "page_icon" => "",
                    "items_id"  => "global_menu_sotbit_b2bcabinet",
                    "items"     => [
                        [
                            'text'      => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_SETTINGS'),
                            'title'     => Loc::getMessage(\SotbitB2bCabinet::MODULE_ID.'_SETTINGS'),
                            'sort'      => 10,
                            'icon'      => '',
                            'page_icon' => '',
                            "items_id"  => "settings",
                            'items'     => $settings,
                        ]
                    ],
                    "more_url" => array(
                        "sotbit.b2bcabinet_settings.php",
                    ),
                ];
            }
        }
        
        $arGlobalMenu['global_menu_sotbit']['items']['sotbit.b2bcabinet'] = $menu;
    }
}
?>