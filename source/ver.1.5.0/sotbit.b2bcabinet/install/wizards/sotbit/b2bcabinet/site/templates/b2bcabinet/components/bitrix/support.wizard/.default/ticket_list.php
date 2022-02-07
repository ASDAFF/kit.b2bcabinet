<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!Loader::includeModule('sotbit.b2bcabinet'))
{
	return false;
}

Loc::loadMessages(__FILE__);

$filter = [];
$filterOption = new Bitrix\Main\UI\Filter\Options('TICKET_LIST');
$filterData = $filterOption->getFilter([]);


unset($_SESSION['main.interface.grid']);
foreach ($filterData as $key => $value)
{
	if(in_array($key, [
		'ID',
		'MESSAGE',
		'CLOSE',
		'LAMP',
        'FIND'
	]))
		$_REQUEST[$key] = $value;
}

?>
<div class="col-sm-19 sm-padding-right-no blank_right-side">
		<div class="personal-right-content">
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:support.ticket.list",
				"",
				[
					"TICKET_EDIT_TEMPLATE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["ticket_edit"],
					"TICKETS_PER_PAGE" => $arParams["TICKETS_PER_PAGE"],
					"SET_PAGE_TITLE" => $arParams["SET_PAGE_TITLE"],
					"TICKET_ID_VARIABLE" => $arResult["ALIASES"]["ID"],
					"SITE_ID" => $arParams["SITE_ID"],
					"SET_SHOW_USER_FIELD" => $arParams["SET_SHOW_USER_FIELD"],
					"AJAX_ID" => $arParams["AJAX_ID"]
				],
				$component,
				['HIDE_ICONS' => 'Y']
			);
			$APPLICATION->SetTitle(Loc::getMessage('TITLE'),false);
			?>
		</div>
</div>