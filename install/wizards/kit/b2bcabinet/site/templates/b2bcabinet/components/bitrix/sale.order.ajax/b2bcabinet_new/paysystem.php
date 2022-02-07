<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div>
    <div class="card-header header-elements-inline checkout_form-title_inner">
        <h6 class="card-title"><span><?=GetMessage("SOA_TEMPL_PAY")?></span></h6>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label"><?=GetMessage("SOA_TEMPL_PAY_SYSTEM")?></label>
        <div class="col-lg-9">
            <div class="index_checkout-payment_system">

            <?
            if ($arResult["PAY_FROM_ACCOUNT"] == "Y")
            {
                $accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
                ?>
                <input type="hidden" id="account_only" value="<?=$accountOnly?>" />
                <div class="form-check form-check-inline disabled">
                    <label class="form-check-label" for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL" onclick="changePaySystem('account');" class="<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo "selected"?>">
                        <input class="form-input-styled" type="checkbox" name="PAY_CURRENT_ACCOUNT"
                               id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?> data-fouc>

                        <div class="delivery_payment_logo">
                            <img class="img-responsive mr-2" src="<?=$templateFolder?>/images/logo-default-ps.gif"
                                 width="<?=$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0]?>" height="auto"
                                 alt="">
                        </div>

                        <div class="index_checkout-delivery_text">
                            <span class="index_checkout-radios_title"><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT")?></span><br>
                            <span class="index_checkout-radios_description">
                                      <?=GetMessage("SOA_TEMPL_PAY_ACCOUNT1")." <b>".$arResult["CURRENT_BUDGET_FORMATED"]?></b>
                                    <? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
                                        <?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?>
                                    <? else:?>
                                        <?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?>
                                    <? endif;?>
                                </span>
                        </div>
                    </label>
                </div>
                <?
            }

            uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

            foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
            {
                if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
                    $imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
                else:
                    $imgUrl = $templateFolder."/images/logo-default-ps.gif";
                endif;

                if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0)
                {
                    ?>
                    <div class="form-check form-check-inline disabled">
                        <label class="form-check-label" for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
                            <?if (count($arResult["PAY_SYSTEM"]) == 1):?><input type="hidden"
                                                                                name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>"><?endif;?>
                            <input type="radio"
                                   class="form-input-styled"
                                   id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?=$arPaySystem["ID"]?>"
                                <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                   onclick="changePaySystem();"
                                   data-fouc
                            />
                            <div class="delivery_payment_logo">
                                <img class="img-responsive mr-2" src="<?=$imgUrl?>"
                                     width="<?=$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0]?>" height="auto"
                                     alt="">
                            </div>

                            <div class="index_checkout-delivery_text">
                                <?if($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
                                    <span class="index_checkout-radios_title"><?=$arPaySystem["PSA_NAME"];?></span><br>
                                <?endif;?>
                                <span class="index_checkout-radios_description">
                                      <?
                                      if (intval($arPaySystem["PRICE"]) > 0)
                                          echo str_replace("#PAYSYSTEM_PRICE#", SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION), $arResult["BASE_LANG_CURRENCY"]), GetMessage("SOA_TEMPL_PAYSYSTEM_PRICE"));
                                      else
                                          echo $arPaySystem["DESCRIPTION"];
                                      ?>
                                </span>
                            </div>
                        </label>
                    </div>
                    <?
                }

                if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0)
                {
                    ?>
                    <div class="form-check form-check-inline disabled">
                        <label class="form-check-label" for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
                            <?if (count($arResult["PAY_SYSTEM"]) == 1):?>
                                <input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
                            <?endif;?>

                            <input type="radio"
                                   class="form-input-styled"
                                   id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?=$arPaySystem["ID"]?>"
                                <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                   onclick="changePaySystem();"
                                   data-fouc
                            />
                            <div class="delivery_payment_logo">
                                <img class="img-responsive mr-2" src="<?=$imgUrl?>"
                                     width="<?=$arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'][0]?>" height="auto"
                                     alt="">
                            </div>

                            <div class="index_checkout-delivery_text">
                                <?if($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
                                    <span class="index_checkout-radios_title">
                                    <?if (count($arResult["PAY_SYSTEM"]) == 1):?>
                                        <?=$arPaySystem["PSA_NAME"];?>
                                    <?else:?>
                                        <?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
                                            <?=$arPaySystem["PSA_NAME"];?>
                                        <?else:?>
                                            <?="&nbsp;"?>
                                        <?endif;?>
                                    <?endif;?>
                                    </span><br>
                                <?endif;?>
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
</div>


<script type="text/javascript">
    function changePaySystem(param)
    {
        if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = true;
                    BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                    BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

                    // deselect all other
                    var el = document.getElementsByName("PAY_SYSTEM_ID");
                    for(var i=0; i<el.length; i++)
                        el[i].checked = false;
                }
            }
            else
            {
                BX("PAY_CURRENT_ACCOUNT").checked = false;
                BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
            }
        }
        else if (BX("account_only") && BX("account_only").value == 'N')
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

                    if (BX("PAY_CURRENT_ACCOUNT").checked)
                    {
                        BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                        BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                    else
                    {
                        BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                        BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                }
            }
        }

        submitForm();
    }
</script>