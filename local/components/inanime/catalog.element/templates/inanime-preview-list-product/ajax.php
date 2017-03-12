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
    // Вопрос-ответ
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
        if(isset($price['RESULT_PRICE']['DISCOUNT_PRICE']))
        {
            $productPrice = $price['RESULT_PRICE']['DISCOUNT_PRICE'];
        }
        else{
            $productPrice = $price['RESULT_PRICE']['BASE_PRICE'];
        }
//        $productPrice = GetCatalogProductPrice($productID, array('BASE'));

        //var_dump($productPrice);

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
        $arOrderPropsValues[] = array( "ORDER_PROPS_ID" => 39, "NAME" => "ФИО", "CODE" => "FIO", "VALUE" => $nameField );
        $arOrderPropsValues[] = array( "ORDER_PROPS_ID" => 40, "NAME" => "E-Mail", "CODE" => "EMAIL", "VALUE" => $emailFiled );
        $arOrderPropsValues[] = array( "ORDER_PROPS_ID" => 41, "NAME" => "Телефон", "CODE" => "PHONE", "VALUE" => $phoneField );
        $arOrderPropsValues[] = array( "ORDER_PROPS_ID" => 44, "NAME" => "Местоположение", "CODE" => "LOCATION", "VALUE" => $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'] );
        $arOrderPropsValues[] = array("ORDER_PROPS_ID" => 45, "NAME" => "Адрес доставки", "CODE" => "ADDRESS", "VALUE" => $emailFiled );

        $zipCode = 0;
        $db_zip = CSaleLocation::GetLocationZIP($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
        if($zipProp = $db_zip->Fetch())
            $zipCode = $zipProp['ZIP'];
        $arOrderPropsValues[] = array("ORDER_PROPS_ID" => 42, "NAME" => "Индекс", "CODE" => "ZIP", "VALUE" => $zipCode );

        // Данный метод вызывает события из списка вплоть до OnSaleCalculateOrder
        // $arShoppingCart - может содержать не всю корзину, а только те элементы которые необходимо добавить.
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

        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 39, "NAME" => "ФИО", "CODE" => "FIO", "VALUE" => $nameField );
        CSaleOrderPropsValue::Add($arFields);

        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 40, "NAME" => "E-Mail", "CODE" => "EMAIL", "VALUE" => $emailFiled );
        CSaleOrderPropsValue::Add($arFields);

        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 41, "NAME" => "Телефон", "CODE" => "PHONE", "VALUE" => $phoneField );
        CSaleOrderPropsValue::Add($arFields);

        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 44, "NAME" => "Местоположение", "CODE" => "LOCATION", "VALUE" => $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'] );
        CSaleOrderPropsValue::Add($arFields);

        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 45, "NAME" => "Адрес доставки", "CODE" => "ADDRESS", "VALUE" => $emailFiled );
        CSaleOrderPropsValue::Add($arFields);

        $zipCode = 0;
        $db_zip = CSaleLocation::GetLocationZIP($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
        if($zipProp = $db_zip->Fetch())
            $zipCode = $zipProp['ZIP'];
        $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 42, "NAME" => "Индекс", "CODE" => "ZIP", "VALUE" => $zipCode );
        CSaleOrderPropsValue::Add($arFields);

        //Применение скидок на товары в корзинах
        $arOrder = array(
            'SITE_ID' => SITE_ID,
            'USER_ID' => $userID,
            'BASKET_ITEMS' => $arShoppingCart
        );
        $arOptions = array();
        $arErrors = array();

        CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);


/*
        $arFields = array(
            "LID"              => SITE_ID,
            "PERSON_TYPE_ID"   => 5,
            "PAYED"            => "N",
            "CANCELED"         => "N",
            "STATUS_ID"        => "N",
            "PRICE"            => $productPrice,
            "CURRENCY"         => "RUB",
            "USER_ID"          => $userID,
            "PAY_SYSTEM_ID"    => 0,
            "PRICE_DELIVERY"   => 0,
            "DELIVERY_ID"      => 0,
            "DISCOUNT_VALUE"   => 0,
            "TAX_VALUE"        => 0,
            "USER_DESCRIPTION" => "Быстрый заказ"
        );
        // add Guest ID
        if (CModule::IncludeModule("statistic"))
            $arFields["STAT_GID"] = CStatistic::GetEventParam();
        $ord = new CSaleOrder();
        $ORDER_ID = $ord->Add($arFields);
        if($ORDER_ID)
        {
            $arFields = array("ORDER_ID" => '--'.$ORDER_ID, "ORDER_PROPS_ID" => 39, "NAME" => "ФИО", "CODE" => "FIO", "VALUE" => $nameField );
            CSaleOrderPropsValue::Add($arFields);

            $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 40, "NAME" => "E-Mail", "CODE" => "EMAIL", "VALUE" => $emailFiled );
            CSaleOrderPropsValue::Add($arFields);

            $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 41, "NAME" => "Телефон", "CODE" => "PHONE", "VALUE" => $phoneField );
            CSaleOrderPropsValue::Add($arFields);

            $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 44, "NAME" => "Местоположение", "CODE" => "LOCATION", "VALUE" => $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'] );
            CSaleOrderPropsValue::Add($arFields);

            $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 45, "NAME" => "Адрес доставки", "CODE" => "ADDRESS", "VALUE" => $emailFiled );
            CSaleOrderPropsValue::Add($arFields);

            $zipCode = 0;
            $db_zip = CSaleLocation::GetLocationZIP($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
            if($zipProp = $db_zip->Fetch())
                $zipCode = $zipProp['ZIP'];
            $arFields = array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 42, "NAME" => "Индекс", "CODE" => "ZIP", "VALUE" => $zipCode );
            CSaleOrderPropsValue::Add($arFields);

            if (!Add2BasketByProductID($productID, $quantity, array('ORDER_ID' => $ORDER_ID), array()))
            {
                echo json_encode(array('error'=>'Ошибка добавления товара в заказ'));
            }
            else{
                $db_res = CSaleBasket::GetList(($by="NAME"), ($order="ASC"), array("FUSER_ID"=>$userID, "LID"=>SITE_ID, "ORDER_ID"=>$ORDER_ID));
                while ($res = $db_res->GetNext())
                {
                    if (strlen($res["CALLBACK_FUNC"])>0)
                    {
                        CSaleBasket::UpdatePrice($res["ID"], $res["CALLBACK_FUNC"], $res["MODULE"], $res["PRODUCT_ID"], $res["QUANTITY"]);
                        $res = CSaleBasket::GetByID($res["ID"]);
                    }
                }
            }
        }
        else
            echo json_encode(array('error'=>$ord->LAST_ERROR));*/

    }
}
die();