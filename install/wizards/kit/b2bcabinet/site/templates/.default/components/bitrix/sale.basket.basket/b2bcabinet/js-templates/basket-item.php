<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */

$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);
$usePicture = in_array("PREVIEW_PICTURE", $arParams['COLUMNS_LIST']);
$useProps = in_array("PROPS", $arParams['COLUMNS_LIST']);


$columsProp = count(array_merge($arResult['headers']['sku_data'], $arResult['headers']['column_list']));
if($useProps) {
    $columsProp += count($arResult['headers']['props']);
}
$restoreColSpan = 2 + $columsProp + $usePicture + $usePriceInAdditionalColumn + $useSumColumn +
$useActionColumn;

?>
<script id="basket-item-template" type="text/html">
	<tr class="{{#SHOW_RESTORE}} basket-items-list-item-container-expend{{/SHOW_RESTORE}}"
		id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">
		{{#SHOW_RESTORE}}
			<td class="basket-items-list-item-notification" colspan="<?=$restoreColSpan?>">
				<div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">
					{{#SHOW_LOADING}}
						<div class="basket-items-list-item-overlay"></div>
					{{/SHOW_LOADING}}
					<div class="basket-items-list-item-removed-container">
						<div>
							<?=Loc::getMessage('SBB_GOOD_CAP')?> <strong>{{NAME}}</strong> <?=Loc::getMessage('SBB_BASKET_ITEM_DELETED')?>.
						</div>
						<div class="basket-items-list-item-removed-block">
							<a href="javascript:void(0)" data-entity="basket-item-restore-button">
								<?=Loc::getMessage('SBB_BASKET_ITEM_RESTORE')?>
							</a>
							<span class="basket-items-list-item-clear-btn" data-entity="basket-item-close-restore-button"></span>
						</div>
					</div>
				</div>
			</td>
		{{/SHOW_RESTORE}}
		{{^SHOW_RESTORE}}

				<!--<div id="basket-item-height-aligner-{{ID}}">-->
					<?
					if (in_array('PREVIEW_PICTURE', $arParams['COLUMNS_LIST']))
					{
						?>
                        <td>
                            <div class="<?=(!isset($mobileColumns['PREVIEW_PICTURE']) ? ' hidden-xs' : '')?>">
                                <!--{{#DETAIL_PAGE_URL}}
                                    <a href="{{DETAIL_PAGE_URL}}">
                                {{/DETAIL_PAGE_URL}}-->

                                <img class="img-responsive"
                                     width="<?=$arParams['IMAGE_SIZE_PREVIEW']?>" height="auto"
                                     alt="{{NAME}}"
                                    src="{{{IMAGE_URL}}}{{^IMAGE_URL}}<?=$templateFolder?>/images/no_photo.png{{/IMAGE_URL}}">

                                <!--{{#DETAIL_PAGE_URL}}
                                    </a>
                                {{/DETAIL_PAGE_URL}}-->
                            </div>
                        </td>
						<?
					}
					?>
                    <td>
                        <!--{{#DETAIL_PAGE_URL}}
                            <a href="{{DETAIL_PAGE_URL}}">
                        {{/DETAIL_PAGE_URL}}-->

                        <span class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                            {{NAME}}
                        </span>

                        <!--{{#DETAIL_PAGE_URL}}
                            </a>
                        {{/DETAIL_PAGE_URL}}-->

                        {{#NOT_AVAILABLE}}
                            <div class="alert-warning">
                                <?=Loc::getMessage('SBB_BASKET_ITEM_NOT_AVAILABLE')?>.
                            </div>
                        {{/NOT_AVAILABLE}}
                        {{#DELAYED}}
                            <div class="alert-warning">
                                <?=Loc::getMessage('SBB_BASKET_ITEM_DELAYED')?>.
                                <a href="javascript:void(0)" data-entity="basket-item-remove-delayed">
                                    <?=Loc::getMessage('SBB_BASKET_ITEM_REMOVE_DELAYED')?>
                                </a>
                            </div>
                        {{/DELAYED}}
                        {{#WARNINGS.length}}
                            <div class="alert-warning" data-entity="basket-item-warning-node">
                                <span class="close" data-entity="basket-item-warning-close">&times;</span>
                                {{#WARNINGS}}
                                <div data-entity="basket-item-warning-text">{{{.}}}</div>
                                {{/WARNINGS}}
                            </div>
                        {{/WARNINGS.length}}

                    </td>

                            <?
                            if (!empty($arParams['PRODUCT_BLOCKS_ORDER']))
                            {
                                foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
                                {
                                    switch (trim((string)$blockName))
                                    {
                                        case 'props':
                                            if (in_array('PROPS', $arParams['COLUMNS_LIST']))
                                            {
                                                ?>
                                                {{#PROPS}}
                                                <td class="<?=(!isset($mobileColumns['PROPS']) ? ' hidden-xs' : '')?>">
                                                    <span class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                        {{{VALUE}}}
                                                    </span>
                                                </td>
                                                {{/PROPS}}
                                                <?
                                            }

                                            break;
                                        case 'sku':
                                            ?>

                                            {{#SKU_BLOCK_LIST}}
                                            <td>
                                                {{#IS_IMAGE}}
                                                    {{#SKU_VALUES_LIST}}
                                                    {{#SELECTED}}
                                                        <span style="background-image: url({{PICT}});"></span>
                                                    {{/SELECTED}}
                                                    {{/SKU_VALUES_LIST}}
                                                {{/IS_IMAGE}}

                                                {{^IS_IMAGE}}
                                                    {{#SKU_VALUES_LIST}}
                                                    {{#SELECTED}}
                                                <span class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                        {{NAME}}
                                                </span>
                                                    {{/SELECTED}}
                                                    {{/SKU_VALUES_LIST}}
                                                {{/IS_IMAGE}}
                                            </td>
                                            {{/SKU_BLOCK_LIST}}
                                            <?
                                            break;
                                        case 'columns':
                                            ?>
                                            {{#COLUMN_LIST}}
                                            <td>
                                                {{#IS_IMAGE}}
                                                    <div class="{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}} {{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}"
                                                        data-entity="basket-item-property">
                                                            {{#VALUE}}
                                                                <span class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                                    <img class="img-responsive mr-2"
                                                                        src="{{{IMAGE_SRC}}}" data-image-index="{{INDEX}}"
                                                                        data-column-property-code="{{CODE}}">
                                                                </span>
                                                            {{/VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_IMAGE}}

                                                {{#IS_TEXT}}
                                                    <div class="{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div
                                                            data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value"
                                                            class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                            {{VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_TEXT}}

                                                {{#IS_HTML}}
                                                    <div class="{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value"
                                                             class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                            {{{VALUE}}}
                                                        </div>
                                                    </div>
                                                {{/IS_HTML}}

                                                {{#IS_LINK}}
                                                    <div class="{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value"
                                                             class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                                                            {{#VALUE}}
                                                            {{{LINK}}}{{^IS_LAST}}<br>{{/IS_LAST}}
                                                            {{/VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_LINK}}
                                            </td>
                                            {{/COLUMN_LIST}}
                                            <?
                                            break;
                                    }
                                }
                            }
                            ?>

                        {{#SHOW_LOADING}}
                            <div class="basket-items-list-item-overlay"></div>
                        {{/SHOW_LOADING}}

			<?if ($usePriceInAdditionalColumn)
			{
				?>
                <td class="<?=(!isset($mobileColumns['PRICE']) ? ' hidden-xs' : '')?>" id="basket-item-price-{{ID}}">

                    <div class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">

                        {{{PRICE_FORMATED}}}

                        {{#SHOW_DISCOUNT_PRICE}}
                            <br><s>{{{FULL_PRICE_FORMATED}}}</s>
                        {{/SHOW_DISCOUNT_PRICE}}

						{{#SHOW_LOADING}}
							<div class="basket-items-list-item-overlay"></div>
						{{/SHOW_LOADING}}

                    </div>

				</td>
				<?
			}
			?>
			<td>
				<div class="form-group{{#NOT_AVAILABLE}} disabled{{/NOT_AVAILABLE}}"
					data-entity="basket-item-quantity-block">

                    <div class="input-group bootstrap-touchspin">
                        <span class="input-group-prepend" data-entity="basket-item-quantity-minus">
                            <button
                                    class="btn btn-light bootstrap-touchspin-down" type="button">-</button>
                        </span>

                            <input type="text" class="form-control touchspin-empty" value="{{QUANTITY}}"
                                {{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}
                                data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
                                id="basket-item-quantity-{{ID}}">

                        <span class="input-group-append" data-entity="basket-item-quantity-plus">
                            <button class="btn btn-light bootstrap-touchspin-up" type="button">+</button>
                        </span>
                    </div>


                    <!--<div class="basket-item-amount-field-description">
						<?/*
						if ($arParams['PRICE_DISPLAY_MODE'] === 'Y')
						{
							*/?>
							{{MEASURE_TEXT}}
							<?/*
						}
						else
						{
							*/?>
							{{#SHOW_PRICE_FOR}}
								{{MEASURE_RATIO}} {{MEASURE_TEXT}} =
								<span id="basket-item-price-{{ID}}">{{{PRICE_FORMATED}}}</span>
							{{/SHOW_PRICE_FOR}}
							{{^SHOW_PRICE_FOR}}
								{{MEASURE_TEXT}}
							{{/SHOW_PRICE_FOR}}
							<?/*
						}
						*/?>
					</div>-->
					{{#SHOW_LOADING}}
						<div class="basket-items-list-item-overlay"></div>
					{{/SHOW_LOADING}}
				</div>
			</td>
			<?
			if ($useSumColumn)
			{
				?>
				<td class="<?=(!isset($mobileColumns['SUM']) ? ' hidden-xs' : '')?>" id="basket-item-sum-price-{{ID}}">
						<div class="{{#NOT_AVAILABLE}} text-muted{{/NOT_AVAILABLE}}">
                            {{{SUM_PRICE_FORMATED}}}

                            {{#SHOW_DISCOUNT_PRICE}}
                                <br><s>{{{SUM_FULL_PRICE_FORMATED}}}</s>
                            {{/SHOW_DISCOUNT_PRICE}}

                            {{#SHOW_LOADING}}
                                <div class="basket-items-list-item-overlay"></div>
                            {{/SHOW_LOADING}}
                        </div>
				</td>
				<?
			}

			if ($useActionColumn)
			{
				?>
				<td class="text-center hidden-xs">
                    <a data-entity="basket-item-delete"><i class="icon-cross2 mr-2"></i></a>
						{{#SHOW_LOADING}}
							<div class="basket-items-list-item-overlay"></div>
						{{/SHOW_LOADING}}
				</td>
				<?
			}
			?>

            <?
            if (isset($mobileColumns['DELETE']))
            {?>
                <!--<td class="text-center visible-xs">
                    <a href="#" data-entity="basket-item-delete"><i class="icon-cross2 mr-2"></i></a>
                </td>-->
            <?}
            ?>

		{{/SHOW_RESTORE}}
	</tr>
</script>