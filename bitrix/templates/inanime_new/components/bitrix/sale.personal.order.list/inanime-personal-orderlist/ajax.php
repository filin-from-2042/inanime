<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (!check_bitrix_sessid()) die();

$ID = intval($_REQUEST['orderID']);?>
<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.detail",
    "inanime-order-detail-small",
    Array(
        "PATH_TO_LIST" => "order_list.php",
        "PATH_TO_CANCEL" => "order_cancel.php",
        "PATH_TO_PAYMENT" => "payment.php",
        "PATH_TO_COPY" => "",
        "ID" => $ID,
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "Y",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "PICTURE_WIDTH" => "110",
        "PICTURE_HEIGHT" => "110",
        "PICTURE_RESAMPLE_TYPE" => "1",
        "CUSTOM_SELECT_PROPS" => array(),
        "PROP_1" => Array(),
        "PROP_2" => Array()
    )
);?>