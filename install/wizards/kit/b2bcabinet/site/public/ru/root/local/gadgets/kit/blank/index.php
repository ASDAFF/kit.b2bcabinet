<?
/**
 * Copyright (c) 2017. Sergey Danilkin.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;

$methodIstall = Option::get('kit.b2bcabinet', 'method_install', '', SITE_ID) == 'AS_TEMPLATE' ? SITE_DIR.'b2bcabinet/' : SITE_DIR;
Loc::loadMessages(__FILE__);

Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');

$idUser = intval($USER->GetID());

if(Loader::includeModule('kit.b2bcabinet') && $idUser > 0)
{
	?>

    <div class="widget_content widget_blank-buttons">
        <a href="<?=$methodIstall?>orders/blank_zakaza/index.php" type="button" class="btn btn-light
        btn-ladda
        btn-ladda-spinner ladda-button ladda-button-left" data-spinner-color="#333" data-style="slide-right">
                                        <span class="ladda-label">
                                            <i class="icon-download mr-2"></i>
                                            <?=Loc::getMessage('GD_KIT_CABINET_BLANK_EXCEL_IN')?>

                                        </span>
            <span class="ladda-spinner"></span></a>
        <a href="<?=$methodIstall?>orders/blank_zakaza/index.php" type="button" class="btn btn-light btn-ladda btn-ladda-spinner ladda-button" data-spinner-color="#333" data-style="slide-right">
                                        <span class="ladda-label">
                                            <i class="icon-download mr-2"></i>
                                            <?=Loc::getMessage('GD_KIT_CABINET_BLANK_EXCEL_OUT')?>

                                        </span>
            <span class="ladda-spinner"></span></a>
    </div>
<?
}
?>
