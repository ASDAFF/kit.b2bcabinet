<?
$aMenuLinks = [
	[
		"Бланк заказа",
        SITE_DIR."b2bcabinet/orders/blank_zakaza/index.php",
		[],
		[
            'ICON_CLASS' => 'icon-pencil3'
        ],
		""
	],
	[
		"Состояние заказов",
        SITE_DIR."b2bcabinet/orders/index.php",
		[
            SITE_DIR."b2bcabinet/order/detail/"
        ],
		[
            'ICON_CLASS' => 'icon-history'
        ],
		""
	],
	[
		"Оформление заказа",
        SITE_DIR."b2bcabinet/orders/make/index.php",
		[
		    SITE_DIR."b2bcabinet/orders/make/make.php"
        ],
		[
            'ICON_CLASS' => 'icon-clipboard5'
        ],
		""
	],
];
?>