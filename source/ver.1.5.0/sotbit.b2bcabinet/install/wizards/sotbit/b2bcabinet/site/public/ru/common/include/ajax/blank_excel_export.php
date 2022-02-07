<?
use \Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Text\Encoding;

define('STOP_STATISTICS', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
IncludeModuleLangFile(__FILE__);


$errMsg = array();

$arrLib = array();
$arrLib = get_loaded_extensions();
if(!in_array('xmlwriter', $arrLib)) {
    $errMsg['TYPE'] = 'error';
    $errMsg['MESSAGE'] = GetMessage('BLANK_EXCEL_EXPORT_LIB_ERROR');

    echo \Bitrix\Main\Web\Json::encode($errMsg);
}
else if(Loader::includeModule("sotbit.b2bcabinet") && Loader::includeModule("catalog") && Loader::includeModule('highloadblock'))
{
    if(isset($_REQUEST['filterProps']) && is_array($_REQUEST['filterProps']))
    {
        if(!empty($_REQUEST['table_header']))
            $tableHeader = $_REQUEST['table_header'];
        else
            $tableHeader = array();

        $priceIds = array();
        if(isset($_REQUEST['priceCodes']) && is_array($_REQUEST['priceCodes']))
        {
            $priceCodes = $_REQUEST['priceCodes'];
            $dbPriceType = CCatalogGroup::GetList(
                array("SORT" => "ASC"),
                array("NAME" => $priceCodes),
                false,
                false,
                array('ID', 'NAME', 'NAME_LANG', 'CAN_ACCESS')
            );

            while ($pTmp = $dbPriceType->fetch())
            {
                if($pTmp['CAN_ACCESS'] != 'Y')
                    continue;
                $priceIds[$pTmp['NAME']]['ID'] = $pTmp['ID'];
                $tableHeader[$pTmp['NAME']] = $pTmp['NAME_LANG'];
            }
        }

        $firstKey = key($_REQUEST['filterProps']);

        $arSelect = array('ID', 'IBLOCK_ID', 'NAME');
        if (!empty($priceIds))
        {
            foreach ($priceIds as $key => $item)
            {
                $arSelect = array_merge($arSelect, array('CATALOG_GROUP_' . $item['ID']));
            }
        }

        $productIDs = array();

        if(strpos($firstKey, 'PROPERTY') !== false)
        {
            $filterProps = $_REQUEST['filterProps'];

            $arFilter = array();
            foreach ($filterProps as $keyProp => $prop)
            {
                if (!empty($prop))
                    $arFilter[$keyProp] = $prop;
            }
        }
        else if(key($_REQUEST['filterProps']) == 'ID' && !empty($_REQUEST['filterProps']['ID']))
        {
            $arFilter['ID'] = $_REQUEST['filterProps']['ID'];
        }
        else
        {
//            $arFilter['IBLOCK_SECTION_ID'] = $_REQUEST['filterProps'];
            $arFilter['SECTION_ID'] = $_REQUEST['filterProps'];
            $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
        }

        if(!empty($arFilter))
        {
            $res = CIBlockElement::GetList(
                array('SORT'=>'ASC'),
                $arFilter,
                false,
                false,
                $arSelect
            );

            while($el = $res->getNextElement())
            {
                $tmp = $el->getFields();
                $products[$tmp['ID']] = $tmp;
                $properties = $el->GetProperties();

                //----HIGHLOAD_BLOCK
                if(!empty($properties) && is_array($properties))
                {
                    foreach ($properties as $key => &$property)
                    {
                        if(
                            $property['PROPERTY_TYPE'] == 'S' &&
                            $property['USER_TYPE'] == 'directory' &&
                            !empty($property['VALUE']) &&
                            !empty($property['USER_TYPE_SETTINGS']['TABLE_NAME'])
                        )
                        {
                            $rsData = HL\HighloadBlockTable::getList(array(
                                'filter'=>array('TABLE_NAME'=>$property['USER_TYPE_SETTINGS']['TABLE_NAME'])
                            ));

                            $hl = $rsData->fetch();
                            if(isset($hl['ID']) && !empty($hl['ID']))
                            {
                                $hlblock = HL\HighloadBlockTable::getById($hl['ID'])->fetch();

                                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                                $entity_data_class = $entity->getDataClass();

                                $rsData = $entity_data_class::getList(array(
                                    "select" => array("*"),
                                    "order" => array("ID" => "ASC"),
                                    "filter" => array('UF_XML_ID' => $property['VALUE'])
                                ));

                                if($arData = $rsData->Fetch())
                                {
                                    $property['VALUE'] = $arData['UF_NAME'];
                                }
                            }
                        }
                    }
                }
                //----\HIGHLOAD_BLOCK

                if(!empty($properties) && is_array($properties))
                    $products[$tmp['ID']] = array_merge($products[$tmp['ID']], $properties);
            }
            if(!empty($products) && is_array($products)) {
//                ksort($products);

                $arrayLetters = [];
                foreach ($products as $key => $product) {
                    $arrayLetters[$key] = $product['NAME'];
                }

                asort($arrayLetters);

                foreach ($arrayLetters as $key => $item) {
                    $arrayLetters[$key] = $products[$key];
                }

                $products = $arrayLetters;
                $productIDs = array_keys($products);
            }



            if (!empty($productIDs) && is_array($productIDs))
            {
                $offersRes = CIBlockElement::GetList(
                    array("SORT" => "ASC"),
                    array("PROPERTY_CML2_LINK" => $productIDs),
                    false,
                    false,
                    $arSelect
                );

                while ($offers = $offersRes->getNextElement())
                {
                    $offerFields = $offers->GetFields();
                    $offerProperies = $offers->GetProperties();

                    //----HIGHLOAD_BLOCK
                    if(!empty($offerProperies) && is_array($offerProperies))
                    {
                        foreach ($offerProperies as $key => &$offProperty)
                        {
                            if(
                                $offProperty['PROPERTY_TYPE'] == 'S' &&
                                $offProperty['USER_TYPE'] == 'directory' &&
                                !empty($offProperty['VALUE']) &&
                                !empty($offProperty['USER_TYPE_SETTINGS']['TABLE_NAME'])
                            )
                            {
                                $rsData = HL\HighloadBlockTable::getList(array(
                                    'filter'=>array('TABLE_NAME'=>$offProperty['USER_TYPE_SETTINGS']['TABLE_NAME'])
                                ));

                                $hl = $rsData->fetch();
                                if(isset($hl['ID']) && !empty($hl['ID']))
                                {
                                    $hlblock = HL\HighloadBlockTable::getById($hl['ID'])->fetch();
                                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                                    $entity_data_class = $entity->getDataClass();

                                    $rsData = $entity_data_class::getList(array(
                                        "select" => array("*"),
                                        "order" => array("ID" => "ASC"),
                                        "filter" => array('UF_XML_ID' => $offProperty['VALUE'])
                                    ));

                                    if($arData = $rsData->Fetch())
                                    {
                                        $offProperty['VALUE'] = $arData['UF_NAME'];
                                    }

                                }
                            }
                        }
                    }
                    //----\HIGHLOAD_BLOCK

                    if(isset($products[$offerFields['ID']]))
                        unset($products[$offerFields['ID']]);

                    $products[$offerProperies['CML2_LINK']['VALUE']]['OFFERS'][$offerFields['ID']] = $offerFields;
                    $products[$offerProperies['CML2_LINK']['VALUE']]['OFFERS'][$offerFields['ID']]['PROPERTIES'] = $offerProperies;
                }

                $productCount = count($products);

                $alfavit = [
                    0 => 'A',
                    1 => 'B',
                    2 => 'C',
                    3 => 'D',
                    4 => 'E',
                    5 => 'F',
                    6 => 'G',
                    7 => 'H',
                    8 => 'I',
                    9 => 'J',
                    10 => 'K',
                    11 => 'L',
                    12 => 'M',
                    13 => 'N',
                    14 => 'O',
                    15 => 'P',
                    16 => 'Q',
                    17 => 'R',
                    18 => 'S',
                    19 => 'T',
                    20 => 'U',
                    21 => 'V',
                    22 => 'W',
                    23 => 'X',
                    24 => 'Y',
                    25 => 'Z',
                ];

                $objPHPExcel = new PHPExcel();
                //        $objPHPExcel = \PHPExcel_IOFactory::load($file);

                $name = '';

                $j = 0;
                $i = -1;
                $colNum = 0;
                foreach ($tableHeader as $key => &$header)
                {
                    if (is_array($header) || $key == 'AVALIABLE' || $key == 'MEASURE' || $key == 'PRICES')
                        continue;

                    if ($j > 25) {
                        $j = 0;
                        $i++;
                    }

                    if ($i === -1) {
                        $letter = $alfavit[$j];
                    } else {
                        $letter = $alfavit[$i] . $alfavit[$j];
                    }

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter . '1', validEncoding($header));

                    $itemCur = 0;
                    foreach ($products as $product)
                    {
                        if ($key == 'NAME' || $key == 'ID')
                        {
                            if (isset($product['OFFERS']) && !empty($product['OFFERS']))
                            {
                                foreach ($product['OFFERS'] as $OFFER)
                                {
                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter . ($itemCur + 2), validEncoding($OFFER[$key]));
                                    $itemCur++;
                                }
                            }
                            else
                            {
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter . ($itemCur + 2), validEncoding($product[$key]));
                                $itemCur++;
                            }
                            if ($key == 'ID')
                            {
                                $objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setVisible(false);
                            }

                        }
                        else if (isset($priceIds[$key]) && !empty($priceIds[$key]))
                        {
                            if (isset($product['OFFERS']) && !empty($product['OFFERS']))
                            {
                                foreach ($product['OFFERS'] as $OFFER)
                                {
                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit(
                                        $letter . ($itemCur + 2),
                                        (!empty($OFFER['CATALOG_PRICE_' . $priceIds[$key]['ID']]) ? $OFFER['CATALOG_PRICE_' . $priceIds[$key]['ID']] : ''),
                                        PHPExcel_Cell_DataType::TYPE_NUMERIC
                                    );

                                    $itemCur++;
                                }
                            }
                            else
                            {
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit(
                                    $letter . ($itemCur + 2),
                                    (!empty($product['CATALOG_PRICE_' . $priceIds[$key]['ID']]) ?  $product['CATALOG_PRICE_' . $priceIds[$key]['ID']] : ''),
                                    PHPExcel_Cell_DataType::TYPE_NUMERIC
                                );
                                $itemCur++;
                            }
                        }
                        else if ($key == 'QUANTITY')
                        {
                            if (isset($product['OFFERS']) && !empty($product['OFFERS']))
                            {
                                foreach ($product['OFFERS'] as $OFFER)
                                {
                                    $qnt = 0;
                                    if (isset($_SESSION['BLANK_IDS'][$OFFER['ID']]) && !empty($_SESSION['BLANK_IDS'][$OFFER['ID']]['QNT']))
                                    {
                                        $qnt = $_SESSION['BLANK_IDS'][$OFFER['ID']]['QNT'];
                                    }
                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter . ($itemCur + 2), $qnt);
                                    $itemCur++;
                                }
                            }
                            else
                            {
                                $qnt = 0;
                                if (isset($_SESSION['BLANK_IDS'][$product['ID']]) && !empty($_SESSION['BLANK_IDS'][$product['ID']]))
                                {
                                    $qnt = $_SESSION['BLANK_IDS'][$product['ID']]['QNT'];
                                }
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter . ($itemCur + 2), $qnt);
                                $itemCur++;
                            }
                        }
                        else
                        {
                            $value = $product[$key]['VALUE'];

                            if (isset($product['OFFERS']) && !empty($product['OFFERS']))
                            {
                                foreach ($product['OFFERS'] as $OFFER)
                                {
                                    if (!empty($OFFER['PROPERTIES'][$key]['VALUE']))
                                        $value = $OFFER['PROPERTIES'][$key]['VALUE'];

                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue(
                                        $letter . ($itemCur + 2),
                                        ($value ? validEncoding($value) : '')
                                    );

                                    $itemCur++;
                                }
                            }
                            else
                            {
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(
                                    $letter . ($itemCur + 2),
                                    (!empty($value) ? validEncoding($value) : '')
                                );
                                $itemCur++;
                            }
                        }
                    }
                    $j++;
                }

                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/blank'))
                {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/blank');
                }

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

                if ($file)
                {
                    $path = $file;
                }
                else
                {
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/blank/' . rand() . '.xlsx';
                }

                $objWriter->save($path);


                $http = 'http://';
                if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
                    $http = 'https://';
                }

                $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
                $path = $http . $_SERVER['SERVER_NAME'] . $path;
                echo $path;
            }
        }
    }
    else
    {
        return false;
    }
}

function validEncoding($str)
{
    if(!Encoding::detectUtf8($str))
        return Encoding::convertEncoding($str, 'Windows-1251', 'UTF-8');
    else
        return $str;
}
?>