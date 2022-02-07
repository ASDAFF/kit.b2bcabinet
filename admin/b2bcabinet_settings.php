<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\{PersonTypeTable, OrderPropsTable};
use Bitrix\Main\GroupTable;
use Bitrix\Main\Config\Option;
use Kit\B2bCabinet\Helper\{Request, Config, Document};

require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
global $APPLICATION;

Loc::loadMessages( __FILE__ );

if( $APPLICATION->GetGroupRight( "main" ) < "R" ) {
    $APPLICATION->AuthForm( Loc::getMessage( "ACCESS_DENIED" ) );
}

$request = Request::getInstance();
$siteID = htmlspecialcharsbx($request->get('site'));

if(empty($siteID)) {
    Config::checkUriSite();
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . KitB2bCabinet::MODULE_ID . '/classes/CModuleOptions.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . KitB2bCabinet::MODULE_ID . "/include.php");

$groups = [];
$access = [
    'REFERENCE_ID' => ['M', 'S'],
    'REFERENCE' => [
        Loc::getMessage( KitB2bCabinet::MODULE_ID . '_ACCESS_M' ),
        Loc::getMessage( KitB2bCabinet::MODULE_ID . '_ACCESS_S' )
    ]
];

$rs = GroupTable::getList();

while($group = $rs->fetch()) {
    $groups['REFERENCE_ID'][] = $group['ID'];
    $groups['REFERENCE'][] = '['.$group['ID'].'] '.$group['NAME'];
}



/**
 * Documents
 */
$arCurrentValues[Document::IBLOCKS_TYPE] = !empty($request->get(Document::IBLOCKS_TYPE)) ?
    htmlspecialcharsbx($request->get(Document::IBLOCKS_TYPE)) :
    Config::get(Document::IBLOCKS_TYPE, $siteID);

$arCurrentValues[Document::IBLOCKS_ID] = !empty($request->get(Document::IBLOCKS_ID)) ?
    intval($request->get(Document::IBLOCKS_ID)) :
    Config::get(Document::IBLOCKS_ID, $siteID);


// Infoblock types
$arIBlockType = Config::getIblockTypes();
if(!empty($arIBlockType))
{
    $arIBlockTypeSel["REFERENCE_ID"][] = "";
    $arIBlockTypeSel["REFERENCE"][] = "";
    foreach ($arIBlockType as $code => $val)
    {
        $arIBlockTypeSel["REFERENCE_ID"][] = $code;
        $arIBlockTypeSel["REFERENCE"][] = $val;
    }
}

// Infoblocks
if(!empty($arCurrentValues[Document::IBLOCKS_TYPE])) {
    $rsIBlock = CIBlock::GetList([
        "sort" => "asc"
    ], [
        "=TYPE" => $arCurrentValues[Document::IBLOCKS_TYPE],
        "ACTIVE" => "Y"
    ]);
    while ($arr = $rsIBlock->Fetch())
    {
        if(!empty($arr)) {
            $arDocumentIBlockSel["REFERENCE_ID"][] = $arr["ID"];
            $arDocumentIBlockSel["REFERENCE"][] = "[" . $arr["ID"] . "] " . $arr["NAME"];
        }
    }
} else {
    $arDocumentIBlockSel["REFERENCE_ID"][] = '';
    $arDocumentIBlockSel["REFERENCE"][] = '';
}




$orderFields = [];
$orderFieldsIds = [];
$rs = OrderPropsTable::getList([
    'filter' => [
        'ACTIVE' => 'Y',
    ],
    'select' => [
        'ID',
        'CODE',
        'NAME'
    ]
]);
while ($property = $rs->fetch())
{
    $orderFields['REFERENCE_ID'][$property['CODE']] = $property['CODE'];
    $orderFields['REFERENCE'][$property['CODE']] = "[" . $property['CODE'] . "] " . $property['NAME'];

    $orderFieldsIds['REFERENCE_ID'][$property['ID']] = $property['ID'];
    $orderFieldsIds['REFERENCE'][$property['ID']] = "[" . $property['ID'] . "][" . $property['CODE'] . "] " . $property['NAME'];
}

$personalTypes = array();
$rs = PersonTypeTable::getList(
    array(
        'filter' => array(
            'ACTIVE' => 'Y',
            array(
                'LOGIC' => 'OR',
                array('LID' => $siteID),
                array('PERSON_TYPE_SITE.SITE_ID' => $siteID),
            ),
        ),
        'select' => array(
            'ID',
            'NAME'
        )
    )
);

while($personalType = $rs->fetch())
{
    if (!in_array($personalType['ID'], $personalTypes['REFERENCE_ID'])) {
        $personalTypes['REFERENCE_ID'][] = $personalType['ID'];
        $personalTypes['REFERENCE'][] = '[' . $personalType['ID'] . '] ' . $personalType['NAME'];
    }
}

$orderProps = ['REFERENCE_ID' => [],'REFERENCE' => []];

$rs = OrderPropsTable::getList();
while($prop = $rs->fetch())
{
    $orderProps['REFERENCE_ID'][] = $prop['ID'];
    $orderProps['REFERENCE'][] = '['.$prop['ID'].']'.'['.$prop['CODE'].'] '.$prop['NAME'];
}

// Tabs
$arTabs = array(
    // Main
    array(
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_edit1' ),
        'ICON' => '',
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_edit1' ),
        'SORT' => '10'
    ),
    // Documents
    array(
        'DIV' => 'edit2',
        'TAB' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_edit2' ),
        'ICON' => '',
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_edit2' ),
        'SORT' => '10'
    ),
);

// Groups
$arGroups = array(
    // Main
    'OPTION_5' => array(
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_OPTION_5' ),
        'TAB' => 1
    ),
    'OPTION_15' => array(
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_OPTION_15' ),
        'TAB' => 1
    ),
    'OPTION_PAGE_ADDRESS' => array(
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_OPTION_PAGE_ADDRESS' ),
        'TAB' => 1
    ),

    // Documents
    'OPTION_20' => array(
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_OPTION_20' ),
        'TAB' => 2
    ),

);

$arOptions = array(
    'LOGO' => array(
        'GROUP' => 'OPTION_5',
        'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_LOGO'),
        'TYPE' => 'FILE',
        'REFRESH' => 'N',
        'SORT' => '10'
    ),
    'OPT_BLANK_GROUPS' => array(
        'GROUP' => 'OPTION_5',
        'TITLE' => GetMessage( KitB2bCabinet::MODULE_ID.'_OPT_BLANK_GROUPS' ),
        'TYPE' => 'MSELECT',
        'SORT' => '22',
        'VALUES' => $groups
    ),
    'OPT_ACCESS_GROUPS' => array(
        'GROUP' => 'OPTION_5',
        'TITLE' => GetMessage( KitB2bCabinet::MODULE_ID.'_OPT_ACCESS_GROUPS' ),
        'TYPE' => 'SELECT',
        'SORT' => '23',
        'VALUES' => $access
    ),
    'BUYER_PERSONAL_TYPE' => array(
        'GROUP' => 'OPTION_5',
        'TITLE' => Loc::getMessage( KitB2bCabinet::MODULE_ID . '_BUYER_PERSONAL_TYPE' ),
        'TYPE' => 'MSELECT',
        'REFRESH' => 'N',
        'SORT' => '30',
        'VALUES' => $personalTypes
    ),

    'PROFILE_ORG_INN' => array(
		'GROUP' => 'OPTION_15',
		'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_PROFILE_ORG_INN'),
		'TYPE' => 'MSELECT',
		'SORT' => '30',
		'VALUES' => $orderFieldsIds
	),
	'PROFILE_ORG_NAME' => array(
		'GROUP' => 'OPTION_15',
		'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_PROFILE_ORG_NAME'),
		'TYPE' => 'MSELECT',
		'SORT' => '30',
		'VALUES' => $orderFieldsIds
	),
    'ADDRESS_COMPANY' => array(
        'GROUP' => 'OPTION_PAGE_ADDRESS',
        'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_ADDRESS_COMPANY'),
        'TYPE' => 'STRING',
        'SORT' => '40',
        'SIZE' => '50',
    ),
    'ADDRESS_ORDER' => array(
        'GROUP' => 'OPTION_PAGE_ADDRESS',
        'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_ADDRESS_ORDER'),
        'TYPE' => 'STRING',
        'SORT' => '50',
        'SIZE' => '50',
    ),


    // Documents
    'DOCUMENT_IBLOCKS_TYPE' => array(
        'GROUP' => 'OPTION_20',
        'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_'.Document::IBLOCKS_TYPE),
        'TYPE' => 'SELECT',
        'REFRESH' => 'Y',
        'SORT' => '10',
        'VALUES' => $arIBlockTypeSel
    ),
);

