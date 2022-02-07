<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Sale\Internals\PersonTypeTable;

if(empty($arParams['BUYER_PERSONAL_TYPE'])) {
    $personalTypes = [];
    $rs = PersonTypeTable::getList(
        [
            'filter' => [
                'ACTIVE' => 'Y',
                [
                    'LOGIC' => 'OR',
                    ['LID' => SITE_ID],
                    ['PERSON_TYPE_SITE.SITE_ID' => SITE_ID],
                ],
            ],
            'select' => [
                'ID',
                'NAME',
            ],
        ]
    );

    while($personalType = $rs->fetch()) {
        $personalTypes[] = $personalType['ID'];
    }

    if(!empty($personalTypes)) {
        $arParams['BUYER_PERSONAL_TYPE'] = $personalTypes;
    }
}

if(Bitrix\Main\Loader::includeModule('Sale') && !empty($arParams['BUYER_PERSONAL_TYPE'])) {
    $result = [];
    $dbSales = CSaleOrderUserProps::GetList(
        array("DATE_UPDATE" => "DESC"),
        array(
            "USER_ID" => $USER->GetID(),
            "PERSON_TYPE_ID" => $arParams['BUYER_PERSONAL_TYPE']
        )
    );

    while ($arrProfiles = $dbSales->Fetch()) {
        $result[$arrProfiles['ID']] = $arrProfiles;

        if(count($result) == 1) {
            $dbPropVals = CSaleOrderUserPropsValue::GetList(
                    array("ID" => "ASC"),
                    Array("USER_PROPS_ID" => $arrProfiles['ID'])
            );

            while ($arPropVals = $dbPropVals->Fetch()) {
                $result[$arrProfiles['ID']]['PROPS'][$arPropVals['ID']] = $arPropVals;
            }
        }
    }

    $arResult['PERSON_PROFILE'] = [];
    if(!empty($result))
        $arResult['PERSON_PROFILE'] = $result;

    $arResult['TRUE_PT'] = false;
    foreach($arResult["PERSON_TYPE"] as $pt)
    {
        if($pt['CHECKED'] === 'Y' && in_array($pt['ID'],$arParams['BUYER_PERSONAL_TYPE']))
        {
            $arResult['TRUE_PT'] = true;
        }
    }
    if(!$arResult['PERSON_PROFILE'])
    {
        $arResult['TRUE_PT'] = false;
    }

    foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $idProfile => $profile)
    {
        if($profile['CHECKED'] === 'Y' && $arResult['PERSON_PROFILE'][$idProfile])
        {
            $arResult['PERSON_PROFILE'][$idProfile]['CHECKED'] = 'Y';
            break;
        }
    }

    // Check PROFILE
    $selectPersonTypeProfile = 0;
    $selectDefaultPersonType = 0;
    $allPersonTypeProfile = [];
    $i=0;
    foreach($arResult['PERSON_PROFILE'] as $key=>$val) {
        if($i == 0)
            $selectDefaultPersonType = $val['PERSON_TYPE_ID'];
        if($val['CHECKED'] === 'Y')
            $selectPersonTypeProfile = $val['PERSON_TYPE_ID'];
        $allPersonTypeProfile[] = $val['PERSON_TYPE_ID'];
        $i++;
    }
    if(!empty($allPersonTypeProfile))
        $allPersonTypeProfile = array_unique($allPersonTypeProfile);

    // Check PERSON TYPE
    $selectPersonType = 0;
    foreach($arResult["PERSON_TYPE"] as $key=>$val) {
        if($val['CHECKED'] === 'Y')
            $selectPersonType = $val['ID'];
    }

    // Select PERSON TYPE
    if(
        !in_array($selectPersonType, $arParams['BUYER_PERSONAL_TYPE'])
        || !in_array($selectPersonType, $allPersonTypeProfile)
    ) {
        // PERSON TYPE
        unset($arResult["PERSON_TYPE"][$selectPersonType]);
        $arResult["PERSON_TYPE"][$selectDefaultPersonType]['CHECKED'] = 'Y';
        $arResult["USER_VALS"]["PERSON_TYPE_ID"] = $selectDefaultPersonType;
    }
}

