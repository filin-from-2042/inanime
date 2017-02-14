<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
    <script type="text/javascript">
        BX.message({
            CPSL_MESS_BTN_DETAIL: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',

            CPSL_MESS_NOT_AVAILABLE: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',
            CPSL_BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_BASKET_REDIRECT');?>',
            CPSL_BASKET_URL: '<?=$arParams["BASKET_URL"];?>',
            CPSL_TITLE_ERROR: '<?=GetMessageJS('CPSL_CATALOG_TITLE_ERROR') ?>',
            CPSL_TITLE_BASKET_PROPS: '<?=GetMessageJS('CPSL_CATALOG_TITLE_BASKET_PROPS') ?>',
            CPSL_BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CPSL_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            CPSL_BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_SEND_PROPS');?>',
            CPSL_BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_CLOSE') ?>',
            CPSL_STATUS_SUCCESS: '<?=GetMessageJS('CPSL_STATUS_SUCCESS');?>',
            CPSL_STATUS_ERROR: '<?=GetMessageJS('CPSL_STATUS_ERROR') ?>'
        });
    </script>
<?
if (!empty($arResult['ITEMS']))
{
    foreach ($arResult['ITEMS'] as $key => $arItem)
    {?>
        <?/*var_dump($arItem);
?>
    <??>
        <div class="product-item-preview vertical" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
            <div class="image-container">
                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" />
                <div class="icons-container">
                    <?if($arItem["DATE_ACTIVE_FROM"]):?>
                        <?if(((strtotime("now")-strtotime($arResult["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                            <div class="additional-icon new"></div>
                        <?endif?>
                    <?endif?>
                    <?if($arItem["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                        <div class="additional-icon bestseller"></div>
                    <?endif?>
                    <?if($arItem["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                        <div class="additional-icon recommended"></div>
                    <?endif?>
                </div>
            </div>
            <div class="data-container">
                <div class="price-container">
                    <?

                    // Для товара с кодом $ID выведем различные цены (по типу и количеству), по
                    // которым данный товар может быть куплен текущим пользователем
                    $dbPrice = CPrice::GetList(
                        array("QUANTITY_FROM" => "ASC", "QUANTITY_TO" => "ASC", "SORT" => "ASC"),
                        array("PRODUCT_ID" => $arItem['ID']),
                        false,
                        false,
                        array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY", "QUANTITY_FROM", "QUANTITY_TO")
                    );
                    $arPrice = array();
                    while ($res = $dbPrice->Fetch())
                    {
                        $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
                            $res["ID"],
                            $USER->GetUserGroupArray(),
                            "N",
                            SITE_ID
                        );
                        $discountPrice = CCatalogProduct::CountPriceWithDiscount(
                            $res["PRICE"],
                            $res["CURRENCY"],
                            $arDiscounts
                        );
                        $arPrice['PRICE'] = $res["PRICE"];
                        $arPrice["DISCOUNT_PRICE"] = $discountPrice;
                    }
                    ?>
                    <?if(count($arPrice)>0){
                        ?>
                        <?if($arPrice["DISCOUNT_PRICE"] < $arPrice["PRICE"]):?>
                            <span class="price old"><?=$arPrice["PRICE"]?><span class="rub"></span></span>
                            <span class="price yellow-text"><?=$arPrice["DISCOUNT_PRICE"]?><span class="rub"></span></span>
                        <?else:?>
                            <span class="price yellow-text"><?=$arPrice["PRICE"]?><span class="rub"></span></span>
                        <?endif;?>
                    <?}else{?>
                        &nbsp;
                    <?}?>
                </div>
                <div class="title-container">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link">
                        <div class="text"><?=$arItem["NAME"]?></div>
                        <div class="article">
                            <?
                            if($arItem["PROPERTIES"]["ARTNUMBER1"]["VALUE"])
                                echo 'арт.'.$arItem["PROPERTIES"]["ARTNUMBER1"]["VALUE"];
                            else echo '';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="buttons-container">
                    <?if($arResult["CAN_BUY"]){?>
                        <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                            ,1
                            ,'<?=$arElement["NAME"]?>'
                            ,<?=($arPrice["DISCOUNT_PRICE"] < $arPrice["PRICE"])?$arPrice["DISCOUNT_PRICE"]:$arPrice["VALUE"]?>
                            ,false)">
                            <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" /></span>
                            <span class="text-btn">В корзину</span>
                        </button>
                        <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                            ,1
                            ,'<?=$arResult["NAME"]?>'
                            ,<?=($arPrice["DISCOUNT_PRICE"] < $arPrice["PRICE"])?$arPrice["DISCOUNT_PRICE"]:$arPrice["PRICE"]?>
                            ,true)">
                            <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                        </button>
                    <?
                    }else{
                        ?>
                        <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="<?=$this->GetEditAreaId($arItem['ID']);?>.deleteSubscribe()">
                            <span class="icon-btn"><i class="fa fa-2x fa-exclamation-circle" aria-hidden="true"></i></span>
                            <span class="text-btn">Отписаться</span>
                        </button>
                    <?
                    }
                    ?>
                </div>
                <div class="rate-container">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:iblock.vote",
                        "stars",
                        array(
                            "IBLOCK_TYPE" => $arItem['IBLOCK_TYPE'],
                            "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                            "ELEMENT_ID" => $arItem['ID'],
                            "ELEMENT_CODE" => "",
                            "MAX_VOTE" => "5",
                            "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                            "SET_STATUS_404" => "N",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600"
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );?>
                </div>
            </div>
        </div>
        <?*/?>
        <?$APPLICATION->IncludeComponent(
        "inanime:catalog.element",
        "inanime-preview-list-product",
        Array(
            "TEMPLATE_THEME" => "blue",
            "DISPLAY_NAME" => "Y",
            "DETAIL_PICTURE_MODE" => "IMG",
            "ADD_DETAIL_TO_SLIDER" => "N",
            "DISPLAY_PREVIEW_TEXT_MODE" => "E",
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "ADD_TO_BASKET_ACTION" => array("BUY"),
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "USE_VOTE_RATING" => "N",
            "USE_COMMENTS" => "N",
            "BRAND_USE" => "N",
            "SEF_MODE" => "N",
            "SEF_RULE" => "",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arItem['IBLOCK_ID'],
            "ELEMENT_ID" => $arItem['ID'],
            "ELEMENT_CODE" => "",
            "SECTION_ID" => $arItem['IBLOCK_SECTION_ID'],
            "SECTION_CODE" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "CHECK_SECTION_ID_VARIABLE" => "N",
            "SET_TITLE" => "Y",
            "SET_CANONICAL_URL" => "N",
            "SET_BROWSER_TITLE" => "Y",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "Y",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "Y",
            "META_DESCRIPTION" => "-",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "ADD_ELEMENT_CHAIN" => "N",
            "PROPERTY_CODE" => array(),
            "OFFERS_LIMIT" => "0",
//            "PRICE_CODE" => array(),
            "PRICE_CODE" => array("BASE","PROF"),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "BASKET_URL" => "/personal/basket.php",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "USE_PRODUCT_QUANTITY" => "N",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(),
            "DISPLAY_COMPARE" => "N",
            "LINK_IBLOCK_TYPE" => "",
            "LINK_IBLOCK_ID" => "",
            "LINK_PROPERTY_SID" => "",
            "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
            "BACKGROUND_IMAGE" => "-",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_NOTES" => "",
            "CACHE_GROUPS" => "Y",
            "USE_GIFTS_DETAIL" => "Y",
            "USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
            "USE_ELEMENT_COUNTER" => "Y",
            "SHOW_DEACTIVATED" => "N",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "HIDE_NOT_AVAILABLE" => "N",
            "CONVERT_CURRENCY" => "N",
            "SET_VIEWED_IN_COMPONENT" => "N",
            "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
            "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
            "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
            "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
            "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
            "GIFTS_SHOW_OLD_PRICE" => "Y",
            "GIFTS_SHOW_NAME" => "Y",
            "GIFTS_SHOW_IMAGE" => "Y",
            "GIFTS_MESS_BTN_BUY" => "Выбрать",
            "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
            "GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
            "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            'RATE_FIRS'=>'Y',
            'SUBSCRIBED'=>'Y'
        ),
        false
    );?>

<?
        $arJSParams = array(
            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_ADD_BASKET_BTN' => false,
            'SHOW_BUY_BTN' => true,
            'SHOW_ABSENT' => true,
            'PRODUCT' => array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['~NAME'],
                'PICT' => ('Y' == $arItem['SECOND_PICT']?$arItem['PREVIEW_PICTURE_SECOND']:$arItem['PREVIEW_PICTURE']),
                'CAN_BUY' => $arItem["CAN_BUY"],
                'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                'ADD_URL' => $arItem['~ADD_URL'],
                'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
            ),
//            'BASKET' => array(
//                'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
//                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
//                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
//                'EMPTY_PROPS' => $emptyProductProperties
//            ),
//            'VISUAL' => array(
//                'ID' => $arItemIDs['ID'],
//                'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
//                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
//                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
//                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
//                'PRICE_ID' => $arItemIDs['PRICE'],
//                'BUY_ID' => $arItemIDs['BUY_LINK'],
//                'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
//                'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
//            ),
            'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
        );
        ?>
        <script type="text/javascript">
            var <?=$this->GetEditAreaId($arItem['ID']);?> = new InAnimeSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
        </script>
        <?
    }
}