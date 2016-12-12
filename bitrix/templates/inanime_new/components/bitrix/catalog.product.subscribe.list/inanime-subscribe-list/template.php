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
        <?
?>
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
                        <td>
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
                        </td>
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