if (!function_exists("getColumnName")) {
    function getColumnName($arHeader)
    {
        return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
    }
}

if (!function_exists("cmpBySort")) {
    function cmpBySort($array1, $array2)
    {
        if (!isset($array1["SORT"]) || !isset($array2["SORT"])) {
            return -1;
        }

        if ($array1["SORT"] > $array2["SORT"]) {
            return 1;
        }

        if ($array1["SORT"] < $array2["SORT"]) {
            return -1;
        }

        if ($array1["SORT"] == $array2["SORT"]) {
            return 0;
        }
    }
}

//props
if (!function_exists("showFilePropertyField")) {
    function showFilePropertyField($name, $property_fields, $values, $max_file_size_show = 50000)
    {
        $res = "";

        if (!is_array($values) || empty($values)) {
            $values = [
                "n0" => 0,
            ];
        }

        if ($property_fields["MULTIPLE"] == "N") {
            $res = "<input class=\"form-input-styled\" data-fouc type=\"file\" size=\""
                .$max_file_size_show."\" value=\""
                .$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\">";
        } else {
            $res .= "<input class=\"form-input-styled\" data-fouc type=\"file\" size=\""
                .$max_file_size_show."\" value=\""
                .$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\">";
            $res .= "<br/>";
            $res .= "<input class=\"form-input-styled\" data-fouc type=\"file\" size=\""
                .$max_file_size_show."\" value=\""
                .$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name
                ."[1]\" onChange=\"addControl(this);\">";
        }

        return $res;
    }
}

