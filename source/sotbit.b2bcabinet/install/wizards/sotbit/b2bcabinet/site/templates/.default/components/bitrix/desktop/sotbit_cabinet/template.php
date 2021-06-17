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
				<span class="default-settings" onclick="getGadgetHolderSC('<?=AddSlashes($arResult["ID"])?>').SetForAll(<?=$mode?>);"><?=Loc::getMessage('CMDESKTOP_TDEF_SET')?></span>
				<?php
			}?>
				
				<span class="clear-settings" onclick="getGadgetHolderSC('<?=AddSlashes($arResult["ID"])?>').ClearUserSettingsConfirm();"><?=Loc::getMessage('CMDESKTOP_TDEF_CLEAR')?></span>
				<span class="add-settings" onclick="getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').ShowAddGDMenu(this);"><?=Loc::getMessage('CMDESKTOP_TDEF_ADD_WIDGET')?></span>
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
					   class="data-table-gadget<?= ($arGadget["HIDE"] == "Y" ? ' gdhided' : '') ?> sotbit-cabinet-gadget sotbit-cabinet-gadget-<?= strtolower($arGadget['GADGET_ID']) ?>">
					<tr>
						<td>
							<div class="gdparent">
								<?
								if($arResult["PERMISSION"] > "R")
								{
								?>
								<div class="gdheader" style="cursor:move;"
									 onmousedown="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').DragStart('<?= $arGadget["ID"] ?>', event)">
									<?
									if($bChangable)
									{
										?><a class="gdremove" href="javascript:void(0)"
											 onclick="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').Delete('<?= $arGadget["ID"] ?>');"
											 title="<?= GetMessage("CMDESKTOP_TDEF_DELETE") ?>"></a><?
									}
									?><a class="gdhide" href="javascript:void(0)"
										 onclick="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').Hide('<?= $arGadget["ID"] ?>', this);"
										 title="<?= GetMessage("CMDESKTOP_TDEF_HIDE") ?>"></a><?
									if($bChangable)
									{
										?><a class="gdsettings<?= ($arGadget["NOPARAMS"] ? ' gdnoparams' : '') ?>"
											 href="javascript:void(0)"
											 onclick="return getGadgetHolderSC('<?= AddSlashes($arResult["ID"]) ?>').ShowSettings('<?= $arGadget["ID"] ?>');"
											 title="<?= GetMessage("CMDESKTOP_TDEF_SETTINGS") ?>"></a><?
									}
									}
									else
									{
									?>
									<div class="gdheader"><?
										}
										?>
										<?= $arGadget["TITLE"] ?>
									</div>
									<div class="gdoptions" style="display:none" id="dset<?= $arGadget["ID"] ?>"></div>
									<div class="gdcontent"
										 id="dgd<?= $arGadget["ID"] ?>"><?= $arGadget["CONTENT"] ?></div>
									<div style="position:relative;"></div>
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