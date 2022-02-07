<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

Asset::getInstance()->addCss($arGadget['PATH_SITEROOT'].'/styles.css');
$idUser = intval($USER->GetID());

if(Loader::includeModule('sotbit.b2bcabinet') && $idUser > 0)
{
    $listOrders = new \Sotbit\B2BCabinet\Shop\OrderCollection();
    $listOrders->setLimit(1);

    $filter = array(
        "USER_ID" => $idUser,
        "LID" => SITE_ID,
        'PAYED' => 'N'
    );
    $orders = $listOrders->getOrders($filter);
	foreach ($orders as $order) {
    ?>
    <div class="wait_pay">
        <div class="widget_content widget_payment_waiting">
            <h5><?=Loc::getMessage('GD_SOTBIT_CABINET_BUYORDER_SUM')?> </h5>
            <h4><b><?=$order->getPrice() ?></b></h4>
        </div>
        <div class="widget_content widget_links_btns widget-payment_waiting-content">
            <div>
                <span class="payment_waiting_text"><?=Loc::getMessage('GD_SOTBIT_CABINET_BUYORDER_DATE')?></span>
                <span><?=$order->getDate()->format("d.m.Y H:i:s")?></span>
            </div>
            <div>
                <span class="payment_waiting_text"><?=Loc::getMessage('GD_SOTBIT_CABINET_BUYORDER_PERSON_TYPE')?></span>
                <span><?=$order->getPersonType()?></span>
            </div>
        </div>
        <div class="widget_button_wrapper">
            <a href="<?=$order->getUrl($arParams['G_BUYORDER_PATH_TO_ORDER_DETAIL'])?>" class="btn btn-light widget_button">
                <?=Loc::getMessage('GD_SOTBIT_CABINET_BUYORDER_BUY_ONLINE')?>
            </a>
            <?
            $pathToDownload = $order->getDownloadBillLink($arParams['G_BUYORDER_PATH_TO_PAY']);
            if($pathToDownload)
            {
                ?>
                <a href="<?=$pathToDownload?>" class="btn btn-light widget_button">
                    <?=Loc::getMessage('GD_SOTBIT_CABINET_BUYORDER_DOWNLOAD')?>
                </a>
                <?
            }?>
        </div>
    </div>
	<?
	}
}
?>
