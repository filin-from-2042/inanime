<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (check_bitrix_sessid())
{
    $locationID = intval($_REQUEST["locationID"]);
    if($locationID>0) $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'] = $locationID;
}