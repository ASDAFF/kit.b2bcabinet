<?
$notRemove = array(
    'NEW_PASSWORD',
    'NEW_PASSWNEW_PASSWORD_CONFIRMORD',
    'SUBMIT_BUTTON'
);

//----- MAIN DATA
    if(strlen($arResult['arUser']['TIMESTAMP_X']) > 0)
        $arResult['MAIN_DATA']['TIMESTAMP_X'] = array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'TIMESTAMP_X',
            'TYPE' => 'text',
            'ATTR' => ['readonly'],
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['TIMESTAMP_X'],
        );
    if(strlen($arResult['arUser']['LAST_LOGIN']) > 0)
        $arResult['MAIN_DATA']['LAST_LOGIN'] = array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'LAST_LOGIN',
            'TYPE' => 'text',
            'ATTR' => ['readonly'],
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['LAST_LOGIN'],
        );

    $arResult['MAIN_DATA'] = array(
        'TITLE' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'TITLE',
            'TYPE' => 'text',
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['TITLE'],
        ),
        'NAME' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'NAME',
            'TYPE' => 'text',
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['NAME'],
        ),
        'LAST_NAME' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'LAST_NAME',
            'TYPE' => 'text',
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['LAST_NAME'],
        ),
        'SECOND_NAME' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'SECOND_NAME',
            'TYPE' => 'text',
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['SECOND_NAME'],
        ),
        'EMAIL' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'EMAIL',
            'TYPE' => 'text',
            'ATTR' => ['readonly', ( $arResult["EMAIL_REQUIRED"] ? 'required' : '')],
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['EMAIL'],
        ),
        'LOGIN' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'LOGIN',
            'TYPE' => 'text',
            'ATTR' => ['required'],
            'skip_manager' => 'Y',
            'VALUE' => $arResult['arUser']['LOGIN'],
        ),
    );

    if($arResult['CAN_EDIT_PASSWORD']) {
        $arResult['MAIN_DATA']['NEW_PASSWORD'] = array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'NEW_PASSWORD',
            'TYPE' => 'password',
            'PLACEHOLDER' => "********",
            'CLASS' => 'bx-auth-input',
            'SECURE_AUTH' => $arResult['SECURE_AUTH'],
            );
        $arResult['MAIN_DATA']['NEW_PASSWNEW_PASSWORD_CONFIRMORD'] = array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'NEW_PASSWORD_CONFIRM',
            'TYPE' => 'password',
            'PLACEHOLDER' => "********",
        );
    }

    $arResult['MAIN_DATA']['SUBMIT_BUTTON'] = array(
        'NAME' => 'SUBMIT_BUTTON',
        "arUserField" => array(
            "USER_TYPE" => "submit_button"
        ),
    );

if(!empty($arParams['USER_PROPERTY_GENERAL_DATA'])) {
    if($arParams['USER_PROPERTY_GENERAL_DATA'][0] == 'HIDE') {
        unset($arResult['MAIN_DATA']);
    } else {
        $arParams['USER_PROPERTY_GENERAL_DATA'] = array_merge($arParams['USER_PROPERTY_GENERAL_DATA'], $notRemove);
        $arResult['MAIN_DATA'] = array_intersect_key($arResult['MAIN_DATA'], array_flip($arParams['USER_PROPERTY_GENERAL_DATA']));
    }
}
$notRemove = array($notRemove[2]);
//----- \MAIN DATA

