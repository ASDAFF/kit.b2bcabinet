<?php
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'kit.b2bcabinet';

CModule::includeModule($module);
CModule::includeModule('iblock');
CModule::includeModule('catalog');

$iblockId = $_SESSION["WIZARD_CATALOG_IBLOCK_ID"];

if(!$iblockId)
{
    $iblockId = Option::get($module,'IBLOCK_ID','', WIZARD_SITE_ID);
}

if(class_exists('\Bitrix\Iblock\PropertyFeatureTable') && $iblockId > 0){
    $exist = [];
    $rs = \Bitrix\Iblock\PropertyFeatureTable::getList(['select' => ['PROPERTY_ID']]);
    while($f = $rs->fetch())
    {
        $exist[] = $f['PROPERTY_ID'];
    }
    $rs = \Bitrix\Iblock\PropertyTable::getList([
        'filter' => [
            'IBLOCK_ID' => $iblockId,
            '!CODE' => [
                'vote_count',
                'vote_sum',
                'rating',
                'REGIONS',
                'BRANDS',
                'SERVIVCES',
                'KHIT',
                'NOVINKA',
                'CML2_TRAITS',
                'CML2_BASE_UNIT',
                'CML2_TAXES',
                'BLOG_POST_ID',
             ]
        ],
        'select' => ['ID']]
    );
    while($prop = $rs->fetch()){
        if($prop['ID'] > 0 && !in_array($prop['ID'],$exist)){
            \Bitrix\Iblock\PropertyFeatureTable::add(['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'iblock','FEATURE_ID' => 'LIST_PAGE_SHOW','IS_ENABLED' => 'Y']);
            \Bitrix\Iblock\PropertyFeatureTable::add(['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'iblock','FEATURE_ID' => 'DETAIL_PAGE_SHOW','IS_ENABLED' => 'Y']);
        }
    }
    $offer = CCatalogSKU::GetInfoByProductIBlock($iblockId);
    if($offer['IBLOCK_ID'] > 0){
        $rs = \Bitrix\Iblock\PropertyTable::getList(
            [
                'filter' => ['IBLOCK_ID' => $offer['IBLOCK_ID'],'!CODE' => ['CML2_LINK','REGIONS']],
                'select' => ['ID','PROPERTY_TYPE','USER_TYPE']
            ]
        );
        while($prop = $rs->fetch()){
            if($prop['ID'] > 0 && !in_array($prop['ID'],$exist)){
                \Bitrix\Iblock\PropertyFeatureTable::add(
                    ['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'iblock','FEATURE_ID' => 'LIST_PAGE_SHOW','IS_ENABLED' => 'Y']
                );
                \Bitrix\Iblock\PropertyFeatureTable::add(
                    ['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'iblock','FEATURE_ID' => 'DETAIL_PAGE_SHOW','IS_ENABLED' => 'Y']
                );
                if($prop['PROPERTY_TYPE'] == 'S' && $prop['USER_TYPE'] == 'directory'){
                    \Bitrix\Iblock\PropertyFeatureTable::add(
                        ['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'catalog','FEATURE_ID' => 'IN_BASKET','IS_ENABLED' => 'Y']
                     );
                    \Bitrix\Iblock\PropertyFeatureTable::add(
                        ['PROPERTY_ID' => $prop['ID'],'MODULE_ID' => 'catalog','FEATURE_ID' => 'OFFER_TREE','IS_ENABLED' => 'Y']
                    );
                }
            }
        }
    }
}
?>