if(Config::getMethodInstall($siteID) == 'AS_TEMPLATE') {
    $arOptions = array_merge(
        $arOptions,
        [
            'PATH' => [
                'GROUP' => 'OPTION_5',
                'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID.'_PATH'),
                'TYPE'  => 'STRING',
                'SORT'  => '5',
            ],
        ]
    );
}

// Documents
if($arCurrentValues[Document::IBLOCKS_TYPE]) {
    $arOptions = array_merge($arOptions, [
        'DOCUMENT_IBLOCKS_ID' => array(
            'GROUP' => 'OPTION_20',
            'TITLE' => Loc::getMessage(KitB2bCabinet::MODULE_ID . '_'.Document::IBLOCKS_ID),
            'TYPE' => 'MSELECT',
            'SORT' => '20',
            'VALUES' => $arDocumentIBlockSel
        ),
    ]);
}

$RIGHT = $APPLICATION->GetGroupRight( KitB2bCabinet::MODULE_ID );

if( $RIGHT != "D" ) {

    $showRightsTab = false;
    $opt = new CModuleOptions( KitB2bCabinet::MODULE_ID, $arTabs, $arGroups, $arOptions, $showRightsTab );
    
    $opt->ShowHTML();
}

$APPLICATION->SetTitle( Loc::getMessage( KitB2bCabinet::MODULE_ID . '_TITLE_SETTINGS' ) );

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");