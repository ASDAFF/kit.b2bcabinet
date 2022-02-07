<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

function ___writeToAreasFile($path, $text)
{
	$fd = @fopen($path, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($path, BX_FILE_PERMISSIONS);
}

if (COption::GetOptionString("main", "upload_dir") == "")
	COption::SetOptionString("main", "upload_dir", "upload");

$methodInstall = $wizard->GetVar("method_install");

COption::SetOptionString("kit.b2bcabinet",'TEL',$wizard->GetVar("siteTelephone"));
COption::SetOptionString("kit.b2bcabinet",'COPYRIGHT',$wizard->GetVar("siteCopy"));
COption::SetOptionString("kit.b2bcabinet",'EMAIL',$wizard->GetVar("shopEmail"));

COption::SetOptionString("kit.b2bcabinet",'URL_CART',WIZARD_SITE_DIR.'personal/cart/');
COption::SetOptionString("kit.b2bcabinet",'URL_ORDER',WIZARD_SITE_DIR.'personal/order/make/');
COption::SetOptionString("kit.b2bcabinet",'URL_PERSONAL',WIZARD_SITE_DIR.'personal/');
COption::SetOptionString("kit.b2bcabinet",'URL_PAYMENT',WIZARD_SITE_DIR.'personal/order/payment/');
COption::SetOptionString("kit.b2bcabinet",'URL_PAGE_ORDER',WIZARD_SITE_DIR.'personal/order/');
//COption::SetOptionString("kit.b2bcabinet",'TABLE_SIZE_URL',WIZARD_SITE_DIR.'clients/table_sizes/#table');
COption::SetOptionString("kit.b2bcabinet", 'DETAIL_TEXT_INCLUDE', GetMessage('DETAIL_TEXT_INCLUDE', array('#SITE_DIR#' => WIZARD_SITE_DIR)));

$wizard =& $this->GetWizard();/*
___writeToAreasFile(WIZARD_SITE_PATH."include/company_name.php", $wizard->GetVar("siteName"));
___writeToAreasFile(WIZARD_SITE_PATH."include/copyright.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include/schedule.php", $wizard->GetVar("siteSchedule"));
___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));
*/


if(COption::GetOptionString("kit.b2bcabinet", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite = array(
    array(
        "CONDITION" => '#^'. WIZARD_SITE_DIR .($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'documents/#',
        "RULE" => "",
        "ID" => 'bitrix:news',
        "PATH" => WIZARD_SITE_DIR . ($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'documents/index.php',
    ),
    array (
        'CONDITION' => '#^'. WIZARD_SITE_DIR .($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'order/#',
        'RULE' => '',
        'ID' => 'bitrix:sale.personal.order',
        'PATH' => WIZARD_SITE_DIR . ($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'orders/index.php',
    ),
    array (
        'CONDITION' => '#^'. WIZARD_SITE_DIR .($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'personal/buyer/#',
        'RULE' => '',
        'ID' => 'bitrix:sale.personal.profile',
        'PATH' => WIZARD_SITE_DIR . ($methodInstall == 'AS_TEMPLATE' ? "b2bcabinet/" : "" ) .'personal/buyer/index.php',
    ),
);

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}

$methodInstall = $wizard->GetVar("method_install");
//    $filePath = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.b2bcabinet/install/wizards/kit/b2bcabinet/site/public/ru/";
$filePath = $_SERVER["DOCUMENT_ROOT"].WIZARD_RELATIVE_PATH.'/site/public/ru/';

if($methodInstall == 'AS_SITE')
{
    CopyDirFiles($filePath . "site/", $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR, true, true);
}
else if($methodInstall == 'AS_TEMPLATE')
{
    if(!is_dir($_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR."b2bcabinet/")) {
        mkdir($_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR."b2bcabinet/", 0775, true);
    }

    CopyDirFiles($filePath . "b2bcabinet/", $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR."b2bcabinet/", true, true);
}

CopyDirFiles($filePath . "common/", $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR, true, true);
CopyDirFiles($filePath . "root/", $_SERVER["DOCUMENT_ROOT"].'/', true, true);

if(WIZARD_SITE_DIR != '/') {
    if(is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix'))
        symlink($_SERVER['DOCUMENT_ROOT'].'/bitrix', $_SERVER['DOCUMENT_ROOT'].WIZARD_SITE_DIR.'bitrix');

    if(is_dir($_SERVER['DOCUMENT_ROOT'].'/upload'))
        symlink($_SERVER['DOCUMENT_ROOT'].'/upload', $_SERVER['DOCUMENT_ROOT'].WIZARD_SITE_DIR.'upload');

    if(is_dir($_SERVER['DOCUMENT_ROOT'].'/local'))
        symlink($_SERVER['DOCUMENT_ROOT'].'/local', $_SERVER['DOCUMENT_ROOT'].WIZARD_SITE_DIR.'local');
}
?>