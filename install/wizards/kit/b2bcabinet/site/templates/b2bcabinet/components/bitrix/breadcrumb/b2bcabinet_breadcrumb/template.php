<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

if(empty($arResult))
	return "";

$strReturn = '<div class="breadcrumb">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
        $strReturn .= '<a href="'.$arResult[$index]["LINK"].'" class="breadcrumb-item">'.((!$index) ? '<i class="icon-home2 mr-2"></i>' : '').$title.'</a>';
	}
	else
	{
        $strReturn .= '<span class="breadcrumb-item active">'.$title.'</span>';
	}
}

$strReturn .= '<div style="clear:both"></div></div>';

return $strReturn;
?>