<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arRes = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("USER", 0, LANGUAGE_ID);
$userProp = array();
if (!empty($arRes))
{
    foreach ($arRes as $key => $val)
        $userProp[$val["FIELD_NAME"]] = (strLen($val["EDIT_FORM_LABEL"]) > 0 ? $val["EDIT_FORM_LABEL"] : $val["FIELD_NAME"]);
}

$userProp1 = array(
    "LOGIN" => GetMessage("MAIN_UL_P_LOGIN"),
    "NAME" => GetMessage("MAIN_UL_P_NAME"),
    "SECOND_NAME" => GetMessage("MAIN_UL_P_SECOND_NAME"),
    "LAST_NAME" => GetMessage("MAIN_UL_P_LAST_NAME"),
    "EMAIL" => GetMessage("MAIN_UL_P_EMAIL"),
    "LAST_LOGIN" => GetMessage("MAIN_UL_P_LAST_LOGIN"),
    "DATE_REGISTER" => GetMessage("MAIN_UL_P_DATE_REGISTER"),
    "PERSONAL_BIRTHDAY" => GetMessage("MAIN_UL_P_PERSONAL_BIRTHDAY"),
    "PERSONAL_PROFESSION" => GetMessage("MAIN_UL_P_PERSONAL_PROFESSION"),
    "PERSONAL_WWW" => GetMessage("MAIN_UL_P_PERSONAL_WWW"),
    "PERSONAL_ICQ" => GetMessage("MAIN_UL_P_PERSONAL_ICQ"),
    "PERSONAL_GENDER" => GetMessage("MAIN_UL_P_PERSONAL_GENDER"),
    "PERSONAL_PHOTO" => GetMessage("MAIN_UL_P_PERSONAL_PHOTO"),
    "PERSONAL_NOTES" => GetMessage("MAIN_UL_P_PERSONAL_NOTES"),
    "PERSONAL_PHONE" => GetMessage("MAIN_UL_P_PERSONAL_PHONE"),
    "PERSONAL_FAX" => GetMessage("MAIN_UL_P_PERSONAL_FAX"),
    "PERSONAL_MOBILE" => GetMessage("MAIN_UL_P_PERSONAL_MOBILE"),
    "PERSONAL_PAGER" => GetMessage("MAIN_UL_P_PERSONAL_PAGER"),
    "PERSONAL_COUNTRY" => GetMessage("MAIN_UL_P_PERSONAL_COUNTRY"),
    "PERSONAL_STATE" => GetMessage("MAIN_UL_P_PERSONAL_STATE"),
    "PERSONAL_CITY" => GetMessage("MAIN_UL_P_PERSONAL_CITY"),
    "PERSONAL_ZIP" => GetMessage("MAIN_UL_P_PERSONAL_ZIP"),
    "PERSONAL_STREET" => GetMessage("MAIN_UL_P_PERSONAL_STREET"),
    "PERSONAL_MAILBOX" => GetMessage("MAIN_UL_P_PERSONAL_MAILBOX"),
    "WORK_COMPANY" => GetMessage("MAIN_UL_P_WORK_COMPANY"),
    "WORK_DEPARTMENT" => GetMessage("MAIN_UL_P_WORK_DEPARTMENT"),
    "WORK_POSITION" => GetMessage("MAIN_UL_P_WORK_POSITION"),
    "WORK_WWW" => GetMessage("MAIN_UL_P_WORK_WWW"),
    "WORK_PROFILE" => GetMessage("MAIN_UL_P_WORK_PROFILE"),
    "WORK_LOGO" => GetMessage("MAIN_UL_P_WORK_LOGO"),
    "WORK_NOTES" => GetMessage("MAIN_UL_P_WORK_NOTES"),
    "WORK_PHONE" => GetMessage("MAIN_UL_P_WORK_PHONE"),
    "WORK_FAX" => GetMessage("MAIN_UL_P_WORK_FAX"),
    "WORK_PAGER" => GetMessage("MAIN_UL_P_WORK_PAGER"),
    "WORK_COUNTRY" => GetMessage("MAIN_UL_P_WORK_COUNTRY"),
    "WORK_STATE" => GetMessage("MAIN_UL_P_WORK_STATE"),
    "WORK_CITY" => GetMessage("MAIN_UL_P_WORK_CITY"),
    "WORK_ZIP" => GetMessage("MAIN_UL_P_WORK_ZIP"),
    "WORK_STREET" => GetMessage("MAIN_UL_P_WORK_STREET"),
    "WORK_MAILBOX" => GetMessage("MAIN_UL_P_WORK_MAILBOX"),
);

$arUserFieldsDef = array(
    "NAME",
    "PERSONAL_PHOTO"
);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(

		"MANAGER_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ELEMENT_ID"),
			"TYPE" => "STRING",
		),
	),
);

$arComponentParameters["PARAMETERS"]["SHOW_FIELDS"] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("MAIN_UL_P_SHOW_FIELDS"),
    "TYPE" => "LIST",
    "VALUES" => $userProp1,
    "MULTIPLE" => "Y",
    "DEFAULT" => $arUserFieldsDef,
    "REFRESH" => 'Y'
);

$arComponentParameters["PARAMETERS"]["USER_PROPERTY"] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("MAIN_UL_P_USER_PROPERTY"),
    "TYPE" => "LIST",
    "VALUES" => $userProp,
    "MULTIPLE" => "Y",
);


?>