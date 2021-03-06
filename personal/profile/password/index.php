<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки профиля");
?>
    <div class="section-personal-header change-password">
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
        <h1 class="ia-page-title">Настройки профиля</h1>
    </div>

    <div class="section-personal change-password">
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
                    $APPLICATION->IncludeComponent(
                        "inanime:main.profile", "only_pass", Array(
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N",
                            "SET_TITLE" => "Y",
                            "SEND_INFO" => "N",
                            "CHECK_RIGHTS" => "N",
                            "USER_PROPERTY_NAME" => "",
                            "AJAX_OPTION_ADDITIONAL" => ""
                        )
                    );
                    ?>
                </div>
            </div>
            <hr class="general-content-bottom-line">
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>