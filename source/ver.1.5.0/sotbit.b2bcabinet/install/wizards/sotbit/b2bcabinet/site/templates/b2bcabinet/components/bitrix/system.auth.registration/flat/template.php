<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

use Bitrix\Main\Config\Option;
use Bitrix\Sale\Internals\PersonTypeTable;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (\Bitrix\Main\Loader::includeModule("sotbit.auth")
    && \Bitrix\Main\Loader::includeModule("sale")
    && \Bitrix\Main\Loader::includeModule("sotbit.b2bcabinet")) {
    $APPLICATION->IncludeComponent(
    "sotbit:sotbit.auth.wholesaler.register",
    "b2bcabinet",
        [
            "AUTH" => "Y",
            'AUTH_URL' => $arResult["AUTH_AUTH_URL"],
            "REQUIRED_FIELDS" => [
                "EMAIL"
            ],
            "REQUIRED_WHOLESALER_FIELDS" => [
                "EMAIL"
            ],
            "SET_TITLE" => "Y",
            "SHOW_FIELDS" => [
                "NAME",
                'LAST_NAME'
            ],
            "SHOW_WHOLESALER_FIELDS" => unserialize(Option::get('sotbit.b2bcabinet', 'OPT_REGISTER_FIELDS', '')),
            "SHOW_WHOLESALER_ORDER_FIELDS" => unserialize(Option::get('sotbit.b2bcabinet', 'OPT_REGISTER_ORDER_FIELDS', '')),
            "SUCCESS_PAGE" => "/",
            "USER_PROPERTY" => [
                'UF_CONFIDENTIAL'
            ],
            "USER_PROPERTY_NAME" => "",
            "USE_BACKURL" => "Y",
            "VARIABLE_ALIASES" => []
        ]);

    return;
}

//echo '<pre>';
//print_r($arResult);
//exit();
//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>


