<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
    <div>
        <?
        if ($arParams['HIDE_COUPON'] !== 'Y') {
            ?>
            <div class="form-group row">
                <div>
                    <span><?= Loc::getMessage('SBB_COUPON_ENTER') ?></span>
                    <div>
                        <input type="text" class="form-control" data-entity="basket-coupon-input">
                    </div>
                </div>

                <button type="button" class="btn btn_b2b index_checkout-formalize_button">
                    <?= Loc::getMessage('SBB_COUPON_ENTER_BUTTON') ?>
                </button>

                <div class="bitrix-error promocode_message">
                    {{#COUPON_LIST}}
                    <label class="validation-invalid-label {{CLASS}}">
                        <strong>{{COUPON}}</strong> - <?= Loc::getMessage('SBB_COUPON') ?> {{JS_CHECK_CODE}}
                        {{#DISCOUNT_NAME}}({{{DISCOUNT_NAME}}}){{/DISCOUNT_NAME}}
                        <a data-entity="basket-coupon-delete" data-coupon="{{COUPON}}">
                            <i class="icon-cross2 mr-2"></i>
                        </a>
                    </label>
                    {{/COUPON_LIST}}
                </div>
            </div>
            <?
        }
        ?>

        <div class="index_checkout-promocode-total">
            <div>
                <div class="index_checkout-promocode-total_text">
                    <h5><?= Loc::getMessage('SBB_TOTAL') ?>:</h5>
                    <h4>
                        <span class="index_checkout-promocode-total_amount" data-entity="basket-total-price">{{{PRICE_FORMATED}}}</span>
                    </h4>
                </div>

                {{#DISCOUNT_PRICE_FORMATED}}
                <div class="index_checkout-promocode-total_text promocod_economy">
                    <h6><?= Loc::getMessage('SBB_BASKET_ITEM_ECONOMY') ?></h6>
                    <h6>
                        <span class="index_checkout-promocode-total_amount">{{{DISCOUNT_PRICE_FORMATED}}}</span>
                    </h6>
                </div>
                {{/DISCOUNT_PRICE_FORMATED}}

            </div>
            <div>
                <button type="button" class="btn btn_b2b index_checkout-formalize_button checkout_btn
                {{#DISABLE_CHECKOUT}}
                        disabled{{/DISABLE_CHECKOUT}}"
                        data-entity="basket-checkout-button">
                    <?= Loc::getMessage('SBB_ORDER') ?>
                </button>
            </div>
        </div>
    </div>
</script>



