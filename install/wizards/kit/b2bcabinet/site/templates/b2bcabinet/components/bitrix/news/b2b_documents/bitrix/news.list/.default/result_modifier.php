<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Loader;

use Kit\B2BCabinet\Client\Shop\Doc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
Loc::loadMessages(__FILE__);
$arResult['ROWS'] = [];
$isB2bTab = false;
if(strpos($arParams['DETAIL_URL'], 'b2b') !== false)
{
    $isB2bTab = true;
}

$files = [];

if($arResult['ITEMS'])
{
    foreach ($arResult['ITEMS'] as $item)
    {
        if($item['PROPERTIES']['DOCUMENT']['VALUE'] > 0)
        {
            $files[$item['PROPERTIES']['DOCUMENT']['VALUE']] = \CFile::GetPath($item['PROPERTIES']['DOCUMENT']['VALUE']);
        }
    }

//	$doc = new Doc();
//	$buyers = $doc->getBuyersByInn();

    $COMPANY_DETAIL_PATH = Option::get('kit.b2bcabinet', 'ADDRESS_COMPANY', '', SITE_ID);
    $ORDER_DETAIL_PATH = Option::get('kit.b2bcabinet', 'ADDRESS_ORDER', '', SITE_ID);

    function checkIssetProfile($id, $userID){
        $issetProfile = CSaleOrderUserProps::GetList(
            [],
            ["ID"=>$id, "USER_ID"=>$userID],
            false,
            false,
            ["ID"])->fetch();

        return $issetProfile;
    }

    foreach ($arResult['ITEMS'] as $item)
    {

        if(Loader::includeModule("sale")){
            $showCompany = "";
            $showOrder = "";
            if(!empty($COMPANY_DETAIL_PATH) && $item['PROPERTIES']['ORGANIZATION']['VALUE']){

                $issetProfile = checkIssetProfile($item['PROPERTIES']['ORGANIZATION']['VALUE'], $item['PROPERTIES']['USER']['VALUE']);

                if(!$issetProfile){
                    $idProfile = [];
                    $dbProp = CSaleOrderUserPropsValue::GetList(
                        [],
                        ["VALUE"=>htmlspecialchars_decode($item['PROPERTIES']['ORGANIZATION']['VALUE'])],
                        false,
                        false,
                        ["USER_PROPS_ID"]
                    );
                    while ($arProps = $dbProp->Fetch())
                    {
                        $idProfile[] = $arProps["USER_PROPS_ID"];
                    }
                    if($idProfile){
                        $issetProfile = checkIssetProfile($idProfile, $item['PROPERTIES']['USER']['VALUE']);
                        if($issetProfile){

                            foreach ($idProfile as $id){
                                $companyPath = preg_replace("/#.*#/", $id, $COMPANY_DETAIL_PATH);
                                $showCompany .= '<p><a href="'.$companyPath.'">'.$item['PROPERTIES']['ORGANIZATION']['VALUE'].'</a></p>';
                            }
                        }
                    }
                }

                if(!$issetProfile){
                    $showCompany = '<span class="not-found">'.$item['PROPERTIES']['ORGANIZATION']['VALUE'].'</span>';
                }
                elseif ($issetProfile && !$idProfile){
                    $companyPath = preg_replace("/#.*#/", $item['PROPERTIES']['ORGANIZATION']['VALUE'], $COMPANY_DETAIL_PATH);
                    $showCompany = '<a href="'.$companyPath.'">'.$item['PROPERTIES']['ORGANIZATION']['VALUE'].'</a>';
                }

            }

            if(!empty($ORDER_DETAIL_PATH) && $item['PROPERTIES']['ORDER']['VALUE']){
                $orderPath = '';
                foreach ($item['PROPERTIES']['ORDER']['VALUE'] as $order){
                    $arFilter = [
                        "USER_ID"=>$item['PROPERTIES']['USER']['VALUE'],
                        'ID' => $order
                    ];
                    $isSetOrder = CSaleOrder::GetList([], $arFilter, false, false, ["ID"], [])->fetch();
                    if (!$isSetOrder)
                    {
                        $showOrder .= '<p><span  class="not-found">'.$order.'</span></p>';
                    }
                    else{
                        $orderPath = preg_replace("/#.*#/", $order, $ORDER_DETAIL_PATH);
                        $showOrder .= '<p><a href="'.$orderPath.'">'.$order.'</a></p>';
                    }
                }
            }
        }

        $actions = [];
        $name = $item["NAME"];
        if($files[$item['PROPERTIES']['DOCUMENT']['VALUE']])
        {
            $name = '<a href="'.$files[$item['PROPERTIES']['DOCUMENT']['VALUE']].'" download>'.$item["NAME"]
                .'</a>';
            $actions = [
                [
                    "ICONCLASS" => "download",
                    "TEXT" => Loc::getMessage('DOC_DOWNLOAD'),
                    "ONCLICK" => "jsUtils.Redirect(arguments, '" . $files[$item['PROPERTIES']['DOCUMENT']['VALUE']] . "')",
                    "DEFAULT" => true
                ]
            ];
        }
        $arResult['ROWS'][] = [
            'data' => [
                "ID" => $item['ID'],
                "NAME" => $name,
                'DATE_CREATE' => $item["DATE_CREATE"],
                'DATE_UPDATE' => $item["TIMESTAMP_X"],
                'ORDER' => $showOrder,
                'ORGANIZATION' => $showCompany,
            ],
            'actions' => $actions,
            'COLUMNS' => [
                "ID" => $item['ID'],
                "NAME" => $item["NAME"],
                'DATE_CREATE' => $item["DATE_CREATE"],
                'DATE_UPDATE' => $item["TIMESTAMP_X"],
                'ORDER' => $showOrder,
                'ORGANIZATION' => $showCompany,
            ],
            'editable' => true,
        ];
    }
}
?>