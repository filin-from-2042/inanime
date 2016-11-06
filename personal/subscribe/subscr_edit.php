<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>
<?$APPLICATION->IncludeComponent("bitrix:subscribe.simple",".default",Array(
        "AJAX_MODE" => "N",
        "SHOW_HIDDEN" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "SET_TITLE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    ));?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>