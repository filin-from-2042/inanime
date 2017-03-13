<?
/** @global CMain $APPLICATION */
define("NO_KEEP_STATISTIC", true);
define('PUBLIC_AJAX_MODE', true);
define("NOT_CHECK_PERMISSIONS", true);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (check_bitrix_sessid())
{
    $action = htmlspecialchars(trim($_REQUEST['action']));

    if($action=='quickOrder')
    {
        CModule::IncludeModule("sale");
        CModule::IncludeModule("catalog");

        $productID = intval($_REQUEST['productID']);
        $quantity = intval($_REQUEST['quantity']);
        $nameField = htmlspecialchars($_REQUEST['nameField']);
        $phoneField = htmlspecialchars($_REQUEST['phoneField']);
        $emailFiled = htmlspecialchars($_REQUEST['emailFiled']);

        $price = CCatalogProduct::GetOptimalPrice($productID, $quantity, (($USER->IsAuthorized()) ? $USER->GetUserGroupArray() : 5) , 'N');

        global $USER;
        if($USER->IsAuthorized())
        {
            $userID =$USER->GetID();
        }else{
            $userID = 102;
            //$USER->Authorize($userID);
        }

        $arErrors = array();
        $arWarnings = array();

        $arOrderPropsValues = array();
        $arOrderPropsValues[39] = $nameField;
        $arOrderPropsValues[40] = $emailFiled ;
        $arOrderPropsValues[41] = $phoneField;
        $arOrderPropsValues[44] = $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'];
        $arOrderPropsValues[45] = '';

        $zipCode = 0;
        $db_zip = CSaleLocation::GetLocationZIP($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
        if($zipProp = $db_zip->Fetch())
            $zipCode = $zipProp['ZIP'];
        $arOrderPropsValues[42] = $zipCode;

        $arShoppingCart = array();
        Add2BasketByProductID($productID, $quantity, array('FUSER_ID' => 102), array());
        $dbBasketItems = CSaleBasket::GetList( array("NAME" => "ASC", "ID" => "ASC"),
            array( "FUSER_ID" => 102),
            false,
            false,
            array()
        );
        while ($arItems = $dbBasketItems->Fetch()) {
            $arShoppingCart[] = $arItems;
        }

        $arOrderDat = CSaleOrder::DoCalculateOrder(
            SITE_ID,
            $userID,
            $arShoppingCart,
            5,
            $arOrderPropsValues,
            0,
            0,
            array(),
            $arErrors,
            $arWarnings
        );

        $arOrderFields = array(
            "LID" => $arOrderDat['LID'],
            "PERSON_TYPE_ID" => $arOrderDat['PERSON_TYPE_ID'],
            "PAYED" => "N",
            "CANCELED" => "N",
            "STATUS_ID" => "N",
            "PRICE" => $arOrderDat['PRICE'],
            "CURRENCY" => $arOrderDat['CURRENCY'],
            "USER_ID" => $arOrderDat['USER_ID'],
            "USER_DESCRIPTION" => "",
            "ADDITIONAL_INFO" => ""
        );
        //Создание заказа и привязка корзин к заказу
        $errors = array();
        $ORDER_ID = CSaleOrder::DoSaveOrder($arOrderDat, $arOrderFields, 0, $errors, array(), array(), true);
        //Применение скидок на товары в корзинах
        $arOrder = array(
            'SITE_ID' => SITE_ID,
            'USER_ID' => $userID,
            'BASKET_ITEMS' => $arShoppingCart
        );
        $arOptions = array();
        $arErrors = array();

        CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);
    }
}
die();