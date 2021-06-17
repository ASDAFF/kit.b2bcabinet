<?
$aMenuLinks = [
    [
        "Личная информация",
        SITE_DIR."personal/index.php",
        [],
        [
            'ICON_CLASS' => 'icon-user'
        ],
        ""
    ],
    [
        "Организации",
        SITE_DIR."personal/buyer/index.php",
        [
            SITE_DIR."personal/buyer/add.php",
            SITE_DIR."personal/buyer/profile_detail.php",
            SITE_DIR."personal/buyer/profile_list.php"
        ],
        [
            'ICON_CLASS' => 'icon-users2'
        ],
        ""
    ],
    [
        "Личный счет",
        SITE_DIR."personal/account/index.php",
        [],
        [
            'ICON_CLASS' => 'icon-credit-card'
        ],
        ""
    ]
];
?>