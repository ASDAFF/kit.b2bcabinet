<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
$idUser = intval($USER->GetID());

if (Loader::includeModule('kit.b2bcabinet') && $idUser > 0)
{?>
    <div class="widget_content widget_links personal_info">
        <?
        $user = new \Kit\B2bCabinet\Personal\User($idUser);
        $avatar = $user->genAvatar(array(
            'width' => 85,
            'height' => 85,
            'resize' => BX_RESIZE_IMAGE_EXACT
        ));
        ?>
        <div class="image_photo">
            <?
            if ($avatar['src'])
            {
                ?>
                <img src="<?= $avatar['src'] ?>" width="<?= $avatar['width'] ?>"
                     height="<?= $avatar['height'] ?>">
                <?
            }
            ?>
        </div>
        <div class="personal_information">
            <span class="display_block"><?php echo $user->getFIO(); ?></span>
            <?php
            if (strlen($user->getEmail()) > 0)
            {
            ?>
                <span class="email">
                    <?php echo Loc::getMessage('GD_KIT_CABINET_EMAIL'); ?>
                </span>
                <span>
                    <?php echo $user->getEmail(); ?>
                </span>
            <?
            }
            if (strlen($user->getPersonalPhone()) > 0)
            {
            ?>
                <span class="phone">
                    <?php echo Loc::getMessage('GD_KIT_CABINET_PHONE'); ?>
                </span>
                <span>
                    <?=$user->getPersonalPhone()?>
                </span>
            <?
            } ?>
        </div>
    </div>
	<?php
}
?>
