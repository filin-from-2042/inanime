<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Активация дисконтной карты");
?>
    <div class="section-personal-header discount">
        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "catalog-chain",
            Array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => SITE_ID
            )
        );
        ?>
        <?
        $APPLICATION->AddChainItem('Активация дисконтной карты');
        ?>
        <h1 class="ia-page-title">Активация дисконтной карты</h1>
    </div>
    <div class="section-personal discount">
        <div class="container">
            <div class="row">
                <div class="col-xs-24 col-sm-8 col-md-6 col-lg-6 menu-column">
                    <?
                    $APPLICATION->IncludeFile("/include/personal_left_menu.php", Array(), Array(
                        "MODE" => "html", // будет редактировать в веб-редакторе
                        "NAME" => "Редактирование включаемой области раздела", // текст всплывающей подсказки на иконке
                    ));
                    ?>
                </div>

                <div class="col-xs-24 col-sm-16 col-md-18 col-lg-18 fields-column">
                <?
                if (!$USER->IsAuthorized())
                {
                    $APPLICATION->AuthForm('Для просмотра дисконтных карт необходимо авторизоваться.', false, false, 'N', false);
                }
                else
                {?>
                    <?$APPLICATION->IncludeComponent("inanime:user.card", "", array(), false, array('HIDE_ICONS'=>'Y')); ?>
                <?}?>
                </div>
            </div>
            <hr class="general-content-bottom-line">
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>