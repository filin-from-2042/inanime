<?
//var_dump($arResult)
?>
<div class="product-item-preview vertical" id="<?=$this->GetEditAreaId($arResult['ID']);?>">
    <div class="image-container">
                    <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" />
        <div class="icons-container">
            <?if($arResult["DATE_ACTIVE_FROM"]):?>
                <?if(((strtotime("now")-strtotime($arResult["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                    <div class="additional-icon new"></div>
                <?endif?>
            <?endif?>
            <?if($arResult["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                <div class="additional-icon bestseller"></div>
            <?endif?>
            <?if($arResult["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                <div class="additional-icon recommended"></div>
            <?endif?>
        </div>
        <?
        $arDiscounts = CCatalogDiscount::GetDiscountByProduct(intVal($arResult['ID']));
        $discountData = array();
        if(count($arDiscounts)>0)
        {
            foreach($arDiscounts as $arDiscount)
            {
                if(intVal($arDiscount['ID'])>=19 && intVal($arDiscount['ID'])<=28)
                {
                    if(!empty($discountData) && floatVal($discountData['VALUE'])>=floatVal($arDiscount['VALUE'])) continue;
                    else $discountData = $arDiscount;
                }
            }
        }
        if($discountData)
        {
            $difference = strtotime($discountData["ACTIVE_TO"]) - strtotime("now");
            $daysLeft = intVal($difference/86400);
            $hoursLeft = intVal(($difference%86400)/3600);
            ?>
            <div class="week-good-icon-container">
                <div class="week-good-icon">
                    <span class="icon-title">Товар недели</span>
                    <span class="time-left"><?=$daysLeft?> д. <?=$hoursLeft?> ч.</span>
                </div>
            </div>
        <?}?>
    </div>
    <div class="data-container">
        <div class="price-container">
            <?
            //var_dump($arResult);
            foreach($arResult["PRICES"] as $code=>$arPrice):?>
                <td>
                    <?if($arPrice = $arResult["PRICES"][$code]):?>
                        <?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                            <span class="price old"><?=$arPrice["PRINT_VALUE"]?></span>
                            <span class="price yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
                        <?else:?>
                            <span class="price yellow-text"><?=$arPrice["PRINT_VALUE"]?></span>
                        <?endif;?>
                    <?else:?>
                        &nbsp;
                    <?endif;?>
                </td>
            <?endforeach;?>
        </div>
        <div class="title-container">
            <a href="<?=$arResult["DETAIL_PAGE_URL"]?>" class="link">
                <div class="text"><?=$arResult["NAME"]?></div>
                <div class="article">
                    <?
                    if($arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"])
                        echo 'арт.'.$arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"];
                    else echo '';
                    ?>
                </div>
            </a>
        </div>
        <?if($arParams['RATE_FIRS'] && $arParams['RATE_FIRS']=='Y'){?>

            <div class="rate-container first-block">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "stars",
                    array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "MAX_VOTE" => "5",
                        "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                        "SET_STATUS_404" => "N",
                        "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME']
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>
            <div class="buttons-container last-block">
                <?if($arResult["CAN_BUY"]){?>
                    <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                        ,1
                        ,'<?=$arResult["NAME"]?>'
                        ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                        ,false)">
                        <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" /></span>
                        <span class="text-btn">В корзину</span>
                    </button>
                <?
                }else{
                    ?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.product.subscribe",
                        "inanime-subscribe",
                        Array(
                            "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                            "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "PRODUCT_ID" => $arResult['ID']
                        )
                    );?>
                <?
                }
                ?>
                <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                    ,1
                    ,'<?=$arResult["NAME"]?>'
                    ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                    ,true)">
                    <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                </button>
            </div>
        <?}else{?>
            <div class="buttons-container">
                <?if($arResult["CAN_BUY"]){?>
                    <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                        ,1
                        ,'<?=$arResult["NAME"]?>'
                        ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                        ,false)">
                        <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" /></span>
                        <span class="text-btn">В корзину</span>
                    </button>
                <?
                }else{
                    ?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.product.subscribe",
                        "inanime-subscribe",
                        Array(
                            "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                            "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "PRODUCT_ID" => $arResult['ID']
                        )
                    );?>
                <?
                }
                ?>
                <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                    ,1
                    ,'<?=$arResult["NAME"]?>'
                    ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                    ,true)">
                    <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                </button>
            </div>
            <div class="rate-container">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "stars",
                    array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "MAX_VOTE" => "5",
                        "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                        "SET_STATUS_404" => "N",
                        "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME']
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>

        <?}?>
    </div>
</div>