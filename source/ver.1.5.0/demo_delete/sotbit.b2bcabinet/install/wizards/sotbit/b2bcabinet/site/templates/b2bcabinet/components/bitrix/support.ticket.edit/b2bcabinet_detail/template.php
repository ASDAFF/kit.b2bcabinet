<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$ticketExist = ( !empty($arResult["TICKET"]['ID']) && !empty($arResult['TICKET']['TITLE']) );

?>
<?=ShowError($arResult["ERROR_MESSAGE"]);?>

<div class="index_appeals">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"><?= Loc::getMessage('SUP_DETAIL_TITLE') ?></h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                        </div>
                    </div>
                </div>


                <?
                if($ticketExist) {
                    ?>
                    <div class="card-body index_appeal_form">
                        <div class="index_appeals-answer-form">
                            <div class="card-header header-elements-inline appeals_form-title_inner">
                                <h6 class="card-title">
                                    <span><?= Loc::getMessage('SUP_TICKET') ?></span>
                                </h6>
                            </div>

                            <div class="index_appeals-answer-row-information">
                                <div>

                                    <div class="row">
                                        <label class="col-lg-3 col-form-label text-muted">
                                            <?= Loc::getMessage('SUP_SOURCE_FROM') ?>
                                        </label>
                                        <div class="col-lg-9 col-form-label">
                                            <?
                                            if (strlen($arResult["TICKET"]["SOURCE_NAME"]) > 0):?>
                                                [<?= $arResult["TICKET"]["SOURCE_NAME"] ?>]
                                            <? else:?>
                                                [web]
                                            <?endif ?>

                                            <?
                                            if (strlen($arResult["TICKET"]["OWNER_SID"]) > 0):?>
                                                <?= $arResult["TICKET"]["OWNER_SID"] ?>
                                            <?endif ?>

                                            <?
                                            if (intval($arResult["TICKET"]["OWNER_USER_ID"]) > 0):?>
                                                [<?= $arResult["TICKET"]["OWNER_USER_ID"] ?>]
                                                (<?= $arResult["TICKET"]["OWNER_LOGIN"] ?>)
                                                <?= $arResult["TICKET"]["OWNER_NAME"] ?>
                                            <?endif ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-lg-3 col-form-label text-muted">
                                            <?= Loc::getMessage('SUP_CREATE') ?>
                                        </label>
                                        <div class="col-lg-9 col-form-label">
                                            <?= FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('FULL')), MakeTimeStamp($arResult["TICKET"]["DATE_CREATE"])) ?>

                                            <?
                                            if (strlen($arResult["TICKET"]["CREATED_MODULE_NAME"]) <= 0 || $arResult["TICKET"]["CREATED_MODULE_NAME"] == "support"):?>
                                                [<?= $arResult["TICKET"]["CREATED_USER_ID"] ?>]
                                                (<?= $arResult["TICKET"]["CREATED_LOGIN"] ?>)
                                                <?= $arResult["TICKET"]["CREATED_NAME"] ?>
                                            <? else:?>
                                                <?= $arResult["TICKET"]["CREATED_MODULE_NAME"] ?>
                                            <?endif ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="" class="col-lg-3 col-form-label text-muted">
                                            <?= Loc::getMessage('SUP_TIMESTAMP') ?>
                                        </label>
                                        <div class="col-lg-9 col-form-label">
                                            <?
                                            if ($arResult["TICKET"]["DATE_CREATE"] != $arResult["TICKET"]["TIMESTAMP_X"]):?>
                                                <?= FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('FULL')), MakeTimeStamp($arResult["TICKET"]["TIMESTAMP_X"])) ?>
                                                <?
                                                if (strlen($arResult["TICKET"]["MODIFIED_MODULE_NAME"]) <= 0 || $arResult["TICKET"]["MODIFIED_MODULE_NAME"] == "support"):?>
                                                    [<?= $arResult["TICKET"]["MODIFIED_USER_ID"] ?>]
                                                    (<?= $arResult["TICKET"]["MODIFIED_BY_LOGIN"] ?>)
                                                    <?= $arResult["TICKET"]["MODIFIED_BY_NAME"] ?>
                                                <? else:?>
                                                    <?= $arResult["TICKET"]["MODIFIED_MODULE_NAME"] ?>
                                                <?endif ?>
                                            <?endif ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="" class="col-lg-3 col-form-label text-muted">
                                            <?= Loc::getMessage('SUP_SLA') ?>
                                        </label>
                                        <div class="col-lg-9 col-form-label">
                                            <?
                                            if (strlen($arResult["TICKET"]["SLA_NAME"]) > 0) :?>
                                                <?= $arResult["TICKET"]["SLA_NAME"] ?>
                                            <?endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body index_appeal_form">
                        <div class="card card-bitrix-cabinet index_appeals-answer-form">
                            <div class="card-header header-elements-inline appeals-messages-title-header appeals_form-title_inner">
                                <h6 class="card-title">
                                    <span><?= Loc::getMessage('SUP_TICKET') ?></span>
                                </h6>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?= $arResult["NAV_STRING"] ?>

                                <?foreach ($arResult["MESSAGES"] as $message):
                                    $arUserGroups = CUser::GetUserGroup($message["OWNER_USER_ID"]);
                                    $rsGroups = Bitrix\Main\GroupTable::GetList(
                                        array(
                                            'filter' => array(
                                                "LOGIC" => "OR",
                                                array("STRING_ID" => "SUPPORT"),
                                                array("STRING_ID" => "SUPPORT_ADMIN")
                                            ),

                                        )
                                    )->fetchAll();
                                    $isSupport = false;
                                    if(is_array($rsGroups)) {
                                        foreach ($rsGroups as $group) {
                                            if (in_array($group['ID'], $arUserGroups)) {
                                                $isSupport = true;
                                                break;
                                            }
                                        }
                                    }
                                    ?>

                                    <div class="index_appeals-answer-row-messages <?echo ($isSupport) ? "support-answer":""?>">
                                        <div class="col-md-12 col-lg-6 <?echo ($isSupport) ? "offset-lg-6":""?> my-2">
                                            <div class="card card-bitrix-cabinet">
                                                <div class="card-header header-elements-inline appeals-messages-title-header bg-default">
                                                    <h5 class="card-title appeals-messages-title">
                                                        <?=Loc::getMessage("SUP_TIME")?> <?=FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('FULL')), MakeTimeStamp($arMessage["DATE_CREATE"]))?>
                                                    </h5>
                                                    <div class="header-elements">
                                                        <div class="list-icons">
                                                            <a class="list-icons-item"
                                                               href="#postform"
                                                               OnMouseDown="javascript:SupQuoteMessage('quotetd<? echo $message["ID"]; ?>')"
                                                            >
                                                                <i class="icon-quotes-right"></i>
                                                            </a>
                                                            <a class="list-icons-item" data-action="collapse"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        <?=Loc::getMessage('SUP_FROM')?>
                                                        <?if (intval($message["OWNER_USER_ID"])>0):?>
                                                            [<?=$message["OWNER_USER_ID"]?>]
                                                            (<?=$message["OWNER_LOGIN"]?>)
                                                            <?=$message["OWNER_NAME"]?>
                                                        <?endif?>
                                                    </div>
                                                    <div id="quotetd<? echo $message["ID"]; ?>">
                                                        <?=$message["MESSAGE"]?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?endforeach;?>

                                <?= $arResult["NAV_STRING"] ?>
                            </div>
                        </div>
                    </div>
                    <?
                }
                ?>
                    <div class="card-body index_appeal_form">
                        <form name="support_edit" id="supportForm" method="post" action="<?=$arResult["REAL_FILE_PATH"]?>" enctype="multipart/form-data">
                            <?=bitrix_sessid_post()?>
                            <input type="hidden" name="set_default" value="Y">
                            <?if(!empty($arParams["ORDER_ID"])):?>
                            <input
                                    type="hidden"
                                    name="TITLE"
                                    value="<?=(
                                            !empty($arResult['TICKET']['TITLE']) ?
                                        $arResult['TICKET']['TITLE'] :
                                        Loc::getMessage('SPOD_ORDER') .Loc::getMessage('SUP_NUM'). htmlspecialcharsbx($arParams["ORDER_ID"])
                                    )?>"
                            >
                            <?endif;?>
                            <input type="hidden" name="UF_ORDER" value="<?=$arParams['ORDER_ID']?>">
                            <input type="hidden" name="ID" value="<?=(empty($arResult["TICKET"]["ID"]) ? 0 : $arResult["TICKET"]["ID"])?>">
                            <input type="hidden" name="lang" value="<?=LANG?>">
                            <input type="hidden" name="edit" value="1">

