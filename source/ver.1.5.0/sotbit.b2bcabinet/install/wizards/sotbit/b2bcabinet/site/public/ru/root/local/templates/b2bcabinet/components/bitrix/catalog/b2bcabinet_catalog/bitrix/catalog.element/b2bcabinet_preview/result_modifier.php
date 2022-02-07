<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Highloadblock;

Loc::loadMessages(__FILE__);
global $analogProducts;
$tmp = [];
$tmpD = [];
$tmp[$arResult['ID']] = $arResult['PREVIEW_PICTURE'];
if($arResult['OFFERS']){
    foreach($arResult['OFFERS'] as $offer){
        $tmp[$offer['ID']] = $offer['PREVIEW_PICTURE'];
        $tmpD[$offer['ID']] = $offer['DETAIL_PICTURE'];
    }
}

$checkSlider = $arParams['ADD_DETAIL_TO_SLIDER'];
$arParams['ADD_DETAIL_TO_SLIDER'] = "N";
$arTmpPhoto = $arResult["MORE_PHOTO"];
unset($arResult["MORE_PHOTO"]);
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$arResult["MORE_PHOTO"] = $arTmpPhoto;
$arParams['ADD_DETAIL_TO_SLIDER'] = $checkSlider;
unset($arTmpPhoto);

$arResult[$arResult['ID']] = $tmp[$arResult['ID']];

if($arResult['JS_OFFERS']){
    foreach($arResult['JS_OFFERS'] as $i => $offer)
    {
        $arResult['JS_OFFERS'][$i]['PREVIEW_PICTURE'] = $tmp[$offer['ID']];
        $arResult['JS_OFFERS'][$i]['DETAIL_PICTURE'] = $tmpD[$offer['ID']];
    }
}

$template = $this->__name;
if($this->__name == '.default'){
    $template = '';
}
$arResult['TEMPLATE'] = $template;

$arResult['SKU'] = $arResult['ID'];
if($arResult['OFFERS'])
{
    $arResult['SKU'] = $arResult['OFFERS'][0]['ID'];
}

//\SotbitOrigami::checkPriceDiscount($arResult);

//$arResult = \SotbitOrigami::changeColorImages($arResult);

//$Element = new \Sotbit\Origami\Image\Element($template);
//$arResult = $Element->prepareImages($arResult);

//$color = \Sotbit\Origami\Helper\Color::getInstance(SITE_ID);
//$arResult = $color::changePropColorView($arResult, $arParams)['RESULT'];


//$arResult["ITEM_PRICE_DELTA"] = \SotbitOrigami::getPriceDelta($arResult, $template);


//$arResult['BRAND'] = [];
//if($arParams['BRAND_USE'] && $arParams['BRAND_PROP_CODE']){
//    $Brand = new \Sotbit\Origami\Brand($template);
//    $Brand->setBrandProps($arParams['BRAND_PROP_CODE']);
//    $Brand->setResize(['width' => 205,'height' => 50,'type' => BX_RESIZE_IMAGE_PROPORTIONAL]);
//    $arResult['BRAND'] = $Brand->findBrandsForElement($arResult['PROPERTIES']);
//}
/**/

//$arResult['VIDEO'] = [];
//$videoProp = Option::get('PROP_VIDEO_'.$template);
//if($arResult['PROPERTIES'][$videoProp]['VALUE']){
//    foreach($arResult['PROPERTIES'][$videoProp]['VALUE'] as $url){
//        $Video = new \Sotbit\Origami\Video($url);
//        $arResult['VIDEO'][] = $Video->getContent();
//    }
//}

if($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
    foreach($arResult['SKU_PROPS'] as $code => $sku) {
        $values = [];
        $table = '';
        foreach ($arResult['OFFERS'] as $i => $o) {
            if ($o['PROPERTIES'][$code]['VALUE']) {
                $table = $o['PROPERTIES'][$code]['USER_TYPE_SETTINGS']['TABLE_NAME'];
                $values[$i] = $o['PROPERTIES'][$code]['VALUE'];
            }
        }

        if ($table && $values) {
            $HL = Highloadblock\HighloadBlockTable::getList([
                "filter" => [
                    'TABLE_NAME' => $table,
                ],
                'limit'  => 1,
            ])->Fetch();
            if ($HL['ID'] > 0) {
                $HLEntity = Highloadblock\HighloadBlockTable::compileEntity($HL)->getDataClass();
                $rs = $HLEntity::getList([
                    'filter' => [
                        'UF_XML_ID' => $values,
                    ]
                ]);
                while ($row = $rs->fetch()) {
                    foreach($arResult['OFFERS'] as $i => $o){
                        if($o['PROPERTIES'][$code]['VALUE'] == $row['UF_XML_ID']){
                            $arResult['OFFERS'][$i]['PROPERTIES'][$code]['DISPLAY_VALUE'] = mb_convert_case($row['UF_NAME'], MB_CASE_TITLE, SITE_CHARSET);
                        }
                    }
                }
            }
        }
    }
}


