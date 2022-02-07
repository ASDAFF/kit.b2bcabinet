<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define("NEED_AUTH", true);

global $APPLICATION;
global $USER;

if(!$USER->IsAuthorized()) {
    include_once "auth_header.php";
    return;
}
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

if(\Bitrix\Main\Loader::includeModule('sale'))
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

$methodIstall = Option::get('kit.b2bcabinet', 'method_install', '', SITE_ID) == 'AS_TEMPLATE' ? SITE_DIR.'b2bcabinet/' : SITE_DIR;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?$APPLICATION->ShowTitle()?></title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <?
    CJSCore::Init();
    $APPLICATION->ShowHead();

    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/icons/icomoon/styles.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap_limitless.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/layout.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/components.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/colors.min.css");


    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/jquery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/bootstrap.bundle.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/loaders/blockui.min.js");

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/visualization/d3/d3.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/visualization/d3/d3_tooltip.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/switchery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/selects/bootstrap_multiselect.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/ui/moment/moment.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/daterangepicker.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/app.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main/dashboard.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/anytime.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/pickadate/picker.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/pickadate/picker.date.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/pickadate/picker.time.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/pickadate/legacy.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/notifications/jgrowl.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/pickers/pickadate/picker_date.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/switch.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_checkboxes_radios.js");

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/selects/select2.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/uniform.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/styling/form_layouts.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/ui/perfect_scrollbar.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/ui/layout_fixed_sidebar_custom.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/loaders/progressbar.min.js");

    //----- for select
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/form_select2.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/pages/uniform_init.js");
    //----- \for select


    //    $APPLICATION->ShowCSS(true, true);
    //    $APPLICATION->ShowHeadStrings();
    //    $APPLICATION->ShowHeadScripts();
    ?>
</head>

<body>
<?$APPLICATION->ShowPanel()?>
<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="navbar-brand">
        <a href="index.html" class="d-inline-block">
            <img class="header_logo" src="<?=COption::GetOptionString("kit.b2bcabinet","LOGO","")?>" alt="">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <div class="header-elements">
            <div class="d-flex justify-content-center">
                <!--							<a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>����������</span></a>-->
                <!--							<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-primary"></i> <span>�����</span></a>-->
                <a href="<?=$methodIstall?>orders/make/index.php" class="btn btn-link btn-float text-default cart-in-header">
                    <i class="icon-cart5 mr-2">
                        <span><?=( !empty($cntBasketItems) && $cntBasketItems > 0 ? $cntBasketItems : 0 )?></span>
                    </i>
                    <!--                                <span>-->
                    <!--                                    --><?//=Loc::getMessage('BASKET')?>
                    <!--                                </span>-->
                </a>
            </div>
        </div>


    </div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">

        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->


        <!-- Sidebar content -->
        <div class="sidebar-content">
            <!-- User menu -->
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.user.link",
                "b2bcabinet_userprofile",
                array(
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "7200",
                    "ID" => $USER->getId(),
                    "NAME_TEMPLATE" => "#NOBR##LAST_NAME# #NAME##/NOBR#",
                    "SHOW_LOGIN" => "Y",
                    "THUMBNAIL_LIST_SIZE" => "38",
                    "THUMBNAIL_DETAIL_SIZE" => "100",
                    "USE_THUMBNAIL_LIST" => "Y",
                    "SHOW_FIELDS" => array(
                        0 => "PERSONAL_BIRTHDAY",
                        1 => "PERSONAL_ICQ",
                        2 => "PERSONAL_PHOTO",
                        3 => "PERSONAL_CITY",
                        4 => "WORK_COMPANY",
                        5 => "WORK_POSITION",
                    ),
                    "USER_PROPERTY" => array(
                    ),
                    "PATH_TO_SONET_USER_PROFILE" => "",
                    "PROFILE_URL" => "",
                    "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                    "SHOW_YEAR" => "Y",
                    "COMPONENT_TEMPLATE" => "b2bcabinet_userprofile"
                ),
                false
            );
            ?>
            <!-- /user menu -->


            <!-- Main navigation -->
            <div class="card card-sidebar-mobile">

                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "b2bcabinet",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "b2bcabinet_menu_inner",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "3",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "b2bcabinet_menu",
                        "USE_EXT" => "Y",
                        "COMPONENT_TEMPLATE" => "b2bcabinet",
                        "MENU_THEME" => "blue",
                        "DISPLAY_USER_NANE" => "N",
                        "CACHE_SELECTED_ITEMS" => false,
                    ),
                    false
                );
                ?>
            </div>
            <!-- /main navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4><i class="icon-arrow-left52 mr-2"></i>
                        <?=$APPLICATION->ShowProperty('title_prefix')?>
                        <?$APPLICATION->ShowTitle(false); ?></h4>
                    <!--						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">�������</span> - ������������ ����������</h4>-->
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>
            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "b2bcabinet_breadcrumb", Array(
                        "START_FROM" => "1",	// ����� ������, ������� � �������� ����� ��������� ������������� �������
                        "PATH" => "",	// ����, ��� �������� ����� ��������� ������������� ������� (�� ���������, ������� ����)
                        "SITE_ID" => SITE_ID,	// C��� (��������������� � ������ ������������� ������, ����� DOCUMENT_ROOT � ������ ������)
                    ),
                        false
                    );?>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>
            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content">#WORK_AREA#<?php
            global $USER;

            if (!$USER->IsAuthorized()) {
                include_once "auth_footer.php";
                return;
            }
            ?>

        </div>
        <!-- /content area -->

        <!-- Footer -->
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
                        data-target="#navbar-footer">
                    <i class="icon-unfold mr-2"></i>
                    Footer
                </button>
            </div>

            <div class="navbar-collapse collapse" id="navbar-footer">
                <ul class="navbar-nav ml-lg-auto">
                    <li class="nav-item">
                        <a href="http://kit.ru" class="navbar-nav-link">
                            <img class="footer_kit_logo" src="/local/templates/b2bcabinet/assets/images/main_logo_kit.png">
                            <span>����������� ��������� "������"</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /footer -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
<div class="feed-back-form">
    <?$APPLICATION->IncludeComponent(
        "bitrix:form",
        "b2b_cabinet_feed_back",
        Array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "N",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "NOT_SHOW_FILTER" => "",
            "NOT_SHOW_TABLE" => "",
            "RESULT_ID" => $_REQUEST[RESULT_ID],
            "SEF_MODE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_STATUS" => "Y",
            "SHOW_VIEW_PAGE" => "N",
            "START_PAGE" => "new",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "action" => "action"
            ),
            "WEB_FORM_ID" => Option::get('kit.b2bcabinet', 'B2BCABINET_FEED_BACK_FORM_ID'),
        )
    );?>
</div>
</body>
</html>
