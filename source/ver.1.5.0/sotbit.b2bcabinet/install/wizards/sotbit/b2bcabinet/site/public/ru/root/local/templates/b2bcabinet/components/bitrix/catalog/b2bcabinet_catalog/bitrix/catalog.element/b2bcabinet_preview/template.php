<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Sotbit\B2bCabinet\Helper\Prop;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

$APPLICATION->ShowAjaxHead();

Asset::getInstance()->addCss($this->GetFolder()."/style.css");
Asset::getInstance()->addCss($this->GetFolder()."/assets/fancybox/jquery.fancybox_custom.css");
Asset::getInstance()->addCss($this->GetFolder()."/assets/PerfScroll/perfect-scrollbar.css");
Asset::getInstance()->addCss($this->GetFolder()."/assets/lightBox/css/lightbox.min.css");

Asset::getInstance()->addJs($this->GetFolder()."/script.js");
Asset::getInstance()->addJs($this->GetFolder()."/assets/fancybox/jquery.fancybox_custom.js");
//Asset::getInstance()->addJs($this->GetFolder()."/assets/PerfScroll/perfect-scrollbar.jquery.js");
Asset::getInstance()->addJs($this->GetFolder()."/assets/lightBox/js/lightbox.min.js");

$this->setFrameMode(true);

global $analogProducts;

$templateLibrary = ['popup', 'fx'];
$currencyList = '';

$template = $this->__name;
if ($this->__name == '.default') {
    $template = '';
}

$templateData = [
    'TEMPLATE_THEME'   => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES'       => $currencyList,
    'ITEM'             => [
        'ID'              => $arResult['ID'],
        'IBLOCK_ID'       => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS'       => $arResult['JS_OFFERS'],
    ],
    'OFFERS_ID' => $arResult["OFFERS_ID"]
];
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = [
    'ID'                    => $mainId,
    'DISCOUNT_PERCENT_ID'   => $mainId.'_dsc_pict',
    'STICKER_ID'            => $mainId.'_sticker',
    'BIG_SLIDER_ID'         => $mainId.'_big_slider',
    'BIG_IMG_CONT_ID'       => $mainId.'_bigimg_cont',
    'SLIDER_CONT_ID'        => $mainId.'_slider_cont',
    'OLD_PRICE_ID'          => $mainId.'_old_price',
    'PRICE_ID'              => $mainId.'_price',
    'DISCOUNT_PRICE_ID'     => $mainId.'_price_discount',
    'PRICE_TOTAL'           => $mainId.'_price_total',
    'SLIDER_CONT_OF_ID'     => $mainId.'_slider_cont_',
    'QUANTITY_ID'           => $mainId.'_quantity',
    'QUANTITY_DOWN_ID'      => $mainId.'_quant_down',
    'QUANTITY_UP_ID'        => $mainId.'_quant_up',
    'QUANTITY_MEASURE'      => $mainId.'_quant_measure',
    'QUANTITY_LIMIT'        => $mainId.'_quant_limit',
    'BUY_LINK'              => $mainId.'_buy_link',
    'ADD_BASKET_LINK'       => $mainId.'_add_basket_link',
    'BASKET_ACTIONS_ID'     => $mainId.'_basket_actions',
    'NOT_AVAILABLE_MESS'    => $mainId.'_not_avail',
    'COMPARE_LINK'          => $mainId.'_compare_link',
    'WISH_LINK'             => $mainId.'_wish_link',
    'WISH_LINK_MODIFICATION' => $mainId.'_wish_link_modification',
    'TREE_ID'               => $mainId.'_skudiv',
    'DISPLAY_PROP_DIV'      => $mainId.'_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
    'OFFER_GROUP'           => $mainId.'_set_group_',
    'BASKET_PROP_DIV'       => $mainId.'_basket_prop',
    'SUBSCRIBE_LINK'        => $mainId.'_subscribe',
    'TABS_ID'               => $mainId.'_tabs',
    'TAB_CONTAINERS_ID'     => $mainId.'_tab_containers',
    'SMALL_CARD_PANEL_ID'   => $mainId.'_small_card_panel',
    'TABS_PANEL_ID'         => $mainId.'_tabs_panel',
    'ALL_PRICES'            => $areaId.'_all_prices',
    'MODIFICATION_ID'       => $mainId.'_modification'
];

