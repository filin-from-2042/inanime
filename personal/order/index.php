<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои заказы");
?>
    <div class="section-personal-header">
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
        <h1 class="ia-page-title">Мои заказы</h1>
    </div>
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
                    <?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.list",
                        "inanime-personal-orderlist",
                        Array(
                            "STATUS_COLOR_N" => "green",
                            "STATUS_COLOR_P" => "yellow",
                            "STATUS_COLOR_F" => "gray",
                            "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
                            "PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
                            "PATH_TO_COPY" => "basket.php",
                            "PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
                            "PATH_TO_BASKET" => "basket.php",
                            "PATH_TO_PAYMENT" => "payment",
                            "ORDERS_PER_PAGE" => 20,
                            "SET_TITLE" => "Y",
                            "SAVE_IN_SESSION" => "Y",
                            "NAV_TEMPLATE" => "",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600",
                            "CACHE_GROUPS" => "Y",
                            "HISTORIC_STATUSES" => "F",
                            "ACTIVE_DATE_FORMAT" => "d.m.Y"
                        )
                    );?>
                </div>
            </div>
        </div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>