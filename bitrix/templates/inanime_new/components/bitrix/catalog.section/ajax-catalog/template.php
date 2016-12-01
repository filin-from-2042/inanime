
<?foreach($arResult["ITEMS"] as $arElement):?>
    <?
    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="product-item-preview vertical" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
        <div class="image-container">
<!--            <img src="--><?//=$arElement["PREVIEW_PICTURE"]["SRC"]?><!--" />-->
            <img data-original="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>"  class="lazy" />
            <div class="icons-container">
                <?if($arElement["DATE_ACTIVE_FROM"]):?>
                    <?if(((strtotime("now")-strtotime($arElement["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                        <div class="additional-icon new"></div>
                    <?endif?>
                <?endif?>
                <?if($arElement["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                    <div class="additional-icon bestseller"></div>
                <?endif?>
                <?if($arElement["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                    <div class="additional-icon recommended"></div>
                <?endif?>
            </div>
        </div>
        <div class="data-container">
            <div class="price-container">
                <?foreach($arResult["PRICES"] as $code=>$arPrice):?>
                    <td>
                        <?if($arPrice = $arElement["PRICES"][$code]):?>
                            <?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                                <span class="price old"><?=$arPrice["PRINT_VALUE"]?> &#8381;</span>
                                <span class="price yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?> &#8381;</span>
                            <?else:?>
                                <span class="price yellow-text"><?=$arPrice["PRINT_VALUE"]?> &#8381;</span>
                            <?endif;?>
                        <?else:?>
                            &nbsp;
                        <?endif;?>
                    </td>
                <?endforeach;?>
            </div>
            <div class="title-container">
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="link">
                    <div class="text"><?=$arElement["NAME"]?></div>
                    <div class="article">
                        <?
                        if($arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"])
                            echo 'арт.'.$arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"];
                        else echo '';
                        ?>
                    </div>
                </a>
            </div>
            <div class="buttons-container">
                <?if($arElement["CAN_BUY"]){?>
                    <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                        ,1
                        ,'<?=$arElement["NAME"]?>'
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
                            "BUTTON_ID" => $arElement['ID']."-in-cart-btn",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "PRODUCT_ID" => $arElement['ID']
                        )
                    );?>
                <?
                }
                ?>
                <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                    ,1
                    ,'<?=$arElement["NAME"]?>'
                    ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                    ,true)">
                    <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                </button>
            </div>
            <div class="rate-container">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "stars",
                    Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "ELEMENT_ID" => $arElement["ID"],
                        "MAX_VOTE" => $arParams["MAX_VOTE"],
                        "VOTE_NAMES" => $arParams["VOTE_NAMES"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                    ),
                    $component
                );?>
            </div>
        </div>
    </div>
<?endforeach?>