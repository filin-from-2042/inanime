<?
define('STOP_STATISTICS', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$GLOBALS['APPLICATION']->RestartBuffer();


if (strlen($_REQUEST["values"]["page"])>0) {$page=$_REQUEST["values"]["page"];unset($_REQUEST["values"]["page"]);} else $page=false;
foreach ($_REQUEST["values"] as $key=>$value) {
if ($page) $_SESSION["USER_VALUES"][$page][$key]=$value; else $_SESSION["USER_VALUES"][$key]=$value;
}
echo json_encode(array("success"=>true));


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>