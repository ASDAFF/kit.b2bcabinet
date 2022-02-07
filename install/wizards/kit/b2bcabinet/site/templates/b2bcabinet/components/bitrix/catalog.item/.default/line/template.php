<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Grid\Declension;
use \Bitrix\Main\Localization\Loc;

$componentElementParams = array(
    'IBLOCK_ID' => $item['IBLOCK_ID'],
    'ELEMENT_ID' => $item['ID'],
);

$productPicture = $item['PREVIEW_PICTURE'];

if(empty($productPicture['ID']) && !empty($item['DETAIL_PICTURE']['ID']))
    $productPicture = $item['DETAIL_PICTURE'];
else if(empty($productPicture['ID']) && !empty($item['MORE_PHOTO'][0]['ID']))
    $productPicture = $item['MORE_PHOTO'][0]['ID'];

if(!empty($productPicture['ID']))
{
    $productPictureOrigin = CFile::GetPath($productPicture['ID']);

    $productPicture = CFile::ResizeImageGet(
            $productPicture['ID'],
            array('width'=>45, 'height'=>45),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
            true
    );
}
$countOffers = 1;
// add first offer to main line view
if(is_array($item['OFFERS']) && count($item['OFFERS']) > 0) {
    if(count($item['OFFERS']) > 1)
        array_unshift($item['OFFERS'], $item['OFFERS'][0]);
    $countOffers = count($item['OFFERS']);
}


$haveOffers = ($countOffers > 1 ? true : false);



// Get min max price
if($haveOffers) {
    $minPrice = [];

    foreach ($item['OFFERS'] as $id => $offer) {
        foreach ($offer['PRICES'] as $codePrice => $price) {
            $minPrice[$codePrice][] = $price['VALUE'];
        }
    }

    foreach($minPrice as $type=>$value) {
        $minPrice[$type] = min($value);
    }
}




