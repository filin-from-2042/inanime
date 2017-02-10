<?
/** @global CMain $APPLICATION */
define("NO_KEEP_STATISTIC", true);
define('PUBLIC_AJAX_MODE', true);
define("NOT_CHECK_PERMISSIONS", true);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (check_bitrix_sessid())
{
    if(CModule::IncludeModule("subscribe"))
    {
        $alreadySubscribed = false;
        $EMAIL = htmlspecialchars(trim($_REQUEST['subscribeMail']));
        $RUB_ID = 1;

        if ($USER->IsAuthorized()){
            global $USER;
            $USER = $USER->GetID() ;
        }
        else {
            $USER = NULL ;
        }

        // определяем текущую подписку на новости
        $rs = CSubscription::GetByEmail($EMAIL);
        while($rubid = $rs->Fetch())
        {
            $subscr_rub = CSubscription::GetRubricList($rubid['ID']);
            while($subscr_rub_arr = $subscr_rub->Fetch())
            {
                if( $subscr_rub_arr["ID"] == $RUB_ID)
                {
                    if($rubid['ACTIVE']!='N') $alreadySubscribed=true;
                    break;
                }
            }
        }

        // подписка на рассылку
        if(!$alreadySubscribed)
        {
            $subscr = new CSubscription;
            $arFields = Array(
                "USER_ID" => $USER_ID,
                "FORMAT" => "html/text",
                "EMAIL" => $EMAIL,
                "ACTIVE" => "Y",
                "RUB_ID" => array($RUB_ID),
                "SEND_CONFIRM" => "N"
            );

            $idsubrscr = $subscr->Add($arFields);
            if($idsubrscr)
                echo  $EMAIL.' удачно подписан на рассылку';
            else
                echo $EMAIL .' уже подписан на рассылку!';
        }
    }
}

die();