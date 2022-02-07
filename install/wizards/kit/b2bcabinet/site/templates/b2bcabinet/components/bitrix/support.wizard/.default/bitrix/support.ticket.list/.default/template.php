<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->AddHeadScript("/bitrix/js/main/utils.js");
?>

<div class="card support_page">
    <div class="support-list__title"><?= Loc::getMessage("SUP_LIST_TITLE") ?></div>
    <a type="button" class="btn btn_b2b btn_create-appeal"
       href="<?= $APPLICATION->GetCurPage() . "?show_wizard=Y&end_wizard=Y" ?>"><?= Loc::getMessage("SUP_ASK") ?></a>
    <div class="main-ui-filter-search-wrapper">
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:main.ui.filter",
            "b2bcabinet",
            array(
                "FILTER_ID" => "TICKET_LIST",
                "GRID_ID" => "TICKET_LIST",
                "FILTER" => $arResult["FILTER"],
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
        [
            'GRID_ID' => 'TICKET_LIST',
            'HEADERS' => [
                [
                    "id" => "LAMP",
                    "name" => Loc::getMessage('SUP_LAMP'),
                    "sort" => "LAMP",
                    "default" => true
                ],
                [
                    "id" => "ID",
                    "name" => Loc::getMessage('SUP_ID'),
                    "sort" => "ID",
                    "default" => true
                ],
                [
                    "id" => "TITLE",
                    "name" => Loc::getMessage('SUP_TITLE'),
                    "default" => true
                ],
                [
                    "id" => "TIMESTAMP_X",
                    "name" => Loc::getMessage('SUP_TIMESTAMP'),
                    "sort" => "TIMESTAMP_X",
                    "default" => true
                ],
                [
                    "id" => "MODIFIED_BY",
                    "name" => Loc::getMessage('SUP_MODIFIED_BY'),
                    "default" => true
                ],
                [
                    "id" => "MESSAGES",
                    "name" => Loc::getMessage('SUP_MESSAGES'),
                    "default" => true
                ],
            ],
            'ROWS' => $arResult['ROWS'],
            'FILTER_STATUS_NAME' => $arResult['FILTER_STATUS_NAME'],
            'AJAX_MODE' => 'Y',

            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",

            "ALLOW_COLUMNS_SORT" => true,
            "ALLOW_ROWS_SORT" => [
                'ID',
                'LAMP',
                'TIMESTAMP_X'
            ],
            "ALLOW_COLUMNS_RESIZE" => true,
            "ALLOW_HORIZONTAL_SCROLL" => true,
            "ALLOW_SORT" => true,
            "ALLOW_PIN_HEADER" => true,
            "ACTION_PANEL" => $arResult['GROUP_ACTIONS'],

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
            "DEFAULT_PAGE_SIZE" => 50,
        ],
        $component,
        ['HIDE_ICONS' => 'Y']
    );
    ?>
    <table class="support-ticket-hint">
        <tr>
            <td>
                <div class="support-lamp-red"></div>
            </td>
            <td> - <?= $bADS ? Loc::getMessage("SUP_RED_ALT_SUP") : Loc::getMessage("SUP_RED_ALT_2") ?></td>
        </tr>
        <tr>
            <td>
                <div class="support-lamp-yellow"></div>
            </td>
            <td> - <?= Loc::getMessage("SUP_YELLOW_ALT_SUP") ?></td>
        </tr>
        <tr>
            <td>
                <div class="support-lamp-green"></div>
            </td>
            <td> - <?= Loc::getMessage("SUP_GREEN_ALT") ?></td>
        </tr>
        <? if ($bADS): ?>
            <tr>
                <td>
                    <div class="support-lamp-green-s"></div>
                </td>
                <td> - <?= Loc::getMessage("SUP_GREEN_S_ALT_SUP") ?></td>
            </tr>
        <? endif; ?>
        <tr>
            <td>
                <div class="support-lamp-grey"></div>
            </td>
            <td> - <?= Loc::getMessage("SUP_GREY_ALT") ?></td>
        </tr>
    </table>
</div>