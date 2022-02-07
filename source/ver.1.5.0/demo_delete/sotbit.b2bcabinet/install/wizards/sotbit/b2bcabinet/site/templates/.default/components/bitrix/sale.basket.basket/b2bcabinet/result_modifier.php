<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arResult['headers']['props'] = [];
$arResult['headers']['sku_data'] = [];
$arResult['headers']['column_list'] = [];

foreach ($arResult['BASKET_ITEM_RENDER_DATA'] as $row) {

    foreach ($row['PROPS'] as $prop) {
        if(!empty($prop['CODE']) && !empty($prop['NAME']))
            $arResult['headers']['props'][$prop['CODE']] = $prop['NAME'];
    }

    foreach ($row['SKU_BLOCK_LIST'] as $sku) {
        if(!empty($sku['CODE']) && !empty($sku['NAME']))
            $arResult['headers']['sku_data'][$sku['CODE']] = $sku['NAME'];
    }

    foreach ($row['COLUMN_LIST'] as $col) {
        if(!empty($col['CODE']) && !empty($col['NAME']))
            $arResult['headers']['column_list'][$col['CODE']] = $col['NAME'];
    }
}

if(empty($arParams['IMAGE_SIZE_PREVIEW']))
    $arParams['IMAGE_SIZE_PREVIEW'] = 23;

$arResult['templateColums'] = [
    'PRICE' => GetMessage("SBB_BASKET_SUM"),
    'QUANTITY' => GetMessage("SBB_BASKET_QUANTITY"),
    'SUM' => GetMessage("SBB_TOTAL"),
    'DELETE' => ''
];
