<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
$APPLICATION->AddChainItem("Оформление заказа", "");
?>
<?$APPLICATION->IncludeComponent(
    "inanime:sale.order.ajax",
//    "bitrix:sale.order.ajax",
    "", array(
        "PAY_FROM_ACCOUNT" => "Y",
        "COUNT_DELIVERY_TAX" => "N",
        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
        "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
        "ALLOW_AUTO_REGISTER" => ($_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"])?$_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"]:"N",
//        "ALLOW_AUTO_REGISTER" => "N",
        "SEND_NEW_USER_NOTIFY" => "Y",
        "DELIVERY_NO_AJAX" => "N",
        "TEMPLATE_LOCATION" => "popup",
        "PROP_1" => array(
        ),
        "PATH_TO_BASKET" => "/personal/order/make/",
        "PATH_TO_PERSONAL" => "/personal/order/",
        "PATH_TO_PAYMENT" => "/personal/order/payment/",
        "PATH_TO_ORDER" => "/personal/order/make/",
        "SET_TITLE" => "Y",
        "DELIVERY2PAY_SYSTEM" => Array(),
        "SHOW_ACCOUNT_NUMBER" => "Y"
    ), false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>