for ($i = 0; $i < $countOffers; $i++)
{
    if($i === 0)
    {
        ?>
        <td >
            <div class="index_blank-product_title">
                <div class="index_blank img-product">
                    <div class="block-cart-img"
                         style="background-image: url('<?= $productPicture['src'] ?>');"
                         <?/*?>onclick="SimpleLightbox.open({
                                 items: ['<?=$productPictureOrigin?>']
                                 })"<?*/?>
                         onclick="quickView('<?=$item["DETAIL_PAGE_URL"]?>');"
                    >
                        <div class="block-cart-bg"></div>
                        <i class="icon-zoomin3"></i>
                    </div>
                </div>
            </div>
        </td>
        <td >
            <div class="index_blank-product_title">
                <span>
                    <span class="quickview-link" onclick="quickView('<?=$item["DETAIL_PAGE_URL"]?>');"><?= $productTitle ?></span>
                </span>
            </div>
        </td>
        <?
    }
    else
    {
        if($countOffers > 1 && $i == 1)
            {
                $viewCountOffers = $countOffers - 1;
                $viewCountOffersDeclension = new Declension(Loc::getMessage("VIEW_OFFERS_DECLENSION_ONE"),
                Loc::getMessage("VIEW_OFFERS_DECLENSION_TWO"), Loc::getMessage("VIEW_OFFERS_DECLENSION_MANY"));


                 /*
                 * Bar offers
                 */?>
                <tr role="row" class="offer-footer">
                    <td>
                        <div class="td-content">
                            <div class="td-content-item">
                                <span class="td-content-item__text td-content-item__text-show">
                                </span>
                                    <span class="td-content-item__text td-content-item__text-hide">
                                </span>
                            </div>
                        </div>
                    </td>
                    <td colspan="<?=(count($arResult['TABLE_HEADER']) + count($arResult['TABLE_HEADER']['PRICES']))?>">
                        <div class="td-content">
                            <div class="td-content-item">
                                <span class="td-content-item__quantity"><?=$viewCountOffers?></span>
                                <span class="td-content-item__text"><?=$viewCountOffersDeclension->get($viewCountOffers)?></span>
                            </div>
                        </div>
                    </td>
                </tr>

                <?/*
                 * Collect properties
                 */
                $viewFilter = [];
                $hiddenFilter = [];

                foreach ($arParams['SKU_PROPS'] as $code => $skuProperty) {
                    $propertyId = $skuProperty['ID'];
                    if (!isset($item['SKU_TREE_VALUES'][$propertyId])) {
                        continue;
                    }
                    if(count($viewFilter) < 3)
                        $viewFilter[$code] = $skuProperty;
                    else
                        $hiddenFilter[$code] = $skuProperty;
                }

                 /*
                 * Output view property in filter
                 */?>
                <tr class="offer-properties">
                    <td colspan="<?=(count($arResult['TABLE_HEADER']) + count($arResult['TABLE_HEADER']['PRICES'])) + 1 ?>">
                        <div class="offer-properties-container">
                        <?
                        if(!empty($viewFilter)):
                            foreach ($viewFilter as $code => $skuProperty):
                                $propertyId = $skuProperty['ID'];
                                $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);?>

                                    <div class="offer-properties-item">
                                        <span class="offer-properties-item__title"><?= $skuProperty['NAME'] ?></span>
                                        <div class="offer-properties-item-inner">
                                        <?
                                        foreach ($skuProperty['VALUES'] as $value):
                                            if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]) || $value['NAME'] == '-') {
                                                continue;
                                            }
                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                            ?>
                                            <?if ($skuProperty['SHOW_MODE'] == "PICT" && !empty($value['PICT']['ID'])):?>
                                                <div class="offer-properties-item-inner__item offer-properties-item-inner__item-color">
                                                    <div class="offer-properties-item-inner__item-inner-color" data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>"
                                                         style="background: url(<?= $value['PICT']['SRC'] ?>)">
                                                    </div>
                                            <?else:?>
                                                <div class="offer-properties-item-inner__item offer-properties-item-inner__item-text">
                                                    <div data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>">
                                                        <span><?= $value['NAME'] ?></span>
                                                    </div>
                                            <?endif;?>
                                            </div>
                                        <?endforeach; ?>
                                        </div>
                                    </div>
                            <?endforeach;?>
                        <?endif;?>


                        <?/*
                         * Apply
                         */?>
                        <?if(!empty($viewFilter)):?>
                                <div class="offer-properties-item-btnBlock">
                                    <div class="offer-properties-item-btnBlock__btn" data-action="reset-sort">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                            <path d="M1 1L11 11M11 1L1 11" stroke="#3E495F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                        <?endif;?>
                        </div>
                        </div>
                    </td>
                </tr>


                <?/*
                 * Output view property in filter
                 * Hidden block
                 */?>
                <?
                if (!empty($hiddenFilter)):
                    $hiddenFilter = array_chunk($hiddenFilter, 3, true);
                    foreach ($hiddenFilter as $SKU_PROPS):?>
                        <tr class="offer-properties offer-properties-hidden">
                            <td colspan="<?=(count($arResult['TABLE_HEADER']) + count($arResult['TABLE_HEADER']['PRICES'])) + 1 ?>">
                                <div class="offer-properties-container">
                                    <?
                                    foreach ($SKU_PROPS as $code => $skuProperty):
                                        $propertyId = $skuProperty['ID'];
                                        $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']); ?>
                                        <div class="offer-properties-item">
                                            <span class="offer-properties-item__title"><?= $skuProperty['NAME'] ?></span>
                                            <div class="offer-properties-item-inner">
                                                <?
                                                foreach ($skuProperty['VALUES'] as $value):
                                                    if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]) || $value['NAME'] == '-') {
                                                        continue;
                                                    }
                                                    $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                    if ($skuProperty['SHOW_MODE'] == "PICT" && !empty($value['PICT']['ID'])):?>
                                                        <div class="offer-properties-item-inner__item
                                                        offer-properties-item-inner__item-color" >
                                                            <div class="offer-properties-item-inner__item-inner-color" data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>"
                                                                 style="background: url(<?= $value['PICT']['SRC'] ?>)">
                                                            </div>
                                                    <? else: ?>
                                                        <div class="offer-properties-item-inner__item
                                                        offer-properties-item-inner__item-text">
                                                            <div data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>">
                                                                <span><?= $value['NAME'] ?></span>
                                                            </div>
                                                    <? endif; ?>
                                                    </div>
                                                <? endforeach; ?>
                                            </div>
                                        </div>
                                    <?endforeach; ?>
                                </div>
                        </tr>
                    <?endforeach;?>
                <?endif;?>

                <?if(!empty($hiddenFilter)):?>
                    <tr class="offer-show-more">
                        <td colspan="<?=(count($arResult['TABLE_HEADER']) + count($arResult['TABLE_HEADER']['PRICES'])) + 1 ?>">
                            <div class="offer-show-more-content">
                                <div class="offer-show-more-content__btn">
                                </div>
                            </div>
                        </td>
                    </tr>
                <?endif;?>
                <?
            }
        ?>
        <tr role="row" class="offer-item">
        <td>
            <div class="index_blank-product_title">
                <div class="index_blank img-product">
                    <div class="block-cart-img"
                         style="background-image: url('<?= $productPicture['src'] ?>');"
                         onclick="SimpleLightbox.open({
                                 items: ['<?= $productPictureOrigin ?>']
                                 })">
                        <div class="block-cart-bg"></div>
                        <i class="icon-zoomin3"></i>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="index_blank-product_title">
                <span>
                        <?= $productTitle ?>
                </span>
            </div>
        </td>
        <?
    }

    foreach ($arResult['TABLE_HEADER'] as $code => $res)
    {
        switch ($code)
        {
            case 'AVALIABLE':
                $productClassStatus = 'icon-check2';
                $productStatusMsg = 'PRODUCT_LABEL_AVAILABLE';
                $avaliable = 'Y';

                if(isset($item['OFFERS'][$i]) && !empty($item['OFFERS'][$i]))
                {
                    if(
                        ($item['OFFERS'][$i]['CATALOG_SUBSCRIBE'] == 'Y' && $item['OFFERS'][$i]['CATALOG_AVAILABLE'] != 'Y') ||
                        ($item['OFFERS'][$i]['CATALOG_QUANTITY'] < 1 && $item['OFFERS'][$i]['PRODUCT']['CAN_BUY_ZERO'] != 'Y')
                    )
                    {
                        $productClassStatus = 'icon-watch2';
                        $productStatusMsg = 'PRODUCT_LABEL_UNDER_THE_ORDER';
                        $avaliable = 'D';
                    }
                    else if ($item['OFFERS'][$i]['CATALOG_AVAILABLE'] != 'Y')
                    {
                        $productClassStatus = 'icon-blocked no-products';
                        $productStatusMsg = 'PRODUCT_LABEL_UNAVAILABLE';
                        $avaliable = 'N';
                    }
                }
                else
                {
                    if(
                        ($item['CATALOG_SUBSCRIBE'] == 'Y' && $item['CATALOG_AVAILABLE'] != 'Y') ||
                        ($item['CATALOG_QUANTITY'] < 1 && $item['PRODUCT']['CAN_BUY_ZERO'] != 'Y')
                    )
                    {
                        $productClassStatus = 'icon-watch2';
                        $productStatusMsg = 'PRODUCT_LABEL_UNDER_THE_ORDER';
                        $avaliable = 'D';
                    }
                    else if ($item['CATALOG_AVAILABLE'] != 'Y')
                    {
                        $productClassStatus = 'icon-blocked no-products';
                        $productStatusMsg = 'PRODUCT_LABEL_UNAVAILABLE';
                        $avaliable = 'N';
                    }
                }

                echo "<td ><i class='mr-2 " .$productClassStatus. "'></i>". Loc::getMessage($productStatusMsg) ."</td>";

                break;
            case 'PRICES':
                foreach ($res as $k => $r)
                {
                    $price = [];

                    /**
                     * For offers
                     */
                    $prices = $item['OFFERS'][$i]['PRICES'][$k];
                    $currency = $item['OFFERS'][$i]['MIN_PRICE']['CURRENCY'];
                    if(
                        is_array($prices)
                        && !empty($prices)
                    )
                    {
                        $priceType = '';
                        if($arParams['SHOW_OLD_PRICE'] == 'Y' && $prices['DISCOUNT_DIFF_PERCENT'] != 0) {
                            $priceType = 'DISCOUNT_';
                            $price['HIGHEST_PRICE'] = $prices['PRINT_VALUE'];
                        }

                        $price['FORMAT'] = $prices['PRINT_'. $priceType .'VALUE'];
                        $price['DEF'] = $prices[$priceType .'VALUE'];


                    /**
                     * For price matrix
                     */
                    } elseif (is_array($item['PRICE_MATRIX']['CAN_BUY']) && in_array($r['ID'], $item['PRICE_MATRIX']['CAN_BUY'])) {

                        $priceMatrix = $item['PRICE_MATRIX']['MATRIX'];
                        $priceType = 'PRICE';

                        if($arParams['SHOW_OLD_PRICE'] == 'Y' && $item['ITEM_PRICES'][0]['PERCENT'] != 0) {
                            $priceType = 'DISCOUNT_PRICE';
                            $price['HIGHEST_PRICE'] = CCurrencyLang::CurrencyFormat(
                                $priceMatrix[$r['ID']]['ZERO-INF']['PRICE'],
                                $priceMatrix[$r['ID']]['ZERO-INF']['CURRENCY']
                            );
                        }

                        $price['FORMAT'] = CCurrencyLang::CurrencyFormat(
                            $priceMatrix[$r['ID']]['ZERO-INF'][$priceType],
                            $priceMatrix[$r['ID']]['ZERO-INF']['CURRENCY']
                        );
                        $price['DEF'] = $priceMatrix[$r['ID']]['ZERO-INF'][$priceType];

                    /**
                     * For product without offers
                     */
                    } elseif(!empty($item['PRICES'][$k])) {
                        $prices = $item['PRICES'][$k];
                        $currency = $item['MIN_PRICE']['CURRENCY'];

                        $priceType = '';
                        if($arParams['SHOW_OLD_PRICE'] == 'Y' && $prices['DISCOUNT_DIFF_PERCENT'] != 0) {
                            $priceType = 'DISCOUNT_';
                            $price['HIGHEST_PRICE'] = $prices['PRINT_VALUE'];
                        }

                        $price['FORMAT'] = $prices['PRINT_'. $priceType .'VALUE'];
                        $price['DEF'] = $prices[$priceType .'VALUE'];
                    }



                    if($haveOffers && $i == 0) {
                        if (Bitrix\Main\Loader::includeModule( "kit.privateprice" ) && Bitrix\Main\Loader::includeModule( "currency" )) {
                            $privatePrice = KitPrivatePriceMain::setPlaceholder($item['OFFERS'][$i]['ID'], CCurrencyLang::CurrencyFormat((!empty($minPrice[$k]) ? $minPrice[$k] : $price['DEF']), CCurrency::GetBaseCurrency()));
                            $privatePriceValue = KitPrivatePriceMain::setPlaceholderValue($item['OFFERS'][$i]['ID'], CCurrencyLang::CurrencyFormat((!empty($minPrice[$k]) ? $minPrice[$k] : $price['DEF']), CCurrency::GetBaseCurrency()));
                        }
                        if (Bitrix\Main\Loader::includeModule( "kit.privateprice" ) && isset($privatePrice) && isset($privatePriceValue)) {
                            echo "<td class='js-price' ".
                                "data-price_name='". $r['NAME'] ."' ".
                                "data-price_code='". $k ."'
                        
                            ". (!empty($minPrice[$k]) ? " data-price_value='". $privatePriceValue : " data-price_value='". $privatePriceValue) ."'".


                                ">";
                            echo $privatePrice;
                            echo "</td>";
                        } else {
                            echo "<td class='js-price' ".
                                "data-price_name='". $r['NAME'] ."' ".
                                "data-price_code='". $k ."'
                            
                            ". (!empty($minPrice[$k]) ? " data-price_value='". $minPrice[$k] : " data-price_value='". $price['DEF']) ."'".

                                ">". (!empty($minPrice[$k]) ?
                                    Loc::getMessage("PRICE_FROM")." ".CCurrencyLang::CurrencyFormat($minPrice[$k], $currency) :
                                    (!empty($price['FORMAT']) ? Loc::getMessage("PRICE_FROM")." ".$price['FORMAT'] : '')) .
                                "</td>";
                        }


                    } else {
                        if (Bitrix\Main\Loader::includeModule( "kit.privateprice" ) && Bitrix\Main\Loader::includeModule( "currency" )) {
                            $privatePrice = KitPrivatePriceMain::setPlaceholder($item['ID'], (isset($price['FORMAT']) && !empty($price['FORMAT']) ? $price['FORMAT'] : ''));
                            $privatePriceValue = KitPrivatePriceMain::setPlaceholderValue($item['ID'], (isset($price['FORMAT']) && !empty($price['FORMAT']) ? $price['FORMAT'] : ''));
                        }

                        if (Bitrix\Main\Loader::includeModule( "kit.privateprice" ) && isset($privatePrice) && isset($privatePriceValue)) {
                            echo "<td class='js-price' ".
                                "data-price_name='". $r['NAME'] ."' ".
                                "data-price_code='". $k ."'". (isset($price['DEF']) && !empty($price['DEF']) ? " data-price_value='". $privatePriceValue : "") ."'".
                                ">".
                                $privatePrice
                                .
                                ( $arParams['SHOW_OLD_PRICE'] == 'Y' && isset($price['HIGHEST_PRICE']) ? "<p style='text-decoration: line-through;'>". $price['HIGHEST_PRICE'] ."</p>" : "" ) .
                                "</td>";
                        } else {
                            echo "<td class='js-price' ".
                                "data-price_name='". $r['NAME'] ."' ".
                                "data-price_code='". $k ."'". (isset($price['DEF']) && !empty($price['DEF']) ? " data-price_value='". $price['DEF'] : "") ."'".
                                ">". (isset($price['FORMAT']) && !empty($price['FORMAT']) ? $price['FORMAT'] : '') .
                                ( $arParams['SHOW_OLD_PRICE'] == 'Y' && isset($price['HIGHEST_PRICE']) ? "<p style='text-decoration: line-through;'>". $price['HIGHEST_PRICE'] ."</p>" : "" ) .
                                "</td>";
                        }


                    }
                }

                break;
            case 'MEASURE':
                if($avaliable != 'Y')
                {
                    echo '<td></td>';
                } else {
                    if (
                        is_array($item['OFFERS'][$i])
                        &&
                        ! empty($item['OFFERS'][$i]['CATALOG_MEASURE_NAME'])
                    ) {
                        $measure = $item['OFFERS'][$i]['CATALOG_MEASURE_NAME'];
                        $measureRatio = $item['OFFERS'][$i]['CATALOG_MEASURE_RATIO'];
                    } else {
                        $measure = $item['CATALOG_MEASURE_NAME'];
                        $measureRatio = $item['CATALOG_MEASURE_RATIO'];
                    }

                    echo '<td>';
                    if($arParams['LIST_SHOW_MEASURE_RATIO'] == "Y") {
                        if(isset($measureRatio) && !empty($measureRatio) && $measureRatio != 1)
                            echo $measureRatio."&nbsp;";
                    }
                    if(isset($measure) && !empty($measure))
                        echo $measure;
                    echo '</td>';
                }

                break;
            case 'QUANTITY':
                if($i === 0 && $haveOffers) {
                    ?><td></td><?
                    break;
                }
                if($avaliable != 'Y')
                {
                    echo '<td></td>';
                }
                else
                {
                    if( is_array($item['OFFERS'][$i]) && !empty($item['OFFERS'][$i]))
                    {
                        $quantity = $item['OFFERS'][$i]['CATALOG_MEASURE_RATIO'];
                        $itemId = $item['OFFERS'][$i]['ID'];
                    }
                    else
                    {
                        $quantity = $item['CATALOG_MEASURE_RATIO'];
                        $itemId = $item['ID'];
                    }

                    $countDecimals = 0;
                    if(is_float($quantity)) {
                        $countDecimals = strlen((string)explode('.', (string)$quantity)[1]);
                    }

                    echo "<td>".
                        ( !empty($quantity)
                            ?
                            '<div class="form-group" data-entity="quantity-block">
                                <input 
                                    type="text"
                                    value="'. ( !empty($_SESSION['BLANK_IDS'][$itemId]) ? $_SESSION['BLANK_IDS'][$itemId]['QNT'] : 0 ) .'" 
                                    class="form-control touchspin-empty"
                                    data-id="'. $itemId .'"
                                    data-iblock="'. ( !empty($item['OFFERS'][$i]) ? $item['OFFERS'][$i]['IBLOCK_ID'] : $item['IBLOCK_ID'] ) .'"
                                    id="'. $areaId .'"
                                    >
                            </div>'
                            :
                            "" ).
                                '<script>
                                    $(".touchspin-empty").TouchSpin({
                                        min: 0,
                                        max: 100000000000,
                                        step: '.( !empty($quantity) ? $quantity : 1 ).',
                                        '. ( is_float($quantity) ? "decimals: ".$countDecimals."," : "" ) .'
                                    });
                                </script>
                            </td>';
                }
                break;
            default:
                if($i === 0 && $haveOffers) {
                    ?><td></td><?
                    break;
                }
                if(
                    isset($item['OFFERS'][$i]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE']) &&
                    !empty($item['OFFERS'][$i]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'])
                )
                {
                    $value = $item['OFFERS'][$i]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'];
                    $propName = $item['OFFERS'][$i]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'];
                }
                else if(
                    isset($item['OFFERS'][$i]['PROPERTIES'][$code]['VALUE']) &&
                    !empty($item['OFFERS'][$i]['PROPERTIES'][$code]['VALUE'])
                )
                {
                    $value = $item['OFFERS'][$i]['PROPERTIES'][$code]['VALUE'];
                    $propName = $item['OFFERS'][$i]['PROPERTIES'][$code]['NAME'];
                }
                else if(
                        isset($item['PROPERTIES'][$code]['LINK_ELEMENT_VALUE']) &&
                        !empty($item['PROPERTIES'][$code]['LINK_ELEMENT_VALUE'])
                )
                {
                    $value = '';
                    foreach ($item['PROPERTIES'][$code]['LINK_ELEMENT_VALUE'] as $DISPLAY_PROPERTY) {
                        $value .= $DISPLAY_PROPERTY['NAME'] ."\n";
                    }
                    $propName = $item['OFFERS'][$i]['PROPERTIES'][$code]['NAME'];
                }
                else
                {
                    $value = $item['PROPERTIES'][$code]['VALUE'];
                    $propName = $item['PROPERTIES'][$code]['NAME'];
                }

                if(is_array($value))
                    $value = implode("\n", $value);

                // Prepare Display props
                if(!empty($arParams['SKU_PROPS'][$code]['XML_MAP'])) {
                    $xmlMap = $arParams['SKU_PROPS'][$code]['XML_MAP'][$value];
                    if(!empty($xmlMap))
                        $value = $arParams['SKU_PROPS'][$code]['VALUES'][$xmlMap]['NAME'];
                }

                echo "<td ".
                    (
                        $arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y' && !empty($value) ?
                        "class='js-product-property' ".
                        "data-propcode='". $code ."'".
                        "data-propname='". $propName ."'".
                        "data-propvalue='". $value ."'":
                        ""
                    )
                    .">" . (isset($value) && !empty($value) ? $value : '') . "</td>";
                break;
        }
    }

    if($i !== 0)
        echo '</tr>';
}