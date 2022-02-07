<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
if(!Loader::includeModule('sotbit.b2cabinet'))
{
	return false;
}

$menu = new \Sotbit\B2BCabinet\Client\Personal\Menu();
?>

<div class="col-sm-19 sm-padding-right-no blank_right-side <?=(!$menu->isOpen()) ? 'blank_right-side_full':''?>" id="blank_right_side">
	<div id="wrapper_blank_resizer" class="wrapper_blank_resizer">
		<div class="blank_resizer">
			<div class="blank_resizer_tool <?=(!$menu->isOpen()) ? 'blank_resizer_tool_open':''?>" ></div>
		</div>
		<div class="personal-right-content">
			<?if(!empty($arResult['ERRORS']['FATAL'])):?>

				<?foreach($arResult['ERRORS']['FATAL'] as $error):?>
					<?=ShowError($error)?>
				<?endforeach?>

			<?else:?>

				<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>

					<?foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
						<?=ShowError($error)?>
					<?endforeach?>

				<?endif?>
				<?
				$key_status = key($arResult['ORDER_BY_STATUS']);
				?>

				<div class="personal_list_order">
					<div class="title"><?=GetMessage("SPOL_STATUS")?></div>
					<p class="title_text"><?=$arResult["INFO"]["STATUS"][$key_status]["DESCRIPTION"] ?></p>

					<div class="main-ui-filter-search-wrapper">
						<?
						$APPLICATION->IncludeComponent('bitrix:main.ui.filter', 'ms_personal_order', [
							'FILTER_ID' => 'ORDER_LIST',
							'GRID_ID' => 'ORDER_LIST',
							'FILTER' => [
								['id' => 'ID', 'name' => GetMessage('SPOL_ORDER_FIELD_NAME_ID'), 'type' => 'string'],
								['id' => 'DATE', 'name' => GetMessage('SPOL_ORDER_FIELD_NAME_DATE'), 'type' => 'date'],
								[
									'id' => 'STATUS',
									'name' => GetMessage('SPOL_ORDER_FIELD_NAME_STATUS'),
									'type' => 'list',
									'items'  => [
										'' => GetMessage('SPOL_ORDER_FIELD_NAME_ANY'),
										'F' => GetMessage('SPOL_ORDER_FIELD_NAME_STATUS_F'),
										'N' => GetMessage('SPOL_ORDER_FIELD_NAME_STATUS_N'),
										'P' => GetMessage('SPOL_ORDER_FIELD_NAME_STATUS_P')
									],
								],
								[
									'id' => 'PAYED',
									'name' => GetMessage('SPOL_ORDER_FIELD_NAME_PAYED'),
									'type' => 'list',
									'items'  => [
										'' => GetMessage('SPOL_ORDER_FIELD_NAME_ANY'),
										'Y' => GetMessage('SPOL_ORDER_FIELD_NAME_PAYED_Y'),
										'N' => Getmessage('SPOL_ORDER_FIELD_NAME_PAYED_N')]
								],
								[
									'id' => 'BUYER',
									'name' => GetMessage('SPOL_ORDER_FIELD_NAME_BUYER'),
									'type' => 'list',
									'items'  => $arResult['BUYERS']
								],
							],
							'ENABLE_LIVE_SEARCH' => true,
							'ENABLE_LABEL' =>  true
						]);
						?>
					</div>


					<?
					$APPLICATION->IncludeComponent(
						'bitrix:main.ui.grid',
						'',
						array(
							'GRID_ID'   => 'ORDER_LIST',
							'HEADERS' => array(
								array("id"=>"ID", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_ID'), "sort"=>"ID", "default"=>true, "editable"=>false),
								array("id"=>"DATE_INSERT", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_DATE'), "sort"=>"DATE_INSERT", "default"=>true, "editable"=>false),
								array("id"=>"STATUS", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_STATUS'), "sort"=>"STATUS", "default"=>true, "editable"=>true),
								array("id"=>"FORMATED_PRICE", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_FORMATED_PRICE'), "default"=>true, "sort"=>"PRICE"),
								array("id"=>"PAYED", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_PAYED'), "sort"=>"PAYED"),
								array("id"=>"PAYMENT_METHOD", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_PAYMENT_METHOD'), "sort"=>"PAY_SYSTEM_ID"),
								array("id"=>"SHIPMENT_METHOD", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_SHIPMENT_METHOD'), "sort"=>"DELIVERY_ID"),
								array("id"=>"ITEMS", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_ITEMS')),
								array("id"=>"BUYER", "name"=>GetMessage('SPOL_ORDER_FIELD_NAME_BUYER')),
							),
							'ROWS'      => $arResult['ROWS'],
							'FILTER_STATUS_NAME' => $arResult['FILTER_STATUS_NAME'],
							'AJAX_MODE'           => 'Y',

							"AJAX_OPTION_JUMP"    => "N",
							"AJAX_OPTION_STYLE"   => "N",
							"AJAX_OPTION_HISTORY" => "N",

							"ALLOW_COLUMNS_SORT"      => true,
							"ALLOW_ROWS_SORT"         => $arParams['ALLOW_COLUMNS_SORT'],
							"ALLOW_COLUMNS_RESIZE"    => true,
							"ALLOW_HORIZONTAL_SCROLL" => true,
							"ALLOW_SORT"              => true,
							"ALLOW_PIN_HEADER"        => true,
							"ACTION_PANEL"            => $arResult['GROUP_ACTIONS'],

							"SHOW_CHECK_ALL_CHECKBOXES" => false,
							"SHOW_ROW_CHECKBOXES"       => false,
							"SHOW_ROW_ACTIONS_MENU"     => true,
							"SHOW_GRID_SETTINGS_MENU"   => true,
							"SHOW_NAVIGATION_PANEL"     => true,
							"SHOW_PAGINATION"           => true,
							"SHOW_SELECTED_COUNTER"     => false,
							"SHOW_TOTAL_COUNTER"        => true,
							"SHOW_PAGESIZE"             => true,
							"SHOW_ACTION_PANEL"         => true,

							"ENABLE_COLLAPSIBLE_ROWS" => true,
							'ALLOW_SAVE_ROWS_STATE'=>true,

							"SHOW_MORE_BUTTON" => false,
							'~NAV_PARAMS'       => $arResult['GET_LIST_PARAMS']['NAV_PARAMS'],
							'NAV_OBJECT'       => $arResult['NAV_OBJECT'],
							'NAV_STRING'       => $arResult['NAV_STRING'],
							"TOTAL_ROWS_COUNT"  => count($arResult['ROWS']),
							"CURRENT_PAGE" => $arResult[ 'CURRENT_PAGE' ],
							"PAGE_SIZES" => $arParams['ORDERS_PER_PAGE'],
							"DEFAULT_PAGE_SIZE" => 50
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);
					?>
				</div>
			<?endif?>
		</div>
	</div>
</div>
<style>
	.main-grid-wrapper
	{
		padding: 5px;
	}
	.nicescroll-rails-hr
	{
		position: relative;
	}
</style>
<script>
	$('.main-grid-container').niceScroll({emulatetouch: true, bouncescroll: false, cursoropacitymin: 1, enabletranslate3d: true, cursorfixedheight: '100', scrollspeed: 25, mousescrollstep: 10,  cursorwidth: '8px', horizrailenabled: true, cursordragontouch: true});

</script>