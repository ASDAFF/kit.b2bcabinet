<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$errors = array();
$result = false;

if(!\Bitrix\Main\Loader::includeModule('sale'))
{
    $errors[] = ['STATUS' => 'ERROR','MESSAGE' => 'Module sale not installed'];
}
else if(!\Bitrix\Main\Loader::IncludeModule("catalog"))
{
    $errors[] = ['STATUS' => 'ERROR','MESSAGE' => 'Module catalog not installed'];
}
else if(!\Bitrix\Main\Loader::includeModule('kit.b2bcabinet'))
{
    $errors[] = ['STATUS' => 'ERROR','MESSAGE' => 'Module catalog not installed'];
}
else
{
    if(!empty($_SESSION['BLANK_IDS']))
        $arProduct = $_SESSION['BLANK_IDS'];

    if(is_array($arProduct))
    {
        foreach ($arProduct as $key => $item)
        {
            if($key == 'TOTAL_PRICE' || $key == 'TOTAL_COUNT')
            {
                continue;
            }

            if(!Add2BasketByProductID($key, $item['QNT'], '', $item['PROPS']))
            {
                if ($ex = $APPLICATION->GetException())
                {
                    $errors[] = ['STATUS' => 'ERROR', 'MESSAGE' => $ex->GetString()];
                }
                else
                {
                    $errors[] = ['STATUS' => 'ERROR', 'MESSAGE' => "Error basket"];
                }
            }
            else
                $result = true;
        }
    }

    if($result == true)
    {
        $cntBasketItems = CSaleBasket::GetList(
            array(),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL",
                "!DELAY" => "Y",
                "CAN_BUY" => 'Y'
            ),
            array()
        );
    }
}



if (!$result) {
    echo \Bitrix\Main\Web\Json::encode($errors);
}
else
{
    unset($_SESSION['BLANK_IDS']);
    echo \Bitrix\Main\Web\Json::encode(['STATUS' => 'OK', 'BASKET_ITEM_QNT' => $cntBasketItems]);
}