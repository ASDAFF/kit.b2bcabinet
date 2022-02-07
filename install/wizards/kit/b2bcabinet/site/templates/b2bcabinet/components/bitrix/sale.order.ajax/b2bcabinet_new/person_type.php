<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(count($arResult["PERSON_PROFILE"]) >= 1)
{
	?>
	<div class="card-header header-elements-inline payer_type-title">
	    <h6 class="card-title"><span><?=GetMessage("SOA_TYPE_PAYER")?></span></h6>
	</div>

	<div class="form-group row index_checkout-payer_type">
	    <div class="">
	    	<?foreach($arResult["PERSON_PROFILE"] as $idBuyer => $profile):?>
	        <div class="form-check form-check-inline disabled">
	            <label class="form-check-label" for="PPROFILE_ID_<?=$profile["ID"]?>">
	                <input
                        type="radio"
                        class="form-input-styled"
                        id="PROFILE_ID_<?=$profile["ID"]?>"
                        name="PROFILE_ID"
                        value="<?=$profile["ID"]?>"
                        data-person-type="<?=$profile['PERSON_TYPE_ID']?>"
                        <?if ($profile["CHECKED"]=="Y") echo " checked=\"checked\"";?>
                        onClick="SetContact('<?=$profile["ID"]?>')"
                        data-fouc
                    >
	               <?=$profile["NAME"]?>
	            </label>
	        </div>
	        <?endforeach;?>
	    </div>
	</div>
    <?
}

if (!empty($arResult["PERSON_TYPE"]))
{
    foreach($arResult["PERSON_TYPE"] as $v)
    {
        if($v["CHECKED"]=="Y")
        {
            ?>
            <input type="hidden" name="PERSON_TYPE" value="<?=$v['ID']?>" />
            <?php
        }
    }
    ?>
        <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>" />
    <?
}
else
{
    if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
    {
        //for IE 8, problems with input hidden after ajax
        ?>
        <span style="display:none;">
            <input type="text" class="form-input-styled" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>" />
            <input type="text" class="form-input-styled" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>" />
		</span>
        <?
    }
    else
    {
        foreach($arResult["PERSON_TYPE"] as $v)
        {
            ?>
            <input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>" />
            <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>" />
            <?
        }
    }
}
?>