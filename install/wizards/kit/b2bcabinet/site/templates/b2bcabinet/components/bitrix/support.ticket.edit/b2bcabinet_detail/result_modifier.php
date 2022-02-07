<?
use Bitrix\Main\UserTable;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arResult['RESPONSIBLE'] = '';
$arResult["TICKET"]["RESPONSIBLE_USER_ID"] = intval($arResult["TICKET"]["RESPONSIBLE_USER_ID"]);
$users = [];


$arResult['SUPPORT_PAGE'] = false;

if(strpos($APPLICATION->GetCurDir(),'support') !== false)
{
	$arResult['SUPPORT_PAGE'] = true;
}

if($arResult['MESSAGES'])
{
	foreach ($arResult['MESSAGES'] as $i => $message)
	{
		$arResult['MESSAGES'][$i]['MESSAGE'] = $arResult['MESSAGES'][$i]['~MESSAGE'];

		$arResult['MESSAGES'][$i]['MESSAGE'] = str_replace([
			'<QUOTE>',
			'</QUOTE>'
		], [
			'<div class="quote"><div class="quote-left"><i class="fa fa-quote-left"></i></div><div class="quote-right">',
			'</div></div>'
		],
			$arResult['MESSAGES'][$i]['MESSAGE']);
		$users[$message['OWNER_USER_ID']] = $message['OWNER_USER_ID'];
	}
}

if($arResult["TICKET"]["RESPONSIBLE_USER_ID"] > 0)
{
	$users[$arResult["TICKET"]["RESPONSIBLE_USER_ID"]] = $arResult["TICKET"]["RESPONSIBLE_USER_ID"];
}
if($users)
{
	$rs = UserTable::getList([
		'filter' => ['ID' => $users],
		'select'
		=> [
			'ID',
			'NAME',
			'LAST_NAME',
			'PERSONAL_PHOTO'
		]
	]);
	while ($user = $rs->fetch())
	{
		if($arResult["TICKET"]["RESPONSIBLE_USER_ID"] > 0 && $arResult["TICKET"]["RESPONSIBLE_USER_ID"] == $user['ID'])
		{
			$arResult['RESPONSIBLE'] = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
		}
		if(!$user['PERSONAL_PHOTO'])
		{
			continue;
		}
		if($arResult['MESSAGES'])
		{
			foreach ($arResult['MESSAGES'] as $i => $message)
			{
				if($message['OWNER_USER_ID'] == $user['ID'])
				{
					$arResult['MESSAGES'][$i]['PERSONAL_PHOTO'] = CFile::ResizeImageGet($user['PERSONAL_PHOTO'],
						[
							'width' => 55,
							'height' => 55
						], BX_RESIZE_IMAGE_EXACT);
				}
				$users[$message['OWNER_USER_ID']] = $message['OWNER_USER_ID'];
			}
		}
	}
}
//default mark
$arResult['DEFAULT_MARK'] = 0;
$rs = CTicketDictionary::GetList($by,$order,array('SID' => ['NOT_SURE']),$is_filtered);
while($mark = $rs->Fetch())
{
	$arResult['DEFAULT_MARK'] = $mark['ID'];
}

$fCategory = 0;
$category = \CTicketDictionary::GetList($by, $sort, ['SID'=>'order_discussion'],$is_filtered)->Fetch();
if($category['ID'] > 0)
{
	$fCategory = $category['ID'];
}

$arResult['ORDER_CATEGORY'] = $fCategory;

if($arResult['DICTIONARY']['CATEGORY'][$fCategory])
{
	unset($arResult['DICTIONARY']['CATEGORY'][$fCategory]);
}



if(strpos($arResult["REAL_FILE_PATH"],'order') !== false)
{
    $arResult["REAL_FILE_PATH"] = $APPLICATION->GetCurPage();
//	$arResult["REAL_FILE_PATH"] = str_replace('index.php','',$arResult["REAL_FILE_PATH"]).'detail/'.$arParams['ORDER_ID'].'/';
}

?>