//----- PERSONAL DATA
    $arResult['PERSONAL_DATA'] = array(
        'PERSONAL_PROFESSION' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_PROFESSION',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_PROFESSION'],
        ),
        'PERSONAL_WWW' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_WWW',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_WWW'],
        ),
        'PERSONAL_ICQ' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_ICQ',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_ICQ'],
        ),
        'PERSONAL_GENDER' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "radio_button",
            'NAME' => 'PERSONAL_GENDER',
            'TYPE' => 'radio',
            'ELEMENTS' => array(
                0 => array(
                    "arUserField" => array(
                        "USER_TYPE" => "radio"
                    ),
                    'NAME' => 'PERSONAL_GENDER',
                    'ATTR' => array(
                        'data-func',
                        ( $arResult['arUser']['PERSONAL_GENDER'] == 'M' ? 'checked' : "" )
                    ),
                    'VALUE' => 'M',
                ),
                1 => array(
                    "arUserField" => array(
                        "USER_TYPE" => "radio"
                    ),
                    'NAME' => 'PERSONAL_GENDER',
                    'ATTR' => array(
                        'data-func',
                        ( $arResult['arUser']['PERSONAL_GENDER'] == 'F' ? 'checked' : "" )
                    ),
                    'VALUE' => 'F',
                ),
            ),

        ),
        'PERSONAL_BIRTHDAY' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "data_picker",
            'NAME' => 'PERSONAL_BIRTHDAY',
            'VALUE' => $arResult['arUser']['PERSONAL_BIRTHDAY'],
        ),
        'PERSONAL_PHOTO' => array(
            "arUserField" => array(
                "USER_TYPE" => "file"
            ),
            'NAME' => 'PERSONAL_PHOTO',
            'VALUE' => $arResult['arUser']['PERSONAL_PHOTO'],
        ),
        'USER_PHONES' => array(
            "arUserField" => array(
                "USER_TYPE" => "card_title"
            ),
            'NAME' => 'USER_PHONES',
        ),
        'PERSONAL_PHONE' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_PHONE',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_PHONE'],
        ),
        'PERSONAL_FAX' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_FAX',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_FAX'],
        ),
        'PERSONAL_MOBILE' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_MOBILE',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_MOBILE'],
        ),
        'PERSONAL_PAGER' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_PAGER',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_PAGER'],
        ),
        'USER_POST_ADDRESS' => array(
            "arUserField" => array(
                "USER_TYPE" => "card_title"
            ),
            'NAME' => 'USER_POST_ADDRESS',
        ),
        'PERSONAL_COUNTRY' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "html",
            'NAME' => 'PERSONAL_COUNTRY',
            'HTML' => $arResult['COUNTRY_SELECT'],
        ),
        'PERSONAL_STATE' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_STATE',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_STATE'],
        ),
        'PERSONAL_CITY' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_CITY',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_CITY'],
        ),
        'PERSONAL_ZIP' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_ZIP',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_ZIP'],
        ),
        'PERSONAL_STREET' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'NAME' => 'PERSONAL_STREET',
            'ATTR' => array(
                "cols='5'",
                "rows='5'"
            ),
            'VALUE' => $arResult['arUser']['PERSONAL_STREET'],
        ),
        'PERSONAL_MAILBOX' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'PERSONAL_MAILBOX',
            'TYPE' => 'text',
            'VALUE' => $arResult['arUser']['PERSONAL_MAILBOX'],
        ),
        'PERSONAL_NOTES' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'NAME' => 'PERSONAL_NOTES',
            'ATTR' => array(
                "cols='5'",
                "rows='5'"
            ),
            'VALUE' => $arResult['arUser']['PERSONAL_NOTES'],
        ),
        'SUBMIT_BUTTON' => array(
            'NAME' => 'SUBMIT_BUTTON',
            "arUserField" => array(
                "USER_TYPE" => "submit_button"
            ),
        )
);

if(!empty($arParams['USER_PROPERTY_PERSONAL_DATA'])) {
    if($arParams['USER_PROPERTY_PERSONAL_DATA'][0] == 'HIDE') {
        unset($arResult['PERSONAL_DATA']);
    } else {
        $arParams['USER_PROPERTY_PERSONAL_DATA'] = array_merge($arParams['USER_PROPERTY_PERSONAL_DATA'], $notRemove);
        $arResult['PERSONAL_DATA'] = array_intersect_key($arResult['PERSONAL_DATA'], array_flip($arParams['USER_PROPERTY_PERSONAL_DATA']));
    }
}
//----- \PERSONAL DATA

