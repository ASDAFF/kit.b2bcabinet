<?
use Bitrix\Main\Localization\Loc;

/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

$APPLICATION->SetAdditionalCSS('/bitrix/themes/.default/pubstyles.css');
?>
<div class="sotbit_cabinet">
    <?
    if(!defined("BX_GADGET_DEFAULT"))
    {
        define("BX_GADGET_DEFAULT", true);

        ?>
        <script type="text/javascript">
            var updateURL = '<?=CUtil::JSEscape(htmlspecialcharsback($arResult['UPD_URL']))?>';
            var bxsessid = '<?=CUtil::JSEscape(bitrix_sessid())?>';
            var langGDError1 = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_ERR1"))?>';
            var langGDError2 = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_ERR2"))?>';
            var langGDConfirm1 = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_CONF"))?>';
            var langGDConfirmUser = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_CONF_USER"))?>';
            var langGDConfirmGroup = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_CONF_GROUP"))?>';
            var langGDClearConfirm = '<?=CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_CLEAR_CONF"))?>';
            var langGDCancel = "<?echo CUtil::JSEscape(GetMessage("CMDESKTOP_TDEF_CANCEL"))?>";
        </script>
        <?
    }

    if($arResult["PERMISSION"] > "R")
    {
        $APPLICATION->AddHeadScript("/bitrix/components/bitrix/desktop/script.js");

        $allGD = Array();
        foreach ($arResult['ALL_GADGETS'] as $gd)
        {
            $allGD[] = Array(
                'ID' => $gd["ID"],
                'TEXT' =>
                    '<div style="text-align: left;">' . ($gd['ICON1'] ? '<img src="' . ($gd['ICON']) . '" align="left">' : '') .
                    '<b>' . (htmlspecialcharsbx($gd['NAME'])) . '</b><br>' . (htmlspecialcharsbx($gd['DESCRIPTION'])) . '</div>',
            );
        }
        ?>
        <script type="text/javascript">
            var arGDGroups = <?=CUtil::PhpToJSObject($arResult["GROUPS"])?>;
            new SCGadget('<?=$arResult["ID"]?>', <?=CUtil::PhpToJSObject($allGD)?>);
        </script>


        <div class="widgets_cabinet show_widgets">
            <div class="widget_buttons">
                <?
                foreach ($arResult['ALL_GADGETS'] as $gd)
                {
                    ?>
                    <div class="widget_button"
                         onclick="getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').Add('<?= $gd['ID'] ?>')">
                        <div class="widgets_cabinet_title">
                            <?= $gd['NAME'] ?>
                        </div>
                        <div class="widgets_cabinet_descr">
                            <?= $gd['DESCRIPTION'] ?>
                        </div>
                    </div>
                    <?
                }
                ?>
            </div>
        </div>


        <div class="sw--all_widgets">


            <div class="widget-bx-gd-buttons">
                <?php
                if($arResult["PERMISSION"]>"W")
                {
                    if ($arParams["MODE"] == "SU")
                    {
                        $mode = "'SU'";
                    }
                    elseif ($arParams["MODE"] == "SG")
                    {
                        $mode = "'SG'";
                    }
                    else
                    {
                        $mode = "";
                    }
                    ?>
                    <span class="btn btn-light" type="button" onclick="getGadgetHolderSC('<?=AddSlashes($arResult["ID"])?>').SetForAll(<?=$mode?>);"><?=Loc::getMessage('CMDESKTOP_TDEF_SET')?></span>
                    <?php
                }?>

                <span class="btn btn-light" onclick="getGadgetHolderSC('<?=AddSlashes($arResult["ID"])?>').ClearUserSettingsConfirm();"><?=Loc::getMessage('CMDESKTOP_TDEF_CLEAR')?></span>
                <span class="btn btn-light btn_b2b" onclick="getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').ShowAddGDMenu(this);"><?=Loc::getMessage('CMDESKTOP_TDEF_ADD_WIDGET')?></span>
            </div>

        </div><?
    }

    ?>
    <form action="<?= POST_FORM_ACTION_URI ?>" method="POST" id="GDHolderForm_<?= $arResult["ID"] ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="holderid" value="<?= $arResult["ID"] ?>">
        <input type="hidden" name="gid" value="0">
        <input type="hidden" name="action" value="">
    </form>

    <div class="gadgetholder" id="GDHolder_<?= $arResult["ID"] ?>">
        <?
        for ($i = 0; $i < $arResult["COLS"]; $i++)
        {
            ?>
        <div class="gd-page-column gd-page-column<?= $i ?>" id="s<?= $i ?>">
            <?
            foreach ($arResult["GADGETS"][$i] as $arGadget)
            {
                $bChangable = true;

                if(
                    !$GLOBALS["USER"]->IsAdmin()
                    && array_key_exists("GADGETS_FIXED", $arParams)
                    && is_array($arParams["GADGETS_FIXED"])
                    && in_array($arGadget["GADGET_ID"], $arParams["GADGETS_FIXED"])
                    && array_key_exists("CAN_BE_FIXED", $arGadget)
                    && $arGadget["CAN_BE_FIXED"]
                )
                    $bChangable = false;

                ?>
                <table id="t<?= $arGadget["ID"] ?>"
                       class="data-table-gadget sotbit-cabinet-gadget sotbit-cabinet-gadget-<?= strtolower($arGadget['GADGET_ID']) ?>">
                    <tr>
                        <td>
                            <div class="gdparent bx-sap card personal_widget <?= ($arGadget["HIDE"] == "Y" ? ' card-collapsed' : '') ?>">
                                <div class="gdheader card-header header-elements-inline"
                                     style="cursor:move;"
                                     onmousedown="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').DragStart('<?= $arGadget["ID"] ?>', event)">
                                    <h5 class="card-title"><?= $arGadget["TITLE"] ?></h5>
                                    <?
                                    if($arResult["PERMISSION"] > "R")
                                    {
                                        ?>

                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <?

                                                ?><a data-action="collapse" class="list-icons-item gdhide" href="javascript:void(0)" onclick="savePositionCollapse('<?=$arResult["ID"]?>');"
                                                     title="<?= GetMessage("CMDESKTOP_TDEF_HIDE") ?>"></a><?
                                                if($bChangable)
                                                {
                                                    ?><a data-action="remove" class="list-icons-item gdremove" href="javascript:void(0)"
                                                         onclick="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').Delete('<?= $arGadget["ID"] ?>');"
                                                         title="<?= GetMessage("CMDESKTOP_TDEF_DELETE") ?>"></a><?
                                                }
                                                if($bChangable && $arGadget['GADGET_ID'] !== 'WEATHER')
                                                {
                                                    ?><a class="list-icons-item gdsettings icon-pencil3 <?= ($arGadget["NOPARAMS"] ? ' gdnoparams' : '') ?>"
                                                         href="javascript:void(0)"
                                                         onclick="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').ShowSettings('<?= $arGadget["ID"] ?>');"
                                                         title="<?= GetMessage("CMDESKTOP_TDEF_SETTINGS") ?>">
                                                    </a><?
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="gdoptions" style="display:none" id="dset<?= $arGadget["ID"] ?>"></div>
                                    <div class="gdcontent"
                                         id="dgd<?= $arGadget["ID"] ?>">

                                        <?= $arGadget["CONTENT"] ?>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            <div style="display:none; border:1px #404040 dashed; margin-bottom:8px;"
                 id="d<?= $arGadget["ID"] ?>"></div><?
            }
            ?></div><?
        }
        ?>
    </div>
</div>
<script>
    if(document.querySelector(".widgets_cabinet"))
    {
        if(!document.querySelector(".body_widgets_main"))
        {
            var el = document.createElement('div');
            el.className = 'body_widgets_main';
            el.setAttribute("onclick", "showAdd();");
            document.body.appendChild(el);
        }
        document.body.classList.add("body_class");
    }
</script>