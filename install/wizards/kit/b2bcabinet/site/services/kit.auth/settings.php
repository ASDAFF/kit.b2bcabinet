<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

if( !CModule::IncludeModule( "kit.auth" ) )
    return;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Option::set("kit.auth", "LOGIN_EQ_EMAIL", "Y", WIZARD_SITE_ID);

CModule::includeModule('sale');
$arPersonTypeNames = array();
$dbPerson = CSalePersonType::GetList( array(), array(
    "LID" => WIZARD_SITE_ID
) );

while ( $arPerson = $dbPerson->Fetch() )
{
    $arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
}
$UrPersonTypes = array();

$idUr = array_search( GetMessage( "WZD_PERSON_TYPE_UR" ), $arPersonTypeNames );
$idIp = array_search( GetMessage( "WZD_PERSON_TYPE_IP" ), $arPersonTypeNames );

if($idUr > 0)
{
    Option::set("kit.auth", "GROUP_FIELDS_".$idUr, serialize(array('EMAIL')), WIZARD_SITE_ID);
    Option::set("kit.auth", "GROUP_REQUIRED_FIELDS_".$idUr, serialize(array('EMAIL')), WIZARD_SITE_ID);
    Option::set("kit.auth", "GROUP_ORDER_FIELDS_".$idUr, serialize(array('COMPANY','INN')), WIZARD_SITE_ID);
    array_push($UrPersonTypes, $idUr);
}

if($idIp > 0)
{
    Option::set("kit.auth", "GROUP_FIELDS_".$idIp, serialize(array('EMAIL')), WIZARD_SITE_ID);
    Option::set("kit.auth", "GROUP_REQUIRED_FIELDS_".$idIp, serialize(array('EMAIL')), WIZARD_SITE_ID);
    Option::set("kit.auth", "GROUP_ORDER_FIELDS_".$idIp, serialize(array('COMPANY','INN')), WIZARD_SITE_ID);
    array_push($UrPersonTypes, $idIp);
}

if($UrPersonTypes)
{
    Option::set("kit.auth", "WHOLESALERS_PERSON_TYPE", serialize($UrPersonTypes), WIZARD_SITE_ID);
}