<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<div class="card card-bitrix-cabinet">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><?=Loc::getMessage("SUP_TICKET")?></h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                </div>
            </div>
    </div>
    <?if($arResult['SUPPORT_PAGE']):?>
        <div class="b2b_detail_order__second__tab b2b_detail_order__second__tab--absolute">
            <a href="<?=$APPLICATION->GetCurDir()?>" class="b2b_detail_order__second__tab__backlink">
                <?= Loc::getMessage('SUP_LIST') ?>
            </a>
        </div>
    <?endif;?>
    <div class="card-body">
        <?if(!empty($arResult["ERROR_MESSAGE"])):?>
            <div class="bitrix-error">
                <label class="validation-invalid-label">
                    <?=ShowError($arResult["ERROR_MESSAGE"]); ?>
                </label>
            </div>
        <?endif;?>
            <div class="form-group form-group-float">
                <label>
                    <?=Loc::getMessage('SUP_MESSAGE')?>
                    <span>*</span>
                </label>
                <form name="support_edit" method="post" id="supportForm" action="<?= $arResult["REAL_FILE_PATH"] ?>"
                      enctype="multipart/form-data">
                    <?= bitrix_sessid_post() ?>
                    <input type="hidden" name="set_default" value="Y"/>
                    <input type="hidden" name="ID"
                           value=<?= (empty($arResult["TICKET"]['ID']) ? 0 : $arResult["TICKET"]["ID"]) ?>/>
                    <input type="hidden" name="edit" value="1">
                    <input type="hidden" name="lang" value="<?= LANG ?>"/>
                    <? if(!$arResult["TICKET"]["DATE_CLOSE"])
                    {
                        ?>
                        <div class="support-form-inner">
                            <div class="support-form__title">
                                <?= (empty($arResult["TICKET"]['ID'])) ? "" : Loc::getMessage("SUP_ANSWER") ?>
                            </div>
                            <?
                            if(empty($arResult["TICKET"]['ID']))
                            {
                                if($arResult['SUPPORT_PAGE'])
                                {
                                    ?>
                                    <div class="support-form__row">
                                        <div class="support-form__row__left">
                                            <label for="TITLE">
                                                <?= Loc::getMessage("SUP_TITLE") ?>
                                            </label>
                                        </div>
                                        <div class="support-form__row__right">
                                            <input type="text" name="TITLE" id="TITLE"
                                                   value="<?= htmlspecialcharsbx($_REQUEST["TITLE"]) ?>" size="48"
                                                   maxlength="255"/>
                                        </div>
                                    </div>
                                    <div class="support-form__row">
                                        <div class="support-form__row__left">
                                            <label for="CATEGORY_ID">
                                                <?= Loc::getMessage("SUP_CATEGORY") ?>
                                            </label>
                                        </div>
                                        <div class="support-form__row__right">
                                            <select name="CATEGORY_ID" id="CATEGORY_ID">
                                                <option value="">&nbsp;</option>
                                                <? foreach ($arResult["DICTIONARY"]["CATEGORY"] as $value => $option)
                                                {
                                                    ?>
                                                    <option value="<?= $value ?>" <?= ($category == $value) ? 'selected="selected"' : '' ?>>
                                                        <?= $option ?>
                                                    </option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <input type="hidden" name="TITLE" value="<?=Loc::getMessage('SUP_ORDER',['#ORDER_ID#' =>
                                        $arParams['ORDER_ID']])?>">
                                    <input type="hidden" name="UF_ORDER" value="<?=$arParams['ORDER_ID']?>">
                                    <?
                                }
                            }
                            if(!$arResult['SUPPORT_PAGE'])
                            {
                                ?>
                                <input type="hidden" name="CATEGORY_ID" value="<?=$arResult['ORDER_CATEGORY']?>">
                                <?
                            }
                            ?>
                            <div class="support-form__row">
                                <div class="support-form__row__right">
                                        <textarea name="MESSAGE" id="MESSAGE" rows="5" class="form-control"><?= htmlspecialcharsbx
                                            ($_REQUEST["MESSAGE"]) ?></textarea>
                                </div>
                            </div>
                            <div class="support-form__row">
                                <div class="media-body">
                                    <label>
                                        <?= Loc::getMessage("SUP_ATTACH") ?>
                                    </label>
