<?
$aMenuLinks = [
	[
		"Бланк заказа",
        SITE_DIR."orders/blank_zakaza/index.php",
		[],
		[
            'ICON_CLASS' => 'icon-pencil3'
        ],
		""
	],
	[
		"Состояние заказов",
        SITE_DIR."orders/index.php",
		[
            SITE_DIR."order/detail/"
        ],
		[
            'ICON_CLASS' => 'icon-history'
        ],
		""
	],
	[
		"Оформление заказа",
        SITE_DIR."orders/make/index.php",
		[
		    SITE_DIR."orders/make/make.php"
        ],
		[
            'ICON_CLASS' => 'icon-clipboard5'
        ],
		""
	],
];
?>