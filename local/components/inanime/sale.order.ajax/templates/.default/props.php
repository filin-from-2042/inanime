<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php");
?>


<?
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"],array("ZIP"));
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"],array("FIO","EMAIL","PHONE"));
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"],array("ADDRESS"));
?>
    <textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>


<div style="display:none;">
<?
$APPLICATION->IncludeComponent(
        "bitrix:sale.ajax.locations", $arParams["TEMPLATE_LOCATION"], array(
    "AJAX_CALL" => "N",
    "COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
    "REGION_INPUT_NAME" => "REGION_tmp",
    "CITY_INPUT_NAME" => "tmp",
    "CITY_OUT_LOCATION" => "Y",
    "LOCATION_VALUE" => "",
    "ONCITYCHANGE" => "submitForm()",
        ), null, array('HIDE_ICONS' => 'Y')
);
?>
</div>