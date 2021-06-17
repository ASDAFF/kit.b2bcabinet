<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$request = Application::getInstance()->getContext()->getRequest();

if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			$APPLICATION->RestartBuffer();
			?>
			<script type="text/javascript">
				window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

?>
<div class="<?=(!empty($request->getQuery("ORDER_ID")) ? "index_order_success" : "index_checkout")?>">
    <?
    if(empty($request->getQuery("ORDER_ID")))
        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket.php");
    ?>

    <div class="row">
        <div class="col-md-12">
            <a name="order_form"></a>

            <!-- Static mode -->
            <div class="card" id="card">

                <div class="card-header header-elements-inline">
                    <h5 class="card-title"><?=GetMessage("SOA_TEMPL_BLOCK_TITLE")?></h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                        </div>
                    </div>
                </div>

                <NOSCRIPT>
                    <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
                </NOSCRIPT>


                <?

                if(empty($arResult['PERSON_PROFILE'])) {
                    ?>
                        <div style="margin-left: 20px;">
                            <p>
                    <?
                    echo ShowError(Loc::getMessage('SOA_NEED_ADD_ORGANIZATION'));
                    ?></br><?
                    echo Loc::getMessage(
                        'SOA_LINK_ADD_ORGINIZATION',
                        array(
                            "#ADD_ORG_LINK#" => SITE_DIR . ( Option::get('sotbit.b2bcabinet', 'method_install', '', SITE_ID) == 'AS_TEMPLATE' ?
                                    'b2bcabinet/' :
                                    '' ) . 'personal/buyer/index.php'
                        )
                    );
                    ?>
                            </p>
                        </div>
                    <?
                    exit();
                }

                if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
                {
                    if(!empty($arResult["ERROR"]))
                    {
                        foreach($arResult["ERROR"] as $v)
                            echo ShowError($v);
                    }
                    elseif(!empty($arResult["OK_MESSAGE"]))
                    {
                        foreach($arResult["OK_MESSAGE"] as $v)
                            echo ShowNote($v);
                    }

                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
                }
                else
                {
                    if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
                    {
                        if(strlen($arResult["REDIRECT_URL"]) == 0)
                        {
                            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
                        }
                    }
                    else
                    {

                        ?>


                        <div class="card-body checkout_form">

                            <?if($_POST["is_ajax_post"] != "Y")
                            {
                                ?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
                                <?=bitrix_sessid_post()?>
                                <div id="order_form_content">
                                <?
                            }
                            else
                            {
                                $APPLICATION->RestartBuffer();
                            }

                            if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
                            {
                                ?>
                                <input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
                                <?
                            }

                            if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
                            {
                                foreach($arResult["ERROR"] as $v)
                                    echo ShowError($v);
                                ?>
                                <script type="text/javascript">
                                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                                </script>
                                <?
                            }

                            // Person type select
                            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");?>

                            <!--(organization) payment information2 -->
                            <div class="checkout_form-individual">
                                <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");?>
                            </div>

                            <!-- payment and delivery2 -->
                            <div class="checkout_form-individual">
                                <?
                                if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
                                {
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
                                }
                                else
                                {
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                                }
                                ?>
                            </div>
                            <!-- /payment and delivery2 -->

                            <?
                            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");


                            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
                            if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
                                echo $arResult["PREPAY_ADIT_FIELDS"];
                            ?>

                            <?if($_POST["is_ajax_post"] != "Y")
                            {
                                ?>
                                    </div>
                                    <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                                    <input type="hidden" name="profile_change" id="profile_change" value="N">
                                    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                                    <input type="hidden" name="json" value="Y">
                                </form>

                                <?
                                if($arParams["DELIVERY_NO_AJAX"] == "N")
                                {
                                    ?>
                                    <div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <script type="text/javascript">
                                    top.BX('confirmorder').value = 'Y';
                                    top.BX('profile_change').value = 'N';
                                </script>
                                <?
                                die();
                            }

                        ?></div><?



                    }
                }
                ?>


            </div>
            <!-- /static mode -->

            <?if(CSaleLocation::isLocationProEnabled()):?>

                <div style="display: none">
                    <?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:sale.location.selector.steps",
                        ".default",
                        array(
                        ),
                        false
                    );?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:sale.location.selector.search",
                        ".default",
                        array(
                        ),
                        false
                    );?>
                </div>

            <?endif?>



        </div>
    </div>
</div>
<!-- -->

<!-- Basic modal -->
<div id="modal_large" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=GetMessage('SOA_ORDER_GIVE')?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="modal_map_content" class="modal-body-issue_point">
            </div>
            <div class="modal-footer popup-window-buttons">
                <div class="popup-window-buttons_btns">
                    <button type="button" class="btn btn-link" data-dismiss="modal"><?=GetMessage('SOA_POPUP_CANCEL')?></button>
                    <button
                            id="crmOk"
                            name="crmOk"
                            type="button"
                            class="btn btn_save"
                            onclick="GetBuyerStore();">
                        <?=GetMessage('SOA_POPUP_SAVE')?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->

<script type="text/javascript">
    BX.showWait();
    BX.ready(function(){BX.closeWait();});

    <?if(CSaleLocation::isLocationProEnabled()):?>

    <?
    // spike: for children of cities we place this prompt
    $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
    ?>

    BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
        'source' => $this->__component->getPath().'/get.php',
        'cityTypeId' => intval($city['ID']),
        'messages' => array(
            'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
            'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
            'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                    '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                    '#ANCHOR_END#' => '</a>'
                )).'</div>'
        )
    ))?>);

    <?endif?>

    var BXFormPosting = false;

    function submitForm(val)
    {
        if (BXFormPosting === true)
            return true;

        BXFormPosting = true;
        if(val != 'Y')
            BX('confirmorder').value = 'N';

        var orderForm = BX('ORDER_FORM');
        BX.showWait();

        <?if(CSaleLocation::isLocationProEnabled()):?>
        BX.saleOrderAjax.cleanUp();
        <?endif?>

        BX.ajax.submit(orderForm, ajaxResult);

        return true;
    }

    function ajaxResult(res)
    {
        var orderForm = BX('ORDER_FORM');
        try
        {
            // if json came, it obviously a successfull order submit

            var json = JSON.parse(res);
            BX.closeWait();

            if (json.error)
            {
                BXFormPosting = false;
                return;
            }
            else if (json.redirect)
            {
                window.top.location.href = json.redirect;
            }
        }
        catch (e)
        {
            // json parse failed, so it is a simple chunk of html

            BXFormPosting = false;
            BX('order_form_content').innerHTML = res;

            <?if(CSaleLocation::isLocationProEnabled()):?>
            BX.saleOrderAjax.initDeferredControl();
            <?endif?>
        }

        BX.closeWait();
        BX.addCustomEvent('onAjaxSuccess', function(){
            $(function() {
                var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
                if(elems) {
                    elems.forEach(function (html) {
                        var switchery = new Switchery(html);
                    });
                }

                $('.form-input-styled').uniform();

                $('.pickadate').pickadate();

                Select2Selects.init();
            });
        });
        BX.onCustomEvent(orderForm, 'onAjaxSuccess');
    }

    function SetContact(profileId)
    {
        $('[name="PERSON_TYPE"]').val($('#PROFILE_ID_'+profileId).data('person-type'));
        BX("profile_change").value = "Y";
        submitForm();
    }
</script>