$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title
    = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

$productPicture = $arResult['PREVIEW_PICTURE'];

if(empty($productPicture['ID']) && !empty($arResult['DETAIL_PICTURE']['ID']))
    $productPicture = $arResult['DETAIL_PICTURE'];
else if(empty($productPicture['ID']) && !empty($arResult['MORE_PHOTO'][0]['ID']))
    $productPicture = $arResult['MORE_PHOTO'][0]['ID'];

if(!empty($productPicture['ID']))
    $productPictureOrigin = CFile::GetPath($productPicture['ID']);


$skuProps = [];
$price = $arResult['actualItem']['ITEM_PRICES'][$arResult['actualItem']['ITEM_PRICE_SELECTED']];
$measureRatio
    = $arResult['actualItem']['ITEM_MEASURE_RATIOS'][$arResult['actualItem']['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT'])
    || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY'])
    ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD',
    $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';

$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y'
    && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $arResult['haveOffers']);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY']
    ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET']
    ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE']
    ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE']
    ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE']
    ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY']
    ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY']
    = $arParams['MESS_RELATIVE_QUANTITY_MANY']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW']
    = $arParams['MESS_RELATIVE_QUANTITY_FEW']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_RELATIVE_QUANTITY_NO'] = $arParams['MESS_RELATIVE_QUANTITY_NO']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_NO');

$positionClassMap = [
    'left'   => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right'  => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top'    => 'product-item-label-top',
];

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'
    && !empty($arParams['DISCOUNT_PERCENT_POSITION'])
) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