<!--                                    <div class="uniform-uploader">-->
                                        <input name="FILE_0" id="FILE_0" type="file" multiple
                                               class="form-input-styled"/>
<!--                                        <label for="FILE_0">--><?//= Loc::getMessage("SUP_CHOOSE") ?><!--</label>-->
<!--                                        <span class="filename" style="user-select: none;">--><?//= Loc::getMessage("SUP_CHOOSE_NO") ?><!--</span>-->
<!--                                        <span class="action btn bg-pink-400" style="user-select: none;">--><?//= Loc::getMessage("SUP_CHOOSE_FILE") ?><!--</span>-->
<!--                                    </div>-->
                                   <!-- <span id="files_table_3"></span>
                                    <div class="blank_detail-add_more_files">
                                        <label OnClick="AddFileInput(
                                                       '<?/*= Loc::getMessage("SUP_MORE") */?>',
                                                       '<?/*= Loc::getMessage("SUP_CHOOSE") */?>',
                                                '<?/*= Loc::getMessage("SUP_CHOOSE_NO") */?>')">
                                            <i class="icon-plus3 ml-2"></i>
                                            <?/*= Loc::getMessage("SUP_MORE")*/?>
                                        </label>
-->
                                        <input type="hidden" name="files_counter" id="files_counter" value="2"/>
                                    <!--</div>-->
                                </div>
                            </div>
                            <div class="text-left blank_detail-support_button">
                                <div class="support-form__row__left">
                                </div>
                                <div class="support-form__row__right">
                                    <button class="btn btn-primary" type="submit" name="apply" value="<?= Loc::getMessage("SUP_APPLY") ?>">
                                        <?= Loc::getMessage("SUP_APPLY") ?>
                                        <i class="icon-paperplane ml-2"></i>
                                    </button>
                                    <input type="hidden" value="Y" name="apply"/>
                                </div>
                            </div>
                        </div>
                        <?
                        if(!empty($arResult["TICKET"]['ID']))
                        {
                            ?>
                            <input type="hidden" name="CLOSE" id="CLOSE" value="N">
                        <? }
                    }
                    else
                    {
                        ?>
                        <input type="submit" name="apply" value="<?= Loc::getMessage("SUP_OPEN") ?>"
                               class="support-form__open">
                        <input type="hidden" name="OPEN" value="Y">
                        <?
                    }
                    ?>

                <script type="text/javascript">
                    var inputs = document.querySelectorAll('.form-input-styled');
                    Array.prototype.forEach.call(inputs, function (input)
                    {
                        var label = input.nextElementSibling,
                            labelVal = label.innerHTML;

                        input.addEventListener('change', function (e)
                        {
                            var fileName = '';
                            if (this.files && this.files.length > 1)
                                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                            else
                                fileName = e.target.value.split('\\').pop();

                            if (fileName)
                            {
                                label.nextElementSibling.innerHTML = fileName;
                            }
                        });
                    });
                    BX.ready(function ()
                    {
                        var buttons = BX.findChildren(document.forms['support_edit'], {attr: {type: 'submit'}});
                        for (i in buttons)
                        {
                            BX.bind(buttons[i], "click", function (e)
                            {
                                setTimeout(function ()
                                {
                                    var _buttons = BX.findChildren(document.forms['support_edit'], {attr: {type: 'submit'}});
                                    for (j in _buttons)
                                    {
                                        _buttons[j].disabled = true;
                                    }

                                }, 30);
                            });
                        }
                    });
                </script>
            </form>
        </div>
    </div>
</div>