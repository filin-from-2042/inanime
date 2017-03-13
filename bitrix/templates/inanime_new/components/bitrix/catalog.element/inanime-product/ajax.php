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
    if($action=='addQuestion')
    {
        $errors = array();
        $email='';
        $username='';
        $userID = 0;
        if($USER->IsAuthorized())
        {
            $email = $USER->GetEmail();
            $username =  $USER->GetFirstName();
            $userID = $USER->GetID();
        }
        else
        {
            $username =  htmlspecialchars(trim($_REQUEST['username']));
            $email = htmlspecialchars(trim($_REQUEST['email']));
        }

        if(!strlen(trim($email))) $errors[] = 'Не указана почта';

        if(sizeof($errors) == 0)
        {
            $questionText = htmlspecialchars(trim($_REQUEST['question']));
            $productID = intval($_REQUEST['productID']);
            $el = new CIBlockElement;
            $PROP = array();
            $PROP['question'] = $questionText;
            $PROP['email'] = $email;
            $PROP['username'] = $username;
            $PROP['question_product_id'] = $productID;

            $arLoadProductArray = array();
            if($userID) $arLoadProductArray["MODIFIED_BY"]=$userID;
            $arLoadProductArray["IBLOCK_SECTION"] = false;
            $arLoadProductArray["IBLOCK_ID"]= 25;
            $arLoadProductArray["PROPERTY_VALUES"]= $PROP;
            $arLoadProductArray["NAME"]= $email;
            $arLoadProductArray["ACTIVE"] = "Y";

            if($el->Add($arLoadProductArray)){
                echo 'Вопрос отправлен';
                $arEventFields = array(
                    "USER_NAME"      => $username,
                    "USER_EMAIL"     => $email,
                    "QUESTION_TEXT"  => $questionText
                );
                CEvent::SendImmediate("USER_NEW_QUESTION",SITE_ID, $arEventFields, 'N', 108);
            }
            else echo "Ошибка: ".$el->LAST_ERROR;
        }
        else
        {
            echo '<div class="error_msg">';
            foreach($errors as $error) echo $error.'<br/>';
            echo '</div>';
        }
    }
    // Нашли дешевле
    elseif($action=='sendCheaper')
    {
        $errors = array();
        $username =  htmlspecialchars(trim($_REQUEST['username']));
        $email = htmlspecialchars(trim($_REQUEST['email']));
        $phone = htmlspecialchars(trim($_REQUEST['phone']));
        $productLink = htmlspecialchars(trim($_REQUEST['productLink']));

        if(!strlen(trim($email))) $errors[] = 'Не указана почта';

        if(sizeof($errors) == 0)
        {
            $arEventFields = array(
                "USER_NAME"      => $username,
                "USER_EMAIL"     => $email,
                "USER_PHONE"     => $phone,
                "PRODUCT_LINK"  => $productLink
            );
            CEvent::SendImmediate("USER_FOUND_CHEAPER",SITE_ID, $arEventFields, 'N', 109);
            echo 'Ссылка отправлена';
        }
        else
        {
            echo '<div class="error_msg">';
            foreach($errors as $error) echo $error.'<br/>';
            echo '</div>';
        }
    }
    // быстрый заказ
    else if($action=='quickOrder')
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