<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Config\Option;

if (!CModule::IncludeModule("iblock"))
    return;

$moduleId = 'kit.b2bcabinet';
$iblockType = "kit_b2bcabinet_type_document";
$arrIblockCode = [
    'documents_contracts',
    'documents_acts',
    'documents_others',
];

foreach($arrIblockCode as $iblockCode) {

    $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/b2bcabinet_".$iblockCode.".xml";
    $iblockXMLID = $iblockCode;
    $iblockAltXMLID = $iblockCode . '_' . WIZARD_SITE_ID;
    $iblockAltId = $iblockCode . '_' . WIZARD_SITE_ID;

    $rsIBlock = CIBlock::GetList([], ["XML_ID" => $iblockXMLID, "TYPE" => $iblockType]);
    $iblockID = false;
    if($arIBlock = $rsIBlock->Fetch()) {
        $iblockID = $arIBlock["ID"];
        if(WIZARD_INSTALL_DEMO_DATA) {
            $oldSites2 = [];
            $rs = CIblock::GetSite($arIBlock["ID"]);
            while($site = $rs->Fetch()) {
                $oldSites2[] = $site['SITE_ID'];
            }
            CIBlock::Delete($arIBlock["ID"]);
            $iblockID = false;
        }
    }

    if($iblockID == false) {
        $permissions = [
            "1" => "X",
            "2" => "R"
        ];
        $dbGroup = CGroup::GetList($by = "", $order = "", ["STRING_ID" => "content_editor"]);
        if($arGroup = $dbGroup->Fetch()) {
            $permissions[$arGroup["ID"]] = 'W';
        };
        $sites = [WIZARD_SITE_ID];

        if($oldSites2 && is_array($oldSites2)) {
            $sites = array_merge($sites, $oldSites2);
        }
        $iblockID = WizardServices::ImportIBlockFromXML(
            $iblockXMLFile,
            $iblockXMLID,
            $iblockType,
            $sites,
            $permissions
        );

        if($iblockID < 1) {
            return;
        }

        //IBlock fields
        $iblock = new CIBlock;
        $arFields = [
            "ACTIVE" => "Y",
            "FIELDS" => [
                'IBLOCK_SECTION'           => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'ACTIVE'                   => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => 'Y',
                ],
                'ACTIVE_FROM'              => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '=today',
                ],
                'ACTIVE_TO'                => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'SORT'                     => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'NAME'                     => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => '',
                ],
                'PREVIEW_PICTURE'          => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'FROM_DETAIL'        => 'N',
                        'SCALE'              => 'N',
                        'WIDTH'              => '',
                        'HEIGHT'             => '',
                        'IGNORE_ERRORS'      => 'N',
                        'METHOD'             => 'resample',
                        'COMPRESSION'        => 95,
                        'DELETE_WITH_DETAIL' => 'N',
                        'UPDATE_WITH_DETAIL' => 'N',
                    ],
                ],
                'PREVIEW_TEXT_TYPE'        => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ],
                'PREVIEW_TEXT'             => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'DETAIL_PICTURE'           => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'SCALE'         => 'N',
                        'WIDTH'         => '',
                        'HEIGHT'        => '',
                        'IGNORE_ERRORS' => 'N',
                        'METHOD'        => 'resample',
                        'COMPRESSION'   => 95,
                    ],
                ],
                'DETAIL_TEXT_TYPE'         => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ],
                'DETAIL_TEXT'              => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'XML_ID'                   => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'CODE'                     => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE'          => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_LEN'       => 100,
                        'TRANS_CASE'      => 'L',
                        'TRANS_SPACE'     => '_',
                        'TRANS_OTHER'     => '_',
                        'TRANS_EAT'       => 'Y',
                        'USE_GOOGLE'      => 'Y',
                    ],
                ],
                'TAGS'                     => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'SECTION_NAME'             => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => '',
                ],
                'SECTION_PICTURE'          => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'FROM_DETAIL'        => 'N',
                        'SCALE'              => 'N',
                        'WIDTH'              => '',
                        'HEIGHT'             => '',
                        'IGNORE_ERRORS'      => 'N',
                        'METHOD'             => 'resample',
                        'COMPRESSION'        => 95,
                        'DELETE_WITH_DETAIL' => 'N',
                        'UPDATE_WITH_DETAIL' => 'N',
                    ],
                ],
                'SECTION_DESCRIPTION_TYPE' => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ],
                'SECTION_DESCRIPTION'      => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'SECTION_DETAIL_PICTURE'   => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'SCALE'         => 'N',
                        'WIDTH'         => '',
                        'HEIGHT'        => '',
                        'IGNORE_ERRORS' => 'N',
                        'METHOD'        => 'resample',
                        'COMPRESSION'   => 95,
                    ],
                ],
                'SECTION_XML_ID'           => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'SECTION_CODE'             => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'UNIQUE'          => 'N',
                        'TRANSLITERATION' => 'N',
                        'TRANS_LEN'       => 100,
                        'TRANS_CASE'      => 'L',
                        'TRANS_SPACE'     => '_',
                        'TRANS_OTHER'     => '_',
                        'TRANS_EAT'       => 'Y',
                        'USE_GOOGLE'      => 'N',
                    ],
                ],
            ],
            "CODE"   => $iblockAltId,
            "XML_ID" => $iblockAltXMLID,
            //"NAME" => "[".WIZARD_SITE_ID."] ".$iblock->GetArrayByID($iblockID, "NAME")
        ];

        $iblock->Update($iblockID, $arFields);
    } else {
        $arSites = [];
        $db_res = CIBlock::GetSite($iblockID);
        while($res = $db_res->Fetch()) {
            $arSites[] = $res["LID"];
        }
        if(!in_array(WIZARD_SITE_ID, $arSites)) {
            $arSites[] = WIZARD_SITE_ID;
            $iblock = new CIBlock;
            $iblock->Update($iblockID, ["LID" => $arSites]);
        }
    }
    $dbSite = CSite::GetByID(WIZARD_SITE_ID);
    if($arSite = $dbSite->Fetch()) {
        $lang = $arSite["LANGUAGE_ID"];
    }
    if(strlen($lang) <= 0) {
        $lang = "ru";
    }

    $arP = \Bitrix\Iblock\PropertyTable::getList(
        ['filter' => ['IBLOCK_ID' => $iblockID], 'select' => ['ID', 'CODE', 'NAME']]
    )->fetchAll();

    $arP = array_column($arP, null, 'CODE');

    CUserOptions::SetOption(
        'form',
        'form_element_'.$iblockID,
        [
            'tabs' => 'edit1--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_edit1').'--,--ID--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_ID'
                ).'--,--DATE_CREATE--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_DATE_CREATE').'--,--TIMESTAMP_X--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_TIMESTAMP_X').'--,--ACTIVE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_ACTIVE'
                ).'--,--ACTIVE_FROM--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_ACTIVE_FROM').'--,--ACTIVE_TO--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_ACTIVE_TO').'--,--NAME--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_NAME'
                ).'--,--CODE--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_CODE').'--,--SORT--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_SORT'
                ).'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IBLOCK_ELEMENT_PROP_VALUE'
                ).'--,--PROPERTY_'.$arP['REGIONS']['ID'].'--#--'.$arP['REGIONS']['NAME'].'--;--edit5--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_edit5'
                ).'--,--PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_PREVIEW_PICTURE')
                .'--,--PREVIEW_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_PREVIEW_TEXT').'--;--edit6--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_edit6').'--,--DETAIL_PICTURE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_DETAIL_PICTURE'
                ).'--,--DETAIL_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_DETAIL_TEXT').'--;--edit14--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_edit14').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_META_TITLE')
                .'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE'
                ).'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME'
                ).'--,--SEO_ADDITIONAL--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_SEO_ADDITIONAL').'--,--TAGS--#--'
                .GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_TAGS').'--;--edit2--#--'.GetMessage(
                    'WIZ_VIEW_NAME_DOCUMENT_EL_edit2'
                ).'--,--SECTIONS--#--'.GetMessage('WIZ_VIEW_NAME_DOCUMENT_EL_SECTIONS').'--;--',
        ],
        true
    );

    $settingIblocks = unserialize(Option::get($moduleId, 'DOCUMENT_IBLOCKS_ID', 'a:0:{}', WIZARD_SITE_ID));
    if(empty($settingIblocks))
        $settingIblocks = [$iblockID];
    elseif(!in_array($settingIblocks, $iblockID))
        $settingIblocks[] = $iblockID;
    Option::set($moduleId, 'DOCUMENT_IBLOCKS_ID', serialize($settingIblocks), WIZARD_SITE_ID);
}
Option::set($moduleId, 'DOCUMENT_IBLOCKS_TYPE', $iblockType, WIZARD_SITE_ID);
?>