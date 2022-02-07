<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

if( !CModule::IncludeModule( 'sale' ) )
    return;

use Bitrix\Sale\BusinessValue,
    Bitrix\Sale\OrderStatus,
    Bitrix\Sale\DeliveryStatus,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main,
    Bitrix\Main\Config\Option,
    Bitrix\Catalog,
    Bitrix\Sale;

$moduleId = 'kit.b2bcabinet';
$saleConverted15 = COption::GetOptionString( "main", "~sale_converted_15", "" ) == "Y";
if( $saleConverted15 )
{
    $BIZVAL_INDIVIDUAL_DOMAIN = BusinessValue::INDIVIDUAL_DOMAIN;
    $BIZVAL_ENTITY_DOMAIN = BusinessValue::ENTITY_DOMAIN;
}
else
{
    $BIZVAL_INDIVIDUAL_DOMAIN = null;
    $BIZVAL_ENTITY_DOMAIN = null;
}

if( COption::GetOptionString( "catalog", "1C_GROUP_PERMISSIONS" ) == "" )
    COption::SetOptionString( "catalog", "1C_GROUP_PERMISSIONS", "1", GetMessage( 'SALE_1C_GROUP_PERMISSIONS' ) );

$arGeneralInfo = Array();


$needEntity = false;
$map = \Bitrix\Sale\Internals\PaySystemActionTable::getMap();
if($map['ENTITY_REGISTRY_TYPE'])
{
    $needEntity = true;
}


$dbSite = CSite::GetByID( WIZARD_SITE_ID );
if( $arSite = $dbSite->Fetch() )
    $lang = $arSite["LANGUAGE_ID"];
if( strlen( $lang ) <= 0 )
    $lang = "ru";
$bRus = false;
if( $lang == "ru" )
    $bRus = true;

$shopLocalization = $wizard->GetDefaultVar( "shopLocalization" );

COption::SetOptionString( $moduleId, "shopLocalization", $shopLocalization, "ru", WIZARD_SITE_ID );
if( $shopLocalization == "kz" )
    $shopLocalization = "ru";

$defCurrency = "EUR";
if( $lang == "ru" )
{
    if( $shopLocalization == "ua" )
        $defCurrency = "UAH";
    elseif( $shopLocalization == "bl" )
        $defCurrency = "BYR";
    else
        $defCurrency = "RUB";
}
elseif( $lang == "en" )
{
    $defCurrency = "USD";
}

$arLanguages = Array();
$rsLanguage = CLanguage::GetList( $by, $order, array() );
while ( $arLanguage = $rsLanguage->Fetch() )
    $arLanguages[] = $arLanguage["LID"];

WizardServices::IncludeServiceLang( "step1.php", $lang );

