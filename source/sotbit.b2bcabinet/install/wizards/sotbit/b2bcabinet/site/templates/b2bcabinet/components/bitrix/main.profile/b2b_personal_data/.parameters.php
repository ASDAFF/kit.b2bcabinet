<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

global  $APPLICATION,
        $USER,
        $USER_FIELD_MANAGER;

$hideSection = array(
    "HIDE" => Loc::getMessage('HIDE_SECTION')
);

if($userID = intval($USER->GetID())) {
    $arrRes = CUser::GetByID($userID)->fetch();

    if($arrRes) {
        $generalFields = array(
            'TITLE',
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
            'EMAIL',
            'LOGIN'
        );

        $arrData['GENERAL'] = $hideSection;

        foreach ($generalFields as $key => $generalField) {
            if(array_key_exists($generalField, $arrRes)) {
                $arrData['GENERAL'][$generalField] = Loc::getMessage($generalField);
            }
        }

        $personalFields = array(
            'PERSONAL_PROFESSION',
            'PERSONAL_WWW',
            'PERSONAL_ICQ',
            'PERSONAL_GENDER',
            'PERSONAL_BIRTHDAY',
            'PERSONAL_PHOTO',
            'PERSONAL_PHONE',
            'PERSONAL_FAX',
            'PERSONAL_MOBILE',
            'PERSONAL_PAGER',
            'PERSONAL_COUNTRY',
            'PERSONAL_STATE',
            'PERSONAL_CITY',
            'PERSONAL_ZIP',
            'PERSONAL_STREET',
            'PERSONAL_MAILBOX',
            'PERSONAL_NOTES',
        );

        $arrData['PERSONAL'] = $hideSection;
        foreach ($personalFields as $key => $personalField) {
            if(array_key_exists($personalField, $arrRes)) {
                $arrData['PERSONAL'][$personalField] = Loc::getMessage($personalField);
            }
        }

        $workFields = array(
            'WORK_COMPANY',
            'WORK_WWW',
            'WORK_DEPARTMENT',
            'WORK_POSITION',
            'WORK_PROFILE',
            'WORK_LOGO',
            'WORK_PHONE',
            'WORK_FAX',
            'WORK_PAGER',
            'WORK_COUNTRY',
            'WORK_STATE',
            'WORK_CITY',
            'WORK_ZIP',
            'WORK_STREET',
            'WORK_MAILBOX',
            'WORK_NOTES',
        );

        $arrData['WORK'] = $hideSection;
        foreach ($workFields as $key => $workField) {
            if(array_key_exists($workField, $arrRes)) {
                $arrData['WORK'][$workField] = Loc::getMessage($workField);
            }
        }
    }

    if(CModule::IncludeModule("forum")) {
        $forumFields = array(
            'ALLOW_POST',
            'SHOW_NAME',
            'DESCRIPTION',
            'INTERESTS',
            'SIGNATURE',
            'AVATAR'
        );

//        $arrRes = CForumUser::GetList(
//            array(),
//            array("USER_ID" => $userID)
//        )->fetch();

//        if($arrRes) {
            $arrData['FORUM'] = $hideSection;
            foreach ($forumFields as $key => $forumField) {
//                if (array_key_exists($forumField, $arrRes)) {
                    $arrData['FORUM']['forum_' . $forumField] = Loc::getMessage('forum_' . $forumField);
//                }
            }
//        }
    }

    if (CModule::IncludeModule("blog")) {
        $blogFields = array(
            'ALIAS',
            'DESCRIPTION',
            'INTERESTS',
            'AVATAR'
        );

//        $arrRes = CBlogUser::GetByID($userID, BLOG_BY_USER_ID);
//        if($arrRes) {
            $arrData['BLOG'] = $hideSection;
            foreach ($blogFields as $key => $blogField) {
//                if (array_key_exists($blogField, $arrRes)) {
                    $arrData['BLOG']['blog_' . $blogField] = Loc::getMessage('blog_' . $blogField);
//                }
            }
//        }
    }

    if(CModule::IncludeModule("learning")) {
        $studentFields = array(
            'PUBLIC_PROFILE',
            'RESUME',
            'TRANSCRIPT',
        );

//        if($arrRes) {
            $arrData['STUDENT'] = $hideSection;
            foreach ($studentFields as $key => $studentField) {
                $arrData['STUDENT']['learning_' . $studentField] = Loc::getMessage('learning_' . $studentField);
            }
//        }
    }

    $arrData['USER_FIELDS'] = $USER_FIELD_MANAGER->GetUserFields('USER', $userID, LANGUAGE_ID);
};

$arTemplateParameters = array(
	"USER_PROPERTY_NAME"=>array(
		"NAME" => GetMessage("USER_PROPERTY_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	)
);

if(!empty($arrData['GENERAL']) && count($arrData['GENERAL']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_GENERAL_DATA"=> array(
                "NAME" => GetMessage("USER_PROPERTY_GENERAL_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['GENERAL']
            )
        )
    );
}

if(!empty($arrData['PERSONAL']) && count($arrData['PERSONAL']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_PERSONAL_DATA" => array(
                "NAME" => GetMessage("USER_PROPERTY_PERSONAL_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['PERSONAL']
            )
        )
    );
}

if(!empty($arrData['WORK']) && count($arrData['WORK']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_WORK_INFORMATION_DATA" => array(
                "NAME" => GetMessage("USER_PROPERTY_WORK_INFORMATION_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['WORK']
            )
        )
    );
}

if(!empty($arrData['FORUM']) && count($arrData['FORUM']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_FORUM_PROFILE_DATA" => array(
                "NAME" => GetMessage("USER_PROPERTY_FORUM_PROFILE_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['FORUM']
            )
        )
    );
}

if(!empty($arrData['BLOG']) && count($arrData['BLOG']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_BLOG_PROFILE_DATA" => array(
                "NAME" => GetMessage("USER_PROPERTY_BLOG_PROFILE_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['BLOG']
            )
        )
    );
}

if(!empty($arrData['STUDENT']) && count($arrData['STUDENT']) > 1) {
    $arTemplateParameters = array_merge($arTemplateParameters,
        array(
            "USER_PROPERTY_STUDENT_PROFILE_DATA" => array(
                "NAME" => GetMessage("USER_PROPERTY_STUDENT_PROFILE_DATA"),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arrData['STUDENT']
            )
        )
    );
}

$arTemplateParameters = array_merge(
    $arTemplateParameters ,
    array(
        "USER_PROPERTY_ADMIN_NOTE_DATA" => array(
            "NAME" => GetMessage("USER_PROPERTY_ADMIN_NOTE_DATA"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $hideSection
        )
    )
);
?>