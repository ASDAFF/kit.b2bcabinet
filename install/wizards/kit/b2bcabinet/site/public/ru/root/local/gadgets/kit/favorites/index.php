<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rnd = rand();

$APPLICATION->SetAdditionalCSS('/local/gadgets/kit/favorites/styles.css');
?>
<?
if(!$arGadget["USERDATA"]["LINKS"])
	$arGadget["USERDATA"]["LINKS"] = Array();

if($arParams["PERMISSION"]>"R")
{
	if($_REQUEST['gdfavorites']=='Y' && $_REQUEST['gdfav']==$id)
	{
		if($_REQUEST['gdfvadd'] && $_SERVER['REQUEST_METHOD']=='POST')
		{
			$arGadget["USERDATA"]["LINKS"][] = Array("NAME"=>$_REQUEST['name'], "URL"=>$_REQUEST['url']);
			$arGadget["FORCE_REDIRECT"] = true;
		}

		if(isset($_REQUEST['gdfvdel']))
		{
			unset($arGadget["USERDATA"]["LINKS"][$_REQUEST['gdfvdel']]);
			$arGadget["FORCE_REDIRECT"] = true;
		}
	}
?>
<script>
function ShowHide<?=$rnd?>(flag)
{
	if(flag)
	{
		document.getElementById('gdfavoriteslink<?=$rnd?>').style.display = 'none';
		document.getElementById('gdfavoritesform<?=$rnd?>').style.display = 'block';
	}
	else
	{
		document.getElementById('gdfavoriteslink<?=$rnd?>').style.display = 'block';
		document.getElementById('gdfavoritesform<?=$rnd?>').style.display = 'none';
	}

	return false;
}

function EditMode<?=$rnd?>(flag)
{
	if(flag)
	{
		document.getElementById('gdfavoriteslink<?=$rnd?>').style.display = 'none';
		document.getElementById('gdfavoriteslink2<?=$rnd?>').style.display = 'block';
		var css = 'inline';
		var cssRevers = 'none';
	}
	else
	{
		document.getElementById('gdfavoriteslink<?=$rnd?>').style.display = 'block';
		document.getElementById('gdfavoriteslink2<?=$rnd?>').style.display = 'none';
		var css = 'none';
        var cssRevers = 'inline';
    }

	var head = document.getElementsByTagName("HEAD");
	if(head)
	{
		var style = document.createElement("STYLE");
		head[0].appendChild(style);
		if(jsUtils.IsIE())
			document.styleSheets[document.styleSheets.length-1].cssText = '.gdfavdellink {display: '+css+';}';
		else if(document.getElementsByClassName)
		{
			var arEls = document.getElementsByClassName("gdfavdellink");
			for(var el=0; el<arEls.length; el++)
				arEls[el].style.display = css;

            var arEls = document.querySelectorAll(".favorite_link .icon-arrow-right13");
            for(var el=0; el<arEls.length; el++)
                arEls[el].style.display = cssRevers;
		}
		else
			style.appendChild(document.createTextNode('.gdfavdellink {display: '+css+';}'));
	}

	return false;
}

function Del<?=$rnd?>(id)
{
	var frm = document.getElementById("gdfavoritesformdel<?=$rnd?>");
	frm['gdfvdel'].value = id;
	frm.submit();
	return false;
}
</script>
<form action="<?=$arParams["UPD_URL"]?>" method="post" id="gdfavoritesformdel<?=$rnd?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="gdfavorites" value="Y">
	<input type="hidden" name="gdfav" value="<?=$id?>">
	<input type="hidden" name="gdfvdel" value="">
</form>



    <div class="widget_content widget_links">

<?foreach($arGadget["USERDATA"]["LINKS"] as $i=>$linkParam):?>
	<div class="gdfavlink">
<?
	if(!preg_match("'^(http://|https://|ftp://|/)'i", $linkParam["URL"]))
		$linkParam["URL"] = 'http://'.$linkParam["URL"];
?>
        <a class="favorite_link" href="<?=htmlspecialcharsbx($linkParam["URL"])?>">
            &gt;&gt; <?=htmlspecialcharsbx(($linkParam["NAME"]!=''?$linkParam["NAME"]:$linkParam["URL"]))?>
            <i class="icon-arrow-right13 mr-2"></i>
            <i class="icon-cross mr-2 gdfavdellink" style="display: none;" onclick="Del<?=$rnd?>('<?=$i?>'); return false;"></i>
        </a>
	</div>
<?endforeach?>
</div>

    <div id="gdfavoriteslink2<?=$rnd?>" style="display: none;" class="widget_content widget_links_btns gdfavaddlink">
        <a href="javascript:void(0)"  onclick="return EditMode<?=$rnd?>(false);"><?echo GetMessage("GD_FAVORITES_CH_EXIT")?></a>
    </div>


    <div id="gdfavoriteslink<?=$rnd?>" class="widget_content widget_links_btns gdfavaddlink">
        <a href="javascript:void(0)" onclick="return ShowHide<?=$rnd?>(true);"><?echo GetMessage("GD_FAVORITES_ADD")?></a><span> | </span>
        <a href="javascript:void(0)" onclick="return EditMode<?=$rnd?>(true);"><?echo GetMessage("GD_FAVORITES_CH")?></a>
    </div>

<div id="gdfavoritesform<?=$rnd?>" style="display: none;" class="gdfavoritesform">
<form action="<?=$arParams["UPD_URL"]?>" method="post">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="gdfavorites" value="Y">
	<input type="hidden" name="gdfav" value="<?=$id?>">
    <div class="widget_content widget_links">
        <div class="form-group form-group-float">
            <label><?echo GetMessage("GD_FAVORITES_NEW_URL")?></label>
            <input type="text" class="form-control" value="http://" name="url">
        </div>
        <div class="form-group form-group-float">
            <label><?echo GetMessage("GD_FAVORITES_NAME")?></label>
            <input type="text" name="name" class="form-control">
        </div>
    </div>

    <div class="widget_content widget_links_btns">
        <input type="submit" class="button_link" name="gdfvadd" value="<?echo GetMessage("GD_FAVORITES_ADD_URL")?>">
        <span> | </span>
        <input type="button" class="button_link" value="<?echo GetMessage("GD_FAVORITES_CANCEL_URL")?>" onclick="ShowHide<?=$rnd?>(false);">
    </div>
</form>
</div>
<?
}
else
{
?>
    <div class="widget_content widget_links">
<?foreach($arGadget["USERDATA"]["LINKS"] as $id=>$linkParam):?>
<?
	if(!preg_match("'^(http://|https://|ftp://)'i", $linkParam["URL"]))
		$linkParam["URL"] = 'http://'.$linkParam["URL"];
?>
        <a href="<?=htmlspecialcharsbx($linkParam["URL"])?>">&gt;&gt; <?=htmlspecialcharsbx(($linkParam["NAME"]!=''?$linkParam["NAME"]:$linkParam["URL"]))?> <i class="icon-arrow-right13 mr-2"></i></a>
<?endforeach?>
</div>
<?

}

?>
