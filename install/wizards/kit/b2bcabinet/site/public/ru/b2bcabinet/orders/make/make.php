<?php
define("NEED_AUTH", true);

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

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
?>

    <div class="blank_resizer">
        <div class="blank_resizer_tool blank_resizer_tool_open"></div>
    </div>

<?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax",
    "b2bcabinet_new",
    Array(
        "IMAGE_SIZE_PREVIEW" => "23",  // размер изображения в колонке свойства "Наименование"
        //"IMAGE_SIZE_DETAIL" => "23", // размер изображения в колонке свойства "детального изображения"
        "FIELDS_USER_INFO" => ['LAST_NAME','NAME','SECOND_NAME','PHONE','EMAIL'], // поля выводимые в блоке информация о покупателе
        "DETAIL_PAGE_URL" => "N",
        "IMAGE_SIZE_DELIVERY_PAYSYSTEM" => [23,23], // размер изображений доставки и оплаты

        "PRODUCT_COLUMNS_VISIBLE" => array(
            1 => "PROPERTY_CML2_ARTICLE",
            2 => "PREVIEW_PICTURE",
            3 => "DISCOUNT_PRICE_PERCENT_FORMATED"
        ),
        "ALLOW_AUTO_REGISTER" => "Y",	// Оформлять заказ с автоматической регистрацией пользователя
        "ALLOW_NEW_PROFILE" => "Y",	// Разрешить множество профилей покупателей
        "BUYER_PERSONAL_TYPE" => unserialize(COption::GetOptionString("kit.b2bcabinet","BUYER_PERSONAL_TYPE","a:0:{}",
            SITE_ID)),
        "COMPONENT_TEMPLATE" => "b2bcabinet",
        "DELIVERY_NO_AJAX" => "Y",	// Когда рассчитывать доставки с внешними системами расчета
        "DELIVERY_NO_SESSION" => "N",	// Проверять сессию при оформлении заказа
        "DELIVERY_TO_PAYSYSTEM" => "d2p",	// Последовательность оформления
        "DISABLE_BASKET_REDIRECT" => "N",	// Оставаться на странице оформления заказа, если список товаров пуст
        "ONLY_FULL_PAY_FROM_ACCOUNT" => "Y",	// Разрешить оплату с внутреннего счета только в полном объеме
        "PATH_TO_AUTH" => "/auth/",	// Путь к странице авторизации
        "PATH_TO_BASKET" => SITE_DIR."b2bcabinet/orders/make/index.php",	// Путь к странице корзины
        "PATH_TO_ORDER" => SITE_DIR."b2bcabinet/orders/make/make.php",      // Путь к странице оформления заказа
        "PATH_TO_PAYMENT" => SITE_DIR."b2bcabinet/orders/payment/index.php",	// Страница подключения платежной системы
        "PATH_TO_PERSONAL" => SITE_DIR."b2bcabinet/orders/index.php",	// Путь к странице персонального раздела
        "PAY_FROM_ACCOUNT" => "Y",	// Разрешить оплату с внутреннего счета
        "PRODUCT_COLUMNS" => "",
        "SEND_NEW_USER_NOTIFY" => "Y",	// Отправлять пользователю письмо, что он зарегистрирован на сайте
        "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
        "SHOW_PAYMENT_SERVICES_NAMES" => "Y",
        "SHOW_STORES_IMAGES" => "N",	// Показывать изображения складов в окне выбора пункта выдачи
        "TEMPLATE_LOCATION" => "popup",	// Визуальный вид контрола выбора местоположений
        "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
    ),
    false
);
?>

<?require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>