//----- WORK DATA
    $arResult['WORK_DATA'] = array(
    'WORK_COMPANY' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_COMPANY',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_COMPANY'],
    ),
    'WORK_WWW' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_WWW',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_WWW'],
    ),
    'WORK_DEPARTMENT' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_DEPARTMENT',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_DEPARTMENT'],
    ),
    'WORK_POSITION' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_POSITION',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_POSITION'],
    ),
    'WORK_PROFILE' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "textarea",
        'NAME' => 'WORK_PROFILE',
        'ATTR' => array(
            "cols='5'",
            "rows='5'"
        ),
        'VALUE' => $arResult['arUser']['WORK_PROFILE'],
    ),
    'WORK_LOGO' => array(
        "arUserField" => array(
            "USER_TYPE" => "file"
        ),
        'NAME' => 'WORK_LOGO',
        'VALUE' => $arResult['arUser']['WORK_LOGO'],
    ),
    'WORK_PHONES' => array(
        "arUserField" => array(
            "USER_TYPE" => "card_title"
        ),
        'NAME' => 'WORK_PHONES',
    ),
    'WORK_PHONE' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_PHONE',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_PHONE'],
    ),
    'WORK_FAX' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_FAX',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_FAX'],
    ),
    'WORK_PAGER' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_PAGER',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_PAGER'],
    ),
    'WORK_POST_ADDRESS' => array(
        "arUserField" => array(
            "USER_TYPE" => "card_title"
        ),
        'NAME' => 'WORK_POST_ADDRESS',
    ),
    'WORK_COUNTRY' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "html",
        'NAME' => 'WORK_COUNTRY',
        'HTML' => $arResult['COUNTRY_SELECT_WORK'],
    ),
    'WORK_STATE' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_STATE',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_STATE'],
    ),
    'WORK_CITY' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_CITY',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_CITY'],
    ),
    'WORK_ZIP' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_ZIP',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_ZIP'],
    ),
    'WORK_STREET' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_STREET',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_STREET'],
    ),
    'WORK_STREET' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "textarea",
        'NAME' => 'WORK_STREET',
        'ATTR' => array(
            "cols='5'",
            "rows='5'"
        ),
        'VALUE' => $arResult['arUser']['WORK_STREET'],
    ),
    'WORK_MAILBOX' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "input",
        'NAME' => 'WORK_MAILBOX',
        'TYPE' => 'text',
        'VALUE' => $arResult['arUser']['WORK_MAILBOX'],
    ),
    'WORK_NOTES' => array(
        "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
        "TEMPLATE" => "textarea",
        'NAME' => 'WORK_NOTES',
        'ATTR' => array(
            "cols='5'",
            "rows='5'"
        ),
        'VALUE' => $arResult['arUser']['WORK_NOTES'],
    ),
    'SUBMIT_BUTTON' => array(
        'NAME' => 'SUBMIT_BUTTON',
        "arUserField" => array(
            "USER_TYPE" => "submit_button"
        ),
    )
);

if(!empty($arParams['USER_PROPERTY_WORK_INFORMATION_DATA'])) {
    if($arParams['USER_PROPERTY_WORK_INFORMATION_DATA'][0] == 'HIDE') {
        unset($arResult['WORK_DATA']);
    } else {
        $arParams['USER_PROPERTY_WORK_INFORMATION_DATA'] = array_merge($arParams['USER_PROPERTY_WORK_INFORMATION_DATA'], $notRemove);
        $arResult['WORK_DATA'] = array_intersect_key($arResult['WORK_DATA'], array_flip($arParams['USER_PROPERTY_WORK_INFORMATION_DATA']));
    }
}
//----- \WORK DATA