if (!function_exists("PrintPropsForm")) {
    function PrintPropsForm($arSource = [], $locationTemplate = ".default", $arPropsForDisplay)
    {
        $arPropsForDisplay = array_diff($arPropsForDisplay, array(''));
        if (!empty($arSource)) {
            ?>
            <div>
                <?
                foreach ($arSource as $arProperties) {
                    if ($arProperties['CODE'] == 'CONFIDENTIAL' || !in_array($arProperties['CODE'], $arPropsForDisplay)) {
                        continue;
                    }
                    ?>

                    <div
                         class="form-group row"
                         data-property-id-row="<?= intval(intval($arProperties["ID"])) ?>">

                        <?
                        if ($arProperties["TYPE"] == "CHECKBOX") {
                            ?>
                            <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="">

                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox"
                                                           class="form-input-styled"
                                                           data-fouc
                                                           name="<?= $arProperties["FIELD_NAME"] ?>"
                                                           id="<?= $arProperties["FIELD_NAME"] ?>"
                                                           value="Y"<?
                                                            if ($arProperties["CHECKED"] == "Y") {
                                                                echo " checked";
                                                            } ?>
                                                    />
                                                    <?
                                                    if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                                        <span class="form-text text-muted">
                                                            <?= $arProperties["DESCRIPTION"] ?>
                                                        </span>
                                                    <?endif; ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        } elseif ($arProperties["TYPE"] == "TEXT") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <input
                                        type="text"
                                        maxlength="250"
                                        class="form-control"
                                        size="<?= $arProperties["SIZE1"] ?>"
                                        value="<?= $arProperties["VALUE"] ?>"
                                        name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>"
                                />
                                <?
                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <span class="form-text text-muted">
                                        <?= $arProperties["DESCRIPTION"] ?>
                                    </span>
                                <?endif; ?>

                            </div>
                            <?
                        } elseif ($arProperties["TYPE"] == "SELECT" || $arProperties["TYPE"] == "MULTISELECT") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <select<?= ($arProperties["TYPE"] == "MULTISELECT" ? " multiple" : "") ?>
                                        class="form-control select index_blank-sorting-select"
                                        name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>"
                                        data-fouc>
                                    <?
                                    foreach ($arProperties["VARIANTS"] as $arVariants):
                                        ?>
                                        <option value="<?= $arVariants["VALUE"] ?>"<?
                                        if ($arVariants["SELECTED"] == "Y") {
                                            echo " selected";
                                        } ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                    endforeach;
                                    ?>
                                </select>
                                <?
                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <span class="form-text text-muted">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </span>
                                <?endif; ?>
                            </div>

                            <?
                        } elseif ($arProperties["TYPE"] == "TEXTAREA") {

                            $rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <textarea rows="<?= $rows ?>" cols="<?= $arProperties["SIZE1"] ?>"
                                          name="<?= $arProperties["FIELD_NAME"] ?>"
                                          id="<?= $arProperties["FIELD_NAME"] ?>" class="form-control"
                                    ><?= $arProperties["VALUE"] ?></textarea>
                                <?
                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <span class="form-text text-muted">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </span>
                                <?endif; ?>
                            </div>
                            <?

                        } elseif ($arProperties["TYPE"] == "DATE") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="icon-calendar5"></i>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control pickadate"
                                           name="<?= $arProperties["FIELD_NAME"] ?>"
                                           id="<?= $arProperties["FIELD_NAME"] ?>"
                                           placeholder="<?=GetMessage("SOA_SELECT_DATE")?>"
                                           value=""
                                            />
                                </div>
                                <?
                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <span class="form-text text-muted">
                                        <?= $arProperties["DESCRIPTION"] ?>
                                    </span>
                                <?endif; ?>

                            </div>
                            <?

                        } elseif ($arProperties["TYPE"] == "RADIO") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?= $arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9 checkout_radio">
                                <?
                                if (is_array($arProperties["VARIANTS"])) {
                                    foreach ($arProperties["VARIANTS"] as $arVariants):?>
                                        <div class="form-check form-check-inline">
                                            <label
                                                    class="form-check-label"
                                                    for="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>">
                                            <input
                                                    class="form-input-styled"
                                                    type="radio"
                                                    name="<?= $arProperties["FIELD_NAME"] ?>"
                                                    id="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>"
                                                    value="<?= $arVariants["VALUE"] ?>" <?
                                            if ($arVariants["CHECKED"] == "Y") {
                                                echo " checked";
                                            } ?> />
                                            <?= $arVariants["NAME"] ?></label>
                                        </div>
                                    <?endforeach;
                                }
                                ?>
                                <?
                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <br><span class="form-text text-muted">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </span>
                                <?endif; ?>
                            </div>

                            <?
                        } elseif ($arProperties["TYPE"] == "LOCATION") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <?
                                $value = 0;
                                if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                                    foreach ($arProperties["VARIANTS"] as $arVariant) {
                                        if ($arVariant["SELECTED"] == "Y") {
                                            $value = $arVariant["ID"];
                                            break;
                                        }
                                    }
                                }
                                $value = $arProperties["VALUE"];
                                // here we can get '' or 'popup'
                                // map them, if needed
                                if (CSaleLocation::isLocationProMigrated()) {
                                    $locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
                                    $locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps'
                                        : $locationTemplateP; // force to "steps"
                                }
                                ?>

                                <?
                                if ($locationTemplateP == 'steps'):?>
                                    <input type="hidden"
                                           id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?= intval($arProperties["ID"]) ?>]"
                                           name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?= intval($arProperties["ID"]) ?>]"
                                           value="<?= ($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])]
                                               ? '1' : '0') ?>"/>
                                <?endif ?>

                                <?
                                CSaleLocation::proxySaleAjaxLocationsComponent([
                                    "AJAX_CALL"          => "N",
                                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                                    "REGION_INPUT_NAME"  => "REGION",
                                    "CITY_INPUT_NAME"    => $arProperties["FIELD_NAME"],
                                    "CITY_OUT_LOCATION"  => "Y",
                                    "LOCATION_VALUE"     => $value,
                                    "ORDER_PROPS_ID"     => $arProperties["ID"],
                                    "ONCITYCHANGE"       => ($arProperties["IS_LOCATION"] == "Y"
                                        || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                    "SIZE1"              => $arProperties["SIZE1"],
                                ],
                                    [
                                        "ID"                       => $value,
                                        "CODE"                     => "",
                                        "SHOW_DEFAULT_LOCATIONS"   => "Y",

                                        // function called on each location change caused by user or by program
                                        // it may be replaced with global component dispatch mechanism coming soon
                                        "JS_CALLBACK"              => "submitFormProxy",

                                        // function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
                                        // it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
                                        "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

                                        // an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
                                        // it may be replaced with global component dispatch mechanism coming soon
                                        "JS_CONTROL_GLOBAL_ID"     => intval($arProperties["ID"]),

                                        "DISABLE_KEYBOARD_INPUT" => "Y",
                                        "PRECACHE_LAST_LEVEL"    => "Y",
                                        "PRESELECT_TREE_TRUNK"   => "Y",
                                        "SUPPRESS_ERRORS"        => "Y",
                                    ],
                                    $locationTemplateP,
                                    false,
                                    ''
                                );


                                if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                    <span class="form-text text-muted">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </span>
                                <?endif; ?>
                            </div>
                            <?

                        } elseif ($arProperties["TYPE"] == "FILE") {
                            ?>
                            <label class="col-lg-3 col-form-label">
                                <?=$arProperties["NAME"]?>
                                <?=($arProperties["REQUIED_FORMATED"] == "Y" ? " *" : "")?>
                            </label>
                            <div class="col-lg-9">
                                <div class="media-body">
                                    <?= showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties,
                                        $arProperties["VALUE"], $arProperties["SIZE1"]) ?>

                                    <?
                                    if (strlen(trim($arProperties["DESCRIPTION"])) > 0): ?>
                                        <span class="form-text text-muted">
                                            <?= $arProperties["DESCRIPTION"] ?>
                                        </span>
                                    <?endif; ?>
                                </div>
                            </div>
                            <?
                        }
                        ?>
                    </div>

                <?
                if (CSaleLocation::isLocationProEnabled()): ?>

                <?
                $propertyAttributes = [
                    'type'        => $arProperties["TYPE"],
                    'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form'
                    // value taken from property DEFAULT_VALUE or it`s a user-typed value?
                ];

                if (intval($arProperties['IS_ALTERNATE_LOCATION_FOR'])) {
                    $propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);
                }

                if (intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION'])) {
                    $propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);
                }

                if ($arProperties['IS_ZIP'] == 'Y') {
                    $propertyAttributes['isZip'] = true;
                }
                ?>

                    <script>

                        <?// add property info to have client-side control on it?>
                        (window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject([
                            'id'         => intval($arProperties["ID"]),
                            'attributes' => $propertyAttributes,
                        ])?>);

                    </script>
                <?
                endif ?>

                    <?
                }
                ?>
            </div>
            <?
        }
    }
}

