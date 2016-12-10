<?
$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
    "delete" => $curPage."delete&id=#ID#",
    "delay" => $curPage."delay&id=#ID#",
    "add" => $curPage."add&id=#ID#",
);

?>

<div class="ia-basket">
    <div class="container">
        <?
        if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
        {
        ?>
            <div id="warning_message">
                <?
                if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
                {
                    foreach ($arResult["WARNING_MESSAGE"] as $v)
                        ShowError($v);
                }
                ?>
            </div>
            <?

            $normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
            $normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

            $delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
            $delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

            $subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
            $subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

            $naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
            $naHidden = ($naCount == 0) ? 'style="display:none;"' : '';
            ?>

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
                            <form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
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

                                <input type="hidden" name="BasketOrder" value="BasketOrder" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?
        }
        else
        {
            ShowError($arResult["ERROR_MESSAGE"]);
        }
        ?>
    </div>
</div>