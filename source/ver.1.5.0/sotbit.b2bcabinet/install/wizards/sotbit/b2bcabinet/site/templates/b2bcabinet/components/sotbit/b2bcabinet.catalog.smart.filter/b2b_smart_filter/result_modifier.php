<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

function fillArrayRecursive(&$array, &$arResult) {
    foreach ($array as $key => &$item) {
        if (isset($item['CHILDS'])) {
            $childs = $item['CHILDS'];
            $array[$item['ID']] = array_merge($arResult['ITEMS']['SECTION_ID']['VALUES'][$item['ID']], array('CHILDS' => $childs));
            fillArrayRecursive($item['CHILDS'], $arResult);
        } else {
            $array[$item['ID']] = $arResult['ITEMS']['SECTION_ID']['VALUES'][$item['ID']];
        }
    }
}

if(
    (!empty($arParams['ARR_SECTIONS']) && is_array($arParams['ARR_SECTIONS'])) &&
    (!empty($arResult['ITEMS']['SECTION_ID']['VALUES']) && is_array($arResult['ITEMS']['SECTION_ID']['VALUES']))
)
{
    $sectionKeys = array_keys($arResult['ITEMS']['SECTION_ID']['VALUES']);

    $max = 0;
    foreach ($arParams['ARR_SECTIONS'] as $item) {
        if ((int)$item['DEPTH_LEVEL'] > $max)
            $max = $item['DEPTH_LEVEL'];
    }


    $sections = $arParams['ARR_SECTIONS'];
    for ($i = $max; $i >= 1; $i--) {
        foreach ($sections as $key => &$item) {
            if ((int)$item['DEPTH_LEVEL'] == $i) {
                if ($i !== 1) {
                    $sections[$item['IBLOCK_SECTION_ID']]['CHILDS'][$item['ID']] = $item;
                    unset($sections[$key]);
                }
            }

        }
    }

    fillArrayRecursive($sections, $arResult);
    $arResult['ITEMS']['SECTION_ID']['FILTRED_FIELDS'] = $sections;

}


global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