$arResult['arForSecondColumn'] = [];
$arResult['arForMainColumn'] = [];
$arParams['FIELDS_USER_INFO'] = (empty($arParams['FIELDS_USER_INFO']) ? [
    'LAST_NAME',
    'NAME',
    'SECOND_NAME',
    'PHONE',
    'EMAIL',
] : $arParams['FIELDS_USER_INFO']);

foreach ($arResult["ORDER_PROP"] as $type => $props) {
    if ($type == "USER_PROPS_Y" || $type == "USER_PROPS_N") {
        foreach ($props as $property) {
            if (in_array($property['CODE'], $arParams['FIELDS_USER_INFO'])) {
                $arResult['arForSecondColumn'][] = $property['CODE'];
            } else {
                $arResult['arForMainColumn'][] = $property['CODE'];
            }
        }
    }
}
if (sizeof($arResult['arForSecondColumn']) == 2) // for physical person
{
    $arResult['arForMainColumn'] = array_merge($arResult['arForMainColumn'], $arResult['arForSecondColumn']);
    $arResult['arForSecondColumn'] = [];
}

// all quantity
if (!empty($arResult["BASKET_ITEMS"])) {
    foreach ($arResult["BASKET_ITEMS"] as &$arItem) {
        if ($arItem["CAN_BUY"] == "Y" && $arItem["DELAY"] == "N") {
            $all_quantity = $all_quantity + $arItem['QUANTITY'];
            $all_vat = $all_vat + $arItem['VAT_VALUE'];
        }
    }
    $arResult['TOTAL_VAT'] = CurrencyFormat($all_vat, CCurrency::GetBaseCurrency());
    $arResult['TOTAL_QUANTITY'] = $all_quantity;
}

if (empty($arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'])) {
    $arParams['IMAGE_SIZE_DELIVERY_PAYSYSTEM'] = [23, 23];
}