//----- FORUM DATA
if ($arResult["INCLUDE_FORUM"] == "Y" && $arParams['USER_PROPERTY_FORUM_PROFILE_DATA'][0] != 'HIDE') {
    $arResult['FORUM_DATA'] = array(
        'forum_ALLOW_POST' => array(
            "arUserField" => array(
                "USER_TYPE" => "switch"
            ),
            'NAME' => 'forum_ALLOW_POST',
            'ATTR' => array(
                'data-fouc',
                ($arResult['arForumUser']['ALLOW_POST'] == 'Y' ? 'checked' : '')
            ),
            'VALUE' => $arResult['arForumUser']['ALLOW_POST'],
        ),
        'forum_SHOW_NAME' => array(
            "arUserField" => array(
                "USER_TYPE" => "switch"
            ),
            'NAME' => 'forum_SHOW_NAME',
            'ATTR' => array(
                'data-fouc',
                ($arResult['arForumUser']['SHOW_NAME'] == 'Y' ? 'checked' : '')
            ),
            'VALUE' => $arResult['arForumUser']['SHOW_NAME'],
        ),
        'forum_DESCRIPTION' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'forum_DESCRIPTION',
            'TYPE' => 'text',
            'VALUE' => $arResult['arForumUser']['DESCRIPTION'],
        ),
        'forum_INTERESTS' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'ATTR' => array(
                'colls="5"',
                'rows="5"',
            ),
            'NAME' => 'forum_INTERESTS',
            'VALUE' => $arResult['arForumUser']['INTERESTS'],
        ),
        'forum_SIGNATURE' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'ATTR' => array(
                'colls="5"',
                'rows="5"',
            ),
            'NAME' => 'forum_SIGNATURE',
            'VALUE' => $arResult['arForumUser']['SIGNATURE'],
        ),
        'forum_AVATAR' => array(
            "arUserField" => array(
                "USER_TYPE" => "file"
            ),
            'NAME' => 'forum_AVATAR',
            'VALUE' => $arResult['arForumUser']['AVATAR'],
        ),
        'SUBMIT_BUTTON' => array(
            'NAME' => 'SUBMIT_BUTTON',
            "arUserField" => array(
                "USER_TYPE" => "submit_button"
            ),
        )
    );

    if(!empty($arParams['USER_PROPERTY_FORUM_PROFILE_DATA'])) {
        $arParams['USER_PROPERTY_FORUM_PROFILE_DATA'] = array_merge($arParams['USER_PROPERTY_FORUM_PROFILE_DATA'], $notRemove);
        $arResult['FORUM_DATA'] = array_intersect_key($arResult['FORUM_DATA'], array_flip($arParams['USER_PROPERTY_FORUM_PROFILE_DATA']));
    }
}
//----- \FORUM DATA

//----- BLOG DATA
    if ($arResult["INCLUDE_BLOG"] == "Y" && $arParams['USER_PROPERTY_BLOG_PROFILE_DATA'][0] != 'HIDE') {
        $arResult['BLOG_DATA'] = array(
            'blog_ALIAS' => array(
                "arUserField" => array(
                    "USER_TYPE" => "profile_string"
                ),
                "TEMPLATE" => "input",
                'NAME' => 'blog_ALIAS',
                'TYPE' => 'text',
                'VALUE' => $arResult['arBlogUser']['ALIAS'],
            ),
            'blog_DESCRIPTION' => array(
                "arUserField" => array(
                    "USER_TYPE" => "profile_string"
                ),
                "TEMPLATE" => "input",
                'NAME' => 'blog_DESCRIPTION',
                'TYPE' => 'text',
                'VALUE' => $arResult['arBlogUser']['DESCRIPTION'],
            ),
            'blog_INTERESTS' => array(
                "arUserField" => array(
                    "USER_TYPE" => "profile_string"
                ),
                "TEMPLATE" => "textarea",
                'ATTR' => array(
                    'colls="30"',
                    'rows="5"',
                ),
                'NAME' => 'blog_INTERESTS',
                'VALUE' => $arResult['arBlogUser']['INTERESTS'],
            ),
            'blog_AVATAR' => array(
                "arUserField" => array(
                    "USER_TYPE" => "file"
                ),
                'NAME' => 'blog_AVATAR',
                'VALUE' => $arResult['arBlogUser']['AVATAR'],
            ),
            'SUBMIT_BUTTON' => array(
                'NAME' => 'SUBMIT_BUTTON',
                "arUserField" => array(
                    "USER_TYPE" => "submit_button"
                ),
            )
        );

        if(!empty($arParams['USER_PROPERTY_BLOG_PROFILE_DATA'])) {
            $arParams['USER_PROPERTY_BLOG_PROFILE_DATA'] = array_merge($arParams['USER_PROPERTY_BLOG_PROFILE_DATA'], $notRemove);
            $arResult['BLOG_DATA'] = array_intersect_key($arResult['BLOG_DATA'], array_flip($arParams['USER_PROPERTY_BLOG_PROFILE_DATA']));
        }
    }
