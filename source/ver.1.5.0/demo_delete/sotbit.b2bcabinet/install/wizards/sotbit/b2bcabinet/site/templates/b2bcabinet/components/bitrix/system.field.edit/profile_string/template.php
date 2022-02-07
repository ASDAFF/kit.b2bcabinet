<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!is_array($arParams['ATTR']))
    $arParams['ATTR'] = array();
?>
<div class="form-group row">
        <?if(!empty($arParams['LABEL'])):?>
            <label class="col-lg-3 col-form-label">
                <?=$arParams['LABEL']?>
                <?if(in_array('required', $arParams['ATTR'])) {
                    echo '<span class="req">*</span>';
                }?>
            </label>
        <?endif;?>
    <div class="col-lg-9">
        <?
        ( empty($arParams['CLASS']) ? $arParams['CLASS'] = 'form-control' : $arParams['CLASS'] .= ' form-control' );

        if( is_array($arParams['ELEMENTS']) ) {
            foreach ($arParams['ELEMENTS'] as $element) {
                $APPLICATION->IncludeComponent(
                    "bitrix:system.field.edit",
                    $arParams['TEMPLATE'],
                    $element,
                    null,
                    array("HIDE_ICONS"=>"Y")
                );
            }
        } else {
            $arParams['arUserField']['USER_TYPE'] = 'Y';
            $APPLICATION->IncludeComponent(
                "bitrix:system.field.edit",
                $arParams['TEMPLATE'],
                $arParams,
                null,
                array("HIDE_ICONS"=>"Y")
            );
        }

        if(!empty($arParams['NOTES'])) {
            echo '<span class="form-text text-muted">'. $arParams["NOTES"] .'</span>';
        }
        if($arParams['SECURE_AUTH'] == 'Y') {
            echo '<span class="bx-auth-secure" 
                        id="bx_auth_secure" 
                        style="display:none"
                        title="Secure mode enabled"
                  >
                  <div class="bx-auth-secure-icon"></div>
                </span>
                <noscript>
                    <span class="bx-auth-secure" title="Secure mode enabled, but js disabled">
                        <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                    </span>
                </noscript>
                <script type="text/javascript">
                    document.getElementById("bx_auth_secure").style.display = "inline-block";
                </script>';
        }
        ?>
    </div>
</div>