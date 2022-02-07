<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

$module = 'kit.b2bcabinet';

if(CModule::includeModule($module) && CModule::includeModule('sale'))
{
    //support
    if(Loader::includeModule('support'))
    {
        //categories
        $arFields = [
            'C_TYPE' => 'C',
            'SID' => 'product_question',
            'SET_AS_DEFAULT' => 'N',
            'C_SORT' => 100,
            'NAME' => Loc::getMessage('WZD_SUPPORT_PRODUCT_QUESTION'),
            'DESCR' => '',
            'RESPONSIBLE_USER_ID' => 'NOT_REF',
            'arrSITE' => [WIZARD_SITE_ID],
        ];
        $ID = \CTicketDictionary::Add($arFields);

        $arFields['SID'] = 'order_question';
        $arFields['NAME'] = Loc::getMessage('WZD_SUPPORT_ORDER_QUESTION');
        $ID = \CTicketDictionary::Add($arFields);

        $arFields['SID'] = 'doc_question';
        $arFields['NAME'] = Loc::getMessage('WZD_SUPPORT_DOC_QUESTION');
        $ID = \CTicketDictionary::Add($arFields);

        $arFields['SID'] = 'other_question';
        $arFields['NAME'] = Loc::getMessage('WZD_SUPPORT_OTHER_QUESTION');
        $ID = \CTicketDictionary::Add($arFields);

        $arFields['SID'] = 'order_discussion';
        $arFields['NAME'] = Loc::getMessage('WZD_SUPPORT_ORDER_DISCUSSION');
        $ID = \CTicketDictionary::Add($arFields);

        //time
        $arFields = [
            'NAME' => Loc::getMessage('WZD_SUPPORT_TIME_NAME'),
            'DESCRIPTION' => '',
            'ArrShedule' => [
                0 => [
                    'OPEN_TIME' => 'CUSTOM',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '10:00',
                            'MINUTE_TILL' => '18:00'
                        ]
                    ]
                ],
                1 => [
                    'OPEN_TIME' => 'CUSTOM',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '10:00',
                            'MINUTE_TILL' => '18:00'
                        ]
                    ]
                ],
                2 => [
                    'OPEN_TIME' => 'CUSTOM',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '10:00',
                            'MINUTE_TILL' => '18:00'
                        ]
                    ]
                ],
                3 => [
                    'OPEN_TIME' => 'CUSTOM',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '10:00',
                            'MINUTE_TILL' => '18:00'
                        ]
                    ]
                ],
                4 => [
                    'OPEN_TIME' => 'CUSTOM',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '10:00',
                            'MINUTE_TILL' => '18:00'
                        ]
                    ]
                ],
                5 => [
                    'OPEN_TIME' => 'CLOSED',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '00:00',
                            'MINUTE_TILL' => '00:00'
                        ]
                    ]
                ],
                6 => [
                    'OPEN_TIME' => 'CLOSED',
                    'CUSTOM_TIME' => [
                        0 => [
                            'MINUTE_FROM' => '00:00',
                            'MINUTE_TILL' => '00:00'
                        ]
                    ]
                ],
            ]

        ];

        $postTimeTableFields = new \CSupportTableFields(\CSupportTimetable::$fieldsTypes);
        $postTimeTableFields->FromArray($arFields);

        $postTimeTableSheduleFields = new \CSupportTableFields(\CSupportTimetable::$fieldsTypesShedule,
            \CSupportTableFields::C_Table);

        $postTimeTableSheduleFields->RemoveExistingRows();
        $arrTTS = [];
        foreach ($arFields['ArrShedule'] as $DateWeekday => $arDay)
        {
            if(!isset($arDay["OPEN_TIME"]) || strlen($arDay["OPEN_TIME"]) <= 0) continue;
            if($arDay["OPEN_TIME"] == "CUSTOM" && !(isset($arDay["CUSTOM_TIME"]) && is_array($arDay["CUSTOM_TIME"]) && count($arDay["CUSTOM_TIME"]) > 0)) continue;

            $arrTTS["TIMETABLE_ID"] = $postTimeTableFields->ID;
            $arrTTS["WEEKDAY_NUMBER"] = $DateWeekday;
            $arrTTS["OPEN_TIME"] = $arDay["OPEN_TIME"];
            if($arDay["OPEN_TIME"] == "CUSTOM")
            {
                foreach ($arDay["CUSTOM_TIME"] as $ar)
                {
                    $presMF = (isset($ar["MINUTE_FROM"]) && strlen($ar["MINUTE_FROM"]) > 0);
                    $presMT = (isset($ar["MINUTE_TILL"]) && strlen($ar["MINUTE_TILL"]) > 0);
                    if($presMF || $presMT)
                    {

                        $minute_from = KitWizardStrToTime(($presMF ? $ar["MINUTE_FROM"] : "00:00"));
                        $minute_till = KitWizardStrToTime(($presMT ? $ar["MINUTE_TILL"] : "23:59"));
                        $postTimeTableSheduleFields->AddRow();
                        $postTimeTableSheduleFields->FromArray($arrTTS);
                        $postTimeTableSheduleFields->MINUTE_FROM = min($minute_from, $minute_till);
                        $postTimeTableSheduleFields->MINUTE_TILL = max($minute_from, $minute_till);
                    }
                }
            }
            else
            {
                $postTimeTableSheduleFields->AddRow();
                $postTimeTableSheduleFields->FromArray($arrTTS);
            }
        }

        \CSupportTimetable::Set($postTimeTableFields, $postTimeTableSheduleFields);

        //level
        $ID = \CTicketSLA::Set([
            'RESPONSE_TIME' => 8,
            'RESPONSE_TIME_UNIT' => 'hour',
            'NOTICE_TIME' => 1,
            'NOTICE_TIME_UNIT' => 'hour',
        ], 1);

        //group
        global $APPLICATION;
        $APPLICATION->SetGroupRight('support', 6, 'R');

        //default mark
        $arFields = array(
            'C_TYPE'				=> 'M',
            'SID'					=> 'NOT_SURE',
            'C_SORT'				=> '100',
            'NAME'					=> Loc::getMessage('WZD_SUPPORT_NOT_SURE'),
            'DESCR'					=> '',
            'arrSITE'				=> [WIZARD_SITE_ID],
        );
        \CTicketDictionary::Add($arFields);



        $regGroup = \Bitrix\Main\GroupTable::getList(['filter'=>['STRING_ID' => ['REGISTERED_USERS']]])->fetch();
        if($regGroup['ID'] > 0)
        {
            $APPLICATION->SetGroupRight('support', $regGroup['ID'], 'R');
        }


        //new groups
        $group = new \CGroup;
        $arFields = Array(
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => Loc::getMessage('WZD_SUPPORT_GROUP_SUPPORT_ADMIN'),
            "DESCRIPTION"  => "",
            "USER_ID"      => array(1),
            "STRING_ID"      => "SUPPORT_ADMIN"
        );
        $idGroup = $group->Add($arFields);
        if($idGroup > 0)
        {
            $APPLICATION->SetGroupRight('support', $idGroup, 'W');
        }

        $group = new \CGroup;
        $arFields = Array(
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => Loc::getMessage('WZD_SUPPORT_GROUP_SUPPORT'),
            "DESCRIPTION"  => "",
            "USER_ID"      => array(),
            "STRING_ID"      => "SUPPORT"
        );
        $idGroup = $group->Add($arFields);
        if($idGroup > 0)
        {
            $APPLICATION->SetGroupRight('support', $idGroup, 'T');
        }


        $group = new \CGroup;
        $arFields = Array(
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => Loc::getMessage('WZD_SUPPORT_GROUP_PANEL'),
            "DESCRIPTION"  => "",
            "USER_ID"      => array(),
            "STRING_ID"      => "CONTROL_PANEL_USERS"
        );
        $idGroup = $group->Add($arFields);
        if($idGroup > 0)
        {
            $APPLICATION->SetGroupRight('main', $idGroup, 'P',false);
            \CGroup::SetTasks($idGroup, array('main' => 2));

            $groups = [];
            $db_groups = \CGroup::GetList($order="sort", $by="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
            while($arGroup = $db_groups->Fetch())
            {
                $rule = $APPLICATION->GetFileAccessPermission(array(WIZARD_SITE_ID,"/bitrix/admin"),[$arGroup['ID']]);
                $groups[$arGroup['ID']] = $rule;
            }
            $groups['*'] = 'D';
            $groups[$idGroup] = 'R';

            $APPLICATION->SetFileAccessPermission(array(WIZARD_SITE_ID,"/bitrix/admin"), $groups);
        }

        $oUserTypeEntity    = new \CUserTypeEntity();

        $aUserFields    = array(
            'ENTITY_ID'         => 'SUPPORT',
            'FIELD_NAME'        => 'UF_ORDER',
            'USER_TYPE_ID'      => 'integer',
            'XML_ID'            => 'UF_ORDER',
            'SORT'              => 500,
            'MULTIPLE'          => 'N',
            'MANDATORY'         => 'N',
            'SHOW_FILTER'       => 'N',
            'SHOW_IN_LIST'      => '',
            'EDIT_IN_LIST'      => 'Y',
            'IS_SEARCHABLE'     => 'N',
            'SETTINGS'          => array(
                'DEFAULT_VALUE' => '',
                'SIZE'          => '20',
                'ROWS'          => '1',
                'MIN_LENGTH'    => '0',
                'MAX_LENGTH'    => '0',
                'REGEXP'        => '',
            ),
            'EDIT_FORM_LABEL'   => array(
                'ru'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
                'en'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
                'en'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
                'en'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
            ),
            'ERROR_MESSAGE'     => array(
                'ru'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
                'en'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
                'en'    => Loc::getMessage('WZD_SUPPORT_ORDER'),
            ),
        );

        $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
    }
}

function KitWizardStrToTime($t)
{
    $a = explode(":", $t);
    $res = (isset($a[0]) ? intval($a[0]) * 60 : 0);
    $res += (isset($a[1]) ? intval($a[1]) : 0);

    return $res;
}