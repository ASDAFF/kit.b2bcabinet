<?
$aMenuLinks = Array(
	Array(
		"Главная",
		"",
		Array(),
		Array("ICON_CLASS"=>"icon-home4"),
		"\\Bitrix\\Main\\Loader::includeModule('kit.b2bcabinet')"
	),
	Array(
		"Персональные данные",
		"personal/",
		Array(),
		Array(),
		"\\Bitrix\\Main\\Loader::includeModule('kit.b2bcabinet')"
	),
	Array(
		"Заказы",
		"orders/",
		Array(),
		Array(),
		"\\Bitrix\\Main\\Loader::includeModule('kit.b2bcabinet')"
	),
	Array(
		"Документы",
		"documents/",
		Array(),
		Array("ICON_CLASS"=>"icon-stack-text"),
		"\\Bitrix\\Main\\Loader::includeModule('kit.b2bcabinet') && \\Kit\\B2bCabinet\\Helper\\Document::getIblocks()"
	),
	Array(
		"Техническая поддержка",
		"support/",
		Array(),
		Array(),
		"\\Bitrix\\Main\\Loader::includeModule('kit.b2bcabinet') && \\Bitrix\\Main\\Loader::includeModule('support')"
	)
);
?>