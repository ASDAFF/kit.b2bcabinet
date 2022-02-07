<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

$OPT1_GROUP_ID = 0;
$Group = CGroup::GetList( ($by = "c_sort"), ($order = "desc"), array(
    'STRING_ID' => 'OPT1'
) )->fetch();
if( !$Group['ID'] )
{
    $group = new CGroup();
    $arFields = Array(
        "ACTIVE" => "Y",
        "C_SORT" => 100,
        "NAME" => GetMessage( "GROUP_OPT1" ),
        "DESCRIPTION" => "",
        "USER_ID" => array(),
        "STRING_ID" => "OPT1"
    );
    $OPT1_GROUP_ID = $group->Add( $arFields );
}
else
{
    $OPT1_GROUP_ID = $Group['ID'];
}

$OPT2_GROUP_ID = 0;
$Group = CGroup::GetList( ($by = "c_sort"), ($order = "desc"), array(
    'STRING_ID' => 'OPT2'
) )->fetch();
if( !$Group['ID'] )
{
    $group = new CGroup();
    $arFields = Array(
        "ACTIVE" => "Y",
        "C_SORT" => 100,
        "NAME" => GetMessage( "GROUP_OPT2" ),
        "DESCRIPTION" => "",
        "USER_ID" => array(),
        "STRING_ID" => "OPT2"
    );
    $OPT2_GROUP_ID = $group->Add( $arFields );
}
else
{
    $OPT2_GROUP_ID = $Group['ID'];
}

$OPT3_GROUP_ID = 0;
$Group = CGroup::GetList( ($by = "c_sort"), ($order = "desc"), array(
    'STRING_ID' => 'OPT3'
) )->fetch();
if( !$Group['ID'] )
{
    $group = new CGroup();
    $arFields = Array(
        "ACTIVE" => "Y",
        "C_SORT" => 100,
        "NAME" => GetMessage( "GROUP_OPT3" ),
        "DESCRIPTION" => "",
        "USER_ID" => array(),
        "STRING_ID" => "OPT3"
    );
    $OPT3_GROUP_ID = $group->Add( $arFields );
}
else
{
    $OPT3_GROUP_ID = $Group['ID'];
}
?>