<?// подключение служебной части пролога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("sale") ;
CModule::IncludeModule("catalog");
//    $fUserID = CSaleBasket::GetBasketUserID(True);
//    $fUserID = IntVal($fUserID);
//    $arFields = array(
//        "PRODUCT_ID" => $_REQUEST['productID'],
//        "PRODUCT_PRICE_ID" => CPrice::GetBasePrice($_REQUEST['productID']),
//        "PRICE" => $_REQUEST['price'],
//        "CURRENCY" => "RUB",
//        "WEIGHT" => 0,
//        "QUANTITY" => 1,
//        "LID" => SITE_ID,
//        "DELAY" => $_REQUEST['delay'],
//        "CAN_BUY" => "Y",
//        "NAME" => $_REQUEST['name'],
//        "MODULE" => "sale",
//        "NOTES" => "",
//        "FUSER_ID" => $fUserID
//    );

//    CSaleBasket::Add($arFields);
    Add2BasketByProductID(intval($_REQUEST['productID']),1, array("DELAY" => $_REQUEST['delay']),array());

    $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "inanime-template", Array(
            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
            "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
            "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
            "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
            "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
            "SHOW_PRODUCTS" => "N",	// Показывать список товаров
            "POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
            "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
            "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
            "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
            "COMPONENT_TEMPLATE" => ".default_old",
            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
            "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
            "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
            "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
            "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
        ),
        false
    );

?>

<?// подключение служебной части эпилога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>