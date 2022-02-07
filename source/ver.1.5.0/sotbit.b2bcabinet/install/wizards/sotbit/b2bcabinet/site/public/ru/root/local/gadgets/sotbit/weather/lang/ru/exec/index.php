<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

$APPLICATION->SetAdditionalCSS('/local/gadgets/sotbit/weather/styles.css');

if($arGadgetParams["CITY"]!='')
	$url = 'region='.substr($arGadgetParams["CITY"], 1).'&ts='.time();
else
	$url = 'ts='.time();

$cache = new CPageCache();
if($arGadgetParams["CACHE_TIME"]>0 && !$cache->StartDataCache($arGadgetParams["CACHE_TIME"], 'c'.$arGadgetParams["CITY"], "gdweather"))
	return;

$http = new \Bitrix\Main\Web\HttpClient();
$http->setTimeout(10);
$res = $http->get("https://export.yandex.ru/bar/reginfo.xml?".$url);

$res = str_replace("\xE2\x88\x92", "-", $res);
$res = $APPLICATION->ConvertCharset($res, 'UTF-8', SITE_CHARSET);

$xml = new CDataXML();
$xml->LoadString($res);
$node = $xml->SelectNodes('/info/region/title');
?>
<h3><?=$node->content?></h3>

<?
$node = $xml->SelectNodes('/info/weather/day/day_part/temperature');
$t = Intval($node->content);
?>
<div class="widget_content widget_links">
    <h5>Москва</h5>
    <div class="widget_weather-content">
        <div class="widget_weather-temp"><span class="t2"><?=$t?></span></div>
        <div class="widget_weather-icons">
            <?$node = $xml->SelectNodes('/info/weather/day/day_part/image-v3');?>
            <img src="<?=$node->content?>" class="gdwico">
        </div>
        <div class="widget_weather-text">
            <?$node = $xml->SelectNodes('/info/weather/day/day_part/weather_type');?>
            <span class="display_block"><?=$node->content?></span>
            <span class="display_block"> </span>
            <?$node = $xml->SelectNodes('/info/weather/day/day_part/wind_direction');?>
            <span class="display_block">
                Ветер: <?=$node->content?>, <?$node = $xml->SelectNodes('/info/weather/day/day_part/wind_speed');?><?=$node->content?> м/сек.
            </span>

            <?$node = $xml->SelectNodes('/info/weather/day/day_part/pressure');?>
            <span class="display_block">Давление: <?=$node->content?> мм.рт.ст.</span>

            <?$node = $xml->SelectNodes('/info/weather/day/day_part/dampness');?>
            <span class="display_block">Влажность: <?=$node->content?>%</span>

            <?$node = $xml->SelectNodes('/info/weather/day/sun_rise');?>
            <span class="display_block">Восход: <?=$node->content?></span>

            <?$node = $xml->SelectNodes('/info/weather/day/sunset');?>
            <span class="display_block">Заход: <?=$node->content?></span>
        </div>
    </div>
</div>

<?if($arGadgetParams["SHOW_URL"]=="Y"):?>
<br />
<?$node = $xml->SelectNodes('/info/weather/url');?>
<a href="<?=htmlspecialcharsbx($node->content)?>">Подробнее</a> <a href="<?=htmlspecialcharsbx($node->content)?>"><img width="7" height="7" border="0" src="/bitrix/components/bitrix/desktop/images/arrows.gif" /></a>
<br />
<?endif?>

<?$cache->EndDataCache();?>
