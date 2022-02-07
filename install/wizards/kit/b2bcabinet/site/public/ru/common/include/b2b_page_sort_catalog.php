<?
use Bitrix\Main\Config\Option;

$arAvailableSortKit = array(
    "default"=>array("default", ""),
    "name_0" => Array("name", "desc"),
    "name_1" => Array("name", "asc"),
    "price_0" => Array("property_minimum_price", "desc"),
    "price_1" => Array("property_minimum_price", "asc"),
    "date_0" => Array("date_create", "desc"),
    "date_1" => Array("date_create", "asc"),
);

$sort_field = strtolower($arParams[1]["ELEMENT_SORT_FIELD"]);
$sort_order = strtolower($arParams[1]["ELEMENT_SORT_ORDER"]);
$currentKey = "default";

foreach($arAvailableSortKit as $key=>$arSort)
{
    if($arSort[0]==$sort_field && $sort_order==$arSort[1]) $currentKey = $key;
}
?>
<form method="POST" action="">
    <?=bitrix_sessid_post()?>
    <span class="index_blank-sorting_title"><?=GetMessage('B2BS_CATALOG_SECT_SORT_LABEL')?></span>
    <select class="form-control select index_blank-sorting-select" name="sort" onchange="this.form.submit()">
        <?foreach($arAvailableSortKit as $key=>$v):?>
            <option <?if($currentKey==$key) echo 'selected'?> value="<?=$key?>"><?=GetMessage('SECT_SORT_'.$key)?></option>
        <?endforeach?>
    </select>
</form>