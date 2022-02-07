<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>" />


<!-- delivery2 -->
<div>
    <div class="card-header header-elements-inline checkout_form-title_inner">
        <h6 class="card-title"><span><?=GetMessage("SOA_TEMPL_SUM_DELIVERY_NOT_PIG")?></span></h6>
    </div>

    <?if(!empty($arResult["DELIVERY"])):?>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label"><?=GetMessage("SOA_TEMPL_DELIVERY")?></label>
            <div class="col-lg-9">
                <div class="index_checkout-delivery_type">

                <?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 850 : 700;?>
                <?

                foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
                    {
                        if ($delivery_id !== 0 && intval($delivery_id) <= 0)
                        {

                            foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
                            {

                                if (count($arDelivery["LOGOTIP"]) > 0):

                                    $arFileTmp = CFile::ResizeImageGet(
                                        $arDelivery["LOGOTIP"]["ID"],
                                        ["width" => $arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0], "height" =>
                                            $arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][1]],
                                        BX_RESIZE_IMAGE_PROPORTIONAL,
                                        true
                                    );

                                    $deliveryImgURL = $arFileTmp["src"];
                                else:
                                    $deliveryImgURL = $templateFolder."/images/logo-default-d.gif";
                                endif;

                                if ($arDelivery["ISNEEDEXTRAINFO"] == "Y") {
                                    $extraParams = "showExtraParamsDialog('".$delivery_id.":".$profile_id."');";
                                } else {
                                    $extraParams = "";
                                }
                                ?>

                                <div class="form-check form-check-inline disabled">
                                    <input type="radio"
                                           id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>"
                                           name="<?=htmlspecialcharsbx($arProfile["FIELD_NAME"])?>"
                                           value="<?=$delivery_id.":".$profile_id;?>"
                                            <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?>
                                           onclick="submitForm();"
                                           style="display: none;"
                                    />
                                    <label class="form-check-label" for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">

                                        <input type="radio"
                                               name="fake_input"
                                                <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?>
                                               class="form-input-styled" data-fouc
                                        />
                                        <div class="delivery_payment_logo" onclick="BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked=true;<?=$extraParams?>submitForm();">
                                            <img class="img-responsive mr-2" src="<?=$deliveryImgURL?>"
                                                 width="<?=$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0]?>" height="auto"
                                                 alt="">
                                        </div>

                                        <div class="index_checkout-delivery_text" onclick="BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked=true;<?=$extraParams?>submitForm();">
                                            <span class="index_checkout-radios_title"><?=htmlspecialcharsbx($arDelivery["TITLE"])." (".htmlspecialcharsbx($arProfile["TITLE"]).")";?></span><br>

                                            <?if($arProfile["CHECKED"] == "Y" && doubleval($arResult["DELIVERY_PRICE"]) > 0):
                                                    if ((isset($arResult["PACKS_COUNT"]) && $arResult["PACKS_COUNT"]) > 1):
                                                        echo GetMessage('SALE_PACKS_COUNT').': <b>'.$arResult["PACKS_COUNT"].'</b>';
                                                    endif;
                                            endif;?>

                                            <span class="index_checkout-radios_description" onclick="BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked=true;submitForm();">
                                                <?if (strlen($arProfile["DESCRIPTION"]) > 0):?>
                                                    <?=nl2br($arProfile["DESCRIPTION"])?>
                                                <?else:?>
                                                    <?=nl2br($arDelivery["DESCRIPTION"])?>
                                                <?endif;?>
                                            </span>

                                        </div>
                                        <div class="index_checkout-deliveries">
                                            <h3 class="index_checkout-delivery_cost">
                                                <?
                                                if($arProfile["CHECKED"] == "Y" && doubleval($arResult["DELIVERY_PRICE"]) > 0):
                                                    ?>
                                                    <?=$arResult["DELIVERY_PRICE_FORMATED"]?>
                                                <?else:
                                                    $APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
                                                        "NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
                                                        "DELIVERY" => $delivery_id,
                                                        "PROFILE" => $profile_id,
                                                        "ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
                                                        "ORDER_PRICE" => $arResult["ORDER_PRICE"],
                                                        "LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
                                                        "LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
                                                        "CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
                                                        "ITEMS" => $arResult["BASKET_ITEMS"],
                                                        "EXTRA_PARAMS_CALLBACK" => $extraParams
                                                    ), null, array('HIDE_ICONS' => 'Y'));
                                                endif;
                                                ?>
                                                <?=($arDelivery["PRICE_FORMATED"] ? $arDelivery["PRICE_FORMATED"] : '')?></h3>
                                        </div>
                                    </label>
                                </div>
                                <?
                            } // endforeach
                        }
                        else // stores and courier
                        {

                            if (count($arDelivery["STORE"]) > 0)
                                $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."')\";";
                            else
                                $clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true;submitForm();\"";
                            ?>
                            <?
                            if (count($arDelivery["LOGOTIP"]) > 0):

                                $arFileTmp = CFile::ResizeImageGet(
                                    $arDelivery["LOGOTIP"]["ID"],
                                    array("width" => $arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0], "height"
                                                  =>$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][1]),
                                    BX_RESIZE_IMAGE_PROPORTIONAL,
                                    true
                                );

                                $deliveryImgURL = $arFileTmp["src"];
                            else:
                                $deliveryImgURL = $templateFolder."/images/logo-default-d.gif";
                            endif;
                            ?>

                            <div class="form-check form-check-inline disabled">

                                <label class="form-check-label" for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" <?=$clickHandler?>>
                                    <input type="radio"
                                           id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                                           name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>"
                                           value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>
                                           type="radio"
                                           class="form-input-styled"
                                           <?(!count($arDelivery["STORE"])) ? 'onclick="submitForm();"' : ''?>
                                    />
                                    <div class="delivery_payment_logo">
                                        <img class="img-responsive" src="<?=$deliveryImgURL?>"
                                             width="<?=$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0]?>" height="auto"
                                             alt="">
                                    </div>
                                    <div class="index_checkout-delivery_text">
                                        <span class="index_checkout-radios_title"><?= htmlspecialcharsbx($arDelivery["NAME"])?></span><br>

                                        <?if(strlen($arDelivery["PERIOD_TEXT"])>0):?><?=GetMessage('SOA_TEMPL_DELIVERY_DAY');?> <span
                                                class="index_checkout-radios_description"><?=$arDelivery["PERIOD_TEXT"]?></span><br><?endif;?>

                                        <?if (count($arDelivery["STORE"]) > 0):
                                            ?>
                                            <span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
												<?=GetMessage('SOA_ORDER_GIVE_TITLE');?>:
												<span
                                                        class="index_checkout-radios_description"
                                                        id="store_desc">
                                                    <?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?>
                                                </span><br>
											</span>
                                        <?endif;?>

                                        <?if(strlen($arDelivery["DESCRIPTION"])>0):?><span
                                                class="index_checkout-radios_description"><?=$arDelivery["DESCRIPTION"]?></span><?endif;?>

                                    </div>
                                    <div class="index_checkout-deliveries">
                                        <h3 class="index_checkout-delivery_cost"><?=
                                            ($arDelivery["PRICE_FORMATED"] ? $arDelivery["PRICE_FORMATED"] : '')?></h3>
                                    </div>
                                </label>
                            </div>

                            <?
                        }
                    }
                ?>

                </div>
            </div>
        </div>
    <?endif;?>
