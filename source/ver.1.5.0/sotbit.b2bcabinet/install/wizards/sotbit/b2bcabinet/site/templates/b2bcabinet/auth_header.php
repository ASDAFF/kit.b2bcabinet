<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define("NEED_AUTH", true);

global $APPLICATION;
use Bitrix\Main\Page\Asset;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?$APPLICATION->ShowTitle()?></title>
    <!--	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>-->
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    
    <?
    CJSCore::Init('jquery');
    $APPLICATION->ShowHead();
    
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap_limitless.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/layout.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/components.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/colors.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/icons/icomoon/styles.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/form.css");
    
    
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/jquery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/bootstrap.bundle.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/loaders/blockui.min.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/visualization/d3/d3.min.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/visualization/d3/d3_tooltip.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/switchery.min.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/selects/bootstrap_multiselect.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/ui/moment/moment.min.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/daterangepicker.js");

//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/dashboard.js");
//    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/loaders/blockui.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/uniform.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/app.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/uniform_init.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_checkboxes_radios.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_inputs.js");

    //    $APPLICATION->ShowCSS(true, true);
    //    $APPLICATION->ShowHeadStrings();
    //    $APPLICATION->ShowHeadScripts();
    ?>
</head>

<body>
<!-- Page content -->
<div class="page-content">
        
        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">
        