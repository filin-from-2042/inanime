<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Просмотр моих заказов");
?>
    <div class="section-personal orders">
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
                <div class="col-xs-24 col-sm-16 col-md-18 col-lg-18 orders-table-column">
                    <?$APPLICATION->IncludeComponent("bitrix:sale.personal.order",
                        "inanime-personal-order",
                        array(
                            "SEF_MODE" => "Y",
                            "SEF_FOLDER" => "/personal/order/",
                            "ORDERS_PER_PAGE" => "10",
                            "PATH_TO_PAYMENT" => "/personal/order/payment/",
                            "PATH_TO_BASKET" => "/personal/cart/",
                            "SET_TITLE" => "Y",
                            "SAVE_IN_SESSION" => "N",
                            "NAV_TEMPLATE" => "arrows",
                            "SEF_URL_TEMPLATES" => array(
                                "list" => "index.php",
                                "detail" => "detail/#ID#/",
                                "cancel" => "cancel/#ID#/",
                            ),
                            "SHOW_ACCOUNT_NUMBER" => "Y"
                        ),
                        false
                    );?>
                </div>
            </div>
        </div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>