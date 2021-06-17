<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<?if( false && $arResult["bDescPageNumbering"] === true):?>

<ul class="pagination-flat justify-content-center twbs-prev-next pagination">

    <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
        <?if($arResult["bSavePage"]):?>
            <li class="page-item first"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>" class="page-link">&#8676;</a></li>
            <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="page-link">&#8592;</a></li>
        <?else:?>
            <li class="page-item first"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-link">&#8676;</a></li>
            <?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
                <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-link">&#8592;</a></li>
            <?else:?>
                <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="page-link">&#8592;</a></li>
            <?endif?>
        <?endif?>
    <?else:?>
        <li class="page-item first disabled"><a href="#" class="page-link">&#8676;</a></li><li class="page-item prev disabled"><a href="#" class="page-link">&#8592;</a></li>
    <?endif?>

    <?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
        <?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

        <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <b><?=$NavRecordGroupPrint?></b>
        <?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
            <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
        <?else:?>
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
        <?endif?>

        <?$arResult["nStartPage"]--?>
    <?endwhile?>

    <?if ($arResult["NavPageNomer"] > 1):?>
        <li class="page-item next"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="page-link">&#8594;</a></li>
        <li class="page-item last"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" class="page-link">&#8677;</a></li>
    <?else:?>
        <li class="page-item next disabled"><a href="#" class="page-link"><?=Loc::getMessage('')?></a></li><li class="page-item last disabled"><a href="#" class="page-link">&#8677;</a></li>
    <?endif?>

    <?else:?>

</ul>
<ul class="pagination-flat justify-content-center twbs-prev-next pagination">

    <?if ($arResult["NavPageNomer"] > 1):?>

        <?if($arResult["bSavePage"]):?>
            <li class="page-item first"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" class="page-link">&#8676;</a></li>
            <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="page-link">&#8592;</a></li>
        <?else:?>
            <li class="page-item first"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-link">&#8676;</a></li>
            <?if ($arResult["NavPageNomer"] > 2):?>
                <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="page-link">&#8592;</a></li>
            <?else:?>
                <li class="page-item prev"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-link">&#8592;</a></li>
            <?endif?>
        <?endif?>

    <?else:?>
        <li class="page-item first disabled"><a href="#" class="page-link">&#8676;</a></li><li class="page-item prev disabled"><a href="#" class="page-link">&#8592;</a></li>
    <?endif?>

    <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

        <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <li class="page-item active"><a href="#" class="page-link"><?=$arResult["nStartPage"]?></a></li>
        <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="page-link"><?=$arResult["nStartPage"]?></a></li>
        <?else:?>
            <li class="page-item"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="page-link"><?=$arResult["nStartPage"]?></a></li>
        <?endif?>
        <?$arResult["nStartPage"]++?>
    <?endwhile?>

    <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
        <li class="page-item next"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="page-link">&#8594;</a></li>
        <li class="page-item last"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>" class="page-link">&#8677;</a></li>
    <?else:?>
        <li class="page-item next disabled"><a href="#" class="page-link">&#8594;</a></li><li class="page-item last disabled"><a href="#" class="page-link">&#8677;</a></li>
    <?endif?>

    <?endif?>

    <?if ( false && $arResult["bShowAll"]):?>
        <noindex>
            <?if ($arResult["NavShowAll"]):?>
                |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=Loc::getMessage("nav_paged")?></a>
            <?else:?>
                |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=Loc::getMessage("nav_all")?></a>
            <?endif?>
        </noindex>
    <?endif?>

</ul>