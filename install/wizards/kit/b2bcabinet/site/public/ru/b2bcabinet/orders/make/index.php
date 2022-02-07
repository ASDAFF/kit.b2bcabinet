<?php
define("NEED_AUTH", true);
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle( Loc::getMessage('ORDERS_MAKE_ORDER') );
$APPLICATION->SetPageProperty('title_prefix', '<span class="font-weight-semibold">'. Loc::getMessage("ORDERS_ORDERS") .'</span> - ');

?>

<?php
if(!Loader::includeModule('kit.b2bcabinet'))
{
    header('Location: '.SITE_DIR.'b2bcabinet/');
    exit;
}
$request = Application::getInstance()->getContext()->getRequest();
?>

    <div class="index_checkout">

        <?php
        $APPLICATION->IncludeComponent("bitrix:sale.basket.basket",
            "b2bcabinet",
            Array(
                "ACTION_VARIABLE" => "action",	// Название переменной действия
                "AJAX_MODE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "SHOW_RESTORE" => "Y",
                "COLUMNS_LIST" => array(
                    0 => "NAME",
                    1 => "DISCOUNT",
                    2 => "WEIGHT",
                    3 => "DELETE",
                    4 => "DELAY",
                    5 => "TYPE",
                    6 => "PRICE",
                    7 => "QUANTITY",
                    8 => "SUM",
                ),

                "COLUMNS_LIST_MOBILE" => array(
                    0 => "PREVIEW_PICTURE",
                    1 => "DISCOUNT",
                    2 => "DELETE",
                    3 => "DELAY",
                    4 => "TYPE",
                    5 => "SUM",
                ),
                "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
                "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
                "IBLOCK_ID" => '',
                "IBLOCK_TYPE" => '',
                "IMG_HEIGHT" => '',
                "IMG_WIDTH" => '',
                "MANUFACTURER_ELEMENT_PROPS" => '',
                "MANUFACTURER_LIST_PROPS" => '',
                "MORE_PHOTO_OFFER_PROPS" => '',
                "MORE_PHOTO_PRODUCT_PROPS" => '',
                "OFFERS_PROPS" => '',	// Свойства, влияющие на пересчет корзины
                "OFFER_COLOR_PROP" => '',
                "OFFER_TREE_PROPS" => '',
                "PATH_TO_ORDER" => SITE_DIR."b2bcabinet/orders/make/make.php",	// Страница оформления заказа
                "PATH_TO_BASKET" => SITE_DIR."b2bcabinet/orders/make/index.php",
                "PICTURE_FROM_OFFER" => '',
                "PRICE_VAT_SHOW_VALUE" => "Y",	// Отображать значение НДС
                "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
                "SET_TITLE" => "N",	// Устанавливать заголовок страницы
                "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
                "SHOW_VAT_PRICE" => "Y",
                "IMAGE_SIZE_PREVIEW" => "23", // Размер изображений
                "EMPTY_BASKET_HINT_PATH" => SITE_DIR."b2bcabinet/orders/blank_zakaza/index.php"
            ),
            false
        );?>

    </div>

<?require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>