<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
Loader::IncludeModule('form');

$arFields1 = array(
    "NAME"              => GetMessage("FORM1_NAME"),
    "SID"               => "MANAGER_CALLBACK",
    "C_SORT"            => 100,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => GetMessage("FORM1_DESCRIPTION"),
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM1_NAME"), "en" => ""),
);

$NEW_ID1 = CForm::Set($arFields1);

if($NEW_ID1)
{
    Option::set('sotbit.b2bcabinet', 'B2BCABINET_FEED_BACK_FORM_ID', $NEW_ID1, WIZARD_SITE_ID);

    $questions = array(
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'NAME',
            'TITLE' => GetMessage("FORM_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'SOTBIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'SOTBIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_QUENST"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID1,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"		=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');
}
?>