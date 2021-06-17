<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(isset($_REQUEST['FIND']) && !empty($_REQUEST['FIND'])) {
    foreach ($arResult['ROWS'] as $key => $row) {
        if($row['data']['ID'] != $_REQUEST['FIND']) {
            unset($arResult['ROWS'][$key]);
        }
    }
}

if(isset($_GET['by']) && in_array($_GET['by'], ['ID','LAMP','TIMESTAMP_X']))
{
	$by = $_GET['by'];
	$order = in_array($_GET['order'], [
		'asc',
		'ASC',
		'desc',
		'DESC'
	]) ? strtolower($_GET['order']) : 'asc';

	for ($i = 0; $i < count($arResult['ROWS']); $i++)
	{
		for ($j = 0; $j < count($arResult['ROWS']) - 1; $j++)
		{
			$change = false;
			$t = [];

			if($order == 'desc' && strcmp($arResult['ROWS'][$i]['data'][$by], $arResult['ROWS'][$j]['data'][$by]) > 0)
			{
				$change = true;
			}
			elseif($order == 'asc' && strcmp($arResult['ROWS'][$i]['data'][$by], $arResult['ROWS'][$j]['data'][$by]) < 0)
			{
				$change = true;
			}

			if($change)
			{
				$t = $arResult['ROWS'][$j];
				$arResult['ROWS'][$j] = $arResult['ROWS'][$i];
				$arResult['ROWS'][$i] = $t;
			}
		}
	}
}
/*
$fCategory = [];
$rs = \CTicketDictionary::GetList($by, $sort, ['SID'=>'order_discussion']);
while($arRes = $rs->GetNext())
{
	$fCategory[] = $arRes['ID'];
}


foreach($arResult['ROWS'] as $i => $row)
{
	if(in_array($row['data']['CATEGORY_ID'],$fCategory))
	{
		unset($arResult['ROWS'][$i]);
		continue;
	}
	$arResult['ROWS'][$i]['data']['TITLE'] = '<a onclick="" href="'.$row['data']['TICKET_EDIT_URL'].'">'
		.$row['data']['TITLE'].'</a>';
	$arResult['ROWS'][$i]['data']['ID'] = "<a onclick='' href='?ID=".$row['data']['ID']."&edit=1'>"
		.$row['data']['ID']."</a>";
}*/