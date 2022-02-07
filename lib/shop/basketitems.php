<?php
namespace Kit\B2BCabinet\Shop;

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

class BasketItems extends \KitB2BCabinet
{
    private $items = array();
    private $sum = 0;
    private $qnt = 0;

    /**
     * BasketItems constructor.
     * @param array $productsFilter
     * @param array $imgProp
     */
    public function __construct(

        $productsFilter = array(),
        $imgProp = array(
            'width' => 70,
            'height' => 70,
            'resize' => BX_RESIZE_IMAGE_PROPORTIONAL,
            'noPhoto' => ''
        )
    )
    {
        $arFilter = $this->getUserFilter();
        if ($this->checkInstalledModules(array(
                'catalog',
                'currency',
                'sale'
            )) && $arFilter)
        {

//            $arFilter = array_merge($arFilter, $productsFilter);

            $idProducts = array();
            $qnt = 0;
            $sum = 0;
            $currency = 'RUB';
            $dbItems = \CSaleBasket::GetList(
                array(
                    "NAME" => "ASC",
                    "ID" => "ASC"
                ),
                $arFilter,
                false,
                false,
                array(
                    "ID",
                    "NAME",
                    "CALLBACK_FUNC",
                    "MODULE",
                    "PRODUCT_ID",
                    "QUANTITY",
                    "DELAY",
                    "CAN_BUY",
                    "PRICE",
                    "WEIGHT",
                    "DETAIL_PAGE_URL",
                    "CURRENCY",
                    "VAT_RATE",
                    "CATALOG_XML_ID",
                    "MEASURE_NAME",
                    "PRODUCT_XML_ID",
                    "SUBSCRIBE",
                    "DISCOUNT_PRICE",
                    "PRODUCT_PROVIDER_CLASS",
                    "TYPE",
                    "SET_PARENT_ID",
                    "BASE_PRICE",
                    "PRODUCT_PRICE_ID",
                    'CUSTOM_PRICE'
                )
            );
            while ($arItem = $dbItems->GetNext(true, false))
            {
                if (\CSaleBasketHelper::isSetItem($arItem))
                    continue;
                $currency = $arItem['CURRENCY'];
                array_push($this->items, new BasketItem($arItem));

                $idProducts[$arItem['PRODUCT_ID']] = $arItem['PRODUCT_ID'];

                $qnt += $arItem['QUANTITY'];
                $sum += $arItem['QUANTITY'] * $arItem['PRICE'];
            }
            $imgProducts = $this->getImages($idProducts, $imgProp);

            if(!is_array($imgProducts))
            {
                $imgProducts = array();
            }

            if ($imgProducts)
            {
                foreach ($imgProducts as $idProduct => $img)
                {
                    foreach ($this->items as $key => $item)
                    {
                        if ($this->items[$key]->getElement()->getId() == $idProduct)
                        {
                            $this->items[$key]->getElement()->setImg($img);
                        }
                    }
                }
            }

            $this->qnt = $qnt;
            $this->sum = \CCurrencyLang::CurrencyFormat($sum,$currency);
        }
    }

    public function getUserFilter()
    {
        $fUserID = (int)\CSaleBasket::GetBasketUserID(true);
        return ($fUserID > 0)
            ? array(
                "FUSER_ID" => $fUserID,
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            )
            : null; // no basket for current user
    }

    /**
     * @param array $ids
     * @param array $arSku2Parent
     * @param array $imgProp
     * @return array
     */
    public function getImages(
        $ids = array(),
        $imgProp = array(
            'width' => 70,
            'height' => 70,
            'resize' => BX_RESIZE_IMAGE_PROPORTIONAL,
            'noPhoto' => ''
        )
    )
    {
        if(
            Loader::includeModule('kit.missshop') ||
            Loader::includeModule('kit.mistershop')
        )
        {
            return $this->getImagesMissShop($ids, $imgProp);
        }
        else
        {
            return $this->getImagesDefault($ids, $imgProp);
        }
    }

