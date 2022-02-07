<?php
use Bitrix\Main\Config\Option;

global $USER;

if (!$USER->IsAuthorized()) {
    include_once "auth_footer.php";
    return;
}
?>

</div>
<!-- /content area -->

<!-- Footer -->
<div class="navbar navbar-expand-lg navbar-light d-none d-lg-block">
    <div class="text-center d-lg-none w-100">
        <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
                data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            Footer
        </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-footer">
        <ul class="navbar-nav ml-lg-auto">
            <li class="nav-item">
                <a href="http://sotbit.ru" class="navbar-nav-link">
                    <img class="footer_sotbit_logo" src="/local/templates/b2bcabinet/assets/images/main_logo_sotbit.png">
                    <span><?=GetMessage('DEVELOPED_COMPANY')?></span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /footer -->

</div>
<!-- /main content -->

</div>
<!-- /page content -->
<div class="feed-back-form">
    <?$APPLICATION->IncludeComponent(
        "bitrix:form",
        "b2b_cabinet_feed_back",
        Array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "N",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "NOT_SHOW_FILTER" => "",
            "NOT_SHOW_TABLE" => "",
            "RESULT_ID" => $_REQUEST[RESULT_ID],
            "SEF_MODE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_STATUS" => "Y",
            "SHOW_VIEW_PAGE" => "N",
            "START_PAGE" => "new",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "action" => "action"
            ),
            "WEB_FORM_ID" => Option::get('sotbit.b2bcabinet', 'B2BCABINET_FEED_BACK_FORM_ID'),
        )
    );?>
</div>
</body>
</html>
