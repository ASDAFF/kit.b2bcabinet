<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
?>
<form name="bform" method="post" target="_top" class="login-form" action="<?=$arResult["AUTH_URL"]?>">
    <?if($arResult["BACKURL"] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?
    if(!empty($arParams["~AUTH_RESULT"])):
        $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
        ?>
        <div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
    <?endif?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="SEND_PWD">
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0"><?=GetMessage('AUTH_SEND_PASS')?></h5>
                <span class="d-block text-muted"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="email" name="USER_EMAIL" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" placeholder="E-Mail" autocomplete="off" required/>
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>
    
            <?if ($arResult["USE_CAPTCHA"]):?>
                
                <div class="text-center mb-3">
                    <span class="d-block text-muted">
                        <?= GetMessage("system_auth_captcha")?>
                        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                    </span>
                </div>
    
                <div class="password_recovery-captcha">
    
                    <div class="form-group form-group-feedback form-group-feedback-right password_recovery-captcha_input">
                        <input type="text" class="form-control" name="captcha_word" maxlength="50" value="" autocomplete="off" required>
                        
                    </div>
    
                    <div class="form-group form-group-feedback form-group-feedback-right password_recovery-captcha_image">
                        <div class="captha_text"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="85" height="35" alt="CAPTCHA" /></div>
                    </div>
    
                </div>
            
            <?endif;?>

            <button type="submit" class="btn bg-blue btn-block"><i class="icon-spinner11 mr-2"></i><?=GetMessage("AUTH_SEND")?></button>
        </div>
    </div>
</form>