    public function getImagesMissShop(
        $ids = array(),
        $imgProp = array(
            'width' => 70,
            'height' => 70,
            'resize' => BX_RESIZE_IMAGE_PROPORTIONAL,
            'noPhoto' => ''
        )
    )
    {
        $module = 'kit.missshop';
        if(Loader::includeModule('kit.mistershop'))
        {
            $module = 'kit.mistershop';
        }
        if(Loader::includeModule('kit.b2bcabinet'))
        {
            $module = 'kit.b2bcabinet';
        }

        $products = array();
        $offers = array();
        $product2offer = array();

        if($ids)
        {
            foreach($ids as $id)
            {
                $arParent = \CCatalogSku::GetProductInfo($id);
                if ($arParent)
                {
                    $products[] = $arParent["ID"];
                    $offers[] = $id;
                    $product2offer[$arParent["ID"]][$id] = $id;
                }
                else
                {
                    $products[] = $id;
                }
            }
        }
        $imagesFromOffers = Option::get($module,'PICTURE_FROM_OFFER','Y');
        $colorCode = Option::get($module,'OFFER_COLOR_PROP','Y');
        $propMorePhotoOffers = Option::get($module,'MORE_PHOTO_OFFER_PROPS','');
        $propMorePhotoProduct = Option::get($module,'MORE_PHOTO_PRODUCT_PROPS','');
        $arOffers = array();
        $arProducts = array();
        $arColors = array();
        $arColorsOffers = array();
        if($imagesFromOffers == 'Y')
        {
            if($offers)
            {
                $rsElement = \CIBlockElement::GetList(array(), array(
                    "=ID" => $offers
                ), false, false, array(
                    "ID",
                    "PREVIEW_PICTURE",
                    "DETAIL_PICTURE",
                    "PROPERTY_".$propMorePhotoOffers,
                ));
                while ($offer = $rsElement->Fetch())
                {
                    if(!$imagesProducts[$offer['ID']])
                    {
                        if($offer['PREVIEW_PICTURE'])
                        {
                            $imagesProducts[$offer['ID']] = $offer['PREVIEW_PICTURE'];
                        }
                        elseif($offer['DETAIL_PICTURE'])
                        {
                            $imagesProducts[$offer['ID']] = $offer['DETAIL_PICTURE'];
                        }
                        elseif($offer['PROPERTY_'.$propMorePhotoOffers.'_VALUE'])
                        {
                            $imagesProducts[$offer['ID']] = $offer['PROPERTY_'.$propMorePhotoOffers.'_VALUE'];
                        }
                    }
                }
                if($products)
                {
                    $rsElement = \CIBlockElement::GetList(array(), array(
                        "=ID" => $products
                    ), false, false, array(
                        "ID",
                        "PREVIEW_PICTURE",
                        "DETAIL_PICTURE",
                    ));
                    while ($product = $rsElement->Fetch())
                    {
                        if($product['PREVIEW_PICTURE'])
                        {
                            $imagesProducts[$product['ID']] = $product['PREVIEW_PICTURE'];
                        }
                        elseif($product['DETAIL_PICTURE'])
                        {
                            $imagesProducts[$product['ID']] = $product['DETAIL_PICTURE'];
                        }

                        if($product2offer[$product['ID']])
                        {
                            foreach($product2offer[$product['ID']] as $idProduct => $idOffer)
                            {
                                if(!$imagesProducts[$idOffer])
                                {
                                    $imagesProducts[$idOffer] = $imagesProducts[$product['ID']];
                                }
                            }
                        }
                    }
                }
                if($ids)
                {
                    foreach($ids as $id)
                    {
                        $Image = $imagesProducts[$id];
                        if($Image)
                        {
                            $Image = \CFile::ResizeImageGet(
                                $Image,
                                array(
                                    "width" => $imgProp['width'],
                                    "height" => $imgProp['height']
                                ),
                                $imgProp['resize'],
                                true
                            );
                        }

                        if($Image)
                        {
                            $return[$id] = $Image;
                        }
                        else
                        {
                            $noPhoto = \CFile::getList(array(),array('MODULE_ID' =>'kit.b2bcabinet'))->fetch();
                            if(!$noPhoto['ID'])
                            {
                                $noPhoto = \CFile::MakeFileArray($imgProp['noPhoto']);
                                $noPhoto['MODULE_ID'] = 'kit.b2bcabinet';
                                $imgNoPhoto = \CFile::SaveFile($noPhoto, $noPhoto['tmp_name']);
                                $noPhoto['ID'] = $imgNoPhoto;
                            }
                            $Image = \CFile::ResizeImageGet(
                                $noPhoto['ID'],
                                array(
                                    "width" => $imgProp['width'],
                                    "height" => $imgProp['height']
                                ),
                                $imgProp['resize'],
                                true
                            );
                            $return[$id] = $Image;
                        }
                    }
                }
            }
        }
        else
        {
            if($offers)
            {
                $rsElement = \CIBlockElement::GetList(array(), array(
                    "=ID" => $offers
                ), false, false, array(
                    "ID",
                    "PREVIEW_PICTURE",
                    "DETAIL_PICTURE",
                    "PROPERTY_".$propMorePhotoOffers,
                    "PROPERTY_".$colorCode,

                ));
                while ($offer = $rsElement->Fetch())
                {
                    $arOffers[$offer['ID']] = $offer;
                    $arColors[$offer['PROPERTY_'.$colorCode.'_VALUE']] = $offer['PROPERTY_'.$colorCode.'_VALUE'];
                    if(!$arColorsOffers[$offer['PROPERTY_'.$colorCode.'_VALUE']])
                    {
                        $arColorsOffers[$offer['PROPERTY_'.$colorCode.'_VALUE']] = array($offer['ID']);
                    }
                    else
                    {
                        $arColorsOffers[$offer['PROPERTY_'.$colorCode.'_VALUE']][] = $offer['ID'];
                    }
                }
            }
            if($products)
            {
                $rsElement = \CIBlockElement::GetList(array(), array(
                    "=ID" => $products
                ), false, false, array(
                    "ID",
                    "PREVIEW_PICTURE",
                    "DETAIL_PICTURE",
                    "PROPERTY_".$propMorePhotoProduct,
                ));
                while ($product = $rsElement->Fetch())
                {
                    if(!$arProducts[$product['ID']])
                    {
                        $arProducts[$product['ID']] = $product;
                        if($arProducts[$product['ID']]['PROPERTY_'.$propMorePhotoProduct.'_VALUE'])
                        {
                            $arProducts[$product['ID']]['PROPERTY_'.$propMorePhotoProduct.'_VALUE'] = array($arProducts[$product['ID']]['PROPERTY_'.$propMorePhotoProduct.'_VALUE']);
                        }
                        else
                        {
                            $arProducts[$product['ID']]['PROPERTY_'.$propMorePhotoProduct.'_VALUE'] = array();
                        }
                    }
                    else
                    {
                        $arProducts[$product['ID']]['PROPERTY_'.$propMorePhotoProduct.'_VALUE'][] = $product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'];
                    }
                }
            }

            if($arColors)
            {
                $arProp = \CIBlockProperty::GetList( Array (
                    "sort" => "asc",
                    "name" => "asc"
                ), array('CODE' => $colorCode))->fetch();
                if (isset( $arProp['USER_TYPE'] ) && $arProp['USER_TYPE'] == 'directory' && isset( $arProp["USER_TYPE_SETTINGS"]["TABLE_NAME"] ))
                {
                    $Table = $arProp["USER_TYPE_SETTINGS"]["TABLE_NAME"];
                }
                if($Table)
                {
                    $HL = \Bitrix\Highloadblock\HighloadBlockTable::getList( array (
                        "filter" => array (
                            'TABLE_NAME' => $Table
                        )
                    ) );
                    while ( $HLBlock = $HL->Fetch() )
                    {
                        $HLEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $HLBlock )->getDataClass();
                        $HLProps = $HLEntity::getList( array (
                            'select' => array (
                                '*'
                            ),
                            'order' => array (),
                            'filter' => array('UF_XML_ID' => $arColors)
                        ) );
                        while ( $HLProp = $HLProps->fetch() )
                        {
                            unset($arColors[$HLProp['UF_XML_ID']]);
                            $arColors[$HLProp['UF_NAME']] = $HLProp;
                            $arColorsOffers[$HLProp['UF_NAME']] = $arColorsOffers[$HLProp['UF_XML_ID']];
                            unset($arColorsOffers[$HLProp['UF_XML_ID']]);
                        }

                    }
                }
            }
            $images = array();
            $imagesProducts = array();
            if($arProducts)
            {
                foreach($arProducts as $idProduct => $product)
                {
                    $imagesProducts[$idProduct] = array();
                    if ($product['PREVIEW_PICTURE'])
                    {
                        $images[] = $product['PREVIEW_PICTURE'];
                        $imagesProducts[$idProduct][] = $product['PREVIEW_PICTURE'];
                    }
                    if ($product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'])
                    {
                        if(is_array($product['PROPERTY_'.$propMorePhotoProduct.'_VALUE']))
                        {
                            foreach($product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'] as $img)
                            {
                                $images[] = $img;
                                $imagesProducts[$idProduct][] = $img;

                            }
                        }
                        elseif($product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'])
                        {
                            $images[] = $product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'];
                            $imagesProducts[$idProduct][] = $product['PROPERTY_'.$propMorePhotoProduct.'_VALUE'];
                        }
                    }
                }

                if($images)
                {
                    $files = array();
                    $fileDescriptions = array();
                    $rs = \CFile::GetList( array(),array('@ID' => $images) );
                    while($file = $rs->fetch())
                    {
                        $files[$file['ID']] = $file;
                        $arDescr = '';
                        if($file["DESCRIPTION"])
                        {
                            $arDescr = explode( "_", mb_strtolower( $file["DESCRIPTION"] ) );
                            $fileDescriptions[$file['ID']][$arDescr[0]] = $arDescr[1];
                        }
                        else
                        {
                            $fileDescriptions[$file['ID']] = '';
                        }
                    }
                }
            }

            $idImages = array();
            if($ids)
            {
                foreach($ids as $id)
                {
                    $Image = array();
                    if(in_array($id, $offers))
                    {
                        foreach($arColorsOffers as $color=>$colorOffers)
                        {
                            if(in_array($id, $colorOffers))
                            {
                                break;
                            }
                        }

                        foreach($product2offer as $idProduct => $productOffers)
                        {
                            if(in_array($id, $productOffers))
                            {
                                break;
                            }
                        }
                        if($imagesProducts[$idProduct])
                        {
                            $min = 0;
                            foreach($imagesProducts[$idProduct] as $idfile)
                            {
                                if($fileDescriptions[$idfile][strtolower($color)])
                                {
                                    if(!$min || $fileDescriptions[$idfile][strtolower($color)] < $min)
                                    {
                                        $min = $fileDescriptions[$idfile][strtolower($color)];
                                        $Image = $files[$idfile];
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if($imagesProducts[$idProduct])
                        {
                            foreach($imagesProducts[$idProduct] as $idfile)
                            {
                                $Image = $files[$idfile];
                                break;
                            }
                        }
                    }
                    if($Image)
                    {
                        $Image = \CFile::ResizeImageGet(
                            $Image,
                            array(
                                "width" => $imgProp['width'],
                                "height" => $imgProp['height']
                            ),
                            $imgProp['resize'],
                            true
                        );
                    }

                    if($Image)
                    {
                        $return[$id] = $Image;
                    }
                    else
                    {
                        $noPhoto = \CFile::getList(array(),array('MODULE_ID' =>'kit.b2bcabinet'))->fetch();
                        if(!$noPhoto['ID'])
                        {
                            $noPhoto = \CFile::MakeFileArray($imgProp['noPhoto']);
                            $noPhoto['MODULE_ID'] = 'kit.b2bcabinet';
                            $imgNoPhoto = \CFile::SaveFile($noPhoto, $noPhoto['tmp_name']);
                            $noPhoto['ID'] = $imgNoPhoto;
                        }
                        $Image = \CFile::ResizeImageGet(
                            $noPhoto['ID'],
                            array(
                                "width" => $imgProp['width'],
                                "height" => $imgProp['height']
                            ),
                            $imgProp['resize'],
                            true
                        );
                        $return[$id] = $Image;
                    }
                }
            }
        }

        return $return;

    }
    public function getImagesDefault(
        $ids = array(),
        $imgProp = array(
            'width' => 70,
            'height' => 70,
            'resize' => BX_RESIZE_IMAGE_PROPORTIONAL,
            'noPhoto' => ''
        )
    )
    {
        $return = array();
        $imagesProducts = array();

        if($ids)
        {
            $rsElement = \CIBlockElement::GetList(array(), array(
                "ID" => $ids
            ), false, false, array(
                "ID",
                "PREVIEW_PICTURE",
                "DETAIL_PICTURE",
            ));
            while ($elem = $rsElement->Fetch())
            {
                if($elem['PREVIEW_PICTURE'])
                {
                    $imagesProducts[$elem['ID']] = $elem['PREVIEW_PICTURE'];
                }
                elseif($elem['DETAIL_PICTURE'])
                {
                    $imagesProducts[$elem['ID']] = $elem['DETAIL_PICTURE'];
                }
            }
            foreach($ids as $id)
            {
                $Image = $imagesProducts[$id];
                if($Image)
                {
                    $Image = \CFile::ResizeImageGet(
                        $Image,
                        array(
                            "width" => $imgProp['width'],
                            "height" => $imgProp['height']
                        ),
                        $imgProp['resize'],
                        true
                    );
                }

                if($Image)
                {
                    $return[$id] = $Image;
                }
                else
                {
                    $noPhoto = \CFile::getList(array(),array('MODULE_ID' =>'kit.b2bcabinet'))->fetch();
                    if(!$noPhoto['ID'])
                    {
                        $noPhoto = \CFile::MakeFileArray($imgProp['noPhoto']);
                        $noPhoto['MODULE_ID'] = 'kit.b2bcabinet';
                        $imgNoPhoto = \CFile::SaveFile($noPhoto, $noPhoto['tmp_name']);
                        $noPhoto['ID'] = $imgNoPhoto;
                    }
                    $Image = \CFile::ResizeImageGet(
                        $noPhoto['ID'],
                        array(
                            "width" => $imgProp['width'],
                            "height" => $imgProp['height']
                        ),
                        $imgProp['resize'],
                        true
                    );
                    $return[$id] = $Image;
                }
            }
        }
        return $return;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setSum($sum)
    {
        $this->sum = $sum;
    }

    public function getQnt()
    {
        return $this->qnt;
    }

    public function setQnt($qnt)
    {
        $this->qnt = $qnt;
    }
}