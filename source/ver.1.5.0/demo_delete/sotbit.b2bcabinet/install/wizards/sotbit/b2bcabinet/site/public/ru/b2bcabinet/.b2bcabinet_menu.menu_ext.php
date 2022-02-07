<?
$aMenuLinks = [
    [
        "Главная",
        SITE_DIR."b2bcabinet/",
        [],
        ['ICON_CLASS' => 'icon-home4'],
        ""
    ],
	[
		"Персональные данные",
        SITE_DIR."b2bcabinet/personal/",
		[],
        [],
		""
	],
    [
        "Заказы",
        SITE_DIR."b2bcabinet/orders/",
        [],
        [],
        ""
    ],
    [
        "Документы",
        SITE_DIR."b2bcabinet/documents/",
        [],
        [],
        ""
    ]
];

if(\Bitrix\Main\Loader::includeModule('support')) {
    $aMenuLinks[] = [
        "Техническая поддержка",
        SITE_DIR."b2bcabinet/support/",
        [],
        [],
        ""
    ];
}
?>