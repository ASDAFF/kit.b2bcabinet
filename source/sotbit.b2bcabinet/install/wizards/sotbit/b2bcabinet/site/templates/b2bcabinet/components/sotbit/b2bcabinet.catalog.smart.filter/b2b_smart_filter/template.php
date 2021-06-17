<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);


$startWrap = 'Y';

function renderRecursiveMenu($section) {
    if (!empty($section['CHILDS']) && is_array($section['CHILDS'])):
        echo '<ul class="nav nav-group-sub">';
        foreach ($section['CHILDS'] as $item):
            if ($item["CHECKED"])
                $item["CHECKED"] = "checked=\'checked\'";
            else
                $item["CHECKED"] = "";



            echo '<li class="nav-item nav-item-submenu">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input
                                        type="checkbox"
                                        class="form-input-styled checkbox__input"
                                        data-fouc
                                        id="' . $item["CONTROL_ID"] .'"
                                        value="' . $item["HTML_VALUE"] .'"
                                        name="' . $item["CONTROL_NAME"] .'"
                                        ' . $item["CHECKED"] . '
                                        onclick="smartFilter.click(this)">'. $item["VALUE"] . '</label>
                            </div>
                            '. renderRecursiveMenu($item) . '
                           </li>';
        endforeach;
        echo '</ul>';
    endif;
}

?>

<? if (!empty($arResult['ITEMS']['SECTION_ID']['NAME']) && !empty($arResult['ITEMS']['SECTION_ID']['FILTRED_FIELDS'])): ?>

