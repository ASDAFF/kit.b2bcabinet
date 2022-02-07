<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!$arResult['IPROPERTY_VALUES']['SECTION_META_TITLE'])
{
	global $headTitle;
	$headTitle = $arResult['SECTION']['PATH'][0]['NAME'];
}
if(!$arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'])
{
	global $h1Title;
	$h1Title = $arResult['SECTION']['PATH'][0]['NAME'];
}
?>