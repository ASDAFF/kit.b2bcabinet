<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

if(IsModuleInstalled('sotbit.b2bshop') && (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.b2bshop/") || file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/sotbit/b2bshop/"))) {
    $moduleName = 'sotbit.b2bcabinet';
    Option::Set($moduleName, 'IBLOCK_TYPE', \Sotbit\Origami\Helper\Config::get('IBLOCK_TYPE'), WIZARD_SITE_ID);
    Option::Set($moduleName, 'IBLOCK_ID', \Sotbit\Origami\Helper\Config::get('IBLOCK_ID'), WIZARD_SITE_ID);

    $obUserField = new CUserTypeEntity();
    $arUserField = array(
        "ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION',
        "FIELD_NAME" => 'UF_DESCR_BOTTOM',
        "USER_TYPE_ID" => 'html',
        "XML_ID" => 'UF_DESCR_BOTTOM',
        "SORT" => 100,
        "MULTIPLE" => "N",
        "MANDATORY" => 'N',
        "SHOW_FILTER" => "N",
        "SHOW_IN_LIST" => "Y",
        "EDIT_IN_LIST" => "Y",
        "IS_SEARCHABLE" => "N",
        "SETTINGS" => array(),
    );

    $userFieldID = $obUserField->Add($arUserField);
    if($userFieldID > 0)
        $obUserField->Update(
            $userFieldID,
            [
                'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'ERROR_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'HELP_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            ]
        );

}
else {

    $moduleName = 'sotbit.b2bcabinet';
    Loader::IncludeModule($moduleName);

    if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
        return;

    if(COption::GetOptionString("sotbit.b2bcabinet", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
        return;



//catalog iblock import
    $shopLocalization = $wizard->GetVar("shopLocalization");

    $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml";

    $arUpdateList = CUpdateClient::GetUpdatesList($errorMessage, LANG, $stableVersionsOnly);

    if($arUpdateList["CLIENT"][0]["@"]['L1'] == 'Small') //small business
    {
        $text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$iblockXMLFile);
        preg_match_all('!<'.GetMessage('WIZ_PRICE_NAME_TYPE').'>(.*?)</'.GetMessage('WIZ_PRICE_NAME_TYPE').'>\n\t\t!si', $text,$matches );
        if($matches){
            foreach($matches[0] as $match){
                if(strpos($match,'BASE') === false){
                    $text = str_replace($match,'',$text);
                }
            }
        }
        preg_match_all('!<'.GetMessage('WIZ_PRICE_NAME_TITLE').'>(.*?)</'.GetMessage('WIZ_PRICE_NAME_TITLE').'>\n\t\t\t\t\t!si', $text,$matches );
        if($matches){
            foreach($matches[0] as $match){
                if(strpos($match,'BASE') === false){
                    $text = str_replace($match,'',$text);
                }
            }
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog_sb.xml",$text);
        $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog_sb.xml";
    }

    $iblockCode = "sotbit_b2bcabinet_catalog_" . WIZARD_SITE_ID;
    $iblockType = "sotbit_b2bcabinet_type_catalog";
    $iblockXMLID = 'sotbit_b2bcabinet_catalog_' . WIZARD_SITE_ID;

    set_time_limit(0);

    $rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockType));
    $IBLOCK_CATALOG_ID = false;
    if ($arIBlock = $rsIBlock->Fetch())
    {
        $IBLOCK_CATALOG_ID = $arIBlock["ID"];
    }

    if (WIZARD_INSTALL_DEMO_DATA && $IBLOCK_CATALOG_ID)
    {
        $boolFlag = true;
        $arSKU = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_CATALOG_ID);
        if (!empty($arSKU))
        {
            $boolFlag = CCatalog::UnLinkSKUIBlock($IBLOCK_CATALOG_ID);
            if (!$boolFlag)
            {
                $strError = "";
                if ($ex = $APPLICATION->GetException())
                {
                    $strError = $ex->GetString();
                }
                else
                {
                    $strError = "Couldn't unlink iblocks";
                }
            }
            $boolFlag = true;
        }

        if ($boolFlag)
        {
            $oldSites2 = [];
            $rs = CIblock::GetSite($IBLOCK_CATALOG_ID);
            while($site = $rs->Fetch())
            {
                $oldSites2[] = $site['SITE_ID'];
            }
            $boolFlag = CIBlock::Delete($IBLOCK_CATALOG_ID);
            if (!$boolFlag)
            {
                $strError = "";
                if ($ex = $APPLICATION->GetException())
                {
                    $strError = $ex->GetString();
                }
                else
                {
                    $strError = "Couldn't delete catalog iblock";
                }
            }
        }
        if ($boolFlag)
        {
            $IBLOCK_CATALOG_ID = false;
        }
    }

    if($IBLOCK_CATALOG_ID == false)
    {
        $permissions = Array(
            "1" => "X",
            "2" => "R"
        );
        $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "sale_administrator"));
        if($arGroup = $dbGroup -> Fetch())
        {
            $permissions[$arGroup["ID"]] = 'W';
        }
        $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
        if($arGroup = $dbGroup -> Fetch())
        {
            $permissions[$arGroup["ID"]] = 'W';
        }

        \Bitrix\Catalog\Product\Sku::disableUpdateAvailable();

        $sites = [WIZARD_SITE_ID];

        if($oldSites2 && is_array($oldSites2))
        {
            $sites = array_merge($sites,$oldSites2);
        }

        $IBLOCK_CATALOG_ID = WizardServices::ImportIBlockFromXML(
            $iblockXMLFile,
            $iblockCode,
            $iblockType,
            $sites,
            $permissions
        );

        \Bitrix\Catalog\Product\Sku::enableUpdateAvailable();
        if ($IBLOCK_CATALOG_ID < 1)
            return;

        $_SESSION["WIZARD_CATALOG_IBLOCK_ID"] = $IBLOCK_CATALOG_ID;


        $iblock = new CIBlock;
        $arFields = array(
            "FIELDS" => array(
                "PREVIEW_PICTURE" => array(
                    "IS_REQUIRED" => "N",
                    "DEFAULT_VALUE" => array(
                        "FROM_DETAIL" => "Y",
                        "SCALE" => "Y",
                        "WIDTH" => "235",
                        "HEIGHT" => "235",
                        "IGNORE_ERRORS" => "N",
                        "METHOD" => "resample",
                        "COMPRESSION" => 75,
                        "DELETE_WITH_DETAIL" => "Y",
                        "UPDATE_WITH_DETAIL" => "Y",
                    ),
                ),
                "PREVIEW_TEXT_TYPE" => array(
                    "IS_REQUIRED" => "Y",
                    "DEFAULT_VALUE" => "text",
                ),
                "DETAIL_TEXT_TYPE" => array(
                    "IS_REQUIRED" => "Y",
                    "DEFAULT_VALUE" => "text",
                ),
                "CODE" => array(
                    "IS_REQUIRED" => "N",
                    "DEFAULT_VALUE" => array(
                        "UNIQUE" => "Y",
                        "TRANSLITERATION" => "Y",
                        "TRANS_LEN" => 100,
                        "TRANS_CASE" => "L",
                        "TRANS_SPACE" => "_",
                        "TRANS_OTHER" => "_",
                        "TRANS_EAT" => "Y",
                        "USE_GOOGLE" => "N",
                    ),
                ),
                "SECTION_CODE" => array(
                    "IS_REQUIRED" => "Y",
                    "DEFAULT_VALUE" => array(
                        "UNIQUE" => "Y",
                        "TRANSLITERATION" => "Y",
                        "TRANS_LEN" => 100,
                        "TRANS_CASE" => "L",
                        "TRANS_SPACE" => "_",
                        "TRANS_OTHER" => "_",
                        "TRANS_EAT" => "Y",
                        "USE_GOOGLE" => "N",
                    ),
                ),
            ),
            "CODE" => $iblockCode,
            "XML_ID" => $iblockXMLID,
        );

        $iblock->Update($IBLOCK_CATALOG_ID, $arFields);

        $arP = \Bitrix\Iblock\PropertyTable::getList(array('filter' => ['IBLOCK_ID' => $IBLOCK_CATALOG_ID],'select' => ['ID', 'CODE', 'NAME']))->fetchAll();

        $arP = array_column($arP, null, 'CODE');

        CUserOptions::SetOption(
            'form',
            'form_element_'.$IBLOCK_CATALOG_ID,
            array (
                'tabs' => 'edit1--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_TAB_EDIT').'--,--ID--#--ID--,--DATE_CREATE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_DATE_CREATE').'--,--TIMESTAMP_X--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_TIMESTAMP_X').'--,--ACTIVE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_ACTIVE').'--,--ACTIVE_FROM--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_ACTIVE_FROM').'--,--ACTIVE_TO--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_ACTIVE_TO').'--,--NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_NAME').'--,--CODE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_CODE').'--,--SORT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SORT').'--,--PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_PREVIEW_PICTURE').'--,--DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_DETAIL_PICTURE').'--,--PROPERTY_'.$arP['30']['ID'].'--#--'.$arP['30']['NAME'].'--,--PREVIEW_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_PREVIEW_TEXT').'--,--DETAIL_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_DETAIL_TEXT').'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IBLOCK_ELEMENT_PROP_VALUE').'--,--PROPERTY_'.$arP['REGIONS']['ID'].'--#--'.$arP['REGIONS']['NAME'].'--,--PROPERTY_'.$arP['BRANDS']['ID'].'--#--'.$arP['BRANDS']['NAME'].'--,--PROPERTY_'.$arP['KHIT']['ID'].'--#--'.$arP['KHIT']['NAME'].'--,--PROPERTY_'.$arP['NOVINKA']['ID'].'--#--'.$arP['NOVINKA']['NAME'].'--,--PROPERTY_'.$arP['RASPRODAZHA']['ID'].'--#--'.$arP['RASPRODAZHA']['NAME'].'--,--PROPERTY_'.$arP['VIDEO']['ID'].'--#--'.$arP['VIDEO']['NAME'].'--,--PROPERTY_'.$arP['VIDEO_CONTENT']['ID'].'--#--'.$arP['VIDEO_CONTENT']['NAME'].'--,--PROPERTY_'.$arP['FILES']['ID'].'--#--'.$arP['FILES']['NAME'].'--,--PROPERTY_'.$arP['DOCUMENTS']['ID'].'--#--'.$arP['DOCUMENTS']['NAME'].'--,--PROPERTY_'.$arP['VYSOTA_PODDONA']['ID'].'--#--'.$arP['VYSOTA_PODDONA']['NAME'].'--,--PROPERTY_'.$arP['OBEM_MOROZILNOY_KAMERY']['ID'].'--#--'.$arP['OBEM_MOROZILNOY_KAMERY']['NAME'].'--,--PROPERTY_'.$arP['RAZMER_OPERATIVNOY_PAMYATI']['ID'].'--#--'.$arP['RAZMER_OPERATIVNOY_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['TIP_SIM_KARTY']['ID'].'--#--'.$arP['TIP_SIM_KARTY']['NAME'].'--,--PROPERTY_'.$arP['VAROCHNAYA_PANEL']['ID'].'--#--'.$arP['VAROCHNAYA_PANEL']['NAME'].'--,--PROPERTY_'.$arP['KONSTRUKTSIYA']['ID'].'--#--'.$arP['KONSTRUKTSIYA']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_PEREDNIKH_STENOK']['ID'].'--#--'.$arP['MATERIAL_PEREDNIKH_STENOK']['NAME'].'--,--PROPERTY_'.$arP['TIP_PAMYATI']['ID'].'--#--'.$arP['TIP_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['VERSIYA_OS']['ID'].'--#--'.$arP['VERSIYA_OS']['NAME'].'--,--PROPERTY_'.$arP['VIDEOKARTA']['ID'].'--#--'.$arP['VIDEOKARTA']['NAME'].'--,--PROPERTY_'.$arP['DUKHOVKA']['ID'].'--#--'.$arP['DUKHOVKA']['NAME'].'--,--PROPERTY_'.$arP['PANEL_UPRAVLENIYA']['ID'].'--#--'.$arP['PANEL_UPRAVLENIYA']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_SIM_KART']['ID'].'--#--'.$arP['KOLICHESTVO_SIM_KART']['NAME'].'--,--PROPERTY_'.$arP['NOMINALNAYA_POTREBLYAEMAYA_MOSHCHNOST']['ID'].'--#--'.$arP['NOMINALNAYA_POTREBLYAEMAYA_MOSHCHNOST']['NAME'].'--,--PROPERTY_'.$arP['OBEM_ZHESTKOGO_DISKA']['ID'].'--#--'.$arP['OBEM_ZHESTKOGO_DISKA']['NAME'].'--,--PROPERTY_'.$arP['PROTIVOSKOLZYASHCHEE_POKRYTIE_DNA']['ID'].'--#--'.$arP['PROTIVOSKOLZYASHCHEE_POKRYTIE_DNA']['NAME'].'--,--PROPERTY_'.$arP['CML2_ARTICLE']['ID'].'--#--'.$arP['CML2_ARTICLE']['NAME'].'--,--PROPERTY_'.$arP['CML2_BASE_UNIT']['ID'].'--#--'.$arP['CML2_BASE_UNIT']['NAME'].'--,--PROPERTY_'.$arP['GABARITY_SHKHVKHD']['ID'].'--#--'.$arP['GABARITY_SHKHVKHD']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_YADER']['ID'].'--#--'.$arP['KOLICHESTVO_YADER']['NAME'].'--,--PROPERTY_'.$arP['MAXIMUM_PRICE']['ID'].'--#--'.$arP['MAXIMUM_PRICE']['NAME'].'--,--PROPERTY_'.$arP['MINIMUM_PRICE']['ID'].'--#--'.$arP['MINIMUM_PRICE']['NAME'].'--,--PROPERTY_'.$arP['CML2_MANUFACTURER']['ID'].'--#--'.$arP['CML2_MANUFACTURER']['NAME'].'--,--PROPERTY_'.$arP['RABOCHAYA_POVERKHNOST']['ID'].'--#--'.$arP['RABOCHAYA_POVERKHNOST']['NAME'].'--,--PROPERTY_'.$arP['RAZMERY_SHXVXT']['ID'].'--#--'.$arP['RAZMERY_SHXVXT']['NAME'].'--,--PROPERTY_'.$arP['CML2_TRAITS']['ID'].'--#--'.$arP['CML2_TRAITS']['NAME'].'--,--PROPERTY_'.$arP['CML2_TAXES']['ID'].'--#--'.$arP['CML2_TAXES']['NAME'].'--,--PROPERTY_'.$arP['CML2_ATTRIBUTES']['ID'].'--#--'.$arP['CML2_ATTRIBUTES']['NAME'].'--,--PROPERTY_'.$arP['CML2_BAR_CODE']['ID'].'--#--'.$arP['CML2_BAR_CODE']['NAME'].'--,--PROPERTY_'.$arP['BLOK_PITANIYA']['ID'].'--#--'.$arP['BLOK_PITANIYA']['NAME'].'--,--PROPERTY_'.$arP['DIAGONAL_EKRANA']['ID'].'--#--'.$arP['DIAGONAL_EKRANA']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_KONFOROK']['ID'].'--#--'.$arP['KOLICHESTVO_KONFOROK']['NAME'].'--,--PROPERTY_'.$arP['TIP_PEREDNEGO_STEKLA']['ID'].'--#--'.$arP['TIP_PEREDNEGO_STEKLA']['NAME'].'--,--PROPERTY_'.$arP['KORPUS']['ID'].'--#--'.$arP['KORPUS']['NAME'].'--,--PROPERTY_'.$arP['RAMA_ROST_VELOSIPEDISTA_SM']['ID'].'--#--'.$arP['RAMA_ROST_VELOSIPEDISTA_SM']['NAME'].'--,--PROPERTY_'.$arP['TIP_DUKHOVKI']['ID'].'--#--'.$arP['TIP_DUKHOVKI']['NAME'].'--,--PROPERTY_'.$arP['TIP_EKRANA']['ID'].'--#--'.$arP['TIP_EKRANA']['NAME'].'--,--PROPERTY_'.$arP['DIAGONAL_EKRANA_1']['ID'].'--#--'.$arP['DIAGONAL_EKRANA_1']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_RAMY']['ID'].'--#--'.$arP['MATERIAL_RAMY']['NAME'].'--,--PROPERTY_'.$arP['RAZMER_IZOBRAZHENIYA']['ID'].'--#--'.$arP['RAZMER_IZOBRAZHENIYA']['NAME'].'--,--PROPERTY_'.$arP['RAZRABOTCHIK_VIDEOKARTY']['ID'].'--#--'.$arP['RAZRABOTCHIK_VIDEOKARTY']['NAME'].'--,--PROPERTY_'.$arP['LINEYKA_PROTSESSOROV']['ID'].'--#--'.$arP['LINEYKA_PROTSESSOROV']['NAME'].'--,--PROPERTY_'.$arP['RAZMERY_RAMY']['ID'].'--#--'.$arP['RAZMERY_RAMY']['NAME'].'--,--PROPERTY_'.$arP['RAZRESHENIE_EKRANA']['ID'].'--#--'.$arP['RAZRESHENIE_EKRANA']['NAME'].'--,--PROPERTY_'.$arP['TYLOVAYA_FOTOKAMERA']['ID'].'--#--'.$arP['TYLOVAYA_FOTOKAMERA']['NAME'].'--,--PROPERTY_'.$arP['SOCKET']['ID'].'--#--'.$arP['SOCKET']['NAME'].'--,--PROPERTY_'.$arP['DIAMETR_KOLES']['ID'].'--#--'.$arP['DIAMETR_KOLES']['NAME'].'--,--PROPERTY_'.$arP['TIP_ZHESTKOGO_DISKA']['ID'].'--#--'.$arP['TIP_ZHESTKOGO_DISKA']['NAME'].'--,--PROPERTY_'.$arP['FRONTALNAYA_KAMERA']['ID'].'--#--'.$arP['FRONTALNAYA_KAMERA']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_YADER_1']['ID'].'--#--'.$arP['KOLICHESTVO_YADER_1']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_OBODA']['ID'].'--#--'.$arP['MATERIAL_OBODA']['NAME'].'--,--PROPERTY_'.$arP['RAZEM_DLYA_NAUSHNIKOV']['ID'].'--#--'.$arP['RAZEM_DLYA_NAUSHNIKOV']['NAME'].'--,--PROPERTY_'.$arP['TIP_PAMYATI_2']['ID'].'--#--'.$arP['TIP_PAMYATI_2']['NAME'].'--,--PROPERTY_'.$arP['WI_FI']['ID'].'--#--'.$arP['WI_FI']['NAME'].'--,--PROPERTY_'.$arP['PEREDNIY_TORMOZ']['ID'].'--#--'.$arP['PEREDNIY_TORMOZ']['NAME'].'--,--PROPERTY_'.$arP['SPUTNIKOVAYA_NAVIGATSIYA']['ID'].'--#--'.$arP['SPUTNIKOVAYA_NAVIGATSIYA']['NAME'].'--,--PROPERTY_'.$arP['CHASTOTA_PROTSESSORA']['ID'].'--#--'.$arP['CHASTOTA_PROTSESSORA']['NAME'].'--,--PROPERTY_'.$arP['BLUETOOTH']['ID'].'--#--'.$arP['BLUETOOTH']['NAME'].'--,--PROPERTY_'.$arP['ZADNIY_TORMOZ']['ID'].'--#--'.$arP['ZADNIY_TORMOZ']['NAME'].'--,--PROPERTY_'.$arP['PROTSESSOR']['ID'].'--#--'.$arP['PROTSESSOR']['NAME'].'--,--PROPERTY_'.$arP['YADRO']['ID'].'--#--'.$arP['YADRO']['NAME'].'--,--PROPERTY_'.$arP['VYKHOD_HDMI']['ID'].'--#--'.$arP['VYKHOD_HDMI']['NAME'].'--,--PROPERTY_'.$arP['INTEGRIROVANNOE_GRAFICHESKOE_YADRO']['ID'].'--#--'.$arP['INTEGRIROVANNOE_GRAFICHESKOE_YADRO']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_SKOROSTEY']['ID'].'--#--'.$arP['KOLICHESTVO_SKOROSTEY']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_YADER_PROTSESSORA']['ID'].'--#--'.$arP['KOLICHESTVO_YADER_PROTSESSORA']['NAME'].'--,--PROPERTY_'.$arP['SOCKET_1']['ID'].'--#--'.$arP['SOCKET_1']['NAME'].'--,--PROPERTY_'.$arP['VIDEOPROTSESSOR']['ID'].'--#--'.$arP['VIDEOPROTSESSOR']['NAME'].'--,--PROPERTY_'.$arP['VYKHOD_AUDIO_NAUSHNIKI']['ID'].'--#--'.$arP['VYKHOD_AUDIO_NAUSHNIKI']['NAME'].'--,--PROPERTY_'.$arP['MOSHCHNOST']['ID'].'--#--'.$arP['MOSHCHNOST']['NAME'].'--,--PROPERTY_'.$arP['OBEM_VSTROENNOY_PAMYATI']['ID'].'--#--'.$arP['OBEM_VSTROENNOY_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['RAZRESHENIE_VEB_KAMERY']['ID'].'--#--'.$arP['RAZRESHENIE_VEB_KAMERY']['NAME'].'--,--PROPERTY_'.$arP['FORM_FAKTOR']['ID'].'--#--'.$arP['FORM_FAKTOR']['NAME'].'--,--PROPERTY_'.$arP['SHAG_TSEPI']['ID'].'--#--'.$arP['SHAG_TSEPI']['NAME'].'--,--PROPERTY_'.$arP['DLINA_SHINY']['ID'].'--#--'.$arP['DLINA_SHINY']['NAME'].'--,--PROPERTY_'.$arP['OBEM_OPERATIVNOY_PAMYATI']['ID'].'--#--'.$arP['OBEM_OPERATIVNOY_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['PROIZVODITEL_TELEFONA']['ID'].'--#--'.$arP['PROIZVODITEL_TELEFONA']['NAME'].'--,--PROPERTY_'.$arP['TIP_PAMYATI_1']['ID'].'--#--'.$arP['TIP_PAMYATI_1']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_SLOTOV_PAMYATI']['ID'].'--#--'.$arP['KOLICHESTVO_SLOTOV_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['MODELI_TELEFONA']['ID'].'--#--'.$arP['MODELI_TELEFONA']['NAME'].'--,--PROPERTY_'.$arP['OBEM_DVIGATELYA']['ID'].'--#--'.$arP['OBEM_DVIGATELYA']['NAME'].'--,--PROPERTY_'.$arP['SLOT_DLYA_KART_PAMYATI']['ID'].'--#--'.$arP['SLOT_DLYA_KART_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['BIOS']['ID'].'--#--'.$arP['BIOS']['NAME'].'--,--PROPERTY_'.$arP['EMKOST_AKKUMULYATORA']['ID'].'--#--'.$arP['EMKOST_AKKUMULYATORA']['NAME'].'--,--PROPERTY_'.$arP['EMKOST_TOPLIVNOGO_BAKA']['ID'].'--#--'.$arP['EMKOST_TOPLIVNOGO_BAKA']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_1']['ID'].'--#--'.$arP['MATERIAL_1']['NAME'].'--,--PROPERTY_'.$arP['SATA']['ID'].'--#--'.$arP['SATA']['NAME'].'--,--PROPERTY_'.$arP['EMKOST_MASLYANOGO_BAKA']['ID'].'--#--'.$arP['EMKOST_MASLYANOGO_BAKA']['NAME'].'--,--PROPERTY_'.$arP['PROZRACHNYY']['ID'].'--#--'.$arP['PROZRACHNYY']['NAME'].'--,--PROPERTY_'.$arP['TIP_RAZEMA_DLYA_ZARYADKI']['ID'].'--#--'.$arP['TIP_RAZEMA_DLYA_ZARYADKI']['NAME'].'--,--PROPERTY_'.$arP['ETHERNET']['ID'].'--#--'.$arP['ETHERNET']['NAME'].'--,--PROPERTY_'.$arP['GROMKAYA_SVYAZ']['ID'].'--#--'.$arP['GROMKAYA_SVYAZ']['NAME'].'--,--PROPERTY_'.$arP['TIP_KAMERY']['ID'].'--#--'.$arP['TIP_KAMERY']['NAME'].'--,--PROPERTY_'.$arP['FUNKTSII_I_VOZMOZHNOSTI']['ID'].'--#--'.$arP['FUNKTSII_I_VOZMOZHNOSTI']['NAME'].'--,--PROPERTY_'.$arP['DIAFRAGMA_1']['ID'].'--#--'.$arP['DIAFRAGMA_1']['NAME'].'--,--PROPERTY_'.$arP['KOMPLEKT_POSTAVKI']['ID'].'--#--'.$arP['KOMPLEKT_POSTAVKI']['NAME'].'--,--PROPERTY_'.$arP['KONSTRUKTSIYA_AKKUMULYATOR']['ID'].'--#--'.$arP['KONSTRUKTSIYA_AKKUMULYATOR']['NAME'].'--,--PROPERTY_'.$arP['RAZEMY_NA_ZADNEY_PANELI']['ID'].'--#--'.$arP['RAZEMY_NA_ZADNEY_PANELI']['NAME'].'--,--PROPERTY_'.$arP['INTERFEYS_PODKLYUCHENIYA']['ID'].'--#--'.$arP['INTERFEYS_PODKLYUCHENIYA']['NAME'].'--,--PROPERTY_'.$arP['OBSHCHEE_CHISLO_PIKSELOV']['ID'].'--#--'.$arP['OBSHCHEE_CHISLO_PIKSELOV']['NAME'].'--,--PROPERTY_'.$arP['TIP']['ID'].'--#--'.$arP['TIP']['NAME'].'--,--PROPERTY_'.$arP['TIP_AKKUMULYATORA']['ID'].'--#--'.$arP['TIP_AKKUMULYATORA']['NAME'].'--,--PROPERTY_'.$arP['BYSTRAYA_ZARYADKA']['ID'].'--#--'.$arP['BYSTRAYA_ZARYADKA']['NAME'].'--,--PROPERTY_'.$arP['KONSTRUKTSIYA_1']['ID'].'--#--'.$arP['KONSTRUKTSIYA_1']['NAME'].'--,--PROPERTY_'.$arP['MAKSIMALNOE_RAZRESHENIE']['ID'].'--#--'.$arP['MAKSIMALNOE_RAZRESHENIE']['NAME'].'--,--PROPERTY_'.$arP['PREDNAZNACHENIE']['ID'].'--#--'.$arP['PREDNAZNACHENIE']['NAME'].'--,--PROPERTY_'.$arP['MAKS_VYKHODNOY_TOK']['ID'].'--#--'.$arP['MAKS_VYKHODNOY_TOK']['NAME'].'--,--PROPERTY_'.$arP['MODEL']['ID'].'--#--'.$arP['MODEL']['NAME'].'--,--PROPERTY_'.$arP['TIP_BESPROVODNOY_SVYAZI']['ID'].'--#--'.$arP['TIP_BESPROVODNOY_SVYAZI']['NAME'].'--,--PROPERTY_'.$arP['CHUVSTVITELNOST']['ID'].'--#--'.$arP['CHUVSTVITELNOST']['NAME'].'--,--PROPERTY_'.$arP['BALANS_BELOGO_1']['ID'].'--#--'.$arP['BALANS_BELOGO_1']['NAME'].'--,--PROPERTY_'.$arP['VID_ZASTEZHKI']['ID'].'--#--'.$arP['VID_ZASTEZHKI']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_KLAVISH']['ID'].'--#--'.$arP['KOLICHESTVO_KLAVISH']['NAME'].'--,--PROPERTY_'.$arP['RAZEM_PODKLYUCHENIYA']['ID'].'--#--'.$arP['RAZEM_PODKLYUCHENIYA']['NAME'].'--,--PROPERTY_'.$arP['VSPYSHKA']['ID'].'--#--'.$arP['VSPYSHKA']['NAME'].'--,--PROPERTY_'.$arP['DLINA_IZDELIYA_PO_SPINKE']['ID'].'--#--'.$arP['DLINA_IZDELIYA_PO_SPINKE']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_PROGRAMMIRUEMYKH_KLAVISH']['ID'].'--#--'.$arP['KOLICHESTVO_PROGRAMMIRUEMYKH_KLAVISH']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_RAZEMOV']['ID'].'--#--'.$arP['KOLICHESTVO_RAZEMOV']['NAME'].'--,--PROPERTY_'.$arP['DLINA_PROVODA']['ID'].'--#--'.$arP['DLINA_PROVODA']['NAME'].'--,--PROPERTY_'.$arP['ZAPIS_VIDEO']['ID'].'--#--'.$arP['ZAPIS_VIDEO']['NAME'].'--,--PROPERTY_'.$arP['KABEL_V_KOMPLEKTE']['ID'].'--#--'.$arP['KABEL_V_KOMPLEKTE']['NAME'].'--,--PROPERTY_'.$arP['POKROY']['ID'].'--#--'.$arP['POKROY']['NAME'].'--,--PROPERTY_'.$arP['INTERFEYS_PODKLYUCHENIYA_1']['ID'].'--#--'.$arP['INTERFEYS_PODKLYUCHENIYA_1']['NAME'].'--,--PROPERTY_'.$arP['POL']['ID'].'--#--'.$arP['POL']['NAME'].'--,--PROPERTY_'.$arP['TIP_NOSITELYA']['ID'].'--#--'.$arP['TIP_NOSITELYA']['NAME'].'--,--PROPERTY_'.$arP['USTANOVKA']['ID'].'--#--'.$arP['USTANOVKA']['NAME'].'--,--PROPERTY_'.$arP['MAKSIMALNOE_RAZRESHENIE_VIDEOSEMKI']['ID'].'--#--'.$arP['MAKSIMALNOE_RAZRESHENIE_VIDEOSEMKI']['NAME'].'--,--PROPERTY_'.$arP['PODSVETKA_KLAVISH']['ID'].'--#--'.$arP['PODSVETKA_KLAVISH']['NAME'].'--,--PROPERTY_'.$arP['POLNOTA_OBUVI_EUR']['ID'].'--#--'.$arP['POLNOTA_OBUVI_EUR']['NAME'].'--,--PROPERTY_'.$arP['TIP_ZAGRUZKI']['ID'].'--#--'.$arP['TIP_ZAGRUZKI']['NAME'].'--,--PROPERTY_'.$arP['ZOOM']['ID'].'--#--'.$arP['ZOOM']['NAME'].'--,--PROPERTY_'.$arP['MAKSIMALNAYA_ZAGRUZKA_BELYA']['ID'].'--#--'.$arP['MAKSIMALNAYA_ZAGRUZKA_BELYA']['NAME'].'--,--PROPERTY_'.$arP['SEZON']['ID'].'--#--'.$arP['SEZON']['NAME'].'--,--PROPERTY_'.$arP['TSIFROVOY_BLOK']['ID'].'--#--'.$arP['TSIFROVOY_BLOK']['NAME'].'--,--PROPERTY_'.$arP['DIAFRAGMA']['ID'].'--#--'.$arP['DIAFRAGMA']['NAME'].'--,--PROPERTY_'.$arP['DISPLEY']['ID'].'--#--'.$arP['DISPLEY']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_DOPOLNITELNYKH_KLAVISH']['ID'].'--#--'.$arP['KOLICHESTVO_DOPOLNITELNYKH_KLAVISH']['NAME'].'--,--PROPERTY_'.$arP['STRANA_PROIZVODITEL']['ID'].'--#--'.$arP['STRANA_PROIZVODITEL']['NAME'].'--,--PROPERTY_'.$arP['GABARITY_SHXGXV']['ID'].'--#--'.$arP['GABARITY_SHXGXV']['NAME'].'--,--PROPERTY_'.$arP['DIAMETR_FILTRA']['ID'].'--#--'.$arP['DIAMETR_FILTRA']['NAME'].'--,--PROPERTY_'.$arP['MAKSIMALNYY_RAZMER_EKRANA']['ID'].'--#--'.$arP['MAKSIMALNYY_RAZMER_EKRANA']['NAME'].'--,--PROPERTY_'.$arP['TIP_POSADKI']['ID'].'--#--'.$arP['TIP_POSADKI']['NAME'].'--,--PROPERTY_'.$arP['ZHK_EKRAN']['ID'].'--#--'.$arP['ZHK_EKRAN']['NAME'].'--,--PROPERTY_'.$arP['KLASS_ENERGOPOTREBLENIYA']['ID'].'--#--'.$arP['KLASS_ENERGOPOTREBLENIYA']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL']['ID'].'--#--'.$arP['MATERIAL']['NAME'].'--,--PROPERTY_'.$arP['UKHOD_ZA_VESHCHAMI']['ID'].'--#--'.$arP['UKHOD_ZA_VESHCHAMI']['NAME'].'--,--PROPERTY_'.$arP['VIDOISKATEL']['ID'].'--#--'.$arP['VIDOISKATEL']['NAME'].'--,--PROPERTY_'.$arP['RASKHOD_VODY_ZA_STIRKU']['ID'].'--#--'.$arP['RASKHOD_VODY_ZA_STIRKU']['NAME'].'--,--PROPERTY_'.$arP['TIP_SOEDINENIYA']['ID'].'--#--'.$arP['TIP_SOEDINENIYA']['NAME'].'--,--PROPERTY_'.$arP['FAKTURA_MATERIALA']['ID'].'--#--'.$arP['FAKTURA_MATERIALA']['NAME'].'--,--PROPERTY_'.$arP['BALANS_BELOGO']['ID'].'--#--'.$arP['BALANS_BELOGO']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_PROGRAMM']['ID'].'--#--'.$arP['KOLICHESTVO_PROGRAMM']['NAME'].'--,--PROPERTY_'.$arP['MIKROFON']['ID'].'--#--'.$arP['MIKROFON']['NAME'].'--,--PROPERTY_'.$arP['ZHESTKIY_DISK']['ID'].'--#--'.$arP['ZHESTKIY_DISK']['NAME'].'--,--PROPERTY_'.$arP['SISTEMA_AKTIVNOGO_SHUMOPODAVLENIYA']['ID'].'--#--'.$arP['SISTEMA_AKTIVNOGO_SHUMOPODAVLENIYA']['NAME'].'--,--PROPERTY_'.$arP['TERMOSTAT']['ID'].'--#--'.$arP['TERMOSTAT']['NAME'].'--,--PROPERTY_'.$arP['MINIMALNAYA_VOSPROIZVODIMAYA_CHASTOTA']['ID'].'--#--'.$arP['MINIMALNAYA_VOSPROIZVODIMAYA_CHASTOTA']['NAME'].'--,--PROPERTY_'.$arP['NAZNACHENIE']['ID'].'--#--'.$arP['NAZNACHENIE']['NAME'].'--,--PROPERTY_'.$arP['OPERATIVNAYA_PAMYAT']['ID'].'--#--'.$arP['OPERATIVNAYA_PAMYAT']['NAME'].'--,--PROPERTY_'.$arP['MAKSIMALNAYA_VOSPROIZVODIMAYA_CHASTOTA']['ID'].'--#--'.$arP['MAKSIMALNAYA_VOSPROIZVODIMAYA_CHASTOTA']['NAME'].'--,--PROPERTY_'.$arP['POKRYTIE']['ID'].'--#--'.$arP['POKRYTIE']['NAME'].'--,--PROPERTY_'.$arP['RAZEMY']['ID'].'--#--'.$arP['RAZEMY']['NAME'].'--,--PROPERTY_'.$arP['SHIRINA_SKASHIVANIYA']['ID'].'--#--'.$arP['SHIRINA_SKASHIVANIYA']['NAME'].'--,--PROPERTY_'.$arP['VYSOTA_SKASHIVANIYA']['ID'].'--#--'.$arP['VYSOTA_SKASHIVANIYA']['NAME'].'--,--PROPERTY_'.$arP['PODDERZHIVAEMYE_NOSITELI']['ID'].'--#--'.$arP['PODDERZHIVAEMYE_NOSITELI']['NAME'].'--,--PROPERTY_'.$arP['SPOSOB_MONTAZHA']['ID'].'--#--'.$arP['SPOSOB_MONTAZHA']['NAME'].'--,--PROPERTY_'.$arP['TIP_KREPLENIYA']['ID'].'--#--'.$arP['TIP_KREPLENIYA']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_2']['ID'].'--#--'.$arP['MATERIAL_2']['NAME'].'--,--PROPERTY_'.$arP['MOROZILNAYA_KAMERA']['ID'].'--#--'.$arP['MOROZILNAYA_KAMERA']['NAME'].'--,--PROPERTY_'.$arP['MOSHCHNOST_DVIGATELYA']['ID'].'--#--'.$arP['MOSHCHNOST_DVIGATELYA']['NAME'].'--,--PROPERTY_'.$arP['PODDERZHIVAEMYE_FORMATY']['ID'].'--#--'.$arP['PODDERZHIVAEMYE_FORMATY']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_POKRYTIYA']['ID'].'--#--'.$arP['MATERIAL_POKRYTIYA']['NAME'].'--,--PROPERTY_'.$arP['OBEM_VSTROENNOY_FLESH_PAMYATI']['ID'].'--#--'.$arP['OBEM_VSTROENNOY_FLESH_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['FORMA']['ID'].'--#--'.$arP['FORMA']['NAME'].'--,--PROPERTY_'.$arP['OBEM']['ID'].'--#--'.$arP['OBEM']['NAME'].'--,--PROPERTY_'.$arP['USTROYSTVO_DLYA_CHTENIYA_KART_PAMYATI']['ID'].'--#--'.$arP['USTROYSTVO_DLYA_CHTENIYA_KART_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['ENERGOPOTREBLENIE']['ID'].'--#--'.$arP['ENERGOPOTREBLENIE']['NAME'].'--,--PROPERTY_'.$arP['ISTOCHNIK_PITANIYA']['ID'].'--#--'.$arP['ISTOCHNIK_PITANIYA']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_KAMER']['ID'].'--#--'.$arP['KOLICHESTVO_KAMER']['NAME'].'--,--PROPERTY_'.$arP['UGLOVAYA_KONSTRUKTSIYA']['ID'].'--#--'.$arP['UGLOVAYA_KONSTRUKTSIYA']['NAME'].'--,--PROPERTY_'.$arP['VOZRASTNYE_OGRANICHENIYA']['ID'].'--#--'.$arP['VOZRASTNYE_OGRANICHENIYA']['NAME'].'--,--PROPERTY_'.$arP['KOLICHESTVO_DVEREY']['ID'].'--#--'.$arP['KOLICHESTVO_DVEREY']['NAME'].'--,--PROPERTY_'.$arP['MODIFIKATSIYA']['ID'].'--#--'.$arP['MODIFIKATSIYA']['NAME'].'--,--PROPERTY_'.$arP['GIDROMASSAZH']['ID'].'--#--'.$arP['GIDROMASSAZH']['NAME'].'--,--PROPERTY_'.$arP['IZ_CHEGO_SDELANO_SOSTAV']['ID'].'--#--'.$arP['IZ_CHEGO_SDELANO_SOSTAV']['NAME'].'--,--PROPERTY_'.$arP['OBSHCHIY_OBEM']['ID'].'--#--'.$arP['OBSHCHIY_OBEM']['NAME'].'--,--PROPERTY_'.$arP['OBEM_KHOLODILNOY_KAMERY']['ID'].'--#--'.$arP['OBEM_KHOLODILNOY_KAMERY']['NAME'].'--,--PROPERTY_'.$arP['SOVMESTIMOST']['ID'].'--#--'.$arP['SOVMESTIMOST']['NAME'].'--,--PROPERTY_'.$arP['FORMA_1']['ID'].'--#--'.$arP['FORMA_1']['NAME'].'--,--PROPERTY_'.$arP['INTERFEYS']['ID'].'--#--'.$arP['INTERFEYS']['NAME'].'--,--PROPERTY_'.$arP['MATERIAL_PODDONA']['ID'].'--#--'.$arP['MATERIAL_PODDONA']['NAME'].'--,--PROPERTY_'.$arP['KREPLENIE_MIKROFONA']['ID'].'--#--'.$arP['KREPLENIE_MIKROFONA']['NAME'].'--,--PROPERTY_'.$arP['LINEYKA_PROTSESSORA']['ID'].'--#--'.$arP['LINEYKA_PROTSESSORA']['NAME'].'--,--LINKED_PROP--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_LINKED_PROP').'--;--cedit1--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_cedit1').'--,--PROPERTY_'.$arP['SERVICES']['ID'].'--#--'.$arP['SERVICES']['NAME'].'--,--PROPERTY_'.$arP['ANALOG_PRODUCTS']['ID'].'--#--'.$arP['ANALOG_PRODUCTS']['NAME'].'--;--cedit2--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_cedit2').'--,--PROPERTY_'.$arP['MAX_PRICE']['ID'].'--#--'.$arP['MAX_PRICE']['NAME'].'--,--PROPERTY_'.$arP['MIN_PRICE']['ID'].'--#--'.$arP['MIN_PRICE']['NAME'].'--,--PROPERTY_'.$arP['BLOG_COMMENTS_CNT']['ID'].'--#--'.$arP['BLOG_COMMENTS_CNT']['NAME'].'--,--PROPERTY_'.$arP['BLOG_POST_ID']['ID'].'--#--'.$arP['BLOG_POST_ID']['NAME'].'--,--PROPERTY_'.$arP['vote_sum']['ID'].'--#--'.$arP['vote_sum']['NAME'].'--,--PROPERTY_'.$arP['vote_count']['ID'].'--#--'.$arP['vote_count']['NAME'].'--,--PROPERTY_'.$arP['rating']['ID'].'--#--'.$arP['rating']['NAME'].'--,--edit14--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_edit14').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_META_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION').'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME').'--,--SEO_ADDITIONAL--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SEO_ADDITIONAL').'--,--TAGS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_TAGS').'--;--edit2--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_edit2').'--,--SECTIONS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTIONS').'--;--edit8--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_edit8').'--,--OFFERS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_OFFERS').'--;--',
            ),
            true
        );

        CUserOptions::SetOption(
            'form',
            'form_section_'.$IBLOCK_CATALOG_ID,
            array (
                'tabs' => 'edit1--#--'.GetMessage('WIZ_VIEW_NAME_SECTION').'--,--ID--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_ID').'--,--DATE_CREATE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_DATE_CREATE').'--,--TIMESTAMP_X--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_TIMESTAMP_X').'--,--ACTIVE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_ACTIVE').'--,--IBLOCK_SECTION_ID--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IBLOCK_SECTION_ID').'--,--NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_NAME').'--,--CODE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_CODE').'--,--PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_PICTURE').'--,--DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_DETAIL_PICTURE').'--,--UF_PHOTO_DETAIL--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_UF_PHOTO_DETAIL').'--,--UF_DESCR_BOTTOM--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_UF_DESCR_BOTTOM').'--,--DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_DESCRIPTION').'--;--edit5--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_edit5').'--,--IPROPERTY_TEMPLATES_SECTION--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION').'--,--IPROPERTY_TEMPLATES_SECTION_META_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_META_TITLE').'--,--IPROPERTY_TEMPLATES_SECTION_META_KEYWORDS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_META_KEYWORDS').'--,--IPROPERTY_TEMPLATES_SECTION_META_DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_META_DESCRIPTION').'--,--IPROPERTY_TEMPLATES_SECTION_PAGE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_PAGE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_META_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION').'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE').'--,--IPROPERTY_TEMPLATES_SECTIONS_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTIONS_PICTURE').'--,--IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_SECTIONS_DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTIONS_DETAIL_PICTURE').'--,--IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_MANAGEMENT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_TEMPLATES_MANAGEMENT').'--,--IPROPERTY_CLEAR_VALUES--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_IPROPERTY_CLEAR_VALUES').'--;--edit2--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_edit2').'--,--SORT--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_SORT').'--;--user_fields_tab--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_user_fields_tab').'--,--USER_FIELDS_ADD--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_USER_FIELDS_ADD').'--,--UF_SHOW_ON_MAIN_PAGE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_UF_SHOW_ON_MAIN_PAGE').'--,--UF_DETAIL_TEMPLATE--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_UF_DETAIL_TEMPLATE').'--;--edit4--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_edit4').'--,--SECTION_PROPERTY--#--'.GetMessage('WIZ_VIEW_NAME_CATALOG_SECTION_SECTION_PROPERTY').'--;--',
            ),
            true
        );

        //analog//
        $el = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => 'fotoapparat_so_smennoy_optikoy_sony_alpha_ilce_7rm2_kit']])->fetch();
        if($el['ID']){
            $ids = [];
            $rs = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => ['zerkalnyy_fotoapparat_pentax_k_1_kit','kompaktnyy_fotoapparat_canon_powershot_sx420_is','kompaktnyy_fotoapparat_nikon_coolpix_p1000','fotoapparat_so_smennoy_optikoy_panasonic_lumix_dmc_gx8_kit','fotoapparat_so_smennoy_optikoy_sony_alpha_ilce_7rm2_kit']]]);
            while($e = $rs->fetch())
            {
                $ids[] = $e['ID'];
            }
            if($ids){
                CIBlockElement::SetPropertyValuesEx($el['ID'], false, array('ANALOG_PRODUCTS' => $ids));
            }
        }

        $el = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => 'smartfon_xiaomi_mi8_6_64gb']])->fetch();
        if($el['ID']){
            $ids = [];
            $rs = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => ['smartfon_apple_iphone_xs_64gb','smartfon_google_pixel_2_xl_128gb','smartfon_huawei_p20_lite','smartfon_oneplus_6_8_128gb','smartfon_oppo_find_x_128gb']]]);
            while($e = $rs->fetch())
            {
                $ids[] = $e['ID'];
            }
            if($ids){
                CIBlockElement::SetPropertyValuesEx($el['ID'], false, array('ANALOG_PRODUCTS' => $ids));
            }
        }

        $el = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => 'smartfon_apple_iphone_xs_64gb']])->fetch();
        if($el['ID']){
            $ids = [];
            $rs = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => ['smartfon_xiaomi_mi8_6_64gb','smartfon_google_pixel_2_xl_128gb','smartfon_huawei_p20_lite','smartfon_oneplus_6_8_128gb','smartfon_oppo_find_x_128gb']]]);
            while($e = $rs->fetch())
            {
                $ids[] = $e['ID'];
            }
            if($ids){
                CIBlockElement::SetPropertyValuesEx($el['ID'], false, array('ANALOG_PRODUCTS' => $ids));
            }
        }

        $el = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => 'smartfon_google_pixel_2_xl_128gb']])->fetch();
        if($el['ID']){
            $ids = [];
            $rs = \Bitrix\Iblock\ElementTable::getlist(['filter' => ['CODE' => ['smartfon_xiaomi_mi8_6_64gb','smartfon_apple_iphone_xs_64gb','smartfon_huawei_p20_lite','smartfon_oneplus_6_8_128gb','smartfon_oppo_find_x_128gb']]]);
            while($e = $rs->fetch())
            {
                $ids[] = $e['ID'];
            }
            if($ids){
                CIBlockElement::SetPropertyValuesEx($el['ID'], false, array('ANALOG_PRODUCTS' => $ids));
            }
        }



    }
    else
    {
        $arSites = array();
        $db_res = CIBlock::GetSite($IBLOCK_CATALOG_ID);
        while ($res = $db_res->Fetch())
            $arSites[] = $res["LID"];
        if (!in_array(WIZARD_SITE_ID, $arSites))
        {
            $arSites[] = WIZARD_SITE_ID;
            $iblock = new CIBlock;
            $iblock->Update($IBLOCK_CATALOG_ID, array("LID" => $arSites));
        }
    }

    if($IBLOCK_CATALOG_ID) {
        Option::Set($moduleName, 'CATALOG_IBLOCK_TYPE', $iblockType, WIZARD_SITE_ID);
        Option::Set($moduleName, 'CATALOG_IBLOCK_ID', $IBLOCK_CATALOG_ID, WIZARD_SITE_ID);
    }

    $obUserField = new CUserTypeEntity();
    $arUserField = array(
        "ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION',
        "FIELD_NAME" => 'UF_DESCR_BOTTOM',
        "USER_TYPE_ID" => 'html',
        "XML_ID" => 'UF_DESCR_BOTTOM',
        "SORT" => 100,
        "MULTIPLE" => "N",
        "MANDATORY" => 'N',
        "SHOW_FILTER" => "N",
        "SHOW_IN_LIST" => "Y",
        "EDIT_IN_LIST" => "Y",
        "IS_SEARCHABLE" => "N",
        "SETTINGS" => array(),
    );

    $userFieldID = $obUserField->Add($arUserField);
    if($userFieldID > 0)
        $obUserField->Update(
            $userFieldID,
            [
                'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'ERROR_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
                'HELP_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            ]
        );
}
?>