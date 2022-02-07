<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="documents_wrapper card">
    <div class="main-ui-filter-search-wrapper">
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:main.ui.filter",
            "b2bcabinet",
            array(
                "FILTER_ID" => "DOCUMENTS_LIST",
                "GRID_ID" => "DOCUMENTS_LIST",
                'FILTER' => [
                    ['id' => 'ID', 'name' => Loc::getMessage('DOC_ID'), 'type' => 'string'],
                    ['id' => 'NAME', 'name' => Loc::getMessage('DOC_NAME'), 'type' => 'string'],
                    ['id' => 'DATE_CREATE', 'name' => Loc::getMessage('DOC_DATE_CREATE'), 'type' => 'date'],
                    ['id' => 'DATE_UPDATE', 'name' => Loc::getMessage('DOC_DATE_UPDATE'), 'type' => 'date'],
                ],
                "ENABLE_LIVE_SEARCH" => true,
                "ENABLE_LABEL" => true,
                "COMPONENT_TEMPLATE" => "b2bcabinet"
            ),
            false
        );
        ?>
    </div>
    <?
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        array(
            'GRID_ID' => 'DOCUMENTS_LIST',
            'HEADERS' => array(
                array("id" => "ID", "name" => Loc::getMessage('DOC_ID'), "sort" => "ID", "default" => true, "editable" => false),
                array("id" => "NAME", "name" => Loc::getMessage('DOC_NAME'), "sort" => "NAME", "default" => true, "editable" => false),
                array("id" => "DATE_CREATE", "name" => Loc::getMessage('DOC_DATE_CREATE'), "sort" => "DATE_CREATE", "default" => true, "editable" => false),
                array("id" => "DATE_UPDATE", "name" => Loc::getMessage('DOC_DATE_UPDATE'), "sort" => "DATE_UPDATE", "default" => true, "editable" => false),
                array("id" => "ORDER", "name" => Loc::getMessage('DOC_ORDER'), "default" => true, "editable" => false),
                array("id" => "ORGANIZATION", "name" => Loc::getMessage('DOC_ORGANIZATION'), "default" => true, "editable" => false),
            ),
            'ROWS' => $arResult['ROWS'],
            'AJAX_MODE' => 'Y',

            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",

            "ALLOW_COLUMNS_SORT" => true,
            "ALLOW_ROWS_SORT" => ['ID', 'NAME', 'DATE_CREATE', 'DATE_UPDATE'],
            "ALLOW_COLUMNS_RESIZE" => true,
            "ALLOW_HORIZONTAL_SCROLL" => true,
            "ALLOW_SORT" => true,
            "ALLOW_PIN_HEADER" => true,
            "ACTION_PANEL" => [],

            "SHOW_CHECK_ALL_CHECKBOXES" => false,
            "SHOW_ROW_CHECKBOXES" => false,
            "SHOW_ROW_ACTIONS_MENU" => true,
            "SHOW_GRID_SETTINGS_MENU" => true,
            "SHOW_NAVIGATION_PANEL" => true,
            "SHOW_PAGINATION" => true,
            "SHOW_SELECTED_COUNTER" => false,
            "SHOW_TOTAL_COUNTER" => true,
            "SHOW_PAGESIZE" => true,
            "SHOW_ACTION_PANEL" => true,

            "ENABLE_COLLAPSIBLE_ROWS" => true,
            'ALLOW_SAVE_ROWS_STATE' => true,

            "SHOW_MORE_BUTTON" => false,
            '~NAV_PARAMS' => $arResult['GET_LIST_PARAMS']['NAV_PARAMS'],
            'NAV_OBJECT' => $arResult['NAV_OBJECT'],
            'NAV_STRING' => $arResult['NAV_STRING'],
            "TOTAL_ROWS_COUNT" => count($arResult['ROWS']),
            "CURRENT_PAGE" => $arResult['CURRENT_PAGE'],
            "PAGE_SIZES" => $arParams['ORDERS_PER_PAGE'],
            "DEFAULT_PAGE_SIZE" => 50
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
    ?>
</div>
