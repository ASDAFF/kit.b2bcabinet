<?
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
__IncludeLang($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/sale.order.ajax/lang/'.LANGUAGE_ID.'/map.php');

CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');
$location = "";
$arStore = array();
$arStoreId = array();

if ($_REQUEST["delivery"])
{
	$deliveryId = IntVal($_REQUEST["delivery"]);
	$selectStoreId = IntVal($_REQUEST["selectStore"]);

	$dbDelivery = CSaleDelivery::GetList(
		array("SORT"=>"ASC"),
		array("ID" => $deliveryId),
		false,
		false,
		array("ID", "STORE")
	);
	$arDelivery = $dbDelivery->Fetch();

	if (count($arDelivery) > 0 && strlen($arDelivery["STORE"]) > 0)
	{
		$arStoreInfo = unserialize($arDelivery["STORE"]);
		foreach ($arStoreInfo as $val)
			$arStoreId[$val] = $val;
	}

	$arStoreLocation = array("yandex_scale" => 11, "PLACEMARKS" => array());

	$siteId = substr($_REQUEST["siteId"], 0, 2);

	$dbList = CCatalogStore::GetList(
		array("SORT" => "DESC", "ID" => "ASC"),
		array("ACTIVE" => "Y", "ID" => $arStoreId, "ISSUING_CENTER" => "Y", "+SITE_ID" => $siteId),
		false,
		false,
		array("ID", "SORT", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S", "SITE_ID", "ISSUING_CENTER", "EMAIL")
	);
	while ($arStoreTmp = $dbList->Fetch())
	{
		$arStore[$arStoreTmp["ID"]] = $arStoreTmp;

		if (intval($arStoreTmp["IMAGE_ID"]) > 0)
		{
			$arImage = CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);
			$imgValue = CFile::ShowImage($arImage, 115, 115, "border=0", "", false);
			$arStore[$arStoreTmp["ID"]]["IMAGE"] = $imgValue;
			$arStore[$arStoreTmp["ID"]]["IMAGE_URL"] = $arImage["SRC"];
		}

		if (floatval($arStoreLocation["yandex_lat"]) <= 0)
			$arStoreLocation["yandex_lat"] = $arStoreTmp["GPS_N"];

		if (floatval($arStoreLocation["yandex_lon"]) <= 0)
			$arStoreLocation["yandex_lon"] = $arStoreTmp["GPS_S"];

		$arLocationTmp = array();
		$arLocationTmp["ID"] = $arStoreTmp["ID"];
		if (strlen($arStoreTmp["GPS_N"]) > 0)
			$arLocationTmp["LAT"] = $arStoreTmp["GPS_N"];
		if (strlen($arStoreTmp["GPS_S"]) > 0)
			$arLocationTmp["LON"] = $arStoreTmp["GPS_S"];
		if (strlen($arStoreTmp["TITLE"]) > 0)
			$arLocationTmp["TEXT"] = $arStoreTmp["TITLE"]."\r\n".$arStoreTmp["DESCRIPTION"];

		$arStoreLocation["PLACEMARKS"][] = $arLocationTmp;
	}
	$location = serialize($arStoreLocation);
}

$showImages = (isset($_REQUEST["showImages"]) && $_REQUEST["showImages"] == "Y") ? true : false;
?>

<?
$rnd = "or".randString(4);
?>

<!-- delivery modal -->

<div class="modal-issue_point">
    <div class="map-wrapper">
        <?
        $mapWidth = (isset($_REQUEST["map_w"])) ? intval($_REQUEST["map_w"]) : 500;
        $APPLICATION->IncludeComponent(
            "bitrix:map.yandex.view",
            ".default",
            Array(
                "INIT_MAP_TYPE" => "MAP",
                "MAP_DATA" => $location,
                "MAP_WIDTH" => $mapWidth,
                "MAP_HEIGHT" => $mapWidth,
                "CONTROLS" => array(0=>"TYPECONTROL",),
                "OPTIONS" => array(0=>"ENABLE_SCROLL_ZOOM",1=>"ENABLE_DRAGGING",),
                "MAP_ID" => $rnd,
            )
        );?>
    </div>

    <script>
        var arStore = <?=CUtil::PhpToJSObject($arStore);?>;
        var objName = '<?=$rnd?>';
    </script>

    <div class="card-body" id="store_table">
        <?
        $i = 1;
        $countCount = count($arStore);
        $arDefaultStore = array_shift(array_values($arStore));

        foreach ($arStore as $val)
        {
            $checked = '';
            if($selectStoreId) {
                if($val["ID"] == $selectStoreId)
                    $checked = "checked";
            } else if($val["ID"] == $arDefaultStore["ID"]) {
                $checked = "checked";
            }

            ?>
            <div class="store_row <?=$checked?>" id="row_<?=$val["ID"]?>" onclick="setChangeStore(<?=$val["ID"]?>);">
                <?
                if ($showImages)
                {
                    ?>
                        <div class="image">
                            <?
                            if (intval($val["IMAGE_ID"]) > 0):
                                ?>
                                <a href="<?=$val["IMAGE_URL"]?>" target="_blank"><?=$val["IMAGE"]?></a>
                            <?
                            else:
                                ?>
                                <img src="/bitrix/components/bitrix/sale.order.ajax/templates/visual/images/no_store.png" />
                            <?
                            endif;
                            ?>
                        </div>
                    <?
                }
                ?>
                <div class="<?=($countCount != $i)?"lilne":"last"?>">
                    <label for="store_<?=$val["ID"]?>">

                        <div class="<?/*card-header*/?> header-elements-inline name">
                            <h5 class="card-title"><?=htmlspecialcharsbx($val["TITLE"])?></h5>
                        </div>

                        <?if($val["PHONE"]):?>
                            <div class="phone"><span class="text-muted"><?=GetMessage('MAP_PHONE');?>:</span></div>
                            <div><label><?=htmlspecialcharsbx($val["PHONE"])?></label></div>
                        <?endif;?>

                        <?if($val["EMAIL"]):?>
                            <div class="email"><span class="text-muted"><?=GetMessage('MAP_EMAIL');?>:</span></div>
                            <div><label><a href="mailto:<?=htmlspecialcharsbx($val["EMAIL"])?>"><?=htmlspecialcharsbx($val["EMAIL"])?></a></label></div>
                        <?endif;?>

                        <?if($val["ADDRESS"]):?>
                            <div class="adres"><span class="text-muted"><?=GetMessage('MAP_ADRES');?>:</span></div>
                            <div><label><?=htmlspecialcharsbx($val["ADDRESS"])?></label></div>
                        <?endif;?>
                        <?if($val["SCHEDULE"]):?>
                            <div class="shud"><span class="text-muted"><?=GetMessage('MAP_WORK');?>:</span></div>
                            <div><label><?=htmlspecialcharsbx($val["SCHEDULE"])?></label></div>
                        <?endif;?>

                    </label>

                    <?if($val["DESCRIPTION"]):?>
                        <div class="desc"><span class="text-muted"><?=GetMessage('MAP_DESC');?>:</span></div>
                        <div><label><?=htmlspecialcharsbx($val["DESCRIPTION"])?></label></div>
                    <?endif;?>
                </div>
            </div>
            <?
            $i++;
        }
        ?>
    </div>
</div>

<hr>
<input type="hidden" name="POPUP_STORE_ID" id="POPUP_STORE_ID" value="<?=$arDefaultStore["ID"]?>" >
<input type="hidden" name="POPUP_STORE_NAME" id="POPUP_STORE_NAME" value="<?=$arDefaultStore["TITLE"]?>" >

<script type="text/javascript">
	function setChangeStore(id)
	{
		var store = arStore[id];

		BX('POPUP_STORE_ID').value = id;
		BX('POPUP_STORE_NAME').value = BX.util.htmlspecialchars(store["TITLE"]);

		var tbl = BX('store_table');
		for (var i = 0; i < tbl.getElementsByTagName('div').length; i++)
			BX.removeClass(BX(tbl.getElementsByTagName('div')[i].id), 'checked');

		BX.addClass(BX('row_' + id), 'checked');

		if(window.GLOBAL_arMapObjects[objName])
			window.GLOBAL_arMapObjects[objName].panTo([parseFloat(store["GPS_N"]), parseFloat(store["GPS_S"])], {flying: 1});
	}

	if (BX('BUYER_STORE') && parseInt(BX('BUYER_STORE').value) > 0)
	{
		BX('POPUP_STORE_ID').value = BX('BUYER_STORE').value;
		BX('POPUP_STORE_NAME').value =  BX.util.htmlspecialchars(arStore[BX('BUYER_STORE').value]["TITLE"]);
        if(window.GLOBAL_arMapObjects[objName])
            window.GLOBAL_arMapObjects[objName].panTo([parseFloat(arStore[BX('POPUP_STORE_ID').value]["GPS_N"]), parseFloat(arStore[BX('POPUP_STORE_ID').value]["GPS_S"])], {flying: 1});
	}
</script>

<script>
    window.onload = function () {
        var psDelivery = new PerfectScrollbar('#store_table', {
            wheelSpeed: 0.3,
            wheelPropagation: true,
            minScrollbarLength: 10
        });
    }

</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>