<form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>"
      method="get" class="smartfilter">

    <? foreach ($arResult["HIDDEN"] as $arItem): ?>
        <input type="hidden" name="<? echo $arItem["CONTROL_NAME"] ?>" id="<? echo $arItem["CONTROL_ID"] ?>"
               value="<? echo $arItem["HTML_VALUE"] ?>"/>
    <? endforeach; ?>

    <input type="hidden" name="refresh_values">
    <div class="card index_blank-categories">

        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold"><?= $arResult['ITEMS']['SECTION_ID']['NAME'] ?></span>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <? if (is_array($arResult['ITEMS']['SECTION_ID']['FILTRED_FIELDS'])): ?>
            <div class="card-body border-0 p-0">
                <ul class="nav nav-sidebar mb-2">
                    <? foreach ($arResult['ITEMS']['SECTION_ID']['FILTRED_FIELDS'] as $section): ?>
                        <? if (!empty($section['VALUE'])): ?>
                    <li class="nav-item <?= (!empty($section['CHILDS']) ? 'nav-item-submenu' : '') ?> catalog_section">
                        <a class="nav-link">
                            <div class="form-check">
                                <label class="form-check-label bx_filter_parameters_box_checkbox">
                                    <input type="checkbox" class="form-input-styled checkbox__input"
                                           data-fouc id="<?= $section["CONTROL_ID"] ?>"
                                           value="<?= $section["HTML_VALUE"] ?>"
                                           name="<?= $section["CONTROL_NAME"] ?>"
                                        <?= $section["CHECKED"] || $section['CHILD_SELECTED'] == 'Y' ? 'checked="checked"' : '' ?>
                                           onclick="smartFilter.click(this);"
                                        <? //= $VALUE["DISABLED"] ? 'disabled': '' ?>
                                    >
                                </label>
                            </div>
                            <?= $section['VALUE'] ?>
                        </a>
                        <?
                        if ((!empty($section['CHILDS']))):
                         renderRecursiveMenu($section);
                        endif;
                        ?>
                    </li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
            </div>
        <? endif; ?>
    </div>

    <? unset($arResult['ITEMS']['SECTION_ID']); ?>
    <? endif; ?>

    <div class="index_blank-filter">
        <div class="card bx_filter_section bx_filter">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold"><?= GetMessage('CT_BCSF_FILTER') ?></span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="anchor_header_filter"></div>
            <div class="card-body">

                <?
                //prices
                foreach ($arResult["ITEMS"] as $key => $arItem) {
                    $key = $arItem["ENCODED_ID"];
                    if (isset($arItem["PRICE"])):
                        if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                            continue;

                        $step_num = 4;
                        $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
                        $prices = array();
                        if (Bitrix\Main\Loader::includeModule("currency")) {
                            for ($i = 0; $i < $step_num; $i++) {
                                $prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step * $i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
                            }
                            $prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
                        } else {
                            $precision = $arItem["DECIMALS"] ? $arItem["DECIMALS"] : 0;
                            for ($i = 0; $i < $step_num; $i++) {
                                $prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * $i, $precision, ".", "");
                            }
                            $prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                        }
                        ?>
                        <div class="bx_filter_parameters_box active form-group"
                             data-propid="P<?= $arItem["ID"] ?>">
                            <div class="bx_filter_parameters_box_title fonts__middle_text font-size-xs text-uppercase text-muted mb-3">
                                <span>
                                    <span class="item_name"><?= $arItem["NAME"] ?></span>
                                </span>
                            </div>
                            <div class="bx_filter_block bx_filter_block_wrapper" data-role="bx_filter_block">
                                <div class="bx_filter_parameters_box_container row">
                                    <div class="bx_filter_parameters_box_container_block">
                                        <div class="bx_filter_input_container">
                                            <input
                                                    class="min-price fonts__middle_comment form-control"
                                                    type="text"
                                                    name="<?
                                                    echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                    id="<?
                                                    echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                    value="<?
                                                    echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                                    placeholder="<?= number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", ""); ?>"
                                            />
                                        </div>
                                    </div>
                                    <div class="bx_filter_parameters_box_container_block">
                                        <div class="bx_filter_input_container">
                                            <input
                                                    class="max-price fonts__middle_comment form-control"
                                                    type="text"
                                                    name="<?
                                                    echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                    id="<?
                                                    echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                    value="<?
                                                    echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                                    placeholder="<?= number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", ""); ?>"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?
                    $arJsParams = array(
                        "leftSlider" => 'left_slider_' . $key,
                        "rightSlider" => 'right_slider_' . $key,
                        "tracker" => "drag_tracker_" . $key,
                        "trackerWrap" => "drag_track_" . $key,
                        "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                        "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                        "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                        "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                        "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                        "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                        "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
                        "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                        "precision" => $precision,
                        "colorUnavailableActive" => 'colorUnavailableActive_' . $key,
                        "colorAvailableActive" => 'colorAvailableActive_' . $key,
                        "colorAvailableInactive" => 'colorAvailableInactive_' . $key,
                    );
                    ?>
                        <script type="text/javascript">
                            BX.ready(function () {
                                if (typeof window.trackBarOptions === 'undefined') {
                                    window.trackBarOptions = {};
                                }
                                window.trackBarOptions['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window.trackBarOptions['<?=$key?>']);
                            });
                        </script>
                    <?endif;
                }
                //not prices
                foreach ($arResult["ITEMS"] as $key => $arItem) {
                    if (
                        empty($arItem["VALUES"])
                        || isset($arItem["PRICE"])
                    )
                        continue;

                    if (
                        $arItem["DISPLAY_TYPE"] == "A"
                        && (
                            $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                        )
                    )
                        continue;

                    $check = false;
                    foreach ($arItem['VALUES'] as $it) {
                        if (!isset($it['DISABLED'])) {
                            $check = true;
                        }
                    }
                    if ($check):

                    ?>
                    <div class="bx_filter_parameters_box form-group
                    <? if ($arItem["DISPLAY_EXPANDED"] == "Y"): ?> active<? endif ?><? if ($arItem["CODE"] == 'RAZMER'): ?> filter-size<? endif ?>"
                         data-propid="<?= $arItem["ID"] ?>">
                        <div class="bx_filter_parameters_box_title fonts__middle_text font-size-xs text-uppercase text-muted mb-3">
                             <span>
                                 <span class="item_name"><?= $arItem["NAME"] ?></span>
                             </span>
                        </div>
                        <div class="bx_filter_block bx_filter_block_wrapper" data-role="bx_filter_block">
                            <div class="bx_filter_parameters_box_container">
                                <?
                                $arCur = current($arItem["VALUES"]);
                                switch ($arItem["DISPLAY_TYPE"]) {
                                case "A"://NUMBERS_WITH_SLIDER
                                    ?>
                                    <div class="input-range-number row">
                                        <div class="bx_filter_parameters_box_container-block bx-left">
                                            <div class="bx_filter_parameters_box_container_block">
                                                <div class="bx_filter_input_container">
                                                    <input
                                                            class="min-price fonts__middle_comment form-control"
                                                            type="text"
                                                            name="<?
                                                            echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                            id="<?
                                                            echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                            value="<?
                                                            echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                            size="5"
                                                            onkeyup="smartFilter.keyup(this)"
                                                            placeholder="<?= GetMessage("CT_BCSF_FILTER_FROM") ?>"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container-block bx-right">
                                            <div class="bx_filter_parameters_box_container_block">
                                                <div class="bx_filter_input_container">
                                                    <input
                                                            class="max-price fonts__middle_comment form-control"
                                                            type="text"
                                                            name="<?
                                                            echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                            id="<?
                                                            echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                            value="<?
                                                            echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                            size="5"
                                                            onkeyup="smartFilter.keyup(this)"
                                                            placeholder="<?= GetMessage("CT_BCSF_FILTER_TO") ?>"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                $arJsParams = array(
                                    "leftSlider" => 'left_slider_' . $key,
                                    "rightSlider" => 'right_slider_' . $key,
                                    "tracker" => "drag_tracker_" . $key,
                                    "trackerWrap" => "drag_track_" . $key,
                                    "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                                    "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                                    "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                                    "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                                    "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                    "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                    "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
                                    "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                                    "precision" => $arItem["DECIMALS"] ? $arItem["DECIMALS"] : 0,
                                    "colorUnavailableActive" => 'colorUnavailableActive_' . $key,
                                    "colorAvailableActive" => 'colorAvailableActive_' . $key,
                                    "colorAvailableInactive" => 'colorAvailableInactive_' . $key,
                                );
                                ?>
                                    <script type="text/javascript">
                                        BX.ready(function () {
                                            if (typeof window.trackBarOptions === 'undefined') {
                                                window.trackBarOptions = {};
                                            }
                                            window.trackBarOptions['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                            window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window.trackBarOptions['<?=$key?>']);
                                        });
                                    </script>
                                <?
                                break;
                                case "B"://NUMBERS
                                ?>
                                    <div class="input-range-number row">
                                        <div class="bx_filter_parameters_box_container-block bx-left">
                                            <div class="bx_filter_input_container">
                                                <input
                                                        class="min-price fonts__middle_comment form-control"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= GetMessage("CT_BCSF_FILTER_FROM") ?>"
                                                />
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container-block bx-right">
                                            <div class="bx_filter_input_container">
                                                <input
                                                        class="max-price fonts__middle_comment form-control"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= GetMessage("CT_BCSF_FILTER_TO") ?>"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "G-color"://CHECKBOXES_WITH_PICTURES
                                break;
                                ?>
                                    <div class="checkboxes_with_pictures-wrapper">
                                        <div class="bx-filter-param-btn-inline checkboxes_with_pictures">
                                            <?
                                            foreach ($arItem["VALUES"] as $val => $ar):?>
                                                <input
                                                        style="display: none"
                                                        type="checkbox"
                                                        name="<?= $ar["CONTROL_NAME"] ?>"
                                                        id="<?= $ar["CONTROL_ID"] ?>"
                                                        value="<?= $ar["HTML_VALUE"] ?>"
                                                    <? echo $ar["CHECKED"] ? 'checked' : '' ?>
                                                />
                                                <?
                                                $class = "";
                                                if ($ar["CHECKED"])
                                                    $class .= " bx-active";
                                                if ($ar["DISABLED"])
                                                    $class .= " disabled";
                                                ?>
                                                <label for="<?= $ar["CONTROL_ID"] ?>"
                                                       data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                       class="bx-filter-param-label <?= $class ?>"
                                                       onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')); BX.toggleClass(this, 'bx-active');"
                                                       style="background-color: ">
                                                <span class="bx-filter-param-btn bx-color-sl">
                                                    <?
                                                    if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                        <span class="bx-filter-btn-color-icon"
                                                              style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"
                                                              title="<?= $ar["VALUE"] ?>"></span>
                                                    <? else: ?>
                                                        <?= $ar["VALUE"] ?>
                                                    <? endif; ?>
                                                </span>
                                                </label>
                                            <? endforeach ?>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                                ?>
                                    <div class="bx-filter-param--checkbox-pict-label">
                                        <div class="bx-filter-param-btn-block row row-labels">
                                            <? foreach ($arItem["VALUES"] as $val => $ar): ?>
                                                <div class="col-3">
                                                    <div>
                                                        <input
                                                                style="display: none"
                                                                type="checkbox"
                                                                name="<?= $ar["CONTROL_NAME"] ?>"
                                                                id="<?= $ar["CONTROL_ID"] ?>"
                                                                value="<?= $ar["HTML_VALUE"] ?>"
                                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                        />
                                                        <?
                                                        $class = "";
                                                        if ($ar["CHECKED"])
                                                            $class .= " bx-active";
                                                        if ($ar["DISABLED"])
                                                            $class .= " disabled";
                                                        ?>
                                                        <label for="<?= $ar["CONTROL_ID"] ?>"
                                                               data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                               class="bx-filter-param-label<?= $class ?> badge badge-flat border-grey text-grey-800 d-flex justify-content-center p-2 mb-2"
                                                               onclick="selectSize(this);"
                                                               style="display: block"
                                                        >
												<span class="bx-filter-param-btn bx-color-sl">
													<? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
                                                        <span class="bx-filter-btn-color-icon"
                                                              style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                    <? endif ?>
												</span>
                                                            <span class="bx-filter-param-text"
                                                                  title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?>
                                                </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            <? endforeach ?>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "P"://DROPDOWN
                                ?>
                                    <div class="">
                                        <div class="bx-filter-select-container">
                                            <div class="bx-filter-select-block"
                                                 onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
                                                <div class="bx-filter-select-text" data-role="currentOption">
                                                    <?
                                                    foreach ($arItem["VALUES"] as $val => $ar) {
                                                        if ($ar["CHECKED"]) {
                                                            echo $ar["VALUE"];
                                                            $checkedItemExist = true;
                                                        }
                                                    }
                                                    if (!$checkedItemExist) {
                                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx-filter-select-arrow"></div>
                                                <input
                                                        style="display: none"
                                                        type="radio"
                                                        name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                                                        id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                        value=""
                                                />
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <input
                                                            style="display: none"
                                                            type="radio"
                                                            name="<?= $ar["CONTROL_NAME_ALT"] ?>"
                                                            id="<?= $ar["CONTROL_ID"] ?>"
                                                            value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                    />
                                                <? endforeach ?>
                                                <div class="bx-filter-select-popup" data-role="dropdownContent"
                                                     style="display: none;">
                                                    <ul>
                                                        <li>
                                                            <label for="<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                   class="bx-filter-param-label"
                                                                   data-role="label_<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                   onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
                                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                            </label>
                                                        </li>
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):
                                                            $class = "";
                                                            if ($ar["CHECKED"])
                                                                $class .= " selected";
                                                            if ($ar["DISABLED"])
                                                                $class .= " disabled";
                                                            ?>
                                                            <li>
                                                                <label for="<?= $ar["CONTROL_ID"] ?>"
                                                                       class="bx-filter-param-label<?= $class ?>"
                                                                       data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                       onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')"><?= $ar["VALUE"] ?></label>
                                                            </li>
                                                        <? endforeach ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                                ?>
                                    <div class="">
                                        <div class="bx-filter-select-container">
                                            <div class="bx-filter-select-block"
                                                 onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
                                                <div class="bx-filter-select-text fix" data-role="currentOption">
                                                    <?
                                                    $checkedItemExist = false;
                                                    foreach ($arItem["VALUES"] as $val => $ar):
                                                        if ($ar["CHECKED"]) {
                                                            ?>
                                                            <?
                                                            if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                <span class="bx-filter-btn-color-icon"
                                                                      style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                            <? endif ?>
                                                            <span class="bx-filter-param-text">
																<?= $ar["VALUE"] ?>
															</span>
                                                            <?
                                                            $checkedItemExist = true;
                                                        }
                                                    endforeach;
                                                    if (!$checkedItemExist) {
                                                        ?><span class="bx-filter-btn-color-icon all"></span> <?
                                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx-filter-select-arrow"></div>
                                                <input
                                                        style="display: none"
                                                        type="radio"
                                                        name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                                                        id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                        value=""
                                                />
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <input
                                                            style="display: none"
                                                            type="radio"
                                                            name="<?= $ar["CONTROL_NAME_ALT"] ?>"
                                                            id="<?= $ar["CONTROL_ID"] ?>"
                                                            value="<?= $ar["HTML_VALUE_ALT"] ?>"
                                                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                    />
                                                <? endforeach ?>
                                                <div class="bx-filter-select-popup" data-role="dropdownContent"
                                                     style="display: none">
                                                    <ul>
                                                        <li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
                                                            <label for="<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                   class="bx-filter-param-label"
                                                                   data-role="label_<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                   onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
                                                                <span class="bx-filter-btn-color-icon all"></span>
                                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                            </label>
                                                        </li>
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):
                                                            $class = "";
                                                            if ($ar["CHECKED"])
                                                                $class .= " selected";
                                                            if ($ar["DISABLED"])
                                                                $class .= " disabled";
                                                            ?>
                                                            <li>
                                                                <label for="<?= $ar["CONTROL_ID"] ?>"
                                                                       data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                       class="bx-filter-param-label<?= $class ?>"
                                                                       onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')">
                                                                    <?
                                                                    if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                        <span class="bx-filter-btn-color-icon"
                                                                              style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                                    <? endif ?>
                                                                    <span class="bx-filter-param-text">
																	<?= $ar["VALUE"] ?>
																</span>
                                                                </label>
                                                            </li>
                                                        <? endforeach ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "K"://RADIO_BUTTONS
                                ?>
                                    <div class="radio_container_wrapper">
                                        <div id="style-overflow-search">
                                            <?
                                            foreach ($arItem["VALUES"] as $val => $ar):?>
                                                <div class="radio_container">
                                                    <input
                                                            id="<?= $ar["CONTROL_ID"] ?>"
                                                            name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                                            value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                            type="radio"
                                                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                            onclick="smartFilter.click(this)"
                                                        <? echo $ar["DISABLED"] ? 'disabled' : '' ?>
                                                    >
                                                    <label for="<?= $ar["CONTROL_ID"] ?>"
                                                           class="radio-label fonts__main_comment">
                                                        <span class="radio-label_title fonts__main_comment">
                                                            <?= $ar["VALUE"]; ?><?
                                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !==
                                                                "N" && isset($ar["ELEMENT_COUNT"])):
                                                                ?>&nbsp;(<span
                                                                    data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                            endif; ?>
                                                        </span>
                                                    </label>
                                                </div>
                                            <? endforeach; ?>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "U"://CALENDAR
                                ?>
                                    <div class="">
                                        <div class="bx_filter_parameters_box_container-block">
                                            <div class="bx_filter_input_container bx-filter-calendar-container">
                                                <?
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                ); ?>
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container-block">
                                            <div class="bx_filter_input_container bx-filter-calendar-container">
                                                <?
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                ); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                default://CHECKBOXES

                                $ch = 0;
                                $maxDisplay = 7;
                                $totalValues = count($arItem["VALUES"]);
                                if ($maxDisplay > $totalValues)
                                    $maxDisplay = $totalValues;
                                ?>

                                    <div class="find_property_value_wrapper">
                                        <input type="text" class="find_property_value"
                                               placeholder="<?= GetMessage('CT_BCSF_FILTER_SELECT') ?>">
                                        <button class="find_property_value__button find_property_value__button-search">
                                            <i class="icon-search4 font-size-base text-muted find_property_value__icon-search"></i>
                                        </button>
                                        <button class="find_property_value__button find_property_value__button-close">
                                            <svg width="12" height="12" viewBox="0 0 12 12" class="find_property_value__icon-close">
                                                <path d="M1 1L6 6M11 11L6 6M6 6L11 1.00003M6 6L1 11" stroke-width="2"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="blank_ul_wrapper type-checkbox <?= ($totalValues > $maxDisplay ? "perfectscroll" : "") ?>">
                                        <?
                                        foreach ($arItem["VALUES"] as $val => $ar):
                                            $ch++;
                                            ?>

                                            <?if(!isset($ar["DISABLED"])):?>
                                            <div class="bx_filter_parameters_box_checkbox form-check <? echo $ar["DISABLED"] ? 'disabled' : '' ?>">
                                                <label class="form-check-label checkbox__label fonts__middle_comment"
                                                       for="<? echo $ar["CONTROL_ID"] ?>">

                                                    <input type="checkbox" class="checkbox__input form-input-styled"
                                                           id="<? echo $ar["CONTROL_ID"] ?>"
                                                           value="<? echo $ar["HTML_VALUE"] ?>"
                                                           name="<? echo $ar["CONTROL_NAME"] ?>"
                                                           onclick="smartFilter.click(this)"
                                                           data-fouc
                                                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                        <? echo $ar["DISABLED"] ? 'disabled' : '' ?>
                                                    >

                                                    <? if ($ar['SEO_LINK']): ?>

                                                        <a <?= ($ar['section_filter_link'] != "" ? 'href="' . $ar['section_filter_link'] . '"' : "") ?>>
                                                            <?= $ar["VALUE"]; ?>
                                                            <? if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])): ?>&nbsp;(

                                                                <span data-role="count_<?= $ar["CONTROL_ID"] ?>">
                                                                    <? echo $ar["ELEMENT_COUNT"]; ?>
                                                                </span>

                                                                )
                                                            <? endif; ?>
                                                        </a>

                                                    <? else: ?>
                                                        <?= $ar["VALUE"]; ?><?
                                                        if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                            ?>&nbsp;(

                                                            <span data-role="count_<?= $ar["CONTROL_ID"] ?>">
                                                                <? echo $ar["ELEMENT_COUNT"]; ?>
                                                            </span>

                                                            )<?
                                                        endif; ?>
                                                    <? endif; ?>

                                                </label>
                                            </div>
                                            <?endif;?>
                                            <? if ($ch == $totalValues && $totalValues > $maxDisplay): ?>
                                            <!-- do nothing -->
                                        <? endif; ?>

                                        <? endforeach; ?>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <? endif;
                }
                ?>
                <div class="clb"></div>
                <div class="anchor_filter"></div>
            </div>
            <!-- filter buttons -->
            <div class="row-under-modifications-filter row-under-modifications-filter-fixed">
                <div class="bx_filter_button_box active">
                    <div class="bx_filter_block button">
                        <div class="bx_filter_parameters_box_container filter_buttons">
                            <div class="bx_filter_popup_result <?= $arParams["POPUP_POSITION"] ?>"
                                 id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>
                                 style="display: inline-block;">
                                <a style="display: none;" href="<?= $arResult["SEF_DEL_FILTER_URL"] ?>"
                                   class="del_filter"><?= GetMessage("CT_BCSF_DEL_FILTER") ?></a>
                                <a style="display: none;" href="<?= $arResult["FILTER_URL"] ?>"
                                   class="set_filter"><?= GetMessage("CT_BCSF_FILTER_SHOW") ?></a>
                                <? echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
                            </div>
                            <input class="bx_filter_search_reset fonts__main_comment btn btn-light" type="submit"
                                   id="del_filter"
                                   name="del_filter" value="<?= GetMessage("CT_BCSF_DEL_FILTER") ?>">
                            <input class="bx_filter_search_button fonts__main_comment btn btn-light" type="submit"
                                   id="set_filter"
                                   name="set_filter" value="<?= GetMessage("CT_BCSF_SET_FILTER") ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /filter buttons -->
        </div>
    </div>
</form>
<!-- /form>-->

<?
$arResult["JS_FILTER_PARAMS"]['FROM'] = GetMessage("CT_BCSF_FILTER_FROM");
$arResult["JS_FILTER_PARAMS"]['TO'] = GetMessage("CT_BCSF_FILTER_TO");
if ($arParams['INSTANT_RELOAD'])
    $arResult["JS_FILTER_PARAMS"]['SEF_SET_FILTER_URL'] = $arResult["FILTER_AJAX_URL"];
$arResult["JS_FILTER_PARAMS"]['INSTANT_RELOAD'] = $arParams['INSTANT_RELOAD'];
?>

<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>