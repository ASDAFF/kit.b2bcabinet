<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global ${$arParams['FILTER_NAME']};
${$arParams['FILTER_NAME']}['PROPERTY_USER'] = $USER->GetID();

Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/main/utils.js');

$filter = [];
$filterOption = new Bitrix\Main\UI\Filter\Options('DOCUMENTS_LIST');
$filterData = $filterOption->getFilter([]);

foreach ($filterData as $key => $value)
{
	if(in_array($key, ['ID','FIND', 'NAME','DATE_CREATE_from','DATE_CREATE_to','DATE_UPDATE_from','DATE_UPDATE_to']))
	{
		switch ($key)
		{
			case 'ID':
				{
					${$arParams['FILTER_NAME']}['ID'] = $value;
					break;
				}
			case 'NAME':
				{
					${$arParams['FILTER_NAME']}['$NAME'] = $value;
					break;
				}
			case 'DATE_CREATE_from':
				{
					${$arParams['FILTER_NAME']}['>=DATE_CREATE'] = $value;
					break;
				}
			case 'DATE_CREATE_to':
				{
					${$arParams['FILTER_NAME']}['<=DATE_CREATE'] = $value;
					break;
				}
			case 'DATE_UPDATE_from':
				{
					${$arParams['FILTER_NAME']}['>=TIMESTAMP_X'] = $value;
					break;
				}
			case 'DATE_UPDATE_to':
				{
					${$arParams['FILTER_NAME']}['<=TIMESTAMP_X'] = $value;
					break;
				}
			default:
				{
					${$arParams['FILTER_NAME']}['%NAME'] = $value;
				}
		}
	}
}
$by = isset($_GET['by']) ?  $_GET['by'] : $arParams["SORT_BY1"];
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : $arParams["SORT_ORDER1"];

if($by == 'DATE_UPDATE')
{
	$by = 'TIMESTAMP_X';
}
$idSection = $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
		"SORT_BY1"	=>	$by,
		"SORT_ORDER1"	=>	$order,
		"SORT_BY2"	=>	$arParams["SORT_BY2"],
		"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
		"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
		"SET_TITLE"	=>	$arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
		"DISPLAY_NAME"	=>	"Y",
		"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
		"PARENT_SECTION" => $arResult["VARIABLES"]["SECTION_ID"],
		"PARENT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	),
	$component
);
global $headTitle;
global $h1Title;
if($headTitle)
{
	$APPLICATION->SetTitle($headTitle);
}
if($h1Title)
{
	$APPLICATION->SetTitle($h1Title,false);
}
?>
