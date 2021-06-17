<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}


/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>

<div class="bx-authform">
    <form class="login-form" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />

        <?if (strlen($arResult["BACKURL"]) > 0):?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?endif?>

        <?foreach ($arResult["POST"] as $key => $value):?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>

        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                    <h5 class="mb-0"><?=GetMessage("AUTH_TITLE")?></h5>
                    <span class="d-block text-muted"><?=GetMessage("AUTH_TITLE_DATA")?></span>
                </div>

                <? if(!empty($arParams["~AUTH_RESULT"])):
                    $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
                ?>
                    <div class="bitrix-error">
                        <label class="validation-invalid-label"><?=nl2br(htmlspecialcharsbx($text))?></label>
                    </div>
                <?endif?>

                <? if($arResult['ERROR_MESSAGE'] <> ''):
                    $text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
                ?>
                    <div class="bitrix-error">
                        <label class="validation-invalid-label"><?=nl2br(htmlspecialcharsbx($text))?></label>
                    </div>
                <?endif?>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="text" class="form-control" maxlength="255" name="USER_LOGIN" placeholder="<?=GetMessage("AUTH_LOGIN")?>">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" class="form-control" name="USER_PASSWORD" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" maxlength="255" autocomplete="off">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <?if($arResult["CAPTCHA_CODE"]):?>
                    <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
                        <div class="bx-captcha">
                            <input class="form-control" type="text" name="captcha_word" maxlength="50" value="" autocomplete="off" placeholder="<?=GetMessage("AUTH_CAPTCHA_PROMT")?>" />
                        </div>
                    </div>
                <?endif;?>

                <div class="form-group d-flex align-items-center">
                    <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                        <div class="form-check mb-0">
                            <label class="form-check-label">
                                <input type="checkbox" name="USER_REMEMBER" class="form-input-styled" value="Y" checked>
                                <?=GetMessage("AUTH_REMEMBER_ME")?>
                            </label>
                        </div>
                    <?endif?>

                    <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" class="ml-auto"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"><?=GetMessage("AUTH_AUTHORIZE")?> <i class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="form-group text-center text-muted content-divider">
                    <span class="px-2"><?=GetMessage("AUTH_NOT_REGISTER")?></span>
                </div>

                <div class="form-group">
                    <a href="<?=$arResult["AUTH_REGISTER_URL"]?>" class="btn btn-light btn-block"><?=GetMessage("AUTH_REGISTER")?></a>
                </div>

                <?/*?><span class="form-text text-center text-muted"><?=GetMessage("AUTH_OBLIGATION_NOTE")?></span><?*/?>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