?>
<div class="quick-view">
    <h1 class="quick-view__title"><?=$name?></h1>
    <div class="quick-view__content">
        <div class="quick-view__aside aside-block">
            <div class="aside-block__image-wrap image-wrap">
            <a data-lightbox="main-photo"  href="<?=$productPictureOrigin?>"
               class="aside-block__image">
                <img class="image_wrap__image" src="<?=$productPictureOrigin?>" alt="<?=$alt?>">
                <div class="image_wrap__svg-wrap">
                    <svg class="image_wrap__svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M15.504 13.615L11.714 10.392C11.322 10.039 10.903 9.878 10.565 9.893C11.46 8.845 12 7.486 12 6C12 2.686 9.314 0 6 0C2.686 0 0 2.686 0 6C0 9.314 2.686 12 6 12C7.486 12 8.845 11.46 9.893 10.565C9.877 10.903 10.039 11.322 10.392 11.714L13.615 15.504C14.167 16.117 15.068 16.169 15.618 15.619C16.168 15.069 16.116 14.167 15.503 13.616L15.504 13.615ZM6 9.999C3.791 9.999 2 8.208 2 5.999C2 3.79 3.791 1.999 6 1.999C8.209 1.999 10 3.79 10 5.999C10 8.208 8.209 9.999 6 9.999ZM7 2.999H5V4.999H3V6.999H5V8.999H7V6.999H9V4.999H7V2.999Z" fill="white"/>
                    </svg>
                </div>
                </a>
            </div>

            <? // Article
            if(
                !empty($arParams['DETAIL_MAIN_ARTICLE_PROPERTY']) &&
                !empty($arResult['actualItem']['PROPERTIES'][$arParams['DETAIL_MAIN_ARTICLE_PROPERTY']])
            ):?>
                <div class="aside-block__article-wrap article-wrap">
                    <span class="article-wrap__title "><?=$arResult['actualItem']['PROPERTIES'][$arParams['DETAIL_MAIN_ARTICLE_PROPERTY']]['~NAME']?>:</span>
                    <span class="article-wrap__text"><?=$arResult['actualItem']['PROPERTIES'][$arParams['DETAIL_MAIN_ARTICLE_PROPERTY']]['~VALUE']?></span>
                </div>
            <?endif;?>

            <?/* Quantity */?>
            <?if($arParams['SHOW_MAX_QUANTITY'] !== 'N'):?>
                <div class="aside-block__availible-wrap availible-wrap">
                        <span class="availible-wrap__title"><?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?></span>
                        <span class="availible-wrap__text">
                            <?if($arParams['SHOW_MAX_QUANTITY'] === 'M'):?>
                                <?if($arResult['actualItem']['CATALOG_QUANTITY'] == 0)
                                {
                                    echo $arParams['MESS_RELATIVE_QUANTITY_NO'];
                                }elseif((float)$arResult['actualItem']['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                }else{
                                    echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                }?>
                            <?else:?>
                                <?if($arResult['actualItem']['CATALOG_QUANTITY'] == 0 )
                                {
                                    echo $arResult['actualItem']['CATALOG_QUANTITY'].' '.$arResult['actualItem']['ITEM_MEASURE']['TITLE'];
                                }else{
                                    echo $arResult['actualItem']['CATALOG_QUANTITY'].' '.$arResult['actualItem']['ITEM_MEASURE']['TITLE'];
                                }?>
                            <?endif;?>
                        </span>
                </div>
            <?endif;?>


            <?if(!$arResult['haveOffers']):?>
                <?/* Price */?>
                <?if(!empty($price['PRINT_PRICE'])):?>
                    <div class="aside-block__price-wrap price-wrap">
                        <?if (Bitrix\Main\Loader::includeModule( "sotbit.privateprice" ) && Bitrix\Main\Loader::includeModule( "currency" )):
                            $privatePrice = SotbitPrivatePriceMain::setPlaceholder($arResult['ID'], $price['PRINT_PRICE']);?>
                            <?if(isset($privatePrice)):?>
                                <span class="price-wrap__text"><?= $privatePrice; ?></span>
                            <?else:?>
                                <span class="price-wrap__text"><?= $price['PRINT_PRICE'] ?></span>
                            <?endif;?>
                        <?else:?>
                            <span class="price-wrap__text"><?= $price['PRINT_PRICE'] ?></span>
                        <?endif;?>
                        <?if ($arParams['SHOW_OLD_PRICE'] === 'Y'):?>
                            <span class="product_card__block__old_price_product fonts__small_title js-product-old-price" id="<?= $itemIds['OLD_PRICE_ID'] ?>" style="display: <?= ($showDiscount ? '' : 'none') ?>;">
                                <?= $price['PRINT_BASE_PRICE'] ?>
                            </span>
                        <?endif;?>

                        <?if ($arParams['SHOW_OLD_PRICE'] === 'Y'):?>
                            <?/* TODO: сделать в стилях перенос на новую строку*/?>
                            <span class="price-wrap__text" <?if(!$price['DISCOUNT']):?>style="display:none"<?endif;?>>
                                <?= Loc::getMessage('DETAIL_SAVE') ?> <?= $price['PRINT_DISCOUNT'] ?>
                            </span>
                        <?endif;?>
                    </div>
                <?endif;?>

                <?/* Add count */?>
                    <?// TODO: Disable add count?>
                    <?if(false && $arResult['actualItem']["CAN_BUY"]):?>
                        <?// TODO: Добавить JS функционал?>
                        <?if ($arParams['USE_PRODUCT_QUANTITY'] && $price['MIN_QUANTITY'] > 0):?>
                            <div class="aside-block__input-wrap input-wrap form-group" data-entity="quantity-block">
                                <input
                                        type="text"
                                        value="<?=( !empty($_SESSION['BLANK_IDS'][$arResult['ID']]) ? $_SESSION['BLANK_IDS'][$arResult['ID']]['QNT'] : 0 )?>"
                                        class="form-control touchspin-empty"
                                        data-id="<?=$arResult['ID']?>"
                                        data-iblock="<?=$arResult['IBLOCK_ID']?>"
                                        id="<?=$itemIds['QUANTITY_ID']?>"
                                >
                            </div>
                            <script>
                                $(".touchspin-empty").TouchSpin({
                                    min: 0,
                                    max: 100000000000,
                                    step: <?=( !empty($quantity) ? $quantity : 1 )?>, <?=( $quantity < 1 && $quantity > 0 ? "decimals: 2," : "" )?>
                                });
                            </script>
                        <?endif;?>
                    <?endif;?>
                <?else:?>
                    <?if(!empty($arResult['minPrice'])):?>
                        <div class="aside-block__price-wrap price-wrap">
                        <?if (Bitrix\Main\Loader::includeModule( "sotbit.privateprice" ) && Bitrix\Main\Loader::includeModule( "currency" )):
                            $privatePrice = SotbitPrivatePriceMain::setPlaceholder($arResult['ID'], $arResult['minPriceFormat']);?>

                            <?if(isset($privatePrice)):?>
                                <span class="price-wrap__text"><?= $privatePrice; ?></span>
                            <?else:?>
                                <span class="price-wrap__text"><?= $arResult['minPriceFormat'] ?></span>
                            <?endif;?>

                            <?if(!empty($arResult['maxPrice']) && max($arResult['maxPrice']) != min($arResult['minPrice'])):?>
                                <span class="price-wrap__text">&nbsp;- <?=$arResult['maxPriceFormat']?></span>
                            <?endif;?>
                        <?else:?>
                            <span class="price-wrap__text"><?=$arResult['minPriceFormat']?></span>
                            <?if(!empty($arResult['maxPrice']) && max($arResult['maxPrice']) != min($arResult['minPrice'])):?>
                                <span class="price-wrap__text">&nbsp;- <?=$arResult['maxPriceFormat']?></span>
                            <?endif;?>
                        <?endif;?>
                        </div>
                    <?endif;?>
                <?endif;?>


            <ul class="aside-block__tabs-list tabs-list">
                <li class="tabs-list__item active"><?=$arParams['MESS_DESCRIPTION_TAB']?></li>
                <?if($arResult['haveOffers']):?>
                    <li class="tabs-list__item item-tab-offer"><?=Loc::getMessage("TAB_OFFERS_QUANTITY_TITLE")?> <span
                                class="item-tab-offer__quantity"><?=$arResult['countOffers']?></span></li>
                <?endif;?>
                <?if(count($arResult['gallery'])):?>
                    <li class="tabs-list__item item-tab-gallery"><?=Loc::getMessage("TAB_GALLERY_TITLE")?></li>
                <?endif;?>
                <?if(count($arResult['documents'])):?>
                    <li class="tabs-list__item"><?=Loc::getMessage("TAB_DOCUMENTS_TITLE")?></li>
                <?endif;?>
            </ul>
        </div>
        <div class="quick-view__main-content">

            <div class="tabs-content active">
                <div class="description-block">
                    <div class="description-block__content">
                        <h2 class="description-block__title"><?=$arParams['MESS_DESCRIPTION_TAB']?></h2>
                        <p class="description-block__text">
                        <?if ($showDescription)
                        {
                            if($arResult['PREVIEW_TEXT'] != '' && ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S' || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')))
                                echo $arResult['PREVIEW_TEXT'];
                            if ($arResult['DETAIL_TEXT'] != '')
                                echo $arResult['DETAIL_TEXT'];
                        }?>
                        </p>
                    </div>
                    <?// Brands
                    // TODO: Output brands image
                    /*if(!empty($arParams['DETAIL_MAIN_BRAND_PROPERTY'])):?>
                        <div class="description-block__logo-wrapper">
                            <?=$arResult['actualItem']['PROPERTIES'][$arParams['DETAIL_MAIN_BRAND_PROPERTY']]['~VALUE']?>
                            <img class="description-block__logo" src="<?=SITE_TEMPLATE_PATH?>/components/bitrix/catalog/
                            .default/bitrix/catalog.element/b2bcabinet_preview/img/img_brand.png" alt="">
                        </div>
                    <?endif;*/?>
                </div>

                <?if($arResult['haveOffers'] && count($arResult['SKU_PROPS'])):?>
                    <div class="table-wrap">
                        <h2 class="table-wrap__title"><?=Loc::getMessage('TAB_OFFERS_TITLE')?></h2>
                        <table class="table_wrap__table">
                            <?foreach ($arResult['SKU_PROPS'] as $skuProps):?>

                                <?$values = array_diff(array_map(function ($v){
                                    return $v['NAME'];
                                }, $skuProps['VALUES']), ['-']);?>

                                <tr class="table_wrap__row">
                                    <td class="table_wrap__col"><?=$skuProps['NAME']?></td>
                                    <td class="table_wrap__col">
                                        <?= (is_array($skuProps['VALUES']) ?
                                            implode(', ', $values) :
                                            $skuProps['VALUES']) ?>
                                    </td>
                                </tr>
                            <?endforeach;?>
                        </table>
                    </div>
                <?endif;?>

                <?if(count($arResult['DISPLAY_PROPERTIES'])):?>
                    <div class="table-wrap">
                        <h2 class="table-wrap__title"><?=Loc::getMessage("TAB_GENERAL_CHARACTERISTICS")?></h2>
                        <table class="table_wrap__table">
                            <?foreach ($arResult['DISPLAY_PROPERTIES'] as $property):?>
                            <tr class="table_wrap__row">
                                <td class="table_wrap__col"> <?= $property['NAME'] ?></td>
                                <td class="table_wrap__col">
                                    <?= (is_array($property['DISPLAY_VALUE']) ?
                                        implode(', ', $property['DISPLAY_VALUE']) :
                                        $property['DISPLAY_VALUE']) ?></td>
                            </tr>
                            <?endforeach;?>
                        </table>
                    </div>
                <?endif;?>
            </div>

            <?/* Offers tab */?>
            <?if($arResult['haveOffers']):?>
                <div class="tabs-content">
                    <div class="offers-block">
                        <h2 class="offers-block__title"><?=Loc::getMessage("TAB_OFFERS_QUANTITY_TITLE")?></h2>

                        <?/* Filter*/?>
                        <?if(count($arResult['SKU_PROPS'])):?>
                            <div class="offers-block__filter filter-block">

                                <?foreach ($arResult['SKU_PROPS'] as $skuProperty):
                                    $propertyId = $skuProperty['ID'];
                                    if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                                        continue;

                                    $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
                                    ?>
                                    <div class="filter-block__item filter-prop-item">
                                    <p class="filter-block__title"><?=$skuProperty['NAME']?></p>
                                        <div class="filter-prop-item__item-wrap">

                                        <?foreach ($skuProperty['VALUES'] as &$value):
                                            if ($value['NAME'] == '-') {
                                                continue;
                                            }
                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                            if ($skuProperty['SHOW_MODE'] == "PICT" && !empty($value['PICT']['ID'])):?>
                                                <div class="filter-prop-item__item filter-prop-item__item-img" data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>">
                                                    <img src="<?= $value['PICT']['SRC'] ?>"
                                                         alt="">
                                                </div>
                                            <?else:?>
                                                <div class="filter-prop-item__item filter-prop-item__item-text" data-name="<?= $skuProperty['NAME'] ?>" data-value="<?= $value['NAME']?>">
                                                    <span><?= $value['NAME'] ?></span>
                                                </div>
                                            <?endif;?>
                                        <?endforeach;?>
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        <?endif;?>

                        
                            <div class="offers-block__btn-openup-wrap">
                                <div class="offers-block__btn-openup btn-openup">
                                    <span class="btn-openup__text"></span>
                                </div>
                            </div>
                     

                        <?/* Offers */?>
                        <?$i = 0;
                        foreach ($arResult['OFFERS'] as $offer):?>
                            <div class="offers-block__item offer-item <?if($i == 0):?>offer-item-first<?endif?>">
                            <a href="<?=$offer['productPictureLarge']['SRC']?>" class="offer-item__img-wrap" data-lightbox="<?=randString(10)?>">
                                <img class="offer-item__img" src="<?=$offer['productPicture']['src']?>" alt="<?=$offer['NAME']?>">
                            </a>

                            <div class="offer-group-wrap">

                                <??>
                                <div class="offer-item__title-block">
                                    <p class="offer-item__title"><?=($offer['NAME'] ?? $name)?></p>
                                    <p class="offer-item__status <?=$offer['productAvailable']['class']?>"><?=Loc::getMessage($offer['productAvailable']['msg'])?></p>
                                </div>


                                <div class="offer-item__properties prop-block">
                                    <ul class="prop-block__list">
                                        <?if(!empty($offer['skuProps'])):?>
                                            <?foreach ($offer['skuProps'] as $code => $sku):?>
                                                <li class="prop-block__item" <?if(!in_array($sku['type'], ['E', 'F', 'G'])):?>data-propname="<?= $sku['name'] ?>" data-propvalue="<?= $sku['value']?>"<?endif;?>>
                                                    <span class="prop-block__item-head">
                                                        <?=$sku['name']?>:
                                                    </span> <?=$sku['value']?>
                                                </li>
                                            <?endforeach;?>
                                        <?endif;?>
                                    </ul>
                                    <?if(count($offer['skuProps']) > 3):?>
                                        <span class="prop-block__show-more"><?=Loc::getMessage('PRODUCT_SKU_PROP_SEE_ALL')?></span>
                                    <?endif;?>
                                </div>

                                <?/* TODO: offers quantity block ?>
                                    <div class="offer-item__quantity-block">
                                    <div class="offer-item__quantity quantity-wrap">
                                        <span class="quantity-wrap__minus">–</span>
                                        <input class="quantity-wrap__input" type="text" value="0">
                                        <span class="quantity-wrap__plus">+</span>
                                    </div>
                                </div><?*/?>
                                <?if(!empty($offer['productPrices'])):?>
                                    <div class="offer-item__price-wrap">
                                    <?foreach ($offer['productPrices'] as $code => $price):?>
                                        <p class="offer-item__price-title">  <?=$price['title']?>:
                                            <span class="offer-item__price"><?=$price['price']?></span>
                                        </p>
                                    <?endforeach;?>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>

                            <?$i++;
                            endforeach;?>
                    </div>
                </div>
            <?endif;?>

            <?if(count($arResult['gallery'])):?>
            <div class="tabs-content">
                <div class="gallery-block">
                    <h2 class="gallery-block__title"><?=Loc::getMessage("TAB_GALLERY_TITLE")?></h2>
                    <div class="gallery-block__content gallery-content">

                        <?
                        foreach ($arResult['gallery'] as $src)
                        {
                            ?>
                            <div class="gallery-content__item gallery-item">
                                <a data-fancybox="gallery"  href="<?=$src?>" class="gallery-item__link">
                                    <img class="gallery-item__image" src="<?=$src?>" alt="">
                                    <div class="gallery-item__svg-wrap">
                                        <svg class="gallery-item__svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M15.504 13.615L11.714 10.392C11.322 10.039 10.903 9.878 10.565 9.893C11.46 8.845 12 7.486 12 6C12 2.686 9.314 0 6 0C2.686 0 0 2.686 0 6C0 9.314 2.686 12 6 12C7.486 12 8.845 11.46 9.893 10.565C9.877 10.903 10.039 11.322 10.392 11.714L13.615 15.504C14.167 16.117 15.068 16.169 15.618 15.619C16.168 15.069 16.116 14.167 15.503 13.616L15.504 13.615ZM6 9.999C3.791 9.999 2 8.208 2 5.999C2 3.79 3.791 1.999 6 1.999C8.209 1.999 10 3.79 10 5.999C10 8.208 8.209 9.999 6 9.999ZM7 2.999H5V4.999H3V6.999H5V8.999H7V6.999H9V4.999H7V2.999Z" fill="white"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                            <?
                        }
                        ?>
                        </div>
                </div>
                <div class="gallery-slider">
                        <div class="gallery-slider__btn-wrap btn-back-gallery">
                             <span class="btn-back-gallery__text">
                                 <?=Loc::getMessage("BACK")?>
                             </span>
                        </div>
                        <div class="gallery-slider__wrapper-slider"></div>
                </div>
            </div>
            <?endif;?>

            <?
            if(!empty($arResult['documents'])):?>
                <div class="tabs-content">
                    <div class="documents-block">
                        <h2 class="documents-block__title"><?=Loc::getMessage("TAB_DOCUMENTS_TITLE")?></h2>
                        <?foreach($arResult['documents'] as $file):?>
                            <div class="documents-block__item document-item">
                                <svg class="document-item__svg" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0)">
                                    <path d="M28.682 7.158C27.988 6.212 27.02 5.104 25.958 4.042C24.896 2.98 23.788 2.012 22.842 1.318C21.23 0.136 20.448 0 20 0H4.5C3.122 0 2 1.122 2 2.5V29.5C2 30.878 3.122 32 4.5 32H27.5C28.878 32 30 30.878 30 29.5V10C30 9.552 29.864 8.77 28.682 7.158ZM24.542 5.458C25.502 6.418 26.254 7.282 26.81 8H21.998V3.19C22.716 3.746 23.582 4.498 24.54 5.458H24.542ZM28 29.5C28 29.772 27.772 30 27.5 30H4.5C4.23 30 4 29.772 4 29.5V2.5C4 2.23 4.23 2 4.5 2C4.5 2 19.998 2 20 2V9C20 9.552 20.448 10 21 10H28V29.5Z" fill="#3E495F"/>
                                    <path d="M23 26H9C8.448 26 8 25.552 8 25C8 24.448 8.448 24 9 24H23C23.552 24 24 24.448 24 25C24 25.552 23.552 26 23 26Z" fill="#3E495F"/>
                                    <path d="M23 22H9C8.448 22 8 21.552 8 21C8 20.448 8.448 20 9 20H23C23.552 20 24 20.448 24 21C24 21.552 23.552 22 23 22Z" fill="#3E495F"/>
                                    <path d="M23 18H9C8.448 18 8 17.552 8 17C8 16.448 8.448 16 9 16H23C23.552 16 24 16.448 24 17C24 17.552 23.552 18 23 18Z" fill="#3E495F"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0">
                                    <rect width="32" height="32" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                                <div class="document-item__wrap">
                                    <span class="document-item__title"><?=$file['NAME']?></span>
                                    <a href="<?=$file['LINK']?>"
                                       class="document-item__download-link"><?=Loc::getMessage("DOWNLOAD")?>
                                        <?=$file['SIZE']?></a>
                                </div>
                            </div>
                        <?endforeach;?>

                        </div>
                </div>
            <?endif;?>

            <div class="quick-view__shadow"></div>
        </div>
    </div>
