<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$ClientID = 'navigation_'.$arResult['NavNum'];

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<div class="wrap_brand_pagination">
    <div class="brand_pagination">

				<ul>
<?
if($arResult["bDescPageNumbering"] === true)
{
	// to show always first and last pages
	$arResult["nStartPage"] = $arResult["NavPageCount"];
	$arResult["nEndPage"] = 1;

	$sPrevHref = '';
	if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
	{
		$bPrevDisabled = false;
		if ($arResult["bSavePage"])
		{
			$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
		}
		else
		{
			if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1))
			{
				$sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
			}
			else
			{
				$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
			}
		}
	}
	else
	{
		$bPrevDisabled = true;
	}
	
	$sNextHref = '';
	if ($arResult["NavPageNomer"] > 1)
	{
		$bNextDisabled = false;
		$sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
	}
	else
	{
		$bNextDisabled = true;
	}
	?>
	<?if (!$bPrevDisabled):?>
				<li><a href="<?=$sPrevHref;?>" id="<?=$ClientID?>_previous_page"><span class="hidden-xs"><?=GetMessage("nav_prev")?></span><span class="visible-xs">&laquo;</span> </a></li>
	<?endif?>

	<?
	$bFirst = true;
	$bPoints = false;
	do
	{
		$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
		if ($arResult["nStartPage"] <= 2 || $arResult["NavPageCount"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2)
		{
			if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
				<li class="bx_active"><span class="nav-current-page"><?=$NavRecordGroupPrint?></span></li>
			<?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
				<li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a></li>
			<?else:?>
				<li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a></li>
			<?endif;
			$bFirst = false;
			$bPoints = true;
		}
		else
		{
			if ($bPoints)
			{
		?><li>...</li><?
				$bPoints = false;
			}
		}
		$arResult["nStartPage"]--;
	} while($arResult["nStartPage"] >= $arResult["nEndPage"]);?>
	<?if (!$bNextDisabled):?>
				<li><a href="<?=$sNextHref;?>" id="<?=$ClientID?>_next_page">  <span class="hidden-xs"><?=GetMessage("nav_next")?></span><span class="visible-xs">&raquo;</span>   </a></li>
	<?endif;
}
else
{
	// to show always first and last pages
	$arResult["nStartPage"] = 1;
	$arResult["nEndPage"] = $arResult["NavPageCount"];

	$sPrevHref = '';
	if ($arResult["NavPageNomer"] > 1)
	{
		$bPrevDisabled = false;
		
		if ($arResult["bSavePage"] || $arResult["NavPageNomer"] > 2)
		{
			$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
		}
		else
		{
			$sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
		}
	}
	else
	{
		$bPrevDisabled = true;
	}

	$sNextHref = '';
	if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
	{
		$bNextDisabled = false;
		$sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
	}
	else
	{
		$bNextDisabled = true;
	}
	?>
	<?if (!$bPrevDisabled):?>
				<li><a href="<?=$sPrevHref;?>" id="<?=$ClientID?>_previous_page" class="navig_previous"> <span class="hidden-xs"><?=GetMessage("nav_prev")?></span><span class="visible-xs">&laquo;</span> </a></li>
	<?endif?>
	<?
	$bFirst = true;
	$bPoints = false;
	do
	{
		if ($arResult["nStartPage"] <= 2 || $arResult["nEndPage"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2)
		{
			if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
				<li class="bx_active"><span class="nav-current-page"><?=$arResult["nStartPage"]?></span></li>
			<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
				<li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
			<?else:?>
				<li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
			<?endif;
			$bFirst = false;
			$bPoints = true;
		}
		else
		{
			if ($bPoints)
			{
		?><li>...</li><?
				$bPoints = false;
			}
		}
		$arResult["nStartPage"]++;
	} while($arResult["nStartPage"] <= $arResult["nEndPage"]);
	?>
	<?if (!$bNextDisabled):?>
				<li><a href="<?=$sNextHref;?>" id="<?=$ClientID?>_next_page"  class="navig_next">  <span class="hidden-xs"><?=GetMessage("nav_next")?></span><span class="visible-xs">&raquo;</span> </a></li>
	<?endif;
}
?>
				<?if ($arResult["bShowAll"]):

				endif;?>
				</ul>
	</div>
</div>  
<!--//bx_pagination_bottom-->

<?CJSCore::Init();?>
<script type="text/javascript">
	BX.bind(document, "keydown", function (event) {

		event = event || window.event;
		if (!event.ctrlKey)
			return;

		var target = event.target || event.srcElement;
		if (target && target.nodeName && (target.nodeName.toUpperCase() == "INPUT" || target.nodeName.toUpperCase() == "TEXTAREA"))
			return;

		var key = (event.keyCode ? event.keyCode : (event.which ? event.which : null));
		if (!key)
			return;

		var link = null;
		if (key == 39)
			link = BX('<?=$ClientID?>_next_page');
		else if (key == 37)
			link = BX('<?=$ClientID?>_previous_page');

		if (link && link.href)
			document.location = link.href;
	});
</script>