if( $bRus || COption::GetOptionString( $moduleId, "wizard_installed", "N", WIZARD_SITE_ID ) != "Y" || WIZARD_INSTALL_DEMO_DATA ) {
    $personType = $wizard->GetDefaultVar("personType");
    $paysystem = $wizard->GetDefaultVar("paysystem");

    if ($shopLocalization == "ru") {
        if (CSaleLang::GetByID(WIZARD_SITE_ID))
            CSaleLang::Update(WIZARD_SITE_ID, array(
                "LID" => WIZARD_SITE_ID,
                "CURRENCY" => "RUB"
            ));
        else
            CSaleLang::Add(array(
                "LID" => WIZARD_SITE_ID,
                "CURRENCY" => "RUB"
            ));

        $shopLocation = $wizard->GetDefaultVar("shopLocation");
        COption::SetOptionString($moduleId, "shopLocation", $shopLocation, false, WIZARD_SITE_ID);
        $shopOfName = $wizard->GetDefaultVar("shopOfName");
        COption::SetOptionString($moduleId, "shopOfName", $shopOfName, false, WIZARD_SITE_ID);
        $shopAdr = $wizard->GetDefaultVar("shopAdr");
        COption::SetOptionString($moduleId, "shopAdr", $shopAdr, false, WIZARD_SITE_ID);

        $shopINN = $wizard->GetDefaultVar("shopINN");
        COption::SetOptionString($moduleId, "shopINN", $shopINN, false, WIZARD_SITE_ID);
        $shopKPP = $wizard->GetDefaultVar("shopKPP");
        COption::SetOptionString($moduleId, "shopKPP", $shopKPP, false, WIZARD_SITE_ID);
        $shopNS = $wizard->GetDefaultVar("shopNS");
        COption::SetOptionString($moduleId, "shopNS", $shopNS, false, WIZARD_SITE_ID);
        $shopBANK = $wizard->GetDefaultVar("shopBANK");
        COption::SetOptionString($moduleId, "shopBANK", $shopBANK, false, WIZARD_SITE_ID);
        $shopBANKREKV = $wizard->GetDefaultVar("shopBANKREKV");
        COption::SetOptionString($moduleId, "shopBANKREKV", $shopBANKREKV, false, WIZARD_SITE_ID);
        $shopKS = $wizard->GetDefaultVar("shopKS");
        COption::SetOptionString($moduleId, "shopKS", $shopKS, false, WIZARD_SITE_ID);
        $siteStamp = $wizard->GetDefaultVar("siteStamp");
        if ($siteStamp == "")
            $siteStamp = COption::GetOptionString($moduleId, "siteStamp", "", WIZARD_SITE_ID);
    } elseif ($shopLocalization == "ua") {
        if (CSaleLang::GetByID(WIZARD_SITE_ID))
            CSaleLang::Update(WIZARD_SITE_ID, array(
                "LID" => WIZARD_SITE_ID,
                "CURRENCY" => "UAH"
            ));
        else
            CSaleLang::Add(array(
                "LID" => WIZARD_SITE_ID,
                "CURRENCY" => "UAH"
            ));

        $shopLocation = $wizard->GetDefaultVar("shopLocation_ua");
        COption::SetOptionString($moduleId, "shopLocation_ua", $shopLocation, false, WIZARD_SITE_ID);
        $shopOfName = $wizard->GetDefaultVar("shopOfName_ua");
        COption::SetOptionString($moduleId, "shopOfName_ua", $shopOfName, false, WIZARD_SITE_ID);
        $shopAdr = $wizard->GetDefaultVar("shopAdr_ua");
        COption::SetOptionString($moduleId, "shopAdr_ua", $shopAdr, false, WIZARD_SITE_ID);

        $shopEGRPU_ua = $wizard->GetDefaultVar("shopEGRPU_ua");
        COption::SetOptionString($moduleId, "shopEGRPU_ua", $shopEGRPU_ua, false, WIZARD_SITE_ID);
        $shopINN_ua = $wizard->GetDefaultVar("shopINN_ua");
        COption::SetOptionString($moduleId, "shopINN_ua", $shopINN_ua, false, WIZARD_SITE_ID);
        $shopNDS_ua = $wizard->GetDefaultVar("shopNDS_ua");
        COption::SetOptionString($moduleId, "shopNDS_ua", $shopNDS_ua, false, WIZARD_SITE_ID);
        $shopNS_ua = $wizard->GetDefaultVar("shopNS_ua");
        COption::SetOptionString($moduleId, "shopNS_ua", $shopNS_ua, false, WIZARD_SITE_ID);
        $shopBank_ua = $wizard->GetDefaultVar("shopBank_ua");
        COption::SetOptionString($moduleId, "shopBank_ua", $shopBank_ua, false, WIZARD_SITE_ID);
        $shopMFO_ua = $wizard->GetDefaultVar("shopMFO_ua");
        COption::SetOptionString($moduleId, "shopMFO_ua", $shopMFO_ua, false, WIZARD_SITE_ID);
        $shopPlace_ua = $wizard->GetDefaultVar("shopPlace_ua");
        COption::SetOptionString($moduleId, "shopPlace_ua", $shopPlace_ua, false, WIZARD_SITE_ID);
        $shopFIO_ua = $wizard->GetDefaultVar("shopFIO_ua");
        COption::SetOptionString($moduleId, "shopFIO_ua", $shopFIO_ua, false, WIZARD_SITE_ID);
        $shopTax_ua = $wizard->GetDefaultVar("shopTax_ua");
        COption::SetOptionString($moduleId, "shopTax_ua", $shopTax_ua, false, WIZARD_SITE_ID);
    }

    $siteTelephone = $wizard->GetDefaultVar("siteTelephone");
    COption::SetOptionString($moduleId, "siteTelephone", $siteTelephone, false, WIZARD_SITE_ID);
    $shopEmail = $wizard->GetDefaultVar("shopEmail");
    COption::SetOptionString($moduleId, "shopEmail", $shopEmail, false, WIZARD_SITE_ID);
    $siteName = $wizard->GetDefaultVar("siteName");
    COption::SetOptionString($moduleId, "siteName", $siteName, false, WIZARD_SITE_ID);

    $obSite = new CSite();
    $obSite->Update(WIZARD_SITE_ID, Array(
        "EMAIL" => $shopEmail,
        "SITE_NAME" => $siteName,
        "SERVER_NAME" => $_SERVER["SERVER_NAME"]
    ));

    $arPersonTypeNames = array();
    $dbPerson = CSalePersonType::GetList(array(), array(
        "LID" => WIZARD_SITE_ID
    ));
    // if(!$dbPerson->Fetch())//if there are no data in module
    // {
    while ($arPerson = $dbPerson->Fetch()) {
        $arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
    }
    // Person Types
    if (!$bRus) {
        $personType["ip"] = "Y";
        $personType["ur"] = "N";
    }

    $ipExist = in_array(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
    $urExist = in_array(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);
    $fizUaExist = in_array(GetMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames);

    $personTypeIp = (isset($personType["ip"]) && $personType["ip"] == "Y" ? "Y" : "N");
    COption::SetOptionString($moduleId, "personTypeIp", $personTypeIp, false, WIZARD_SITE_ID);
    $personTypeUr = (isset($personType["ur"]) && $personType["ur"] == "Y" ? "Y" : "N");
    COption::SetOptionString($moduleId, "personTypeUr", $personTypeUr, false, WIZARD_SITE_ID);

    if (in_array(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames)) {
        $arGeneralInfo["personType"]["ip"] = array_search(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
        $fields = Array(
            "ACTIVE" => $personTypeIp,
        );
        if ($needEntity) {
            $fields['ENTITY_REGISTRY_TYPE'] = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER;
        }
        CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames), $fields);
    } elseif ($personTypeIp == "Y") {
        $fields = Array(
            "LID" => WIZARD_SITE_ID,
            "NAME" => GetMessage("SALE_WIZARD_PERSON_1"),
            "SORT" => "100",
        );
        if ($needEntity) {
            $fields['ENTITY_REGISTRY_TYPE'] = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER;
        }
        $arGeneralInfo["personType"]["ip"] = CSalePersonType::Add($fields);
    }

    if (in_array(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames)) {
        $arGeneralInfo["personType"]["ur"] = array_search(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);

        $fields = Array(
            "ACTIVE" => $personTypeUr
        );
        if ($needEntity) {
            $fields['ENTITY_REGISTRY_TYPE'] = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER;
        }

        CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames), $fields);
    } elseif ($personTypeUr == "Y") {
        $fields = Array(
            "LID" => WIZARD_SITE_ID,
            "NAME" => GetMessage("SALE_WIZARD_PERSON_2"),
            "SORT" => "150",
        );
        if ($needEntity) {
            $fields['ENTITY_REGISTRY_TYPE'] = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER;
        }
        $arGeneralInfo["personType"]["ur"] = CSalePersonType::Add($fields);
    }

    /*$moduleId*/

    if (COption::GetOptionString($moduleId, "wizard_installed", "N", WIZARD_SITE_ID) != "Y" || WIZARD_INSTALL_DEMO_DATA) {
        $dbCurrency = Bitrix\Sale\Internals\SiteCurrencyTable::getList(array(
            "filter" => array(
                "LID" => WIZARD_SITE_ID
            )
        ));
        if ($curCurrency = $dbCurrency->fetch()) {
            if ($curCurrency["CURRENCY"] != $defCurrency) {
                Bitrix\Sale\Internals\SiteCurrencyTable::update(WIZARD_SITE_ID, array(
                    "CURRENCY" => $defCurrency
                ));
            }
        } else {
            Bitrix\Sale\Internals\SiteCurrencyTable::add(array(
                "LID" => WIZARD_SITE_ID,
                "CURRENCY" => $defCurrency
            ));
        }

        // Set options
        COption::SetOptionString('sale', 'default_currency', $defCurrency);
        COption::SetOptionString('sale', 'delete_after', '30');
        COption::SetOptionString('sale', 'order_list_date', '30');
        COption::SetOptionString('sale', 'MAX_LOCK_TIME', '30');
        COption::SetOptionString('sale', 'GRAPH_WEIGHT', '600');
        COption::SetOptionString('sale', 'GRAPH_HEIGHT', '600');
        COption::SetOptionString('sale', 'path2user_ps_files', '/bitrix/php_interface/include/sale_payment/');
        COption::SetOptionString('sale', 'lock_catalog', 'Y');
        COption::SetOptionString('sale', 'order_list_fields', 'ID,USER,PAY_SYSTEM,PRICE,STATUS,PAYED,PS_STATUS,CANCELED,BASKET');
        COption::SetOptionString('sale', 'GROUP_DEFAULT_RIGHT', 'D');
        COption::SetOptionString('sale', 'affiliate_param_name', 'partner');
        COption::SetOptionString('sale', 'show_order_sum', 'N');
        COption::SetOptionString('sale', 'show_order_product_xml_id', 'N');
        COption::SetOptionString('sale', 'show_paysystem_action_id', 'N');
        COption::SetOptionString('sale', 'affiliate_plan_type', 'N');
        if ($bRus) {
            COption::SetOptionString('sale', '1C_SALE_SITE_LIST', WIZARD_SITE_ID);
            COption::SetOptionString('sale', '1C_EXPORT_PAYED_ORDERS', 'N');
            COption::SetOptionString('sale', '1C_EXPORT_ALLOW_DELIVERY_ORDERS', 'N');
            COption::SetOptionString('sale', '1C_EXPORT_FINAL_ORDERS', '');
            COption::SetOptionString('sale', '1C_FINAL_STATUS_ON_DELIVERY', 'F');
            COption::SetOptionString('sale', '1C_REPLACE_CURRENCY', GetMessage("SALE_WIZARD_PS_BILL_RUB"));
            COption::SetOptionString('sale', '1C_SALE_USE_ZIP', 'Y');
        }
        COption::SetOptionString('sale', 'weight_unit', GetMessage("SALE_WIZARD_WEIGHT_UNIT"), false, WIZARD_SITE_ID);
        COption::SetOptionString('sale', 'WEIGHT_different_set', 'N', false, WIZARD_SITE_ID);
        COption::SetOptionString('sale', 'ADDRESS_different_set', 'N');
        COption::SetOptionString('sale', 'measurement_path', '/bitrix/modules/sale/measurements.php');
        COption::SetOptionString('sale', 'delivery_handles_custom_path', '/bitrix/php_interface/include/sale_delivery/');
        if ($bRus)
            COption::SetOptionString('sale', 'location_zip', '101000');
        COption::SetOptionString('sale', 'weight_koef', '1000', false, WIZARD_SITE_ID);

        COption::SetOptionString('sale', 'recalc_product_list', 'Y');
        COption::SetOptionString('sale', 'recalc_product_list_period', '4');
        COption::SetOptionString('sale', 'order_email', $shopEmail);
        COption::SetOptionString('sale', 'encode_fuser_id', 'Y');

        $arParamPersonalBuyerID = array();
        if(!empty(intval($arGeneralInfo["personType"]["ur"])))
            $arParamPersonalBuyerID[] = $arGeneralInfo["personType"]["ur"];
        if(!empty(intval($arGeneralInfo["personType"]["ip"])))
            $arParamPersonalBuyerID[] = $arGeneralInfo["personType"]["ip"];

        if(!empty($arParamPersonalBuyerID))
            Bitrix\Main\Config\Option::set($moduleId, 'BUYER_PERSONAL_TYPE', serialize($arParamPersonalBuyerID), WIZARD_SITE_ID);

        if (!$bRus)
            $shopLocation = GetMessage("WIZ_CITY");

        if (\Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y') {
            $location = '';

            if (strlen($shopLocation)) {
                // get city with name equal to $shopLocation
                $item = \Bitrix\Sale\Location\LocationTable::getList(array(
                    'filter' => array(
                        '=NAME.LANGUAGE_ID' => $lang,
                        '=NAME.NAME' => $shopLocation,
                        '=TYPE.CODE' => 'CITY'
                    ),
                    'select' => array(
                        'CODE'
                    )
                ))->fetch();

                if ($item)
                    $location = $item['CODE']; // city found, simply take it`s code an proceed with it
                else {
                    // city were not found, create it
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . WIZARD_SERVICE_RELATIVE_PATH . "/locations/pro/country_codes.php"))
                        require($_SERVER['DOCUMENT_ROOT'] . WIZARD_SERVICE_RELATIVE_PATH . "/locations/pro/country_codes.php");
                    else
                        $LOCALIZATION_COUNTRY_CODE_MAP = array(
                            'ru' => '0000028023',
                            'ua' => '0000000364',
                            'kz' => '0000000276',
                            'bl' => '0000000001'
                        );

                    // due to some reasons, $shopLocalization is being changed at the beginning of the step,
                    // but here we want to have real country selected, so introduce a new variable
                    $shopCountry = $wizard->GetDefaultVar("shopLocalization");

                    $countryCode = $LOCALIZATION_COUNTRY_CODE_MAP[$shopCountry];
                    $countryId = false;

                    if (strlen($countryCode)) {
                        // get country which matches the current localization
                        $countryId = 0;
                        $item = \Bitrix\Sale\Location\LocationTable::getList(array(
                            'filter' => array(
                                '=CODE' => $countryCode,
                                '=TYPE.CODE' => 'COUNTRY'
                            ),
                            'select' => array(
                                'ID'
                            )
                        ))->fetch();

                        // country found
                        if ($item)
                            $countryId = $item['ID'];
                    }

                    // at this point types must exist
                    $types = array();
                    $res = \Bitrix\Sale\Location\TypeTable::getList();
                    while ($item = $res->fetch())
                        $types[$item['CODE']] = $item['ID'];

                    if (isset($types['COUNTRY']) && isset($types['CITY'])) {
                        if (!$countryId) {
                            // such country were not found, create it

                            $data = array(
                                'CODE' => 'demo_country_' . WIZARD_SITE_ID,
                                'TYPE_ID' => $types['COUNTRY'],
                                'NAME' => array()
                            );
                            foreach ($arLanguages as $langID) {
                                $data["NAME"][$langID] = array(
                                    'NAME' => GetMessage("WIZ_COUNTRY_" . ToUpper($shopCountry))
                                );
                            }

                            $res = \Bitrix\Sale\Location\LocationTable::add($data);
                            if ($res->isSuccess())
                                $countryId = $res->getId();
                        }

                        if ($countryId) {
                            // ok, so country were created, now create demo-city

                            $data = array(
                                'CODE' => 'demo_city_' . WIZARD_SITE_ID,
                                'TYPE_ID' => $types['CITY'],
                                'NAME' => array(),
                                'PARENT_ID' => $countryId
                            );
                            foreach ($arLanguages as $langID) {
                                $data["NAME"][$langID] = array(
                                    'NAME' => $shopLocation
                                );
                            }

                            $res = \Bitrix\Sale\Location\LocationTable::add($data);
                            if ($res->isSuccess())
                                $location = 'demo_city_' . WIZARD_SITE_ID;
                        }
                    }
                }
            }
        } else {
            $location = 0;
            $dbLocation = CSaleLocation::GetList(Array(
                "ID" => "ASC"
            ), Array(
                "LID" => $lang,
                "CITY_NAME" => $shopLocation
            ));
            if ($arLocation = $dbLocation->Fetch()) // if there are no data in module
            {
                $location = $arLocation["ID"];
            }
            if (IntVal($location) <= 0) {
                $CurCountryID = 0;
                $db_contList = CSaleLocation::GetList(Array(), Array(
                    "COUNTRY_NAME" => GetMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization)),
                    "LID" => $lang
                ));
                if ($arContList = $db_contList->Fetch()) {
                    $LLL = IntVal($arContList["ID"]);
                    $CurCountryID = IntVal($arContList["COUNTRY_ID"]);
                }

                if (IntVal($CurCountryID) <= 0) {
                    $arArrayTmp = Array();
                    $arArrayTmp["NAME"] = GetMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization));
                    foreach ($arLanguages as $langID) {
                        WizardServices::IncludeServiceLang("step1.php", $langID);
                        $arArrayTmp[$langID] = array(
                            "LID" => $langID,
                            "NAME" => GetMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization))
                        );
                    }
                    $CurCountryID = CSaleLocation::AddCountry($arArrayTmp);
                }

                $arArrayTmp = Array();
                $arArrayTmp["NAME"] = $shopLocation;
                foreach ($arLanguages as $langID) {
                    $arArrayTmp[$langID] = array(
                        "LID" => $langID,
                        "NAME" => $shopLocation
                    );
                }
                $city_id = CSaleLocation::AddCity($arArrayTmp);

                $location = CSaleLocation::AddLocation(array(
                    "COUNTRY_ID" => $CurCountryID,
                    "CITY_ID" => $city_id
                ));
                if ($bRus)
                    CSaleLocation::AddLocationZIP($location, "101000");

                WizardServices::IncludeServiceLang("step1.php", $lang);
            }
        }

        COption::SetOptionString('sale', 'location', $location);
    }


    // Order Prop Group
    if ($ipExist) {
        $dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_IP1")
        ), false, false, array(
            "ID"
        ));
        if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
            $arGeneralInfo["propGroup"]["user_ip"] = $arSaleOrderPropsGroup["ID"];

        $dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_IP2")
        ), false, false, array(
            "ID"
        ));
        if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
            $arGeneralInfo["propGroup"]["adres_ip"] = $arSaleOrderPropsGroup["ID"];
    } elseif ($personType["ip"] == "Y") {
        $arGeneralInfo["propGroup"]["user_ip"] = CSaleOrderPropsGroup::Add(Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_IP1"),
            "SORT" => 100
        ));
        $arGeneralInfo["propGroup"]["adres_ip"] = CSaleOrderPropsGroup::Add(Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_IP2"),
            "SORT" => 200
        ));
    }

    if ($urExist) {
        $dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1")
        ), false, false, array(
            "ID"
        ));
        if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
            $arGeneralInfo["propGroup"]["user_ur"] = $arSaleOrderPropsGroup["ID"];

        $dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2")
        ), false, false, array(
            "ID"
        ));
        if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
            $arGeneralInfo["propGroup"]["adres_ur"] = $arSaleOrderPropsGroup["ID"];
    } elseif ($personType["ur"] == "Y") {
        $arGeneralInfo["propGroup"]["user_ur"] = CSaleOrderPropsGroup::Add(Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1"),
            "SORT" => 300
        ));
        $arGeneralInfo["propGroup"]["adres_ur"] = CSaleOrderPropsGroup::Add(Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
            "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2"),
            "SORT" => 400
        ));
    }

    $businessValuePersonDomain = array();

    $businessValueGroups = array(
        'COMPANY' => array(
            'SORT' => 100
        ),
        'CLIENT' => array(
            'SORT' => 200
        ),
        'CLIENT_COMPANY' => array(
            'SORT' => 300
        )
    );

    $businessValueCodes = array();

    $arProps = Array();

    if ($personType["ip"] == "Y") {
        $businessValuePersonDomain[$arGeneralInfo["personType"]["ip"]] = $BIZVAL_ENTITY_DOMAIN;

        if ($shopLocalization != "ua") {
            $businessValueCodes['COMPANY_NAME'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 200,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_6"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 200,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "Y",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "NAME",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_SECOND_NAME'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 200,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_8"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 200,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "Y",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "SECOND_NAME",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_ADDRESS'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 210,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_7"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 210,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_INN'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 220,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_13"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 220,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "INN",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_KPP'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 230,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_14"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 230,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "KPP",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_CONTACT_NAME'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 240,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_10"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 240,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "Y",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CONTACT_NAME",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_EMAIL'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 250,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => "E-Mail",
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 250,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "Y",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EMAIL",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_PHONE'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 260,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_9"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 260,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "IS_PHONE" => "Y",
                "CODE" => "PHONE",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_FAX'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 270,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_11"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 270,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "FAX",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_ZIP'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 280,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_4"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "101000",
                "SORT" => 280,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 8,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ZIP",
                "IS_FILTERED" => "N",
                "IS_ZIP" => "Y"
            );

            $businessValueCodes['COMPANY_CITY'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 285,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_21"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => $shopLocation,
                "SORT" => 285,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CITY",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_LOCATION'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 290,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_2"),
                "TYPE" => "LOCATION",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 290,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "Y",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "Y",
                "CODE" => "LOCATION",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_OPERATION_ADDRESS'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 300,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_12"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 300,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 10,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "OPERATION_ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );
        } else {
            $businessValueCodes['COMPANY_EMAIL'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 110,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => "E-Mail",
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 110,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "Y",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EMAIL",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_NAME'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 130,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_40"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 130,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "Y",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "NAME",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_ADDRESS'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 140,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_47"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 140,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_EGRPU'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 150,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_48"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 150,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EGRPU",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_INN'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 160,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_49"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 160,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "INN",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_NDS'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 170,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_46"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 170,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "NDS",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_ZIP'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 180,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_44"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 180,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 8,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ZIP",
                "IS_FILTERED" => "N",
                "IS_ZIP" => "Y"
            );

            $businessValueCodes['COMPANY_CITY'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 190,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_43"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => $shopLocation,
                "SORT" => 190,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CITY",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_OPERATION_ADDRESS'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 200,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_42"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 200,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 3,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "OPERATION_ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_PHONE'] = array(
                'GROUP' => 'CLIENT_COMPANY',
                'SORT' => 210,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_45"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 210,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ip"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "PHONE",
                "IS_FILTERED" => "N"
            );
        }
    }

    if ($personType["ur"] == "Y") {
        $businessValuePersonDomain[$arGeneralInfo["personType"]["ur"]] = $BIZVAL_ENTITY_DOMAIN;

        if ($shopLocalization != "ua") {
            $businessValueCodes['COMPANY_NAME'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 200,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_8"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 200,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "Y",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "COMPANY",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_ADDRESS'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 210,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_7"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 210,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "COMPANY_ADR",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_INN'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 220,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_13"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 220,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "INN",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_KPP'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 230,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_14"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 230,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "KPP",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_CONTACT_NAME'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 240,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_10"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 240,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "Y",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CONTACT_PERSON",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_EMAIL'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 250,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => "E-Mail",
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 250,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "Y",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EMAIL",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_PHONE'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 260,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_9"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 260,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "IS_PHONE" => "Y",
                "CODE" => "PHONE",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_FAX'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 270,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_11"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 270,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "FAX",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_ZIP'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 280,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_4"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "101000",
                "SORT" => 280,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 8,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ZIP",
                "IS_FILTERED" => "N",
                "IS_ZIP" => "Y"
            );

            $businessValueCodes['COMPANY_CITY'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 285,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_21"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => $shopLocation,
                "SORT" => 285,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CITY",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_LOCATION'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 290,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_2"),
                "TYPE" => "LOCATION",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 290,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "Y",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "Y",
                "CODE" => "LOCATION",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_OPERATION_ADDRESS'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 300,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_12"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 300,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 10,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );
        } else {

            $businessValueCodes['COMPANY_EMAIL'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 110,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => "E-Mail",
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 110,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "Y",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EMAIL",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_NAME'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 130,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_40"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 130,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "Y",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "COMPANY_NAME",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_ADDRESS'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 140,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_47"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 140,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 40,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "COMPANY_ADR",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_EGRPU'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 150,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_48"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 150,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "EGRPU",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_INN'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 160,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_49"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 160,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "INN",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_NDS'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 170,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_46"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 170,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "NDS",
                "IS_FILTERED" => "N"
            );

            $businessValueCodes['COMPANY_ZIP'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 180,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_44"),
                "TYPE" => "TEXT",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 180,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 8,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ZIP",
                "IS_FILTERED" => "N",
                "IS_ZIP" => "Y"
            );

            $businessValueCodes['COMPANY_CITY'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 190,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_43"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => $shopLocation,
                "SORT" => 190,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "CITY",
                "IS_FILTERED" => "Y"
            );

            $businessValueCodes['COMPANY_OPERATION_ADDRESS'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 200,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_42"),
                "TYPE" => "TEXTAREA",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 200,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 3,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "ADDRESS",
                "IS_FILTERED" => "N",
                "IS_ADDRESS" => "Y"
            );

            $businessValueCodes['COMPANY_PHONE'] = array(
                'GROUP' => 'COMPANY',
                'SORT' => 210,
                'DOMAIN' => $BIZVAL_ENTITY_DOMAIN
            );
            $arProps[] = Array(
                "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                "NAME" => GetMessage("SALE_WIZARD_PROP_45"),
                "TYPE" => "TEXT",
                "REQUIED" => "Y",
                "DEFAULT_VALUE" => "",
                "SORT" => 210,
                "USER_PROPS" => "Y",
                "IS_LOCATION" => "N",
                "PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
                "SIZE1" => 30,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                "IS_LOCATION4TAX" => "N",
                "CODE" => "PHONE",
                "IS_FILTERED" => "N"
            );
        }
    }

    $propCityId = 0;
    reset($businessValueCodes);

    foreach ($arProps as $prop) {
        $variants = Array();
        if (!empty($prop["VARIANTS"])) {
            $variants = $prop["VARIANTS"];
            unset($prop["VARIANTS"]);
        }

        if ($prop["CODE"] == "LOCATION" && $propCityId > 0) {
            $prop["INPUT_FIELD_LOCATION"] = $propCityId;
            $propCityId = 0;
        }

        $dbSaleOrderProps = CSaleOrderProps::GetList(array(), array(
            "PERSON_TYPE_ID" => $prop["PERSON_TYPE_ID"],
            "CODE" => $prop["CODE"]
        ));
        if ($arSaleOrderProps = $dbSaleOrderProps->GetNext())
            $id = $arSaleOrderProps["ID"];
        else
            $id = CSaleOrderProps::Add($prop);

        if ($prop["CODE"] == "CITY") {
            $propCityId = $id;
        }
        if (strlen($prop["CODE"]) > 0) {
            // $arGeneralInfo["propCode"][$prop["CODE"]] = $prop["CODE"];
            $arGeneralInfo["propCodeID"][$prop["CODE"]] = $id;
            $arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]] = $prop;
            $arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]]["ID"] = $id;
        }

        if (!empty($variants)) {
            foreach ($variants as $val) {
                $val["ORDER_PROPS_ID"] = $id;
                CSaleOrderPropsVariant::Add($val);
            }
        }

        // add business value mapping to property
        $businessValueCodes[key($businessValueCodes)]['MAP'] = array(
            $prop['PERSON_TYPE_ID'] => array(
                'PROPERTY',
                $id
            )
        );
        next($businessValueCodes);
    }

    /*
	 * $propReplace = "";
	 * foreach($arGeneralInfo["properies"] as $key => $val)
	 * {
	 * if(IntVal($val["LOCATION"]["ID"]) > 0)
	 * $propReplace .= '"PROP_'.$key.'" => Array(0 => "'.$val["LOCATION"]["ID"].'"), ';
	 * }
	 * WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/order/", Array("PROPS" => $propReplace));
	 */
    // 1C export
    if ($personType["ip"] == "Y" && !$ipExist) {
        $val = serialize(
            Array(
                "AGENT_NAME" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_COMPANY"]["ID"]
                ),
                "FULL_NAME" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_COMPANY"]["ID"]
                ),
                "ADDRESS_FULL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_COMPANY_ADR"]["ID"]
                ),
                "COUNTRY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_LOCATION"]["ID"] . "_COUNTRY"
                ),
                "CITY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_LOCATION"]["ID"] . "_CITY"
                ),
                "STREET" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_COMPANY_ADR"]["ID"]
                ),
                "INN" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_INN"]["ID"]
                ),
                "KPP" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_KPP"]["ID"]
                ),
                "PHONE" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_PHONE"]["ID"]
                ),
                "EMAIL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_EMAIL"]["ID"]
                ),
                "CONTACT_PERSON" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_NAME"]["ID"]
                ),
                "F_ADDRESS_FULL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_ADDRESS"]["ID"]
                ),
                "F_COUNTRY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_LOCATION"]["ID"] . "_COUNTRY"
                ),
                "F_CITY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_LOCATION"]["ID"] . "_CITY"
                ),
                "F_INDEX" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_ZIP"]["ID"]
                ),
                "F_STREET" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip"]]["IP_ADDRESS"]["ID"]
                ),
                "IS_FIZ" => "N"
            ));

        $allPersonTypes = BusinessValue::getPersonTypes(true);
        $personTypeId = $arGeneralInfo["personType"]["ip"];
        $domain = BusinessValue::INDIVIDUAL_DOMAIN;

        if (!isset($allPersonTypes[$personTypeId]['DOMAIN'])) {
            $r = Bitrix\Sale\Internals\BusinessValuePersonDomainTable::add(array(
                'PERSON_TYPE_ID' => $personTypeId,
                'DOMAIN' => $domain
            ));
            if ($r->isSuccess()) {
                $allPersonTypes[$personTypeId]['DOMAIN'] = $domain;
                BusinessValue::getPersonTypes(true, $allPersonTypes);
            }
        }

        CSaleExport::Add(Array(
            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
            "VARS" => $val
        ));
    }
    if ($personType["ur"] == "Y" && !$urExist) {
        $val = serialize(
            Array(
                "AGENT_NAME" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY"]["ID"]
                ),
                "FULL_NAME" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY"]["ID"]
                ),
                "ADDRESS_FULL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY_ADR"]["ID"]
                ),
                "COUNTRY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_COUNTRY"
                ),
                "CITY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_CITY"
                ),
                "STREET" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY_ADR"]["ID"]
                ),
                "INN" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["INN"]["ID"]
                ),
                "KPP" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["KPP"]["ID"]
                ),
                "PHONE" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["PHONE"]["ID"]
                ),
                "EMAIL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["EMAIL"]["ID"]
                ),
                "CONTACT_PERSON" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["NAME"]["ID"]
                ),
                "F_ADDRESS_FULL" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ADDRESS"]["ID"]
                ),
                "F_COUNTRY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_COUNTRY"
                ),
                "F_CITY" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_CITY"
                ),
                "F_INDEX" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ZIP"]["ID"]
                ),
                "F_STREET" => Array(
                    "TYPE" => "PROPERTY",
                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ADDRESS"]["ID"]
                ),
                "IS_FIZ" => "N"
            ));
        /*
                        $allPersonTypes = BusinessValue::getPersonTypes(true);
                        $personTypeId = $arGeneralInfo["personType"]["ur"];
                        $domain = BusinessValue::ENTITY_DOMAIN;

                        if (!isset($allPersonTypes[$personTypeId]['DOMAIN'])) {
                            $r = Bitrix\Sale\Internals\BusinessValuePersonDomainTable::add(array(
                                'PERSON_TYPE_ID' => $personTypeId,
                                'DOMAIN' => $domain
                            ));
                            if ($r->isSuccess()) {
                                $allPersonTypes[$personTypeId]['DOMAIN'] = $domain;
                                BusinessValue::getPersonTypes(true, $allPersonTypes);
                            }
                        }

                        CSaleExport::Add(Array(
                            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
                            "VARS" => $val
                        ));
                    }
                    if ($shopLocalization == "ua" && !$fizUaExist) {
                        $val = serialize(
                            Array(
                                "AGENT_NAME" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["FIO"]["ID"]
                                ),
                                "FULL_NAME" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["FIO"]["ID"]
                                ),
                                "SURNAME" => Array(
                                    "TYPE" => "USER",
                                    "VALUE" => "LAST_NAME"
                                ),
                                "NAME" => Array(
                                    "TYPE" => "USER",
                                    "VALUE" => "NAME"
                                ),
                                "ADDRESS_FULL" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["ADDRESS"]["ID"]
                                ),
                                "INDEX" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["ZIP"]["ID"]
                                ),
                                "COUNTRY" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["LOCATION"]["ID"] . "_COUNTRY"
                                ),
                                "CITY" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["LOCATION"]["ID"] . "_CITY"
                                ),
                                "STREET" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["ADDRESS"]["ID"]
                                ),
                                "EMAIL" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["EMAIL"]["ID"]
                                ),
                                "CONTACT_PERSON" => Array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ip_ua"]]["CONTACT_PERSON"]["ID"]
                                ),
                                "IS_IP" => "Y"
                            ));
                        CSaleExport::Add(Array(
                            "PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ip"],
                            "VARS" => $val
                        ));
                    }*/
        // PaySystem
        $arPaySystems = array();
        if ($paysystem["cash"] == "Y") {
            $arPaySystems[] = array(
                'PAYSYSTEM' => array(
                    "NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
                    "PSA_NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
                    "SORT" => 80,
                    "ACTIVE" => "Y",
                    "IS_CASH" => "Y",
                    "DESCRIPTION" => GetMessage("SALE_WIZARD_PS_CASH_DESCR"),
                    "ACTION_FILE" => "cash",
                    "RESULT_FILE" => "",
                    "NEW_WINDOW" => "N",
                    "PARAMS" => "",
                    "HAVE_PAYMENT" => "Y",
                    "HAVE_ACTION" => "N",
                    "HAVE_RESULT" => "N",
                    "HAVE_PREPAY" => "N",
                    "HAVE_RESULT_RECEIVE" => "N"
                ),
                'PERSON_TYPE' => array(
                    $arGeneralInfo["personType"]["ip"]
                )
            );
        }

        if ($paysystem["collect"] == "Y") {
            $arPaySystems[] = array(
                'PAYSYSTEM' => array(
                    "NAME" => GetMessage("SALE_WIZARD_PS_COLLECT"),
                    "SORT" => 110,
                    "ACTIVE" => "Y",
                    "DESCRIPTION" => GetMessage("SALE_WIZARD_PS_COLLECT_DESCR"),
                    "PSA_NAME" => GetMessage("SALE_WIZARD_PS_COLLECT"),
                    "ACTION_FILE" => "cashondeliverycalc",
                    "RESULT_FILE" => "",
                    "NEW_WINDOW" => "N",
                    "HAVE_PAYMENT" => "Y",
                    "HAVE_ACTION" => "N",
                    "HAVE_RESULT" => "N",
                    "HAVE_PREPAY" => "N",
                    "HAVE_RESULT_RECEIVE" => "N"
                ),
                'PERSON_TYPE' => array(
                    $arGeneralInfo["personType"]["ip"],
                    $arGeneralInfo["personType"]["ur"]
                )
            );
        }
        if ($personType["ip"] == "Y" && $shopLocalization != "ua") {
            if ($bRus) {
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YMoney"),
                        "SORT" => 50,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YMoney_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YMoney"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "PS_MODE" => "PC",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y"
                    ),
                    'PERSON_TYPE' => array(
                        $arGeneralInfo["personType"]["ip"]
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            ),
                            "PS_IS_TEST" => array(
                                "VALUE" => "Y"
                            ),
                            "PS_CHANGE_STATUS_PAY" => array(
                                "VALUE" => "Y"
                            ),
                            "YANDEX_SHOP_ID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SCID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SHOP_KEY" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            )
                        )
                    )
                );

                $logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/yandex_cards.png";
                $arPicture = CFile::MakeFileArray($logo);
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YCards"),
                        "SORT" => 60,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YCards_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YCards"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y",
                        "PS_MODE" => "AC",
                        "LOGOTIP" => $arPicture
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "ORDER",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            ),
                            "PS_IS_TEST" => array(
                                "VALUE" => "Y"
                            ),
                            "PS_CHANGE_STATUS_PAY" => array(
                                "VALUE" => "Y"
                            ),
                            "YANDEX_SHOP_ID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SCID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SHOP_KEY" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            )
                        )
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["ip"]
                    )
                );
                $logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/yandex_terminals.png";
                $arPicture = CFile::MakeFileArray($logo);
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YTerminals"),
                        "SORT" => 70,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YTerminals_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YTerminals"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y",
                        "LOGOTIP" => $arPicture
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "ORDER",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            ),
                            "PS_IS_TEST" => array(
                                "VALUE" => "Y"
                            ),
                            "PS_CHANGE_STATUS_PAY" => array(
                                "VALUE" => "Y"
                            ),
                            "YANDEX_SHOP_ID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SCID" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            ),
                            "YANDEX_SHOP_KEY" => array(
                                "TYPE" => "",
                                "VALUE" => ""
                            )
                        )
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["ip"]
                    )
                );
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_PS_WM"),
                        "SORT" => 90,
                        "ACTIVE" => "N",
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_PS_WM_DESCR"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_PS_WM"),
                        "ACTION_FILE" => "webmoney",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "Y",
                        "PARAMS" => "",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "Y",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "N"
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["ip"]
                    )
                );

                if ($paysystem["sber"] == "Y") {
                    $arPaySystems[] = array(
                        'PAYSYSTEM' => array(
                            "NAME" => GetMessage("SALE_WIZARD_PS_SB"),
                            "SORT" => 110,
                            "DESCRIPTION" => GetMessage("SALE_WIZARD_PS_SB_DESCR"),
                            "PSA_NAME" => GetMessage("SALE_WIZARD_PS_SB"),
                            "ACTION_FILE" => "sberbank",
                            "RESULT_FILE" => "",
                            "NEW_WINDOW" => "Y",
                            "HAVE_PAYMENT" => "Y",
                            "HAVE_ACTION" => "N",
                            "HAVE_RESULT" => "N",
                            "HAVE_PREPAY" => "N",
                            "HAVE_RESULT_RECEIVE" => "N"
                        ),
                        "PERSON_TYPE" => array(
                            $arGeneralInfo["personType"]["fiz"]
                        ),
                        "BIZVAL" => array(
                            '' => array(
                                "SELLER_COMPANY_NAME" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopOfName
                                ),
                                "SELLER_COMPANY_INN" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopINN
                                ),
                                "SELLER_COMPANY_KPP" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopKPP
                                ),
                                "SELLER_COMPANY_BANK_ACCOUNT" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopNS
                                ),
                                "SELLER_COMPANY_BANK_NAME" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopBANK
                                ),
                                "SELLER_COMPANY_BANK_BIC" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopBANKREKV
                                ),
                                "SELLER_COMPANY_BANK_ACCOUNT_CORR" => array(
                                    "TYPE" => "",
                                    "VALUE" => $shopKS
                                ),
                                "PAYMENT_ID" => array(
                                    "TYPE" => "PAYMENT",
                                    "VALUE" => "ACCOUNT_NUMBER"
                                ),
                                "PAYMENT_DATE_INSERT" => array(
                                    "TYPE" => "PAYMENT",
                                    "VALUE" => "DATE_INSERT_DATE"
                                ),
                                "BUYER_PERSON_FIO" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "FIO"
                                ),
                                "BUYER_PERSON_ZIP" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "ZIP"
                                ),
                                "BUYER_PERSON_COUNTRY" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "LOCATION_COUNTRY"
                                ),
                                "BUYER_PERSON_REGION" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "LOCATION_REGION"
                                ),
                                "BUYER_PERSON_CITY" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "LOCATION_CITY"
                                ),
                                "BUYER_PERSON_ADDRESS_FACT" => array(
                                    "TYPE" => "PROPERTY",
                                    "VALUE" => "ADDRESS"
                                ),
                                "PAYMENT_SHOULD_PAY" => array(
                                    "TYPE" => "PAYMENT",
                                    "VALUE" => "SUM"
                                )
                            )
                        )
                    );
                }
            } else {
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => "PayPal",
                        "SORT" => 90,
                        "DESCRIPTION" => "",
                        "PSA_NAME" => "PayPal",
                        "ACTION_FILE" => "paypal",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y"
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL_DATE"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            ),
                            "PAYMENT_CURRENCY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "CURRENCY"
                            )
                        )
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["fiz"]
                    )
                );
            }
        }
        if ($personType["ur"] == "Y" && $paysystem["bill"] == "Y" && $shopLocalization != "ua") {
            $arPaySystems[] = array(
                'PAYSYSTEM' => array(
                    "NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
                    "SORT" => 100,
                    "DESCRIPTION" => "",
                    "PSA_NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
                    "ACTION_FILE" => "bill",
                    "RESULT_FILE" => "",
                    "NEW_WINDOW" => "Y",
                    "HAVE_PAYMENT" => "Y",
                    "HAVE_ACTION" => "N",
                    "HAVE_RESULT" => "N",
                    "HAVE_PREPAY" => "N",
                    "HAVE_RESULT_RECEIVE" => "N"
                ),
                "PERSON_TYPE" => array(
                    $arGeneralInfo["personType"]["ur"]
                ),
                "BIZVAL" => array(
                    '' => array(
                        "PAYMENT_DATE_INSERT" => Array(
                            "TYPE" => "PAYMENT",
                            "VALUE" => "DATE_BILL_DATE"
                        ),
                        "SELLER_COMPANY_NAME" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopOfName
                        ),
                        "SELLER_COMPANY_ADDRESS" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopAdr
                        ),
                        "SELLER_COMPANY_PHONE" => Array(
                            "TYPE" => "",
                            "VALUE" => $siteTelephone
                        ),
                        "SELLER_COMPANY_INN" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopINN
                        ),
                        "SELLER_COMPANY_KPP" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopKPP
                        ),
                        "SELLER_COMPANY_BANK_ACCOUNT" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopNS
                        ),
                        "SELLER_COMPANY_BANK_ACCOUNT_CORR" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopKS
                        ),
                        "SELLER_COMPANY_BANK_BIC" => Array(
                            "TYPE" => "",
                            "VALUE" => $shopBANKREKV
                        ),
                        "BUYER_PERSON_COMPANY_NAME" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "COMPANY_NAME"
                        ),
                        "BUYER_PERSON_COMPANY_INN" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "INN"
                        ),
                        "BUYER_PERSON_COMPANY_ADDRESS" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "COMPANY_ADR"
                        ),
                        "BUYER_PERSON_COMPANY_PHONE" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "PHONE"
                        ),
                        "BUYER_PERSON_COMPANY_FAX" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FAX"
                        ),
                        "BUYER_PERSON_COMPANY_NAME_CONTACT" => Array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "CONTACT_PERSON"
                        ),
                        "BILL_PATH_TO_STAMP" => Array(
                            "TYPE" => "",
                            "VALUE" => $siteStamp
                        )
                    )
                )
            );
        }
        // Ukraine
        if ($shopLocalization == "ua") {
            // oshadbank
            if (($personType["fiz"] == "Y" || $personType["fiz_ua"] == "Y") && $paysystem["oshad"] == "Y") {
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_PS_OS"),
                        "SORT" => 90,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_PS_OS_DESCR"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_PS_OS"),
                        "ACTION_FILE" => "/bitrix/modules/sale/payment/oshadbank",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "Y",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "N"
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["fiz"],
                        $arGeneralInfo["personType"]["fiz_ua"]
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "RECIPIENT_NAME" => array(
                                "TYPE" => "",
                                "VALUE" => $shopOfName
                            ),
                            "RECIPIENT_ID" => array(
                                "TYPE" => "",
                                "VALUE" => $shopEGRPU_ua
                            ),
                            "RECIPIENT_NUMBER" => array(
                                "TYPE" => "",
                                "VALUE" => $shopNS_ua
                            ),
                            "RECIPIENT_BANK" => array(
                                "TYPE" => "",
                                "VALUE" => $shopBank_ua
                            ),
                            "RECIPIENT_CODE_BANK" => array(
                                "TYPE" => "",
                                "VALUE" => $shopMFO_ua
                            ),
                            "PAYER_FIO" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "FIO"
                            ),
                            "PAYER_ADRES" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "ADDRESS"
                            ),
                            "ORDER_ID" => array(
                                "TYPE" => "ORDER",
                                "VALUE" => "ID"
                            ),
                            "DATE_INSERT" => array(
                                "TYPE" => "ORDER",
                                "VALUE" => "DATE_INSERT_DATE"
                            ),
                            "PAYER_CONTACT_PERSON" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "FIO"
                            ),
                            "PAYER_INDEX" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "ZIP"
                            ),
                            "PAYER_COUNTRY" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "LOCATION_COUNTRY"
                            ),
                            "PAYER_TOWN" => array(
                                "TYPE" => "PROPERTY",
                                "VALUE" => "LOCATION_CITY"
                            ),
                            "SHOULD_PAY" => array(
                                "TYPE" => "ORDER",
                                "VALUE" => "PRICE"
                            )
                        )
                    )
                );
            }
            if ($personType["fiz"] == "Y") {
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YMoney"),
                        "SORT" => 60,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YMoney_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YMoney"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "PS_MODE" => "PC",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y"
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["fiz"]
                    ),
                    "PARAMS" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            )
                        )
                    )
                );
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YCards"),
                        "SORT" => 70,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YCards_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YCards"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "PS_MODE" => "AC",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y"
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["fiz"]
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            )
                        )
                    )
                );
                $arPaySystems[] = array(
                    'PAYSYSTEM' => array(
                        "NAME" => GetMessage("SALE_WIZARD_YTerminals"),
                        "SORT" => 80,
                        "DESCRIPTION" => GetMessage("SALE_WIZARD_YTerminals_DESC"),
                        "PSA_NAME" => GetMessage("SALE_WIZARD_YTerminals"),
                        "ACTION_FILE" => "yandex",
                        "RESULT_FILE" => "",
                        "NEW_WINDOW" => "N",
                        "HAVE_PAYMENT" => "Y",
                        "HAVE_ACTION" => "N",
                        "HAVE_RESULT" => "N",
                        "HAVE_PREPAY" => "N",
                        "HAVE_RESULT_RECEIVE" => "Y",
                        "PS_MODE" => "GP"
                    ),
                    "PERSON_TYPE" => array(
                        $arGeneralInfo["personType"]["fiz"]
                    ),
                    "BIZVAL" => array(
                        '' => array(
                            "PAYMENT_ID" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "ID"
                            ),
                            "PAYMENT_DATE_INSERT" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "DATE_BILL"
                            ),
                            "PAYMENT_SHOULD_PAY" => array(
                                "TYPE" => "PAYMENT",
                                "VALUE" => "SUM"
                            )
                        )
                    )
                );
            }
            // bill
            if ($paysystem["bill"] == "Y") {
                $arPaySystem['PAYSYSTEM'] = array(
                    "NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
                    "PSA_NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
                    "ACTION_FILE" => "billua",
                    "RESULT_FILE" => "",
                    "NEW_WINDOW" => "Y",
                    "HAVE_PAYMENT" => "Y",
                    "HAVE_ACTION" => "N",
                    "HAVE_RESULT" => "N",
                    "HAVE_PREPAY" => "N",
                    "HAVE_RESULT_RECEIVE" => "N"
                );

                $arPaySystem['PERSON_TYPE'] = array();
                $arPaySystem['BIZVAL'] = array();

                if ($personType["ur"] == "Y") {
                    $arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["ur"];
                    $arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["ur"]] = array(
                        "PAYMENT_DATE_INSERT" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "DATE_INSERT_DATE"
                        ),
                        "SELLER_COMPANY_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopOfName
                        ),
                        "SELLER_COMPANY_ADDRESS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopAdr
                        ),
                        "SELLER_COMPANY_PHONE" => array(
                            "TYPE" => "",
                            "VALUE" => $siteTelephone
                        ),
                        "SELLER_COMPANY_IPN" => array(
                            "TYPE" => "",
                            "VALUE" => $shopINN_ua
                        ),
                        "SELLER_COMPANY_EDRPOY" => array(
                            "TYPE" => "",
                            "VALUE" => $shopEGRPU_ua
                        ),
                        "SELLER_COMPANY_BANK_ACCOUNT" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNS_ua
                        ),
                        "SELLER_COMPANY_BANK_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopBank_ua
                        ),
                        "SELLER_COMPANY_MFO" => array(
                            "TYPE" => "",
                            "VALUE" => $shopMFO_ua
                        ),
                        "SELLER_COMPANY_PDV" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNDS_ua
                        ),
                        "PAYMENT_ID" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "ID"
                        ),
                        "SELLER_COMPANY_SYS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopTax_ua
                        ),
                        "BUYER_PERSON_COMPANY_NAME" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "COMPANY_NAME"
                        ),
                        "BUYER_PERSON_COMPANY_ADDRESS" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "COMPANY_ADR"
                        ),
                        "BUYER_PERSON_COMPANY_PHONE" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "PHONE"
                        ),
                        "BUYER_PERSON_COMPANY_FAX" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FAX"
                        ),
                        "BILLUA_PATH_TO_STAMP" => array(
                            "TYPE" => "",
                            "VALUE" => $siteStamp
                        )
                    );
                }

                if ($personType["ip"] == "Y") {
                    $arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["fiz"];
                    $arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["fiz"]] = array(
                        "PAYMENT_DATE_INSERT" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "DATE_INSERT_DATE"
                        ),
                        "SELLER_COMPANY_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopOfName
                        ),
                        "SELLER_COMPANY_ADDRESS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopAdr
                        ),
                        "SELLER_COMPANY_PHONE" => array(
                            "TYPE" => "",
                            "VALUE" => $siteTelephone
                        ),
                        "SELLER_COMPANY_IPN" => array(
                            "TYPE" => "",
                            "VALUE" => $shopINN_ua
                        ),
                        "SELLER_COMPANY_EDRPOY" => array(
                            "TYPE" => "",
                            "VALUE" => $shopEGRPU_ua
                        ),
                        "SELLER_COMPANY_BANK_ACCOUNT" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNS_ua
                        ),
                        "SELLER_COMPANY_BANK_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopBank_ua
                        ),
                        "SELLER_COMPANY_MFO" => array(
                            "TYPE" => "",
                            "VALUE" => $shopMFO_ua
                        ),
                        "SELLER_COMPANY_PDV" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNDS_ua
                        ),
                        "PAYMENT_ID" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "ID"
                        ),
                        "SELLER_COMPANY_SYS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopTax_ua
                        ),
                        "BUYER_PERSON_COMPANY_NAME" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FIO"
                        ),
                        "BUYER_PERSON_COMPANY_ADDRESS" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "ADDRESS"
                        ),
                        "BUYER_PERSON_COMPANY_PHONE" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "PHONE"
                        ),
                        "BUYER_PERSON_COMPANY_FAX" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FAX"
                        ),
                        "BILLUA_PATH_TO_STAMP" => array(
                            "TYPE" => "",
                            "VALUE" => $siteStamp
                        )
                    );
                }

                if ($personType["fiz_ua"] == "Y") {
                    $arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["fiz_ua"];
                    $arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["fiz_ua"]] = array(
                        "PAYMENT_DATE_INSERT" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "DATE_INSERT_DATE"
                        ),
                        "SELLER_COMPANY_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopOfName
                        ),
                        "SELLER_COMPANY_ADDRESS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopAdr
                        ),
                        "SELLER_COMPANY_PHONE" => array(
                            "TYPE" => "",
                            "VALUE" => $siteTelephone
                        ),
                        "SELLER_COMPANY_IPN" => array(
                            "TYPE" => "",
                            "VALUE" => $shopINN_ua
                        ),
                        "SELLER_COMPANY_EDRPOY" => array(
                            "TYPE" => "",
                            "VALUE" => $shopEGRPU_ua
                        ),
                        "SELLER_COMPANY_BANK_ACCOUNT" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNS_ua
                        ),
                        "SELLER_COMPANY_BANK_NAME" => array(
                            "TYPE" => "",
                            "VALUE" => $shopBank_ua
                        ),
                        "SELLER_COMPANY_MFO" => array(
                            "TYPE" => "",
                            "VALUE" => $shopMFO_ua
                        ),
                        "SELLER_COMPANY_PDV" => array(
                            "TYPE" => "",
                            "VALUE" => $shopNDS_ua
                        ),
                        "PAYMENT_ID" => array(
                            "TYPE" => "ORDER",
                            "VALUE" => "ID"
                        ),
                        "SELLER_COMPANY_SYS" => array(
                            "TYPE" => "",
                            "VALUE" => $shopTax_ua
                        ),
                        "BUYER_PERSON_COMPANY_NAME" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FIO"
                        ),
                        "BUYER_PERSON_COMPANY_ADDRESS" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "COMPANY_ADR"
                        ),
                        "BUYER_PERSON_COMPANY_PHONE" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "PHONE"
                        ),
                        "BUYER_PERSON_COMPANY_FAX" => array(
                            "TYPE" => "PROPERTY",
                            "VALUE" => "FAX"
                        ),
                        "BILLUA_PATH_TO_STAMP" => array(
                            "TYPE" => "",
                            "VALUE" => $siteStamp
                        )
                    );
                }

                $arPaySystems[] = $arPaySystem;
            }
        }
        // }

        foreach ($arPaySystems as $i => $arPaySystem) {
            $updateFields = array();

            $val = $arPaySystem['PAYSYSTEM'];
            if (array_key_exists('LOGOTIP', $val) && is_array($val['LOGOTIP'])) {
                $updateFields['LOGOTIP'] = $val['LOGOTIP'];
                unset($val['LOGOTIP']);
            }

            $dbRes = \Bitrix\Sale\PaySystem\Manager::getList(array(
                'select' => array(
                    "ID",
                    "NAME"
                ),
                'filter' => array(
                    "NAME" => $val["NAME"],
                )
            ));
            $tmpPaySystem = $dbRes->fetch();

            if (!$tmpPaySystem) {
                if ($needEntity) {
                    $val['ENTITY_REGISTRY_TYPE'] = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER;
                }

                $resultAdd = \Bitrix\Sale\Internals\PaySystemActionTable::add($val);
                if ($resultAdd->isSuccess()) {
                    $id = $resultAdd->getId();

                    if (array_key_exists('BIZVAL', $arPaySystem) && $arPaySystem['BIZVAL']) {
                        $arGeneralInfo["paySystem"][$arPaySystem["ACTION_FILE"]] = $id;
                        foreach ($arPaySystem['BIZVAL'] as $personType => $codes) {
                            foreach ($codes as $code => $map) {
                                \Bitrix\Sale\BusinessValue::setMapping($code, 'PAYSYSTEM_' . $id, $personType, array(
                                    'PROVIDER_KEY' => $map['TYPE'] ?: 'VALUE',
                                    'PROVIDER_VALUE' => $map['VALUE']
                                ), true);
                            }
                        }
                    }

                    if ($arPaySystem['PERSON_TYPE']) {
                        $params = array(
                            'filter' => array(
                                "SERVICE_ID" => $id,
                                "SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
                                "=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
                            )
                        );

                        $dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
                        if (!$dbRes->fetch()) {
                            $fields = array(
                                "SERVICE_ID" => $id,
                                "SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
                                "SORT" => 100,
                                "PARAMS" => array(
                                    'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
                                )
                            );
                            \Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields);
                        }
                    }

                    $updateFields['PARAMS'] = serialize(array(
                        'BX_PAY_SYSTEM_ID' => $id
                    ));
                    $updateFields['PAY_SYSTEM_ID'] = $id;

                    $image = '/bitrix/modules/sale/install/images/sale_payments/' . $val['ACTION_FILE'] . '.png';
                    if ((!array_key_exists('LOGOTIP', $updateFields) || !is_array($updateFields['LOGOTIP'])) && \Bitrix\Main\IO\File::isFileExists($_SERVER['DOCUMENT_ROOT'] . $image)) {
                        $updateFields['LOGOTIP'] = CFile::MakeFileArray($image);
                        $updateFields['LOGOTIP']['MODULE_ID'] = "sale";
                    }
                    CFile::SaveForDB($updateFields, 'LOGOTIP', 'sale/paysystem/logotip');
                    \Bitrix\Sale\Internals\PaySystemActionTable::update($id, $updateFields);
                }
            } else {
                $flag = false;

                $params = array(
                    'filter' => array(
                        "SERVICE_ID" => $tmpPaySystem['ID'],
                        "SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
                        "=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
                    )
                );

                $dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
                $restriction = $dbRes->fetch();

                if ($restriction) {
                    foreach ($restriction['PARAMS']['PERSON_TYPE_ID'] as $personTypeId) {
                        if (array_search($personTypeId, $arPaySystem['PERSON_TYPE']) === false) {
                            $arPaySystem['PERSON_TYPE'][] = $personTypeId;
                            $flag = true;
                        }
                    }

                    $restrictionId = $restriction['ID'];
                }

                if ($flag) {
                    $fields = array(
                        "SERVICE_ID" => $restrictionId,
                        "SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
                        "SORT" => 100,
                        "PARAMS" => array(
                            'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
                        )
                    );

                    \Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields, $restrictionId);
                }
                if ($needEntity) {
                    \Bitrix\Sale\Internals\PaySystemActionTable::update($tmpPaySystem['ID'], ['ENTITY_REGISTRY_TYPE' => \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER]);
                }
            }
        }

        if (COption::GetOptionString($moduleId, "wizard_installed", "N", WIZARD_SITE_ID) != "Y" || WIZARD_INSTALL_DEMO_DATA) {
            if ($saleConverted15) {
                $orderPaidStatus = 'P';
                $deliveryAssembleStatus = 'DA';
                $deliveryGoodsStatus = 'DG';
                $deliveryTransportStatus = 'DT';
                $deliveryShipmentStatus = 'DS';

                $statusIds = array(
                    $orderPaidStatus,
                    $deliveryAssembleStatus,
                    $deliveryGoodsStatus,
                    $deliveryTransportStatus,
                    $deliveryShipmentStatus
                );

                $statusLanguages = array();

                foreach ($arLanguages as $langID) {
                    Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/lib/status.php', $langID);

                    foreach ($statusIds as $statusId) {
                        if ($statusName = Loc::getMessage("SALE_STATUS_{$statusId}")) {
                            $statusLanguages[$statusId][] = array(
                                'LID' => $langID,
                                'NAME' => $statusName,
                                'DESCRIPTION' => Loc::getMessage("SALE_STATUS_{$statusId}_DESCR")
                            );
                        }
                    }
                }

                OrderStatus::install(array(
                    'ID' => $orderPaidStatus,
                    'SORT' => 150,
                    'NOTIFY' => 'Y',
                    'LANG' => $statusLanguages[$orderPaidStatus]
                ));
                CSaleStatus::CreateMailTemplate($orderPaidStatus);

                DeliveryStatus::install(array(
                    'ID' => $deliveryAssembleStatus,
                    'SORT' => 310,
                    'NOTIFY' => 'Y',
                    'LANG' => $statusLanguages[$deliveryAssembleStatus]
                ));

                DeliveryStatus::install(array(
                    'ID' => $deliveryGoodsStatus,
                    'SORT' => 320,
                    'NOTIFY' => 'Y',
                    'LANG' => $statusLanguages[$deliveryGoodsStatus]
                ));

                DeliveryStatus::install(array(
                    'ID' => $deliveryTransportStatus,
                    'SORT' => 330,
                    'NOTIFY' => 'Y',
                    'LANG' => $statusLanguages[$deliveryTransportStatus]
                ));

                DeliveryStatus::install(array(
                    'ID' => $deliveryShipmentStatus,
                    'SORT' => 340,
                    'NOTIFY' => 'Y',
                    'LANG' => $statusLanguages[$deliveryShipmentStatus]
                ));
            } else {
                $bStatusP = false;
                $dbStatus = CSaleStatus::GetList(Array(
                    "SORT" => "ASC"
                ));
                while ($arStatus = $dbStatus->Fetch()) {
                    $arFields = Array();
                    foreach ($arLanguages as $langID) {
                        WizardServices::IncludeServiceLang("step1.php", $langID);
                        $arFields["LANG"][] = Array(
                            "LID" => $langID,
                            "NAME" => GetMessage("WIZ_SALE_STATUS_" . $arStatus["ID"]),
                            "DESCRIPTION" => GetMessage("WIZ_SALE_STATUS_DESCRIPTION_" . $arStatus["ID"])
                        );
                    }
                    $arFields["ID"] = $arStatus["ID"];
                    CSaleStatus::Update($arStatus["ID"], $arFields);
                    if ($arStatus["ID"] == "P")
                        $bStatusP = true;
                }
                if (!$bStatusP) {
                    $arFields = Array(
                        "ID" => "P",
                        "SORT" => 150
                    );
                    foreach ($arLanguages as $langID) {
                        WizardServices::IncludeServiceLang("step1.php", $langID);
                        $arFields["LANG"][] = Array(
                            "LID" => $langID,
                            "NAME" => GetMessage("WIZ_SALE_STATUS_P"),
                            "DESCRIPTION" => GetMessage("WIZ_SALE_STATUS_DESCRIPTION_P")
                        );
                    }

                    $ID = CSaleStatus::Add($arFields);
                    if ($ID !== '') {
                        CSaleStatus::CreateMailTemplate($ID);
                    }
                }
            }

            if (CModule::IncludeModule("currency")) {
                $dbCur = CCurrency::GetList($by = "currency", $o = "asc");
                while ($arCur = $dbCur->Fetch()) {
                    if ($lang == "ru")
                        CCurrencyLang::Update($arCur["CURRENCY"], $lang, array(
                            "DECIMALS" => 2,
                            "HIDE_ZERO" => "Y"
                        ));
                    elseif ($arCur["CURRENCY"] == "EUR")
                        CCurrencyLang::Update($arCur["CURRENCY"], $lang, array(
                            "DECIMALS" => 2,
                            "FORMAT_STRING" => "&euro;#"
                        ));
                }
            }
            WizardServices::IncludeServiceLang("step1.php", $lang);

            if (CModule::IncludeModule("catalog")) {
                $dbVat = CCatalogVat::GetListEx(array(), array(
                    'RATE' => 0
                ), false, false, array(
                    'ID',
                    'RATE'
                ));
                if (!($dbVat->Fetch())) {
                    $arF = array(
                        "ACTIVE" => "Y",
                        "SORT" => "100",
                        "NAME" => GetMessage("WIZ_VAT_1"),
                        "RATE" => 0
                    );
                    CCatalogVat::Add($arF);
                }
                $dbVat = CCatalogVat::GetListEx(array(), array(
                    'RATE' => GetMessage("WIZ_VAT_2_VALUE")
                ), false, false, array(
                    'ID',
                    'RATE'
                ));
                if (!($dbVat->Fetch())) {
                    $arF = array(
                        "ACTIVE" => "Y",
                        "SORT" => "200",
                        "NAME" => GetMessage("WIZ_VAT_2"),
                        "RATE" => GetMessage("WIZ_VAT_2_VALUE")
                    );
                    CCatalogVat::Add($arF);
                }
                $dbResultList = CCatalogGroup::GetList(array(), array(
                    "CODE" => "BASE"
                ));
                if ($arRes = $dbResultList->Fetch()) {
                    $arFields = Array();
                    foreach ($arLanguages as $langID) {
                        WizardServices::IncludeServiceLang("step1.php", $langID);
                        $arFields["USER_LANG"][$langID] = GetMessage("WIZ_PRICE_NAME");
                    }
                    $arFields["BASE"] = "Y";
                    if ($wizard->GetDefaultVar("installPriceBASE") == "Y") {
                        $db_res = CCatalogGroup::GetGroupsList(array(
                            "CATALOG_GROUP_ID" => '1',
                            "BUY" => "Y"
                        ));
                        if ($ar_res = $db_res->Fetch()) {
                            $wizGroupId[] = $ar_res['GROUP_ID'];
                        }
                        $wizGroupId[] = 2;
                        $arFields["USER_GROUP"] = $wizGroupId;
                        $arFields["USER_GROUP_BUY"] = $wizGroupId;
                    }
                    CCatalogGroup::Update($arRes["ID"], $arFields);
                }
            }

            // making orders
            function __MakeOrder(array $arData, array $productFilter, $prdCnt = 1)
            {
                static $catalogIncluded = null;
                static $saleIncluded = null;

                if (empty($arData) || empty($productFilter))
                    return false;

                $prdCnt = ( int )$prdCnt;
                if ($prdCnt < 1 || $prdCnt > 20)
                    $prdCnt = 1;

                if ($catalogIncluded === null)
                    $catalogIncluded = Main\Loader::includeModule('catalog');
                if (!$catalogIncluded)
                    return false;
                if ($saleIncluded === null)
                    $saleIncluded = Main\Loader::includeModule('sale');
                if (!$saleIncluded)
                    return false;

                $arPrd = array();
                $dbItem = CIBlockElement::GetList(array(), $productFilter, false, array(
                    "nTopCount" => 100
                ), array(
                    "ID",
                    "IBLOCK_ID",
                    "NAME"
                ));
                while ($arItem = $dbItem->Fetch())
                    $arPrd[] = $arItem;
                unset($arItem, $dbItem);

                if (empty($arPrd))
                    return false;

                $order = Sale\Order::create($arData['SITE_ID'], $arData['USER_ID'], $arData['CURRENCY']);
                $order->setPersonTypeId($arData['PERSON_TYPE_ID']);
                if (!empty($arData['PROPS'])) {
                    $propertyValues = array();
                    $propertyCollection = $order->getPropertyCollection();
                    /** @var Sale\PropertyValue $property */
                    foreach ($propertyCollection as $property) {
                        if ($property->isUtil())
                            continue;

                        $propertyId = $property->getPropertyId();
                        if (!isset($arData['PROPS'][$propertyId]) && $property->isRequired())
                            return false;

                        $propertyValues[$propertyId] = $arData['PROPS'][$propertyId];
                        unset($propertyId);
                    }
                    unset($property);
                    if (!empty($propertyValues)) {
                        $result = $propertyCollection->setValuesFromPost(array(
                            'PROPERTIES' => $propertyValues
                        ), array());
                        if (!$result->isSuccess())
                            return false;
                        unset($result);
                    }
                    unset($propertyValues);
                }

                $basket = Sale\Basket::create($arData['SITE_ID']);
                $basket->setFUserId($arData['FUSER_ID']);

                while ($prdCnt > 0) {
                    $product = $arPrd[mt_rand(0, 99)];
                    $item = $basket->createItem('catalog', $product['ID']);

                    $result = $item->setFields(array(
                        'NAME' => $product['NAME'],
                        'QUANTITY' => 1,
                        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
                    ));

                    if (!$result->isSuccess())
                        return false;

                    $prdCnt--;
                    unset($result, $product);
                }

                $result = $order->setBasket($basket);
                if (!$result->isSuccess())
                    return false;
                unset($result);

                $shipmentCollection = $order->getShipmentCollection();
                $shipment = $shipmentCollection->createItem();
                $shipmentItemCollection = $shipment->getShipmentItemCollection();

                /** @var Sale\BasketItem $basketItem */
                foreach ($order->getBasket() as $basketItem) {
                    /** @var Sale\ShipmentItem $shipmentItem */
                    $shipmentItem = $shipmentItemCollection->createItem($basketItem);
                    $result = $shipmentItem->setQuantity($basketItem->getQuantity());
                    if (!$result->isSuccess())
                        return false;
                    unset($result);
                }
                unset($basketItem);

                $emptyDeliveryServiceId = Sale\Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId();
                $result = $shipment->setField('DELIVERY_ID', $emptyDeliveryServiceId);
                if (!$result->isSuccess())
                    return false;
                unset($result);

                $paySystemObject = Sale\PaySystem\Manager::getObjectById($arData['PAY_SYSTEM_ID']);
                if ($paySystemObject === null)
                    return false;
                $paymentCollection = $order->getPaymentCollection();
                /** @var \Bitrix\Sale\Payment $payment */
                $payment = $paymentCollection->createItem($paySystemObject);

                $discounts = $order->getDiscount();
                $result = $discounts->calculate();
                if (!$result->isSuccess())
                    return false;
                unset($result);

                $result = $payment->setFields(array(
                    'SUM' => $order->getPrice(),
                    'CURRENCY' => $order->getCurrency()
                ));
                if (!$result->isSuccess())
                    return false;
                unset($result);

                $result = $order->save();
                if (!$result->isSuccess())
                    return false;
                unset($result);

                return $order->getId();
            }

            $personType = $arGeneralInfo["personType"]["ur"];
            if (IntVal($arGeneralInfo["personType"]["ip"]) > 0)
                $personType = $arGeneralInfo["personType"]["ip"];
            if (IntVal($personType) <= 0) {
                $dbPerson = CSalePersonType::GetList(array(), Array(
                    "LID" => WIZARD_SITE_ID
                ));
                if ($arPerson = $dbPerson->Fetch()) {
                    $personType = $arPerson["ID"];
                }
            }
            $paySystem = 0;
            if (IntVal($arGeneralInfo["paySystem"]["cash"]) > 0)
                $paySystem = $arGeneralInfo["paySystem"]["cash"];
            elseif (IntVal($arGeneralInfo["paySystem"]["bill"]) > 0)
                $paySystem = $arGeneralInfo["paySystem"]["bill"];
            elseif (IntVal($arGeneralInfo["paySystem"]["sberbank"]) > 0)
                $paySystem = $arGeneralInfo["paySystem"]["sberbank"];
            elseif (IntVal($arGeneralInfo["paySystem"]["paypal"]) > 0)
                $paySystem = $arGeneralInfo["paySystem"]["paypal"];
            else {
                $dbPS = \Bitrix\Sale\PaySystem\Manager::getList(array());
                if ($arPS = $dbPS->fetch())
                    $paySystem = $arPS["ID"];
            }

            if (\Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y') {
                if (!strlen($location)) {
                    // get first found
                    $item = \Bitrix\Sale\Location\LocationTable::getList(array(
                        'limit' => 1,
                        'select' => array(
                            'CODE'
                        )
                    ))->fetch();
                    if ($item)
                        $location = $item['CODE'];
                }
            } else {
                if (IntVal($location) <= 0) {
                    $dbLocation = CSaleLocation::GetList(Array(
                        "ID" => "ASC"
                    ), Array(
                        "LID" => $lang
                    ));
                    if ($arLocation = $dbLocation->Fetch()) {
                        $location = $arLocation["ID"];
                    }
                }
            }

            if (empty($arGeneralInfo["properies"][$personType])) {
                $dbProp = CSaleOrderProps::GetList(array(), Array(
                    "PERSON_TYPE_ID" => $personType
                ));
                while ($arProp = $dbProp->Fetch())
                    $arGeneralInfo["properies"][$personType][$arProp["CODE"]] = $arProp;
            }

            if (WIZARD_INSTALL_DEMO_DATA) {
                $db_sales = CSaleOrder::GetList(array(
                    "DATE_INSERT" => "ASC"
                ), array(
                    "LID" => WIZARD_SITE_ID
                ), false, false, array(
                    "ID"
                ));
                while ($ar_sales = $db_sales->Fetch()) {
                    CSaleOrder::Delete($ar_sales["ID"]);
                }
            }

            $arData = Array(
                "SITE_ID" => WIZARD_SITE_ID,
                "PERSON_TYPE_ID" => $personType,
                "CURRENCY" => $defCurrency,
                "USER_ID" => 1,
                "FUSER_ID" => Sale\Fuser::getIdByUserId(1),
                "PAY_SYSTEM_ID" => $paySystem,
                "PROPS" => Array()
            );
            foreach ($arGeneralInfo["properies"][$personType] as $key => $val) {
                $propertyValue = '';

                if ($key == "FIO" || $key == "CONTACT_PERSON")
                    $propertyValue = GetMessage("WIZ_ORD_FIO");
                elseif ($key == "ADDRESS" || $key == "COMPANY_ADR")
                    $propertyValue = GetMessage("WIZ_ORD_ADR");
                elseif ($key == "EMAIL")
                    $propertyValue = "example@example.com";
                elseif ($key == "PHONE")
                    $propertyValue = "8 495 2312121";
                elseif ($key == "ZIP")
                    $propertyValue = "101000";
                elseif ($key == "LOCATION")
                    $propertyValue = $location;
                elseif ($key == "CITY")
                    $propertyValue = $shopLocation;
                $arData["PROPS"][$val["ID"]] = $propertyValue;
            }

            $productFilter = array(
                "=IBLOCK_TYPE" => "catalog",
                "=IBLOCK_SITE_ID" => WIZARD_SITE_ID,
                "PROPERTY_NEWPRODUCT" => false,
                "ACTIVE" => "Y",
                "CATALOG_AVAILABLE" => "Y",
                "CATALOG_TYPE" => Catalog\ProductTable::TYPE_OFFER
            );

            $orderID = __MakeOrder($arData, $productFilter, 3);
            if ($orderID) {
                CSaleOrder::DeliverOrder($orderID, "Y");
                CSaleOrder::PayOrder($orderID, "Y");
                CSaleOrder::StatusOrder($orderID, "F");
            }
            $orderID = __MakeOrder($arData, $productFilter, 4);
            if ($orderID) {
                CSaleOrder::DeliverOrder($orderID, "Y");
                CSaleOrder::PayOrder($orderID, "Y");
                CSaleOrder::StatusOrder($orderID, "F");
            }
            $orderID = __MakeOrder($arData, $productFilter, 2);
            if ($orderID) {
                CSaleOrder::PayOrder($orderID, "Y");
                CSaleOrder::StatusOrder($orderID, "P");
            }
            $orderID = __MakeOrder($arData, $productFilter, 1);
            $orderID = __MakeOrder($arData, $productFilter, 1);
            if ($orderID) {
                CSaleOrder::CancelOrder($orderID, "Y");
            }
            CAgent::RemoveAgent("CSaleProduct::RefreshProductList();", "sale");
            CAgent::AddAgent("CSaleProduct::RefreshProductList();", "sale", "N", 60 * 60 * 24 * 4, "", "Y");
        }
    }

    $orderProps = CSaleOrderProps::GetList(
        array("SORT" => "ASC"),
        array('CODE' => ['INN', 'COMPANY', 'NAME', 'SECOND_NAME']),
        false,
        false,
        array()
    );

    $arrInn = array();
    $arrCompany = array();
    while($res = $orderProps->fetch()){
        if($res['CODE'] == 'INN')
            $arrInn[] = $res['ID'];
        if($res['CODE'] == 'COMPANY' || $res['CODE'] == 'NAME' || $res['CODE'] == 'SECOND_NAME')
            $arrCompany[] = $res['ID'];
    }

    if( count($arrInn) > 0 )
        Option::set($moduleId, 'PROFILE_ORG_INN', serialize($arrInn), WIZARD_SITE_ID);
    if( count($arrCompany) > 0 )
        Option::set($moduleId, 'PROFILE_ORG_NAME', serialize($arrCompany), WIZARD_SITE_ID);

    Option::set($moduleId, 'ADDRESS_COMPANY', "/b2bcabinet/personal/buyer/profile_detail.php?ID=#ID#", WIZARD_SITE_ID);
    Option::set($moduleId, 'ADDRESS_ORDER', "/b2bcabinet/order/detail/#ID#/", WIZARD_SITE_ID);
}
?>