</div>



<?
$allPrices = [];

if ($arResult['haveOffers'] && $arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    foreach ($arResult['OFFERS'] as $offer)
    {
        $allPrices[$offer['ID']] = $offer['ITEM_ALL_PRICES'][$arResult['actualItem']['ITEM_PRICE_SELECTED']]["PRICES"];
    }
} elseif($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    $allPrices[$arResult['ID']] = $arResult['ITEM_ALL_PRICES'][$arResult['actualItem']['ITEM_PRICE_SELECTED']]["PRICES"];
}

if ($arResult['haveOffers'])
{
    $offerIds = [];
    $offerCodes = [];

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
    {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS'])
        {
            if (!empty($jsOffer['DISPLAY_PROPERTIES']))
            {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
                {
                    $current
                        = '<div class="product-preview-info-block-property fonts__middle_comment"><span class="property-title">'
                        .$property['NAME']
                        .': </span><span class="property-value">'.(
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ).'</span></div>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
                    {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
        {
            $strPriceRangesRatio = '('.Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    [
                        '#RATIO#' => ($useRatio
                                ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                                : '1'
                            ).' '.$measureName,
                    ]
                ).')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
            {
                if ($range['HASH'] !== 'ZERO-INF')
                {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
                    {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
                        {
                            break;
                        }
                    }

                    if ($itemPrice)
                    {
                        $strPriceRanges .= '<dt>'.Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                [
                                    '#FROM#' => $range['SORT_FROM'].' '
                                        .$measureName,
                                ]
                            ).' ';

                        if (is_infinite($range['SORT_TO']))
                        {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        }else{
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                ['#TO#' => $range['SORT_TO'].' '.$measureName]
                            );
                        }

                        $strPriceRanges .= '</dt><dd>'.($useRatio
                                ? $itemPrice['PRINT_RATIO_PRICE']
                                : $itemPrice['PRINT_PRICE']).'</dd>';
                    }
                }
            }

            unset($range, $itemPrice);
        }

        $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
        $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
        $jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
        $jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
    }

    $templateData['OFFER_IDS'] = $offerIds;
    $templateData['OFFER_CODES'] = $offerCodes;
    unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

    $jsParams = [
        'CONFIG'          => [
            'USE_CATALOG'              => $arResult['CATALOG'],
            'SHOW_QUANTITY'            => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE'               => true,
            'SHOW_DISCOUNT_PERCENT'    => $arParams['SHOW_DISCOUNT_PERCENT']
                === 'Y',
            'SHOW_OLD_PRICE'           => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT'          => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE'          => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS'           => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP'              => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE'        => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION'     => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP'         => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY'        => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS'             => true,
            'USE_SUBSCRIBE'            => $showSubscribe,
            'SHOW_SLIDER'              => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL'          => $arParams['SLIDER_INTERVAL'],
            'ALT'                      => $alt,
            'TITLE'                    => $title,
            'SITE_DIR'                 => SITE_DIR,
            'SITE_ID'                  => SITE_ID,
            'IBLOCK_ID'                => $arParams['IBLOCK_ID'],
            'MAGNIFIER_ZOOM_PERCENT'   => 200,
//            'SHOW_ZOOM'                => Config::get('SHOW_ZOOM_'.$template),
            'USE_ENHANCED_ECOMMERCE'   => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME'          => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY'           => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null,
        ],
        'PRODUCT_TYPE'    => $arResult['CATALOG_TYPE'],
        'VISUAL'          => $itemIds,
        'DEFAULT_PICTURE' => [
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE'  => $arResult['DEFAULT_PICTURE'],
        ],
        'MESS'            => [
            'NO' => $arParams['~MESS_RELATIVE_QUANTITY_NO'],
        ],
        'PRODUCT'         => [
            'ID'         => $arResult['ID'],
            'ACTIVE'     => $arResult['ACTIVE'],
            'NAME'       => $arResult['~NAME'],
            'CATEGORY'   => $arResult['CATEGORY_PATH'],
            'ALL_PRICES' => $allPrices,
            'VIDEOS'     => $arResult['VIDEOS'],
        ],
        'BASKET'          => [
            'QUANTITY'         => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'BASKET_URL_AJAX' => SITE_DIR.'include/ajax/buy.php',
            'SKU_PROPS'        => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        ],
        'WISH'            => [
            'WISHES'            => [],
            'WISH_URL_TEMPLATE' => SITE_DIR.'include/ajax/wish.php',
        ],
        'OFFERS'          => $arResult['JS_OFFERS'],
        'OFFER_SELECTED'  => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS'      => $skuProps,
    ];
}else{
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
    {
        ?>
		<div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach (
                    $arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo
                ) {
                    ?>
					<input type="hidden"
					       name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
					       value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                    <?
                    unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                }
            }

            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties)
            {
                ?>
				<table>
                    <?
                    foreach (
                        $arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo
                    ) {
                        ?>
						<tr>
							<td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
							<td>
                                <?
                                if (
                                    $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE']
                                    === 'L'
                                    && $arResult['PROPERTIES'][$propId]['LIST_TYPE']
                                    === 'C'
                                ) {
                                    foreach (
                                        $propInfo['VALUES'] as $valueId =>
                                        $value
                                    ) {
                                        ?>
										<label>
											<input type="radio"
											       name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
											       value="<?= $valueId ?>" <?= ($valueId
                                            == $propInfo['SELECTED']
                                                ? '"checked"' : '') ?>>
                                            <?= $value ?>
										</label>
										<br>
                                        <?
                                    }
                                } else {
                                    ?>
									<select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                        <?
                                        foreach (
                                            $propInfo['VALUES'] as $valueId =>
                                            $value
                                        ) {
                                            ?>
											<option value="<?= $valueId ?>" <?= ($valueId
                                            == $propInfo['SELECTED']
                                                ? '"selected"' : '') ?>>
                                                <?= $value ?>
											</option>
                                            <?
                                        }
                                        ?>
									</select>
                                    <?
                                }
                                ?>
							</td>
						</tr>
                        <?
                    }
                    ?>
				</table>
                <?
            }
            ?>
		</div>
        <?
    }
    unset($emptyProductProperties);
}

?>
	<script>
        BX.message({
            JS_HIDE: '<?=GetMessageJS('JS_HIDE')?>',
            JS_FILTER: '<?=GetMessageJS('JS_FILTER')?>',
            JS_SHOW_ALL: '<?=GetMessageJS('JS_SHOW_ALL')?>',
        });

        initQuickView();

	</script>
<?
unset($arResult['actualItem'], $itemIds, $jsParams);