<!-- Main content -->
<div class="content-wrapper content_no_padding registration-form-wrapper">

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <div class="bx-authform">

            <?
            if (!empty($arParams["~AUTH_RESULT"])):
                $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
                ?>
                <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
            <? endif ?>

            <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) && $arParams["AUTH_RESULT"]["TYPE"] === "OK"): ?>
                <div class="alert alert-success"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
            <? else: ?>

            <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
                <div class="alert alert-warning"><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></div>
            <? endif ?>

                <!-- Registration form -->
                <noindex>
                    <form class="login-form" method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform"
                          enctype="multipart/form-data">

                        <? if ($arResult["BACKURL"] <> ''): ?>
                            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                        <? endif ?>
                        <input type="hidden" name="AUTH_FORM" value="Y"/>
                        <input type="hidden" name="TYPE" value="REGISTRATION"/>


                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h5 class="mb-0"><?= GetMessage("AUTH_CREATE_ACCOUNT_TITLE") ?></h5>
                                    <span class="d-block text-muted"><?= GetMessage("AUTH_REQ") ?></span>
                                </div>

                                <div class="form-group text-center text-muted content-divider">
                                    <span class="px-2"><?= GetMessage("AUTH_LOGIN_DATA") ?></span>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="text"
                                           class="form-control"
                                           placeholder="<?= GetMessage("AUTH_LOGIN_MIN") ?>"
                                           value="<?= $arResult["USER_LOGIN"] ?>"
                                           name="USER_LOGIN"
                                           maxlength="255"/>
                                    <div class="form-control-feedback">
                                        <i class="icon-user-check text-muted"></i>
                                    </div>
                                    <span class="form-text text-danger"><i class="icon-cancel-circle2 mr-2"></i> Логин уже существует</span>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <? if ($arResult["SECURE_AUTH"]): ?>
                                        <div class="bx-authform-psw-protected" id="bx_auth_secure">
                                            <div class="bx-authform-psw-protected-desc">
                                                <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                                        </div>
                                    <? endif ?>
                                    <input
                                            type="password"
                                            class="form-control"
                                            placeholder="<?= GetMessage("AUTH_PASSWORD_REQ") ?>"
                                            name="USER_PASSWORD"
                                            maxlength="255"
                                            value="<?= $arResult["USER_PASSWORD"] ?>"
                                    />
                                    <div class="form-control-feedback">
                                        <i class="icon-user-lock text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <? if ($arResult["SECURE_AUTH"]): ?>
                                        <div class="bx-authform-psw-protected" id="bx_auth_secure_conf">
                                            <div class="bx-authform-psw-protected-desc">
                                                <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                                        </div>
                                    <? endif ?>
                                    <input type="password"
                                           class="form-control"
                                           placeholder="<?= GetMessage("AUTH_CONFIRM") ?>"
                                           name="USER_CONFIRM_PASSWORD"
                                           maxlength="255" value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>"
                                           autocomplete="off"
                                    />
                                    <div class="form-control-feedback">
                                        <i class="icon-user-lock text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group text-center text-muted content-divider">
                                    <span class="px-2"><?= GetMessage("AUTH_CONTACTS") ?></span>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="email"
                                           class="form-control"
                                           placeholder="<?= GetMessage("AUTH_EMAIL") ?>"
                                           name="USER_EMAIL"
                                           maxlength="255"
                                           value="<?= $arResult["USER_EMAIL"] ?>"
                                    />
                                    <div class="form-control-feedback">
                                        <i class="icon-mention text-muted"></i>
                                    </div>
                                </div>

                                <div class="form-group form-group-feedback">
                                    <input type="text"
                                           class="form-control"
                                           placeholder="<?= GetMessage("AUTH_NAME") ?>"
                                           value="<?= $arResult["USER_NAME"] ?>"
                                           name="USER_NAME"
                                           maxlength="255"
                                    />
                                </div>

                                <div class="form-group form-group-feedback">
                                    <input type="text"
                                           class="form-control"
                                           placeholder="<?= GetMessage("AUTH_LAST_NAME") ?>"
                                           value="<?= $arResult["USER_LAST_NAME"] ?>"
                                           name="USER_LAST_NAME"
                                           maxlength="255"
                                    />
                                </div>

                                <? if ($arResult["USE_CAPTCHA"] == "Y"): ?>

                                    <div class="form-group text-center text-muted content-divider">
                                        <span class="px-2"><?= GetMessage("CAPTCHA_REGF_PROMT") ?></span>
                                    </div>

                                    <div class="form-group form-group-feedback">
                                        <div class="create_account-captcha">
                                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                            <input type="text"
                                                   class="form-control"
                                                   name="captcha_word"
                                                   maxlength="50"
                                                   value=""
                                                   autocomplete="off"
                                            />
                                            <div class="captha_text text-muted">
                                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"/>
                                            </div>
                                            <!--                            <div class="captcha_reload_icon">-->
                                            <!--                                <i class="icon-spinner11"></i>-->
                                            <!--                            </div>-->
                                        </div>
                                    </div>
                                <? endif ?>

                                <? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
                                    <? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="remember" class="form-input-styled"
                                                           data-fouc>
                                                    <?= GetMessage("AUTH_IS_ACCEPT") ?><a
                                                            href="#"><?= GetMessage("AUTH_POLITIC") ?></a>
                                                </label>
                                            </div>
                                        </div>

                                    <? endforeach; ?>
                                <? endif; ?>

                                <div class="bx-authform-input-container">
                                    <? $APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
                                        array(
                                            "ID" => COption::getOptionString("main", "new_user_agreement", ""),
                                            "IS_CHECKED" => "Y",
                                            "AUTO_SAVE" => "N",
                                            "IS_LOADED" => "Y",
                                            "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
                                            "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
                                            "INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
                                            "REPLACE" => array(
                                                "button_caption" => GetMessage("AUTH_REGISTER"),
                                                "fields" => array(
                                                    rtrim(GetMessage("AUTH_NAME"), ":"),
                                                    rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
                                                    rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
                                                    rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
                                                    rtrim(GetMessage("AUTH_EMAIL"), ":"),
                                                )
                                            ),
                                        )
                                    ); ?>
                                </div>


                                <div class="form-group form-group-feedback">
                                    <button type="submit"
                                            class="btn bg-teal-400 btn-block"><?= GetMessage("AUTH_REGISTER") ?><i
                                                class="icon-circle-right2 ml-2"></i>
                                    </button>
                                </div>

                                <div class="form-group text-center text-muted content-divider">
                                    <span class="px-2"><?= GetMessage("AUTH_IS_REGISTERED") ?></span>
                                </div>

                                <div>
                                    <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                       class="btn btn-light btn-block"><?= GetMessage("AUTH_ENTER") ?></a>
                                </div>

                            </div>
                        </div>
                    </form>
                </noindex>
                <!-- /registration form -->


                <script type="text/javascript">
                    document.bform.USER_NAME.focus();
                </script>

            <? endif ?>
        </div>


    </div>
    <!-- /content area -->


</div>
<!-- /main content -->