//$colorCode = \Sotbit\Origami\Helper\Config::get('COLOR');
//if($arResult['SKU_PROPS'][$colorCode]) {
//    $tmp = [$colorCode => $arResult['SKU_PROPS'][$colorCode]];
//    foreach($arResult['SKU_PROPS'] as $code => $prop){
//        if($code == $colorCode){
//            continue;
//        }
//        $tmp[$code] = $prop;
//    }
//    $arResult['SKU_PROPS'] = $tmp;
//}

//if (Bitrix\Main\Loader::includeModule( "sotbit.price" ))
//{
    //$arResult = SotbitPrice::ChangeMinPrice( $arResult );
//}
//if (Bitrix\Main\Loader::includeModule( "sotbit.regions" ))
//{
    //$arResult = \Sotbit\Regions\Sale\Price::change( $arResult );
//}

$arResult["SHOW_BUY"] = 0;
$arResult["SHOW_DELAY"] = 0;
$arResult["SHOW_COMPARE"] = 0;

if(isset($arParams['ACTION_PRODUCTS']))
{
    if(in_array("BUY", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_BUY"] = 1;

    if(in_array("DELAY", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_DELAY"] = 1;

    if(in_array("COMPARE", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_COMPARE"] = 1;
}


$arResult['haveOffers'] = !empty($arResult['OFFERS']);

$arResult['countOffers'] = 0;
if($arResult['haveOffers'])
    $arResult['countOffers'] = count($arResult['OFFERS']);

if ($arResult['haveOffers']) {
    $arResult['actualItem']
        = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);

    $arResult['showSliderControls'] = false;

    $arResult['canNotBuyOffers'] = [];
    $arResult['minPrice'] = [];
    $arResult['maxPrice'] = [];
    $arResult['currency'] = false;
    foreach ($arResult['OFFERS'] as $id => $offer) {
        if ( ! $offer['CAN_BUY']) {
            $arResult['canNotBuyOffers'][] = $offer;
        }

        // Offers product picture
        $productPicture = $offer['PREVIEW_PICTURE'];
        if(empty($productPicture['ID'])
            && !empty($offer['DETAIL_PICTURE']['ID'])
        ) {
            $productPicture = $offer['DETAIL_PICTURE'];
        } elseif(
            empty($productPicture['ID'])
            && !empty($offer['MORE_PHOTO'][0]['ID'])
        ) {
            $productPicture = $offer['MORE_PHOTO'][0];
        }


        if ( ! empty($productPicture['ID'])) {
            $productPicture = array_merge(CFile::ResizeImageGet(
                $productPicture['ID'],
                ['width' => 45, 'height' => 45],
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                true
            ), ['id' => $productPicture['ID']]);

            $productPictureLarge = CFile::GetFileArray($productPicture['id']);
        }

        if ( ! empty($productPicture)) {
            $arResult['OFFERS'][$id]['productPicture'] = $productPicture;
            $arResult['OFFERS'][$id]['productPictureLarge'] = $productPictureLarge;
        } else {
            $arResult['OFFERS'][$id]['productPicture']['src']
                = $arResult['OFFERS'][$id]['productPictureLarge']['SRC']
                = '/upload/no_photo_small.jpg';
        }

        // collection min max price for offers
        foreach ($offer['PRICES'] as $codePrice => $price) {
            $arResult['minPrice'][$codePrice][] = $price['VALUE'];
            if (empty($arResult['currency'])) {
                $arResult['currency'] = $price['CURRENCY'];
            }
        }

        // Available offers
        $productClassStatus = 'offer-item__status-available';
        $productStatusMsg = 'PRODUCT_LABEL_AVAILABLE';
        if (
            ($offer['CATALOG_SUBSCRIBE'] === 'Y'
                && $offer['CATALOG_AVAILABLE'] !== 'Y')
            || ($offer['CATALOG_QUANTITY'] < 1
                && $offer['PRODUCT']['CAN_BUY_ZERO'] !== 'Y')
        ) {
            // preorder
            $productClassStatus = 'offer-item__status-preorder';
            $productStatusMsg = 'PRODUCT_LABEL_UNDER_THE_ORDER';
        } else {
            // no item
            if ($offer['CATALOG_AVAILABLE'] !== 'Y') {
                $productClassStatus = 'offer-item__status-unavailable';
                $productStatusMsg = 'PRODUCT_LABEL_UNAVAILABLE';
            }
        }
        if ($productClassStatus && $productStatusMsg) {
            $arResult['OFFERS'][$id]['productAvailable'] = [
                'class' => $productClassStatus,
                'msg' => $productStatusMsg
            ];
        }


        // SKU props
        if(!empty($arResult['SKU_PROPS']) && !empty($offer['PROPERTIES'])) {

            if(!empty($offer['DISPLAY_PROPERTIES'])) {
                foreach ($offer['DISPLAY_PROPERTIES'] as $code => $prop) {
                    if ( ! empty($prop)) {
                        $arResult['OFFERS'][$id]['skuProps'][$code] = [
                            'name'  => $prop['NAME'],
                            'value' => ($prop['DISPLAY_VALUE'] ? $prop['DISPLAY_VALUE'] : $prop['VALUE']),
                            'type' => $prop['PROPERTY_TYPE']
                        ];
                    }
                }
            }

            foreach ($arResult['SKU_PROPS'] as $code => $sku) {
                $value = false;
                $propName = false;

                if(
                    isset($offer['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE']) &&
                    !empty($offer['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'])
                )
                {
                    $value = $offer['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'];
                    $propName = $offer['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'];
                } elseif ( ! empty($offer['PROPERTIES'][$code]['DISPLAY_VALUE'])) {
                    $value
                        = $offer['PROPERTIES'][$code]['DISPLAY_VALUE'];
                    $propName
                        = $offer['PROPERTIES'][$code]['NAME'];
                } elseif ( ! empty($offer['PROPERTIES'][$code]['VALUE'])) {
                    $value
                        = $offer['PROPERTIES'][$code]['VALUE'];
                    $propName
                        = $offer['PROPERTIES'][$code]['NAME'];
                }

                if (is_array($value)) {
                    $value = implode("\n", $value);
                }

                // Prepare Display props
                if ( ! empty($arResult['SKU_PROPS'][$code]['XML_MAP'])) {
                    $xmlMap = $arResult['SKU_PROPS'][$code]['XML_MAP'][$value];
                    if ( ! empty($xmlMap)) {
                        $value
                            = $arResult['SKU_PROPS'][$code]['VALUES'][$xmlMap]['NAME'];
                    }
                }

                if ( ! empty($value)) {
                    $arResult['OFFERS'][$id]['skuProps'][$code] = [
                        'name' => $propName,
                        'value' => $value,
                        'type' => $sku['PROPERTY_TYPE']
                    ];
                }
            }
        }


        // Prices
        if ( ! empty($arResult["CAT_PRICES"])) {
            foreach ($arResult["CAT_PRICES"] as $code => $arPrice) {
                if ($offer['PRICES'][$code]['CAN_ACCESS']) {
                    if (
                        is_array($offer['PRICES'][$code])
                        &&
                        ! empty($offer['PRICES'][$code])
                    ) {
                        $priceType = '';
                        $price = [];
                        if ($arParams['SHOW_OLD_PRICE'] === 'Y'
                            && $offer['PRICES'][$code]['DISCOUNT_DIFF_PERCENT']
                            != 0
                        ) {
                            $priceType = 'DISCOUNT_';
                            $price['HIGHEST_PRICE']
                                = $offer['PRICES'][$code]['PRINT_VALUE'];
                        }

                        $price['FORMAT'] = $offer['PRICES'][$code]['PRINT_'
                        .$priceType.'VALUE'];
                        $price['DEF'] = $offer['PRICES'][$code][$priceType
                        .'VALUE'];


                        $arResult['OFFERS'][$id]['productPrices'][$code] = [
                            'price' => (
                                (isset($price['FORMAT']) && ! empty($price['FORMAT']) ? $price['FORMAT'] : '').
                                ($arParams['SHOW_OLD_PRICE'] === 'Y' && isset($price['HIGHEST_PRICE']) ? "<p style='text-decoration: line-through;'>".$price['HIGHEST_PRICE'] : "")
                            ),
                            'title' => $arPrice['TITLE']
                        ];
                    }
                }
            }
        }


        if ($offer['MORE_PHOTO_COUNT'] >= 1)
        {
            $arResult['showSliderControls'] = true;
        }

    }
    // process min max price
    foreach($arResult['minPrice'] as $type=>$value) {
        $arResult['minPrice'][$type] = min($value);
        $arResult['maxPrice'][$type] = max($value);
    }
} else {
    $arResult['actualItem'] = $arResult;
    $arResult['showSliderControls'] = $arResult['MORE_PHOTO_COUNT'] >= 1;
}
if($arResult['VIDEO'])
{
    $arResult['showSliderControls'] = true;
}

// Price format for min max
if(!empty($arResult['minPrice'])) {
    if(empty($arResult['maxPrice']) || min($arResult['minPrice']) == max($arResult['maxPrice']))
        $arResult['minPriceFormat'] = CCurrencyLang::CurrencyFormat(min($arResult['minPrice']), $arResult['currency']);
    else
        $arResult['minPriceFormat'] = CurrencyFormatNumber(min($arResult['minPrice']), $arResult['currency']);
}

if(!empty($arResult['maxPrice']))
    $arResult['maxPriceFormat'] = CCurrencyLang::CurrencyFormat(max($arResult['maxPrice']), $arResult['currency']);


// Gallery
$arResult['gallery'] = [];
if ($arResult['showSliderControls'])
{
    if ($arResult['haveOffers'])
    {
        foreach ($arResult['OFFERS'] as $keyOffer => $offer)
        {
            if (!isset($offer['MORE_PHOTO_COUNT']) || $offer['MORE_PHOTO_COUNT'] <= 0)
                continue;

            foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo)
            {
                if(!empty($photo['SRC']))
                    $arResult['gallery'][] = $photo['SRC'];
            }
        }
    }
    else
    {
        if (!empty($arResult['actualItem']['MORE_PHOTO']))
        {
            foreach ($arResult['actualItem']['MORE_PHOTO'] as $key => $photo)
            {
                if(!empty($photo['SRC']))
                    $arResult['gallery'][] = $photo['SRC'];
            }
        }
    }
}
if(!empty($arResult['gallery']) && is_array($arResult['gallery'])) {
    $arResult['gallery'] = array_unique($arResult['gallery']);
}

// Documents
$arResult['documents'] = [];
$propDoc = $arParams['DETAIL_MAIN_FILES_PROPERTY'];

if($propDoc && !empty($arResult['PROPERTIES'][$propDoc]['VALUE'])) {
    $arFiles = $arResult['PROPERTIES'][$propDoc];
    if($arFiles['PROPERTY_TYPE'] === 'F') {

        if(!is_array($arFiles['VALUE']))
            $arFiles['VALUE'] = [$arFiles['VALUE']];

        foreach($arFiles['VALUE'] as $doc){
            $file = CFile::GetFileArray($doc);
            $arResult['documents'][] = [
                'SIZE' => CFile::FormatSize($file['FILE_SIZE']),
                'PATH' => $file['SRC'],
                'NAME' => ($file['ORIGINAL_NAME'] ? $file['ORIGINAL_NAME'] : $file['FILE_NAME']),
                'LINK' => $file['SRC']
            ];
        }

    } else if($arFiles['PROPERTY_TYPE'] === 'S') {
        $link = '';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            $link.='https://';
        else
            $link.='http://';


        $server = Main\Context::getCurrent()->getServer();
        $link .= $server->getServerName();
        foreach($arFiles['VALUE'] as $doc) {
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$doc)) {
                $arResult['documents'][] = [
                    'SIZE' => CFile::FormatSize(filesize($_SERVER['DOCUMENT_ROOT'].$doc)),
                    'PATH' => $doc,
                    'NAME' => end(explode('/',$doc)),
                    'LINK' => (stripos($doc, "http") !== false ? $doc : $link.$doc)
                ];
            }
        }
    }
}

$this->__component->arResultCacheKeys = array_merge( $this->__component->arResultCacheKeys, [
    'ADVANTAGES_SECTIONS',
    'OFFERS',
    'SKU_PROPS',
    'PROPERTIES',
    'ITEM_MEASURE',
    'ITEM_MEASURE_RATIOS',
    //'TABS',
    'ID',
    'OFFERS_SELECTED',
    'SECTION',
    'IBLOCK_ID',
    'QUANTITY',
    'PRODUCT_PROVIDER_CLASS',
    'MODULE',
    'OFFERS_IBLOCK',
    'CATALOG',
    'OFFERS_ID',
    'SHOW_BUY',
    'SHOW_DELAY',
    'SHOW_COMPARE',
    'actualItem',
    'gallery',
    'documents',
] );
?>