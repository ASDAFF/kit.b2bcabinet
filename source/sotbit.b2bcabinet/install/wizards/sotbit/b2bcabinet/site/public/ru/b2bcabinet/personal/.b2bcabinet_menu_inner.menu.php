<?
$aMenuLinks = [
    [
        "Личная информация",
        SITE_DIR."b2bcabinet/personal/index.php",
        [],
        [
            'ICON_CLASS' => 'icon-user'
        ],
        ""
    ],
    [
        "Организации",
        SITE_DIR."b2bcabinet/personal/buyer/index.php",
        [
            SITE_DIR."b2bcabinet/personal/buyer/add.php",
            SITE_DIR."b2bcabinet/personal/buyer/profile_detail.php",
            SITE_DIR."b2bcabinet/personal/buyer/profile_list.php"
        ],
        [
            'ICON_CLASS' => 'icon-users2'
        ],
        ""
    ],
    [
        "Личный счет",
        SITE_DIR."b2bcabinet/personal/account/index.php",
        [],
        [
            'ICON_CLASS' => 'icon-credit-card'
        ],
        ""
    ]
];
?>