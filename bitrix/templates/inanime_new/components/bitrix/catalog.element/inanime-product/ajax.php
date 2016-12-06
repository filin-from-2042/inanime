<?
/** @global CMain $APPLICATION */
define("NO_KEEP_STATISTIC", true);
define('PUBLIC_AJAX_MODE', true);
define("NOT_CHECK_PERMISSIONS", true);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
{
    AddMessage2Log(
        'To: '.$to.PHP_EOL.
        'Subject: '.$subject.PHP_EOL.
        'Message: '.$message.PHP_EOL.
        'Headers: '.$additional_headers.PHP_EOL.
        'Params: '.$additional_parameters.PHP_EOL
    );
    if ($additional_parameters!='') {
        return @mail($to, $subject, $message, $additional_headers, $additional_parameters);
    } else {
        return @mail($to, $subject, $message, $additional_headers);
    }
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (check_bitrix_sessid())
{
    $action = $_REQUEST['action'];
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
            $el = new CIBlockElement;
            $PROP = array();
            $PROP['question'] = $questionText;
            $PROP['email'] = $email;
            $PROP['username'] = $username;

            $arLoadProductArray = array();
            if($userID) $arLoadProductArray["MODIFIED_BY"]=$userID;
            $arLoadProductArray["IBLOCK_SECTION"] = false;
            $arLoadProductArray["IBLOCK_ID"]= 25;
            $arLoadProductArray["PROPERTY_VALUES"]= $PROP;
            $arLoadProductArray["NAME"]= $email;
            $arLoadProductArray["ACTIVE"] = "N";

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
}
die();