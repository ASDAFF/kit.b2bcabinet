<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
    $arParams['HTML'] = html_entity_decode(html_entity_decode($arParams['HTML']));
    $arParams['HTML'] = preg_replace('/class\=\'typeselect\'/', 'class="select"', $arParams['HTML']);

    echo $arParams['HTML']
?>