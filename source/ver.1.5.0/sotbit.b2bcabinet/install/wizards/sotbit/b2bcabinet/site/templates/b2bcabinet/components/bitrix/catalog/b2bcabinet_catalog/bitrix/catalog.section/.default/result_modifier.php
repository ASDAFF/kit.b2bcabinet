<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$arTableHeader = [
    'NAME' => Loc::getMessage('HEAD_NAME'),
    'AVALIABLE' => Loc::getMessage('HEAD_AVAILABLE')
];

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

// Settings -> Infoblocks -> Property Features Enabled
// When this option is enabled, the properties displayed in the list are selected in the Infoblock TYPES
$isEnabledFeature = (\COption::getOptionString('iblock', 'property_features_enabled', '') == 'Y');

if(
    is_array($arParams['PROPERTY_CODE']) && !empty($arParams['PROPERTY_CODE']) &&
    is_array($arResult['ITEMS'][0]['PROPERTIES']) && !empty($arResult['ITEMS'][0]['PROPERTIES'])
    )
{
    $arPropKeys = [];

    if(!$isEnabledFeature)
        $arPropKeys = array_flip($arParams['PROPERTY_CODE']);

    if(!empty($arResult['ITEMS'][0]['DISPLAY_PROPERTIES'])) {
        $arPropKeys = array_merge($arPropKeys, array_flip(array_keys($arResult['ITEMS'][0]['DISPLAY_PROPERTIES'])));
    }

    foreach ($arResult['ITEMS'][0]['PROPERTIES'] as $key => $prop)
    {
        if(
            array_key_exists($key, $arPropKeys) &&
            !empty($prop['NAME']) &&
            $prop['PROPERTY_TYPE'] !== 'F'
        )
        {
            $arProductParams[$key] = $prop['NAME'];
        }
    }
}

if($isEnabledFeature) {
    // Get sku props
    foreach ($arResult['SKU_PROPS'][$arResult['IBLOCK_ID']] as $key => $prop) {
        if (!array_key_exists($key, $arProductParams) && $prop['PROPERTY_TYPE'] !== 'F') {
            $arProductParams[$key] = $prop['NAME'];
        }
    }
} else {
    if(is_array($arParams['OFFERS_PROPERTY_CODE']) && !empty($arParams['OFFERS_PROPERTY_CODE'])) {
        $arOfferPropKeys = array_flip($arParams['OFFERS_PROPERTY_CODE']);

        foreach ($arResult['ITEMS'] as $ITEM) {
            if (isset($ITEM['OFFERS'][0]) && !empty($ITEM['OFFERS'][0])) {
                foreach ($ITEM['OFFERS'][0]['PROPERTIES'] as $key => $prop) {
                    if (
                        !array_key_exists($key, $arProductParams)
                        && array_key_exists($key, $arOfferPropKeys) &&
                        $prop['PROPERTY_TYPE'] !== 'F'
                    ) {
                        $arProductParams[$key] = $prop['NAME'];
                    }
                }
            }
        }
    }
}

if(is_array($arResult['PRICES']) && !empty($arResult['PRICES']))
{
    $arProductParams['QUANTITY'] = Loc::getMessage('HEAD_QUANTITY');
    $arProductParams['MEASURE'] = Loc::getMessage('HEAD_MEASURE');

    foreach ($arResult['PRICES'] as $key => $PRICE)
    {
        if($PRICE['CAN_VIEW'])
        {
            $arProductParams['PRICES'][$key]['NAME'] = (empty($PRICE['TITLE']) ? $PRICE['CODE'] : $PRICE['TITLE']);
            $arProductParams['PRICES'][$key]['ID'] = $PRICE['ID'];
        }
    }
}

$arParams['TABLE_HEADER'] = array_merge($arTableHeader, $arProductParams);

/*$this->__component->arResultCacheKeys = array_merge( $this->__component->arResultCacheKeys, array (
    "",
) );*/