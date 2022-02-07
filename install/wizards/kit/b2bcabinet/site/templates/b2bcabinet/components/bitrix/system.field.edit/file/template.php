<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

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
        <div class="media mt-0">
            <div class="mr-3">
                    <?if(!empty($arParams['VALUE'])):?>
                        <?=CFile::ShowImage(
                            $arParams['VALUE'],
                            60,
                            60,
                            'class="rounded-round"'
                        )?>
                    <?else:?>
                        <img src="/local/templates/b2bcabinet/assets/images/placeholders/user.png"
                             width="60"
                             height="60"
                             class="rounded-round"
                             alt="">
                    <?endif;?>
            </div>

            <div class="media-body">
                <input
                    <?=(!empty($arParams['NAME']) ? "name='".$arParams['NAME']."'" : "")?>
                    type="file"
                    class="form-input-styled"
                    <?if(is_array($arParams['ATTR']) && !empty($arParams['ATTR'])) {
                        foreach ($arParams['ATTR'] as $attr) {
                            if(!empty($attr))
                                echo $attr ." ";
                        }
                    }?>
                >
                <?
                if(!empty($arParams['NOTES'])) {
                    echo '<span class="form-text text-muted">'. $arParams["NOTES"] .'</span>';
                }
                ?>
            </div>
        </div>
    </div>
</div>