<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\UI;
use Bitrix\Main\Config\Option;

UI\Extension::load("ui.tooltip");
$methodIstall = Option::get('kit.b2bcabinet', 'method_install', '', SITE_ID) == 'AS_TEMPLATE' ? SITE_DIR.'b2bcabinet/' : SITE_DIR;
?>
<div class="sidebar-user">
    <?
    if(strlen($arResult["FatalError"])>0)
    {
        ?><span class='errortext'><?=$arResult["FatalError"]?></span><br /><br /><?
    }
    else
    {
        $anchor_id = RandString(8);

        if ($arParams["INLINE"] != "Y")
        {

            $tooltipUserId = (
                strlen($arResult["User"]["DETAIL_URL"]) > 0
                && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"]
                && (
                    !array_key_exists("USE_TOOLTIP", $arResult)
                    || $arResult["USE_TOOLTIP"]
                )
                    ? $arResult["User"]["ID"]
                    : ''
            );

            if (strlen($arResult["User"]["DETAIL_URL"]) > 0 && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"]) {
                ?><div class="sidebar-user bx-user-info-anchor" id="anchor_<?=$anchor_id?>" bx-tooltip-user-id="<?=$tooltipUserId?>"><?
            } else {
                ?><div class="sidebar-user bx-user-info-anchor-nolink" id="anchor_<?=$anchor_id?>" bx-tooltip-user-id="<?=$tooltipUserId?>"><?
            }
            ?><div class="card-body">
                <div class="media"><?
            if ($arParams["USE_THUMBNAIL_LIST"] == "Y")
            {
                ?><div class="mr-3 bx-user-info-anchor-cell"><?
                if (strlen($arResult["User"]["HREF"]) > 0) {
                    ?><a href="<?=$arResult["User"]["HREF"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?>><?=$arResult["User"]["PersonalPhotoImgThumbnail"]["Image"]?></a><?
                } elseif ( strlen($arResult["User"]["DETAIL_URL"]) > 0 && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"] ) {
                    ?><a href="<?=$arResult["User"]["DETAIL_URL"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?>><?=$arResult["User"]["PersonalPhotoImgThumbnail"]["Image"]?></a><?
                } else {
                    ?><?=$arResult["User"]["PersonalPhotoImgThumbnail"]["Image"]?><?
                }
                    ?></div><?
            }
            ?><div class="media-body font-weight-semibold bx-user-info-anchor-cell" valign="top"><?
            if (strlen($arResult["User"]["HREF"]) > 0) {
                ?><a class="bx-user-info-name" href="<?=$arResult["User"]["HREF"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?>><?=$arResult["User"]["NAME_FORMATTED"]?></a><?
            } elseif ( strlen($arResult["User"]["DETAIL_URL"]) > 0 && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"] ) {
                ?><a class="bx-user-info-name" href="<?=$arResult["User"]["DETAIL_URL"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?>><?=$arResult["User"]["NAME_FORMATTED"]?></a><?
            } else {
                ?><div class="bx-user-info-name"><?=$arResult["User"]["NAME_FORMATTED"]?></div><?
            }
            ?><?=(strlen($arResult["User"]["NAME_DESCRIPTION"]) > 0 ? " (".$arResult["User"]["NAME_DESCRIPTION"].")": "")?><?
            if ($arResult["bSocialNetwork"])
            {
                if (strlen($arResult["User"]["HREF"]) > 0) {
                    $link = $arResult["User"]["HREF"];
                } elseif ( strlen($arResult["User"]["DETAIL_URL"]) > 0 && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"]) {
                    $link = $arResult["User"]["DETAIL_URL"];
                } else {
                    $link = false;
                }
                ?>
                <?
            }
            ?>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-pin font-size-sm"></i> &nbsp;������
                        </div>
                        </div>
                    <div class="ml-3 align-self-center">
                        <a href="<?=$methodIstall?>personal/index.php" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                    </div>
                </div>
        </div><?
        }
        else
        {
            if ( strlen($arResult["User"]["DETAIL_URL"]) > 0 && $arResult["CurrentUserPerms"]["Operations"]["viewprofile"] ) {
                ?><a href="<?=$arResult["User"]["DETAIL_URL"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?> id="anchor_<?=$anchor_id?>" bx-tooltip-user-id="<?=$arResult["User"]["ID"]?>"><?=$arResult["User"]["NAME_FORMATTED"]?></a><?
            } elseif (strlen($arResult["User"]["DETAIL_URL"]) > 0 && !$arResult["bSocialNetwork"]) {
                ?><a href="<?=$arResult["User"]["DETAIL_URL"]?>"<?=($arParams["SEO_USER"] == "Y" ? ' rel="nofollow"' : '')?> id="anchor_<?=$anchor_id?>"><?=$arResult["User"]["NAME_FORMATTED"]?></a><?
            } else {
                ?><?=$arResult["User"]["NAME_FORMATTED"]?><?
            }
            ?><?=(strlen($arResult["User"]["NAME_DESCRIPTION"]) > 0 ? " (".$arResult["User"]["NAME_DESCRIPTION"].")": "")?><?
        }
    }
    ?>
</div>
