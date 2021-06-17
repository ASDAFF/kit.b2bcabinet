<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;

$bPreviewPicture = false;
$bDetailPicture = false;
// prelimenary column handling
foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn)
{
    if ($arColumn["id"] == "PROPS")
        $bPropsColumn = true;

    if ($arColumn["id"] == "NOTES")
        $bPriceType = true;

    if ($arColumn["id"] == "PREVIEW_PICTURE")
        $bPreviewPicture = true;

    if ($arColumn["id"] == "DETAIL_PICTURE")
        $bDetailPicture = true;
}

if ($bPreviewPicture || $bDetailPicture)
    $bShowNameWithPicture = true;

$arProps = [];
foreach ($arResult["GRID"]["ROWS"] as $k => $arData):
    foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):
        $arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
        if ($arColumn["id"] == "NAME"):

            if ($bPropsColumn):
                foreach ($arItem["PROPS"] as $val):
                    $arProps['NAME'][$val["CODE"]] = $val["NAME"];
                endforeach;

            endif;

        endif;
    endforeach;
endforeach;
?>


<div class="row">
    <div class="col-md-12">
        <!-- Static mode -->
        <div class="card card-collapsed">

            <div class="card-header header-elements-inline">
                <h5 class="card-title"><?=GetMessage("SOA_TEMPL_BASKET")?></h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item rotate-180" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body index_checkout-table" style="display:none">
                <div class="table-responsive">
                    <table class="table datatable-save-state dataTable no-footer" id="DataTables_Table_0">
                        <thead>
                        <tr>
                            <?
                            foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

                                if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES"))) // some values are not shown in columns in this template
                                    continue;

                                if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture)
                                    continue;

                                if ($arColumn["id"] == "NAME" && $bShowNameWithPicture):?>
                                    <th colspan="2">
                                        <?=GetMessage("SALE_PRODUCTS");?>
                                    </th>
                                    <?if ($bPropsColumn):
                                        foreach ($arProps['NAME'] as $valP):?>
                                            <th><?=$valP?></th>
                                        <?endforeach;
                                    endif;?>

                                <?elseif ($arColumn["id"] == "NAME" && !$bShowNameWithPicture):?>
                                    <th>
                                        <?=$arColumn["name"];?>
                                    </th>
                                <?elseif ($arColumn["id"] == "PRICE_FORMATED"): ?>
                                    <th>
                                        <?=$arColumn["name"];?>
                                    </th>
                                    <?if($bPriceType):?>
                                        <th>
                                            <?=GetMessage("SALE_TYPE")?>
                                        </th>
                                    <?endif;?>
                                <?else:?>
                                    <th>
                                        <?=$arColumn["name"];?>
                                    </th>
                                <?endif;?>

                            <?endforeach;?>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):?>
                            <tr>
                                <? // IMG
                                if ($bPreviewPicture):
                                    ?>
                                    <td>
                                            <?
                                            if (strlen($arData["data"]["PREVIEW_PICTURE_SRC"]) > 0):
                                                $url = $arData["data"]["PREVIEW_PICTURE_SRC"];
                                            elseif (strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0):
                                                $url = $arData["data"]["DETAIL_PICTURE_SRC"];
                                            else:
                                                $url = $templateFolder."/images/no_photo.png";
                                            endif;

                                            if ($arParams['DETAIL_PAGE_URL'] == "Y" && strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?>
                                                <a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>">
                                            <?endif;?>

                                            <img class="img-responsive"
                                                 width="<?=$arParams['IMAGE_SIZE_PREVIEW']?>" height="auto"
                                                 alt="<?=$arData["data"]["NAME"]?>"
                                                 src="<?=$url?>">

                                            <?if ($arParams['DETAIL_PAGE_URL'] == "Y" && strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?>
                                                </a>
                                            <?endif;?>
                                    </td>
                                <?endif;?>


                                <?
                                // HEADER
                                foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

                                    if (in_array($arColumn["id"], array("PREVIEW_PICTURE", "PROPS", "TYPE", "NOTES")))
                                        // some values are not shown in columns in this template
                                        continue;

                                    $arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];

                                    if ($arColumn["id"] == "NAME"):
                                        ?>

                                        <? // NAME ?>
                                        <td>
                                            <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0 && $arParams['DETAIL_PAGE_URL'] == "Y")
                                                :?><a
                                                    href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
                                                <?=$arItem["NAME"]?>
                                            <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0 && $arParams['DETAIL_PAGE_URL'] == "Y"):?></a><?endif;?>
                                        </td>

                                        <? // PROPS?>

                                        <?
                                        if ($bPropsColumn):
                                            foreach ($arProps['NAME'] as $codeCol => $va) {
                                                $iss = array_search($codeCol, array_column($arItem["PROPS"], 'CODE'));
                                                if($iss !== false) {
                                                    ?><td><?=$arItem["PROPS"][$iss]["VALUE"]?></td><?
                                                } else {
                                                    ?><td></td><?
                                                }
                                            }

                                        endif;
                                        ?>
                                            <? // DATA
                                            if (is_array($arItem["SKU_DATA"])):
                                                ?><td><?
                                                foreach ($arItem["SKU_DATA"] as $propId => $arProp):

                                                    // is image property
                                                    $isImgProperty = false;
                                                    foreach ($arProp["VALUES"] as $id => $arVal)
                                                    {
                                                        if (isset($arVal["PICT"]) && !empty($arVal["PICT"]))
                                                        {
                                                            $isImgProperty = true;
                                                            break;
                                                        }
                                                    }

                                                    $full = (count($arProp["VALUES"]) > 5) ? "full" : "";

                                                    if ($isImgProperty): // iblock element relation property
                                                        ?>
                                                        <div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
