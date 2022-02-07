<?
define('STOP_STATISTICS', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$state = $request->get("state");
if(isset($state)){
    $stateLeftPanel = CUserOptions::SetOption("intranet", "StateLeftPanel", $state);
}
?>