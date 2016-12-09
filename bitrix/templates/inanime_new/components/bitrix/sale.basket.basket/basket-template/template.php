<?
$arUrls = array(
    "delete" => $curPage."delete&id=#ID#",
    "delay" => $curPage."delay&id=#ID#",
    "add" => $curPage."add&id=#ID#",
);
?>
<div class="ia-basket">
    <div class="container">
        <div>
            <?$APPLICATION->IncludeComponent(
                "bitrix:breadcrumb",
                "product-chain",
                array(
                    "START_FROM" => "0",
                    "PATH" => "",
                    "SITE_ID" => "s2",
                    "COMPONENT_TEMPLATE" => "catalog-chain",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                ),
                false
            );
            ?>
        </div>
        <h1>Корзина и оформление заказа</h1>
        <div class="basket-tabs">
            <div class="basket-tabs-container">
                <div class="nav-tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#my-basket" aria-controls="my-basket" role="tab" data-toggle="tab">Моя корзина</a></li>
                        <li role="presentation"><a href="#put-aside" aria-controls="put-aside" role="tab" data-toggle="tab">Отложенные товары</a></li>
                        <li role="presentation"><a href="#not-available" aria-controls="not-available" role="tab" data-toggle="tab">Нет в наличии</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="my-basket">
                        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="put-aside">
                        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="not-available">
                        <?//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");?>
                        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>