1
                                                            <div class="bx_scu_scroller_container">

                                                                <div class="bx_scu">
                                                                    <ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
                                                                        <?
                                                                        foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

                                                                            $selected = "";
                                                                            foreach ($arItem["PROPS"] as $arItemProp):
                                                                                if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
                                                                                {
                                                                                    if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
                                                                                        $selected = "class=\"bx_active\"";
                                                                                }
                                                                            endforeach;
                                                                            ?>
                                                                            <li style="width:10%;" <?=$selected?>>
                                                                                <a href="javascript:void(0);">
                                                                                    <span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
                                                                                </a>
                                                                            </li>
                                                                        <?
                                                                        endforeach;
                                                                        ?>
                                                                    </ul>
                                                                </div>

                                                                <div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                                <div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                            </div>

                                                        </div>
                                                    <?
                                                    else:
                                                        ?>
                                                        <div class="bx_item_detail_size_small_noadaptive <?=$full?>">
                                                            <div class="bx_size_scroller_container">
                                                                <div class="bx_size">
                                                                    <ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
                                                                        <?
                                                                        foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

                                                                            $selected = "";
                                                                            foreach ($arItem["PROPS"] as $arItemProp):
                                                                                if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
                                                                                {
                                                                                    if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
                                                                                        $selected = "class=\"bx_active\"";
                                                                                }
                                                                            endforeach;
                                                                            ?>
                                                                            <li style="width:10%;" <?=$selected?>>
                                                                                <a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
                                                                            </li>
                                                                        <?
                                                                        endforeach;
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                                <div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                            </div>

                                                        </div>
                                                    <?
                                                    endif;
                                                endforeach;
                                                ?><td></td><?
                                            endif;
                                            ?>


                                    <? //PRICE
                                    elseif ($arColumn["id"] == "PRICE_FORMATED"):
                                        ?>
                                        <td>
                                           <?=$arItem["PRICE_FORMATED"]?>
                                        </td>
                                        <?if ($bPriceType):?>
                                            <td>
                                                <?if(strlen($arItem["NOTES"]) > 0):?>
                                                    <?=$arItem["NOTES"]?>
                                                <?endif;?>
                                            </td>
                                        <?endif;?>
                                    <?
                                    elseif ($arColumn["id"] == "DISCOUNT"):
                                        ?>
                                        <td class="custom right">
                                            <?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?>
                                        </td>
                                    <?
                                    elseif ($arColumn["id"] == "DETAIL_PICTURE"):
                                        ?>
                                        <td>
                                                <?
                                                $url = "";
                                                if ($arColumn["id"] == "DETAIL_PICTURE" && strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0)
                                                    $url = $arData["data"]["DETAIL_PICTURE_SRC"];

                                                if ($url == "")
                                                    $url = $templateFolder."/images/no_photo.png";

                                                if ($arParams['DETAIL_PAGE_URL'] == "Y" && strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?>
                                                        <a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>">
                                                <?endif;?>

                                                <img class="img-responsive"
                                                     <?if($arParams['IMAGE_SIZE_DETAIL']):?>
                                                        width="<?=$arParams['IMAGE_SIZE_DETAIL']?>"
                                                        height="auto"
                                                     <?endif;?>
                                                     alt="<?=$arData["data"]["NAME"]?>"
                                                     src="<?=$url?>">

                                                <?if ($arParams['DETAIL_PAGE_URL'] == "Y" && strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?>
                                                        </a>
                                                <?endif;?>
                                        </td>
                                    <?
                                    elseif (in_array($arColumn["id"], array("QUANTITY", "WEIGHT_FORMATED", "DISCOUNT_PRICE_PERCENT_FORMATED", "SUM"))):
                                        ?>
                                        <td>
                                            <?=$arItem[$arColumn["id"]]?>
                                        </td>
                                    <?
                                    else: // some property value

                                        if (is_array($arItem[$arColumn["id"]])):

                                            foreach ($arItem[$arColumn["id"]] as $arValues)
                                                if ($arValues["type"] == "image")
                                                    $columnStyle = "width:20%";
                                            ?>
                                            <td style="<?=$columnStyle?>">
                                                <?
                                                foreach ($arItem[$arColumn["id"]] as $arValues):
                                                    if ($arValues["type"] == "image"):
                                                        ?>
                                                        <img class="img-responsive"
                                                            <?if($arParams['IMAGE_SIZE_DETAIL']):?>
                                                                width="<?=$arParams['IMAGE_SIZE_DETAIL']?>"
                                                                height="auto"
                                                            <?endif;?>
                                                             src="<?=$arValues["value"]?>">
                                                    <?
                                                    else: // not image
                                                        echo $arValues["value"]."<br/>";
                                                    endif;
                                                endforeach;
                                                ?>
                                            </td>
                                        <?
                                        else: // not array, but simple value
                                            ?>
                                            <td style="<?=$columnStyle?>">
                                                <?
                                                echo $arItem[$arColumn["id"]];
                                                ?>
                                            </td>
                                        <?
                                        endif;
                                    endif;

                                endforeach;
                                ?>
                            </tr>
                        <?endforeach;?>

                        </tbody>
                    </table>
                </div>
                <div class="index_checkout-promocode 45t6yhju">
                    <div>
                        <div class="form-group row">
                        </div>
                        <div class="index_checkout-promocode-total">
                            <div>
                                <div class="index_checkout-promocode-total_text">
                                    <h5><?=GetMessage("SOA_TEMPL_SUM_IT")?></h5>
                                    <h4>
                                        <span class="index_checkout-promocode-total_amount"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></span>
                                    </h4>
                                </div>
                                <?if(doubleval($arResult["DISCOUNT_PRICE"]) > 0):?>
                                    <div class="index_checkout-promocode-total_text promocod_economy">
                                        <h6><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?>:</h6>
                                        <h6>
                                            <span class="index_checkout-promocode-total_amount"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?></span>
                                        </h6>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /static mode -->

    </div>
</div>