//----- \BLOG DATA

//----- LEARNING DATA
if ($arResult["INCLUDE_LEARNING"] == "Y" && $arParams['USER_PROPERTY_STUDENT_PROFILE_DATA'][0] != 'HIDE') {
    $arResult['LEARNING_DATA'] = array(
        'learning_PUBLIC_PROFILE' => array(
            "arUserField" => array(
                "USER_TYPE" => "switch"
            ),
            'NAME' => 'learning_PUBLIC_PROFILE',
            'ATTR' => array(
                'data-fouc',
                ( $arResult['arStudent']['PUBLIC_PROFILE'] == 'Y' ? 'checked' : "" )
            ),
            'VALUE' => $arResult['arForumUser']['PUBLIC_PROFILE'],

        ),
        'learning_RESUME' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'ATTR' => array(
                'colls="5"',
                'rows="5"',
            ),
            'NAME' => 'learning_RESUME',
            'VALUE' => $arResult['arStudent']['RESUME'],
        ),
        'learning_TRANSCRIPT' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "input",
            'NAME' => 'learning_TRANSCRIPT',
            'TYPE' => 'text',
            'ATTR' => array(
                'readonly'
            ),
            'VALUE' => $arResult['arStudent']['TRANSCRIPT'] ." - ". $arResult['ID'],
        ),
        'SUBMIT_BUTTON' => array(
            'NAME' => 'SUBMIT_BUTTON',
            "arUserField" => array(
                "USER_TYPE" => "submit_button"
            ),
        )
    );

    if(!empty($arParams['USER_PROPERTY_STUDENT_PROFILE_DATA'])) {
        $arParams['USER_PROPERTY_STUDENT_PROFILE_DATA'] = array_merge($arParams['USER_PROPERTY_STUDENT_PROFILE_DATA'], $notRemove);
        $arResult['LEARNING_DATA'] = array_intersect_key($arResult['LEARNING_DATA'], array_flip($arParams['USER_PROPERTY_STUDENT_PROFILE_DATA']));
    }
}
//----- \LEARNING DATA

//----- ADMIN DATA
if ($arResult["IS_ADMIN"] == "Y" && $arParams['USER_PROPERTY_ADMIN_NOTE_DATA'][0] != 'HIDE') {
    $arResult['ADMIN_DATA'] = array(
        'ADMIN_NOTES' => array(
            "arUserField" => array(
                "USER_TYPE" => "profile_string"
            ),
            "TEMPLATE" => "textarea",
            'ATTR' => array(
                'colls="5"',
                'rows="5"',
            ),
            'NAME' => 'ADMIN_NOTES',
            'VALUE' => $arResult['arUser']['ADMIN_NOTES'],
        ),
        'SUBMIT_BUTTON' => array(
            'NAME' => 'SUBMIT_BUTTON',
            "arUserField" => array(
                "USER_TYPE" => "submit_button"
            ),
        )
    );
}
//----- \ADMIN DATA
?>