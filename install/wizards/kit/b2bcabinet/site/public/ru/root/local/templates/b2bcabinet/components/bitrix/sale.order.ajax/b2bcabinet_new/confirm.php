<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="card-body checkout_form">
    <div class="checkout_form-individual">
    <?php if (!empty($arResult["ORDER"])): ?>
        <?php
        $linkDetail = is_dir($_SERVER["DOCUMENT_ROOT"].'/'.\KitB2bCabinet::PATH.'/') ? SITE_DIR.\KitB2bCabinet::PATH.'/' : SITE_DIR;
        $linkDetail .= 'order/detail/'.$arResult["ORDER"]['ID'].'/';
        ?>
        <div>
            <div class="card-header header-elements-inline checkout_form-title_inner">
                <h6 class="card-title"><span><?= GetMessage("SOA_TEMPL_SUMMARY_TITLE") ?></span></h6>
            </div>

            <a class="validation-invalid-label validation-valid-label" href="<?=$linkDetail?>">
                <?= GetMessage("SOA_TEMPL_ORDER_SUC", [
                    "#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"],
                    "#ORDER_ID#"   => $arResult["ORDER"]["ACCOUNT_NUMBER"],
                ]) ?>
                </a>
            </a>

            <div>
                <span>
                    <?= GetMessage("SOA_TEMPL_ORDER_SUC1", ["#LINK#" => $arParams["PATH_TO_PERSONAL"]]) ?>
                </span>
            </div>
            <div class="description">
                <span>
                    <?= GetMessage("SOA_TEMPL_ORDER_SUC2") ?>
                </span>
            </div>
        </div>

        <? if (!empty($arResult["PAY_SYSTEM"])): ?>
            <div>
                <div class="card-header header-elements-inline checkout_form-title_inner">
                    <h6 class="card-title"><span><?= GetMessage("SOA_TEMPL_PAY") ?></span></h6>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label"><?= GetMessage("SOA_TEMPL_PAY_SYSTEM") ?></label>
                    <div class="col-lg-9">
                        <div class="index_checkout-payment_system">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <div class="delivery_payment_logo">
                                        <?= CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 30, 30,
                                            "class=\"img-responsive mr-2\" height=\"auto\"", "",
                                            false); ?>
                                    </div>
                                    <div class="index_checkout-delivery_text">
                                        <div class="index_checkout-radios_title">
                                            <span><?= $arResult["PAY_SYSTEM"]["NAME"] ?></span>
                                        </div>
                                        <div class="index_checkout-radios_description">

                                            <?
                                            if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y") {
                                                ?>
                                                <script type="JavaScript">
                                                    window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
                                                </script>
                                                <?= GetMessage("SOA_TEMPL_PAY_LINK", [
                                                    "#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID="
                                                        .urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"])),
                                                ]) ?>
                                                <?
                                                if (CSalePdf::isPdfAvailable()
                                                    && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE'])
                                                ) {
                                                    ?><br/>
                                                    <?= GetMessage("SOA_TEMPL_PAY_PDF", [
                                                        "#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID="
                                                            .urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))
                                                            ."&pdf=1&DOWNLOAD=Y",
                                                    ]) ?>
                                                    <?
                                                }
                                            } else {
                                                if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]) > 0) {
                                                    try {
                                                        include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
                                                    } catch (\Bitrix\Main\SystemException $e) {
                                                        if ($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE) {
                                                            $message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
                                                        } else {
                                                            $message = $e->getMessage();
                                                        }

                                                        echo '<span style="color:red;">'.$message.'</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>
    <? else: ?>
        <div>
            <div class="card-header header-elements-inline checkout_form-title_inner">
                <h6 class="card-title"><span><?= GetMessage("SOA_TEMPL_ERROR_ORDER") ?></span></h6>
            </div>

            <label class="validation-invalid-label validation-valid-label">
                <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", ["#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]]) ?>
            </label>

            <div>
                <span>
                    <?= GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1") ?>
                </span>
            </div>
        </div>
    <? endif; ?>
    </div>
</div>
