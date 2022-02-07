<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
    "main" => Array(
        "NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
        "STAGES" => Array(
            "modules.php",
            "files.php", // Copy bitrix files
            "template.php", // Install template
            "menu.php", // Install menu
            "forms.php",
            "userfields.php",
            "settings.php",
        ),
    ),
    "catalog" => Array(
        "NAME" => GetMessage("SERVICE_CATALOG_SETTINGS"),
        "STAGES" => Array(
            "index.php",
            "settings.php"
        ),
    ),
    "iblock" => Array(
        "NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
        "STAGES" => Array(
            "types.php",
            "catalog.php",
            "document.php",
        ),
    ),
    "sale" => Array(
        "NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
        "STAGES" => Array(
            "locations.php",
            "step1.php",
            "payments.php",
            "orders.php"
        ),
    ),
    "support" => Array(
        "NAME" => GetMessage("SERVICE_SUPPORT_DEMO_DATA"),
        "STAGES" => Array(
            "settings.php",
        ),
    ),
    "kit.auth" => Array(
        "NAME" => GetMessage( "SERVICE_KIT_AUTH_DATA" ),
        "STAGES" => Array(
            "settings.php"
        )
    ),
    "kit.checkcompany" => Array(
        "NAME" => GetMessage( "SERVICE_KIT_CHECK_DATA" ),
        "STAGES" => Array(
            "settings.php"
        )
    ),
    "kit.b2bcabinet" => Array(
        "NAME" => GetMessage("SERVICE_B2BCABINET"),
        "STAGES" => Array(
            "settings.php",
            "iblock_props.php"
        )
    ),
);
?>