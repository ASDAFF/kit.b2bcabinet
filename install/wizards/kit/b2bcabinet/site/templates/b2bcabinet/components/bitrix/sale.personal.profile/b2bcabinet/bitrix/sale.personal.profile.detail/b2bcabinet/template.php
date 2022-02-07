<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

$APPLICATION->AddChainItem(Loc::getMessage('SPPD_EDIT_PROFILE', array('#ID#'=>$arResult['ID'])));

$this->addExternalCss('/local/templates/b2bcabinet/assets/css/components.min.css');
?>
<div class="row companies-wrapper">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title"><?= Loc::getMessage('SPPD_PROFILE_NO', array("#ID#" => $arResult["ID"]))?></h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="blank_detail-menu">
                    <ul class="nav nav-tabs nav-tabs-highlight .b2b_detail_order__nav_ul__block">
                        <li class="nav-item">
                            <a href="#basic-tab1" class="nav-link active" data-toggle="tab">
                                <?=Loc::getMessage('SPPD_COMMON')?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#basic-tab2" class="nav-link" data-toggle="tab">
                                <?=Loc::getMessage('DOCS_NAME')?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#basic-tab3" class="nav-link" data-toggle="tab">
                                <?=Loc::getMessage('ORDERS_TAB')?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane show active" id="basic-tab1">
                        <div class="blank_detail_table">
                            <?
                            if(strlen($arResult["ID"])>0)
                            {
                                ShowError($arResult["ERROR_MESSAGE"]);
                                ?>
                                <form method="post"  class="col-md-12 sale-profile-detail-form" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
                                    <?=bitrix_sessid_post()?>
                                    <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">

                                    <div class="form-group">
                                        <div class="row profile-types__wrapper">
                                            <label class="col-lg-12 col-form-label"><?=Loc::getMessage('SALE_PERS_TYPE')?>:</label>
                                            <div class="col-lg-12">
                                                <input readonly="" type="text" class="form-control" placeholder="<?=$arResult["PERSON_TYPE"]["NAME"]?>" value="<?=$arResult["PERSON_TYPE"]["NAME"]?>">
                                            </div>
                                        </div>
                                        <div class="row profile-types__wrapper">
                                            <label class="col-lg-12 col-form-label"><?=Loc::getMessage('SALE_PNAME')?>:</label>
                                            <div class="col-lg-12">
                                                <input readonly="" type="text" name="NAME" class="form-control" placeholder="<?=$arResult["NAME"]?>" value="<?=$arResult["NAME"]?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?
                                        foreach($arResult["ORDER_PROPS"] as $block)
                                        {
                                            if (!empty($block["PROPS"]))
                                            {
                                                ?>
                                                <div class="col-md-12 ñol-lg-6 col-xl-6 my-2">
                                                    <div class="card card-bitrix-cabinet">
                                                        <div class="card-header header-elements-inline">
                                                            <h5 class="card-title"><?= $block["NAME"]?></h5>
                                                            <div class="header-elements">
                                                                <div class="list-icons">
                                                                    <a class="list-icons-item" data-action="collapse"></a>
                                                                    <!--                                        <a class="list-icons-item" data-action="reload"></a>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <?
                                                            foreach($block["PROPS"] as $property)
                                                            {
                                                                $key = (int)$property["ID"];
                                                                $name = "ORDER_PROP_".$key;
                                                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                                                $alignTop = ($property["TYPE"] === "LOCATION" && $arParams['USE_AJAX_LOCATIONS'] === 'Y') ? "vertical-align-top" : "";
                                                                ?>
                                                                <div class="form-group form-group-float">
                                                                    <label class="text-muted" for="sppd-property-<?=$key?>">
                                                                        <?= $property["NAME"]?>:
                                                                        <? if ($property["REQUIED"] == "Y") {
                                                                            ?>
                                                                            <span class="req">*</span>
                                                                            <?
                                                                        }
                                                                        ?>
                                                                    </label>
                                                                    <?
                                                                    if ($property["TYPE"] == "CHECKBOX")
                                                                    {
                                                                        ?>
                                                                        <input
                                                                                class="sale-personal-profile-detail-form-checkbox"
                                                                                id="sppd-property-<?=$key?>"
                                                                                type="checkbox"
                                                                                name="<?=$name?>"
                                                                                value="Y"
                                                                            <?if ($currentValue == "Y" || !isset($currentValue) && $property["DEFAULT_VALUE"] == "Y") echo " checked";?>/>
                                                                        <?
                                                                    }
                                                                    elseif ($property["TYPE"] == "TEXT")
                                                                    {
                                                                        if ($property["MULTIPLE"] === 'Y')
                                                                        {
                                                                            if (empty($currentValue) || !is_array($currentValue))
                                                                                $currentValue = array('');
                                                                            foreach ($currentValue as $elementValue)
                                                                            {
                                                                                ?>
                                                                                <input
                                                                                        class="form-control"
                                                                                        type="text" name="<?=$name?>[]"
                                                                                        maxlength="50"
                                                                                        id="sppd-property-<?=$key?>"
                                                                                        value="<?=$elementValue?>"/>
                                                                                <?
                                                                            }
                                                                            ?>
                                                                            <span class="btn-themes btn-default btn-md btn input-add-multiple"
                                                                                  data-add-type=<?=$property["TYPE"]?>
                                                                                  data-add-name="<?=$name?>[]"><?=Loc::getMessage('SPPD_ADD')?></span>
                                                                            <?
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <input
                                                                                    class="form-control"
                                                                                    type="text" name="<?=$name?>"
                                                                                    maxlength="50"
                                                                                    id="sppd-property-<?=$key?>"
                                                                                    value="<?=$currentValue?>"/>
                                                                            <?
                                                                        }
                                                                    }
                                                                    elseif ($property["TYPE"] == "SELECT")
                                                                    {
                                                                        ?>
                                                                        <select
                                                                                class="form-control"
                                                                                name="<?=$name?>"
                                                                                id="sppd-property-<?=$key?>"
                                                                                size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:1; ?>">
                                                                            <?
                                                                            foreach ($property["VALUES"] as $value)
                                                                            {
                                                                                ?>
                                                                                <option value="<?= $value["VALUE"]?>" <?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"]==$property["DEFAULT_VALUE"]) echo " selected"?>>
                                                                                    <?= $value["NAME"]?>
                                                                                </option>
                                                                                <?
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <?
                                                                    }
                                                                    elseif ($property["TYPE"] == "MULTISELECT")
                                                                    {
                                                                        ?>
                                                                        <select
                                                                                class="form-control"
                                                                                id="sppd-property-<?=$key?>"
                                                                                multiple name="<?=$name?>[]"
                                                                                size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:5; ?>">
                                                                            <?
                                                                            $arCurVal = array();
                                                                            $arCurVal = explode(",", $currentValue);
                                                                            for ($i = 0, $cnt = count($arCurVal); $i < $cnt; $i++)
                                                                                $arCurVal[$i] = trim($arCurVal[$i]);
                                                                            $arDefVal = explode(",", $property["DEFAULT_VALUE"]);
                                                                            for ($i = 0, $cnt = count($arDefVal); $i < $cnt; $i++)
                                                                                $arDefVal[$i] = trim($arDefVal[$i]);
                                                                            foreach($property["VALUES"] as $value)
                                                                            {
                                                                                ?>
                                                                                <option value="<?= $value["VALUE"]?>"<?if (in_array($value["VALUE"], $arCurVal) || !isset($currentValue) && in_array($value["VALUE"], $arDefVal)) echo" selected"?>>
                                                                                    <?= $value["NAME"]?>
                                                                                </option>
                                                                                <?
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <?
                                                                    }
                                                                    elseif ($property["TYPE"] == "TEXTAREA")
                                                                    {
                                                                        ?>
                                                                        <textarea
                                                                                class="form-control"
                                                                                id="sppd-property-<?=$key?>"
                                                                                rows="<?echo ((int)($property["SIZE2"])>0)?$property["SIZE2"]:4; ?>"
                                                                                cols="<?echo ((int)($property["SIZE1"])>0)?$property["SIZE1"]:40; ?>"
                                                                                name="<?=$name?>"><?= (isset($currentValue)) ? $currentValue : $property["DEFAULT_VALUE"];?>
                                                                </textarea>
                                                                        <?
                                                                    }
                                                                    elseif ($property["TYPE"] == "LOCATION")
                                                                    {
                                                                        $locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";
                                                                        $locationClassName = 'location-block-wrapper';
                                                                        if ($arParams['USE_AJAX_LOCATIONS'] === 'Y')
                                                                        {
                                                                            $locationClassName .= ' location-block-wrapper-delimeter';
                                                                        }
                                                                        if ($property["MULTIPLE"] === 'Y')
                                                                        {
                                                                            if (empty($currentValue) || !is_array($currentValue))
                                                                                $currentValue = array($property["DEFAULT_VALUE"]);

                                                                            foreach ($currentValue as $code => $elementValue)
                                                                            {
                                                                                $locationValue = intval($elementValue) ? $elementValue : $property["DEFAULT_VALUE"];
                                                                                CSaleLocation::proxySaleAjaxLocationsComponent(
                                                                                    array(
                                                                                        "ID" => "propertyLocation".$name."[$code]",
                                                                                        "AJAX_CALL" => "N",
                                                                                        'CITY_OUT_LOCATION' => 'Y',
                                                                                        'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
                                                                                        'CITY_INPUT_NAME' => $name."[$code]",
                                                                                        'LOCATION_VALUE' => $locationValue,
                                                                                    ),
                                                                                    array(
                                                                                    ),
                                                                                    $locationTemplate,
                                                                                    true,
                                                                                    $locationClassName
                                                                                );
                                                                            }
                                                                            ?>
                                                                            <span class="btn-themes btn-default btn-md btn input-add-multiple"
                                                                                  data-add-type=<?=$property["TYPE"]?>
                                                                                  data-add-name="<?=$name?>"
                                                                                  data-add-last-key="<?=$code?>"
                                                                                  data-add-template="<?=$locationTemplate?>"><?=Loc::getMessage('SPPD_ADD')?></span>
                                                                            <?
                                                                        }
                                                                        else
                                                                        {
                                                                            $locationValue = (int)($currentValue) ? (int)$currentValue : $property["DEFAULT_VALUE"];

                                                                            CSaleLocation::proxySaleAjaxLocationsComponent(
                                                                                array(
                                                                                    "AJAX_CALL" => "N",
                                                                                    'CITY_OUT_LOCATION' => 'Y',
                                                                                    'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
                                                                                    'CITY_INPUT_NAME' => $name,
                                                                                    'LOCATION_VALUE' => $locationValue,
                                                                                ),
                                                                                array(
                                                                                ),
                                                                                $locationTemplate,
                                                                                true,
                                                                                'location-block-wrapper'
                                                                            );
                                                                        }
                                                                    }
                                                                    elseif ($property["TYPE"] == "RADIO")
                                                                    {
                                                                        foreach($property["VALUES"] as $value)
                                                                        {
                                                                            ?>
                                                                            <div class="radio">
                                                                                <input
                                                                                        type="radio"
                                                                                        id="sppd-property-<?=$key?>"
                                                                                        name="<?=$name?>"
                                                                                        value="<?= $value["VALUE"]?>"
                                                                                    <?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " checked"?>>
                                                                                <?= $value["NAME"]?>
                                                                            </div>
                                                                            <?
                                                                        }
                                                                    }
                                                                    elseif ($property["TYPE"] == "FILE")
                                                                    {
                                                                        $multiple = ($property["MULTIPLE"] === "Y") ? "multiple" : '';
                                                                        $profileFiles = is_array($currentValue) ? $currentValue : array($currentValue);
                                                                        if (count($currentValue) > 0)
                                                                        {
                                                                            ?>
                                                                            <input type="hidden" name="<?=$name?>_del" class="profile-property-input-delete-file">
                                                                            <?
                                                                            foreach ($profileFiles as $file)
                                                                            {
                                                                                ?>
                                                                                <div class="sale-personal-profile-detail-form-file">
                                                                                    <?
                                                                                    $fileId = $file['ID'];
                                                                                    if (CFile::IsImage($file['FILE_NAME']))
                                                                                    {
                                                                                        ?>
                                                                                        <div class="sale-personal-profile-detail-prop-img">
                                                                                            <?=CFile::ShowImage($fileId, 150, 150, "border=0", "", true)?>
                                                                                        </div>
                                                                                        <?
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <a download="<?=$file["ORIGINAL_NAME"]?>" href="<?=CFile::GetFileSRC($file)?>">
                                                                                            <?=Loc::getMessage('SPPD_DOWNLOAD_FILE', array("#FILE_NAME#" => $file["ORIGINAL_NAME"]))?>
                                                                                        </a>
                                                                                        <?
                                                                                    }
                                                                                    ?>
                                                                                    <input type="checkbox" value="<?=$fileId?>" class="profile-property-check-file" id="profile-property-check-file-<?=$fileId?>">
                                                                                    <label for="profile-property-check-file-<?=$fileId?>"><?=Loc::getMessage('SPPD_DELETE_FILE')?></label>
                                                                                </div>
                                                                                <?
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <label>
                                                                    <span class="btn-themes btn-default btn-md btn">
                                                                        <?=Loc::getMessage('SPPD_SELECT')?>
                                                                    </span>
                                                                            <span class="sale-personal-profile-detail-load-file-info">
                                                                        <?=Loc::getMessage('SPPD_FILE_NOT_SELECTED')?>
                                                                    </span>
                                                                            <?=CFile::InputFile($name."[]", 20, null, false, 0, "IMAGE", "class='btn sale-personal-profile-detail-input-file' ".$multiple)?>
                                                                        </label>
                                                                        <span class="sale-personal-profile-detail-load-file-cancel sale-personal-profile-hide"></span>
                                                                        <?
                                                                    }

                                                                    if (strlen($property["DESCRIPTION"]) > 0)
                                                                    {
                                                                        ?>
                                                                        <br /><small><?= $property["DESCRIPTION"] ?></small>
                                                                        <?
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-offset-3 col-sm-9 sale-personal-profile-btn-block">
                                        <input type="submit" class="btn btn_b2b" name="save" value="<?echo GetMessage("SALE_SAVE") ?>">
                                        &nbsp;
                                        <input type="submit" class="btn btn_b2b"  name="apply" value="<?=GetMessage("SALE_APPLY")?>">
                                        &nbsp;
                                        <input type="submit" class="btn btn-light"  name="reset" value="<?echo GetMessage("SALE_RESET")?>">
                                    </div>
                                </form>
                                <div class="clearfix"></div>
                            <?
                            $javascriptParams = array(
                                "ajaxUrl" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
                            );
                            $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
                            ?>
                                <script>
                                    BX.message({
                                        SPPD_FILE_COUNT: '<?=Loc::getMessage('SPPD_FILE_COUNT')?>',
                                        SPPD_FILE_NOT_SELECTED: '<?=Loc::getMessage('SPPD_FILE_NOT_SELECTED')?>'
                                    });
                                    BX.Sale.PersonalProfileComponent.PersonalProfileDetail.init(<?=$javascriptParams?>);
                                </script>
                                <?
                            }
                            else
                            {
                                ShowError($arResult["ERROR_MESSAGE"]);
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="basic-tab2">
                        <div class="blank_detail_table">
                            <div class="main-ui-filter-search-wrapper">
                                <?
                                $APPLICATION->IncludeComponent(
                                    'bitrix:main.ui.filter',
                                    'b2bcabinet',
                                    [
                                        'FILTER_ID' => 'DOCUMENTS_LIST',
                                        'GRID_ID' => 'DOCUMENTS_LIST',
                                        'FILTER' => [
                                            ['id' => 'ID', 'name' => Loc::getMessage('DOC_ID'), 'type' => 'string'],
                                            ['id' => 'NAME', 'name' => Loc::getMessage('DOC_NAME'), 'type' => 'string'],
                                            ['id' => 'DATE_CREATE', 'name' => Loc::getMessage('DOC_DATE_CREATE'), 'type' => 'date'],
                                            ['id' => 'ORDER', 'name' => Loc::getMessage('DOC_ORDER'), 'type' => 'string'],
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
                                    'GRID_ID'   => 'DOCUMENTS_LIST',
                                    'HEADERS' => array(
                                        array("id"=>"ID", "name"=>Loc::getMessage('DOC_ID'), "sort"=>"ID", "default"=>true, "editable"=>false),
                                        array("id"=>"NAME", "name"=>Loc::getMessage('DOC_NAME'), "sort"=>"NAME", "default"=>true, "editable"=>false),
                                        array("id"=>"DATE_CREATE", "name"=>Loc::getMessage('DOC_DATE_CREATE'), "sort"=>"DATE_CREATE", "default"=>true, "editable"=>false),
                                        array("id"=>"ORDER", "name"=>Loc::getMessage('DOC_ORDER'),  "default"=>true, "editable"=>false),
                                    ),
                                    'ROWS'      => $arResult['ROWS'],
                                    'AJAX_MODE'           => 'Y',

                                    "AJAX_OPTION_JUMP"    => "N",
                                    "AJAX_OPTION_STYLE"   => "N",
                                    "AJAX_OPTION_HISTORY" => "N",

                                    "ALLOW_COLUMNS_SORT"      => true,
                                    "ALLOW_ROWS_SORT"         => ['ID','NAME','DATE_CREATE','DATE_UPDATE'],
                                    "ALLOW_COLUMNS_RESIZE"    => true,
                                    "ALLOW_HORIZONTAL_SCROLL" => true,
                                    "ALLOW_SORT"              => true,
                                    "ALLOW_PIN_HEADER"        => true,
                                    "ACTION_PANEL"            => [],

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
                    </div>
                    <div class="tab-pane" id="basic-tab3">
                        <?
                        $_REQUEST['show_all']='Y';
                        $_REQUEST['by'] = isset( $_GET['by'] ) ? $_GET['by'] : 'ID';
                        $_REQUEST['order'] = isset( $_GET['order'] ) ? strtoupper( $_GET['order'] ) : 'DESC';

                        $APPLICATION->IncludeComponent(
                            "bitrix:sale.personal.order.list",
                            "b2bcabinet_profile_detail",
                            array(
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "ALLOW_INNER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "CUSTOM_SELECT_PROPS" => array(
                                    0 => "PROPERTY_CML2_ARTICLE",
                                    1 => "PROPERTY_RAZMER",
                                    2 => "",
                                ),
                                "DETAIL_HIDE_USER_INFO" => array(
                                    0 => "0",
                                ),
                                "DISALLOW_CANCEL" => "N",
                                "HISTORIC_STATUSES" => array(
                                    0 => "F",
                                ),
                                "NAV_TEMPLATE" => "",
                                "ONLY_INNER_FULL" => "N",
                                "ORDERS_PER_PAGE" => "20",
                                "ORDER_DEFAULT_SORT" => "STATUS",
                                "PATH_TO_BASKET" => SITE_DIR . "b2bcabinet/orders/make/",
                                "PATH_TO_CATALOG" => SITE_DIR . "catalog/",
                                "PATH_TO_PAYMENT" => SITE_DIR . "b2bcabinet/orders/payment/",
                                "PROP_1" => array(),
                                "PROP_2" => array(),
                                "PROP_3" => array(),
                                "REFRESH_PRICES" => "N",
                                "RESTRICT_CHANGE_PAYSYSTEM" => array(
                                    0 => "0",
                                ),
                                "SAVE_IN_SESSION" => "Y",
                                "SEF_MODE" => "Y",
                                "SET_TITLE" => "N",
                                "STATUS_COLOR_F" => "gray",
                                "STATUS_COLOR_N" => "green",
                                "STATUS_COLOR_P" => "yellow",
                                "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
                                "COMPONENT_TEMPLATE" => "b2bcabinet_profile_detail",
                                "IBLOCK_TYPE" => "catalog",
                                "IBLOCK_ID" => "",
                                "OFFER_TREE_PROPS" => array(),
                                "OFFER_COLOR_PROP" => "",
                                "MANUFACTURER_ELEMENT_PROPS" => "",
                                "MANUFACTURER_LIST_PROPS" => "",
                                "PICTURE_FROM_OFFER" => "N",
                                "MORE_PHOTO_PRODUCT_PROPS" => "",
                                "IMG_WIDTH" => "80",
                                "IMG_HEIGHT" => "120",
                                "PATH_TO_DETAIL" => "/b2bcabinet/order/detail/#ID#/",
                                "PATH_TO_CANCEL" => "/b2bcabinet/order/cancel/#ID#/",
                                "PATH_TO_COPY" => "/b2bcabinet/order/index.php" . '?ID=#ID#',
                                "PROFILE_ID" => $arResult["ID"]

                            ),
                            false
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.b2b_detail_order__nav_ul__block a').click(function (e)
    {
        e.preventDefault();
        $(this).tab('show');
    })
</script>

