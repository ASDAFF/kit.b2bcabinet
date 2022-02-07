<?
$aMenuLinks = [
    [
        "Главная",
        SITE_DIR,
        [],
        ['ICON_CLASS' => 'icon-home4'],
        ""
    ],
	[
		"Персональные данные",
        SITE_DIR."personal/",
		[],
        [],
		""
	],
    [
        "Заказы",
        SITE_DIR."orders/",
        [],
        [],
        ""
    ],
    [
        "Документы",
        SITE_DIR."documents/",
        [],
        [],
        ""
    ]
];

if(\Bitrix\Main\Loader::includeModule('support')) {
    $aMenuLinks[] = [
        "Техническая поддержка",
        SITE_DIR."support/",
        [],
        [],
        ""
    ];
}
?>