</div>


<script type="text/javascript">
	function fShowStore(id, showImages, formWidth, siteId)
	{
	    var mapWidthDefault = 500;
	    var selectId = BX('BUYER_STORE');
        var strUrl = '<?=$templateFolder?>' + '/map.php';
        var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId +
            (screen.width < mapWidthDefault ? '&map_w='+(screen.width - 50) : '&map_w='+mapWidthDefault) +
            (selectId.value ? '&selectStore='+selectId.value : '');

        var wait = BX.showWait();
        BX.ajax({
            method: 'POST',
            dataType: 'html',
            url: strUrl,
            data: strUrlPost,
            skipAuthCheck: true,
            onsuccess: function(data) {
                BX.closeWait(null, wait);
                $('#modal_map_content').html(data);
                $("#modal_large").modal('show');
            }
        });

	}

	function GetBuyerStore()
	{
		BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
		//BX('ORDER_DESCRIPTION').value = '<?=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
		BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
		BX.show(BX('select_store'));
        $("#modal_large").modal('hide');
        submitForm();
	}

	function showExtraParamsDialog(deliveryId)
	{
		var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
		var formName = 'extra_params_form';
		var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

		if(window.BX.SaleDeliveryExtraParams)
		{
			for(var i in window.BX.SaleDeliveryExtraParams)
			{
				strUrlPost += '&'+encodeURI(i)+'='+encodeURI(window.BX.SaleDeliveryExtraParams[i]);
			}
		}

		var paramsDialog = new BX.CDialog({
			'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
			head: '',
			'content_url': strUrl,
			'content_post': strUrlPost,
			'width': 500,
			'height':200,
			'resizable':true,
			'draggable':false
		});

		var button = [
			{
				title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
				id: 'saleDeliveryExtraParamsOk',
				'action': function ()
				{
					insertParamsToForm(deliveryId, formName);
					BX.WindowManager.Get().Close();
				}
			},
			BX.CDialog.btnCancel
		];

		paramsDialog.ClearButtons();
		paramsDialog.SetButtons(button);
		//paramsDialog.adjustSizeEx();
		paramsDialog.Show();
	}

	function insertParamsToForm(deliveryId, paramsFormName)
	{
		var orderForm = BX("ORDER_FORM"),
			paramsForm = BX(paramsFormName);
			wrapDivId = deliveryId + "_extra_params";

		var wrapDiv = BX(wrapDivId);
		window.BX.SaleDeliveryExtraParams = {};

		if(wrapDiv)
			wrapDiv.parentNode.removeChild(wrapDiv);

		wrapDiv = BX.create('div', {props: { id: wrapDivId}});

		for(var i = paramsForm.elements.length-1; i >= 0; i--)
		{
			var input = BX.create('input', {
				props: {
					type: 'hidden',
					name: 'DELIVERY_EXTRA['+deliveryId+']['+paramsForm.elements[i].name+']',
					value: paramsForm.elements[i].value
					}
				}
			);

			window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

			wrapDiv.appendChild(input);
		}

		orderForm.appendChild(wrapDiv);

		BX.onCustomEvent('onSaleDeliveryGetExtraParams',[window.BX.SaleDeliveryExtraParams]);
	}

</script>