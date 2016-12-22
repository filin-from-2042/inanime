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
}
die();