<!--                            --><?//if(strlen($arResult['TICKET']['DATE_CLOSE'] <= 0)):?>
                                <div class="index_appeals-answer-form">
                    <?if(strlen($arResult['TICKET']['DATE_CLOSE'] <= 0)):?>
                                <div class="card-header header-elements-inline appeals_form-title_inner">
                                    <h6 class="card-title">
                                        <?=Loc::getMessage('SUP_ANSWER')?>
                                    </h6>
                                </div>
                    <?endif;?>
                                <div class="index_appeals-answer-row">
                                <?if(empty($arParams["ORDER_ID"])):?>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">
                                            <?=Loc::getMessage('SUP_TITLE')?>
                                        </label>
                                        <div class="col-lg-9">
                                            <input
                                                    type="text"
                                                    name="TITLE"
                                                    id="TITLE"
                                                    value="<?=$arResult['TICKET']['TITLE']?>"
                                                    class="form-control"
                                                    <?=( !empty($arResult['TICKET']['TITLE']) ? 'disabled' : '' )?>
                                            >
                                        </div>
                                    </div>
                                <?endif;?>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">
                                            <?=Loc::getMessage('SUP_CATEGORY')?>
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="CATEGORY_ID"
                                                    id="CATEGORY_ID"
                                                    data-placeholder="<?=Loc::getMessage('SUP_CHOOSE_OPTION')?>"
                                                <?=( isset($arResult['TICKET']['CATEGORY_ID']) && !empty($arResult['TICKET']['CATEGORY_ID']) && $ticketExist ? 'disabled' : '' )?>
                                                    class="form-control select"
                                                    data-fouc
                                            >
                                                <option value="">&nbsp;</option>
                                                <?foreach ($arResult["DICTIONARY"]["CATEGORY"] as $value => $category):?>
                                                    <option value="<?=$value?>" <?= ($value == $arResult['TICKET']['CATEGORY_ID']) ? 'selected="selected"' : '' ?>>
                                                        <?=$category?>
                                                    </option>
                                                <?endforeach?>
                                            </select>
                                        </div>
                                    </div>

                    <?if(strlen($arResult['TICKET']['DATE_CLOSE'] <= 0)):?>
                                    <div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">
                                                <?=Loc::getMessage('SUP_MESSAGE')?>
                                            </label>
                                            <div class="col-lg-9">
                                                <textarea required name="MESSAGE" id="MESSAGE" rows="5" cols="5" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">
                                                <?=Loc::getMessage('SUP_ATTACH')?>
                                            </label>
                                            <div class="col-lg-9">

                                                <div class="media-body">
                                                    <div class="upload-file">
                                                       <input type="file" name="FILE_0" size="30" class="input-file" data-fouc style="">
                                                        <span class="filename"><?=Loc::getMessage('SUP_CHOOSE_NO')?></span>
                                                        <span class="action btn btn_b2b"><?=Loc::getMessage('SUP_CHOOSE')?></span>
                                                    </div>
                                                </div>

                                                <div class="index_appeals-add_more_files">
                                                </div>

                                                <label class="add-more-files" OnClick="AddFileInput('<?=GetMessage("SUP_MORE")?>')">
                                                    <i class="icon-plus3 ml-2"></i>
                                                    <?=Loc::getMessage('SUP_MORE')?>
                                                </label>

                                                <input type="hidden" name="files_counter" id="files_counter" value="1" />
                                                <input type="hidden"
                                                       name="MAX_FILE_SIZE"
                                                       value="<?= ($arResult["OPTIONS"]["MAX_FILESIZE"] * 1024) ?>"
                                                >
                                            </div>
                                        </div>
                                    </div>
                    <?endif;?>
                                    <div>
                    <?if(strlen($arResult['TICKET']['DATE_CLOSE'] <= 0)):?>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">
                                                <?=Loc::getMessage('SUP_CRITICALITY')?>
                                            </label>
                                            <div class="col-lg-9">
                                                <?if (empty($arResult["TICKET"]) || strlen($arResult["ERROR_MESSAGE"]) > 0 )
                                                {
                                                    if (strlen($arResult["DICTIONARY"]["CRITICALITY_DEFAULT"]) > 0 && strlen($arResult["ERROR_MESSAGE"]) <= 0)
                                                        $criticality = $arResult["DICTIONARY"]["CRITICALITY_DEFAULT"];
                                                    else
                                                        $criticality = htmlspecialcharsbx($_REQUEST["CRITICALITY_ID"]);
                                                }
                                                else
                                                    $criticality = $arResult["TICKET"]["CRITICALITY_ID"];
                                                ?>
                                                <select data-placeholder="<?=Loc::getMessage('SUP_CHOOSE_OPTION')?>"
                                                        name="CRITICALITY_ID"
                                                        id="CRITICALITY_ID"
                                                        class="form-control select"
                                                        data-fouc
                                                >
                                                    <option value="">&nbsp;</option>
                                                    <?foreach ($arResult["DICTIONARY"]["CRITICALITY"] as $value => $option):?>
                                                        <option value="<?=$value?>" <?if($criticality == $value):?>selected="selected"<?endif?>><?=$option?></option>
                                                    <?endforeach?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">
                                                <?=Loc::getMessage('SUP_RATE_ANSWER')?>
                                            </label>
                                            <div class="col-lg-9">
                                                <?
                                                $mark = (strlen($arResult["ERROR_MESSAGE"]) > 0 ?
                                                    htmlspecialcharsbx($_REQUEST["MARK_ID"]) :
                                                    $arResult["TICKET"]["MARK_ID"]);
                                                ?>
                                                <select name="MARK_ID"
                                                        id="MARK_ID"
                                                        data-placeholder="<?=Loc::getMessage('SUP_CHOOSE_OPTION')?>"
                                                        class="form-control select"
                                                        data-fouc
                                                >
                                                    <option value="">&nbsp;</option>
                                                    <?foreach ($arResult["DICTIONARY"]["MARK"] as $value => $option):?>
                                                        <option value="<?=$value?>" <?if($mark == $value):?>selected="selected"<?endif?>><?=$option?></option>
                                                    <?endforeach?>
                                                </select>
                                            </div>
                                        </div>
                    <?endif;?>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label"></label>
                                            <div class="col-lg-9">
                                                <div class="form-check-label">
                                                    <label class="form-check">
                                                        <?if(!empty($arResult["TICKET"]['ID'])):?>
                                                            <?if (strlen($arResult["TICKET"]["DATE_CLOSE"]) <= 0):?>
                                                                <input type="checkbox" name="CLOSE" value="Y" class="form-input-styled" <?if($arResult["TICKET"]["CLOSE"] == "Y"):?>checked<?endif?> data-fouc>
                                                                <?=Loc::getMessage('SUP_CLOSE')?>
                                                            <?else:?>
                                                                <input type="checkbox" name="OPEN" value="Y" class="form-input-styled" <?if($arResult["TICKET"]["OPEN"] == "Y"):?>checked<?endif?> data-fouc>
                                                                <?=Loc::getMessage('SUP_OPEN')?>
                                                            <?endif;?>
                                                        <?endif;?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label"></label>
                                            <div class="col-lg-9">
                                                <input type="submit" class="btn btn_b2b" name="save" value="<?=GetMessage("SUP_SAVE")?>" />&nbsp;
                                                <input type="submit" class="btn btn_b2b apply_support_message" name="apply" value="<?=GetMessage("SUP_APPLY")?>" />&nbsp;
                                                <input type="hidden" value="Y" name="apply" />
                                            </div>
                                            <script>
                                                (function () {
                                                    window.addEventListener("DOMContentLoaded", function () {
                                                        let applyBtn = document.querySelector(".apply_support_message");

                                                        function setCookie(name, value, options = {}) {

                                                            options = {
                                                                path: '/',
                                                            };

                                                            if (options.expires !== undefined && options.expires.toUTCString) {
                                                                options.expires = options.expires.toUTCString();
                                                            }

                                                            let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

                                                            for (let optionKey in options) {
                                                                updatedCookie += "; " + optionKey;
                                                                let optionValue = options[optionKey];
                                                                if (optionValue !== true) {
                                                                    updatedCookie += "=" + optionValue;
                                                                }
                                                            }

                                                            document.cookie = updatedCookie;
                                                        }

                                                        applyBtn.addEventListener("click", function () {
                                                            setCookie("sended", "Y");
                                                        })
                                                    })
                                                })();
                                                (function () {
                                                    // window.location.href = "#basic-tab6";
                                                })();
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--                            --><?//endif;?>
                        </form>
                    </div>
                <?
//                }
                ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var file_not_selected_text = "<?=Loc::getMessage("SUP_CHOOSE_NO")?>";
    var choose_file_text = "<?=Loc::getMessage("SUP_CHOOSE")?>";

    var inputs = document.querySelectorAll('.input-file');
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
                label.innerHTML = fileName;
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