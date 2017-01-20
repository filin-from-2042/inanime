<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="row">
    <div class="col-md-24 col-lg-24">
        <div class="sort-container clearfix">
            <div class="select-title"><?= GetMessage('SECT_SORT_LABEL'); ?>:</div>
            <div class="dropdown select-container order">
                <?
                $strList = '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">'."\n\r";
                $currSort;
                foreach ($_SESSION["inanime_new_catalogdata"]["arAvailableSort"] as $key => $val)
                {
                    if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='desc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-bottom"></span><span class="sort-value hidden">'.$val[0].';desc</span>';
                    if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='asc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-top"></span><span class="sort-value hidden">'.$val[0].';asc</span>';
                    $strList .= '<li>
                                    <span onclick="inanime_new.ddSetSelectedCatalogFilter(this);">
                                        '.GetMessage('SECT_SORT_' . $key).'
                                        <span class="glyphicon glyphicon-triangle-bottom"></span>
                                        <span class="sort-value hidden">'.$val[0].';desc</span>
                                    </span>
                                </li>'."\n\r";
                    $strList .= '<li>
                                    <span onclick="inanime_new.ddSetSelectedCatalogFilter(this);">
                                        '.GetMessage('SECT_SORT_' . $key).'
                                        <span class="glyphicon glyphicon-triangle-top"></span>
                                        <span class="sort-value hidden">'.$val[0].';asc</span>
                                    </span>
                                </li>'."\n\r";
                }
                $strList .= '</ul>'."\n\r";
                ?>
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-chevron-down"></span>
                    <span class="text"><?=$currSort?></span>
                </button>
                <?=$strList?>
            </div>
            <div class="type-buttons">
                <button type="button" class="btn btn-primary type-btn topsale" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_TOPSALE');?></button>
                <button type="button" class="btn btn-primary type-btn discount" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_DISCOUNT');?></button>
                <button type="button" class="btn btn-primary type-btn week-goods" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_WEEK_GOODS');?></button>
            </div>
        </div>
        <hr>
    </div>
</div>
<div class="row">
    <div class="hidden-xs hidden-sm col-md-6 col-lg-6">
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter", "visual_vertical1", Array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => $arCurSection['ID'],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_NOTES" => "",
                "CACHE_GROUPS" => "Y",
                "SAVE_IN_SESSION" => "N",
                "XML_EXPORT" => "Y",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION"
            ), $component, array('HIDE_ICONS' => 'Y')
        );
        ?>
    </div>
    <div class="col-xs-24 col-sm-24 col-md-18 col-lg-18">
        <?if($arParams["DISPLAY_TOP_PAGER"]):?>
            <p><?=$arResult["NAV_STRING"]?></p>
        <?endif?>

        <div class="items-section">
            <div class="items-container">

                <?foreach($arResult["ITEMS"] as $arElement):?>
                    <?
                    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <?$APPLICATION->IncludeComponent(
                        "inanime:catalog.element",
                        "inanime-preview-list-product",
                        Array(
                            "IBLOCK_ID" => $arElement['IBLOCK_ID'],
                            "ELEMENT_ID" => $arElement['ID'],
                            "ELEMENT_CODE" => "",
                            "SECTION_ID" => $arElement['IBLOCK_SECTION_ID'],
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

                            "OFFERS_CART_PROPERTIES" => array("COLOR_REF"),
                            "OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
                            "OFFERS_PROPERTY_CODE" => array( "COLOR_REF","SIZE_GLK","ARTNUMBER", "SIZES_SHOES", "SIZES_CLOTHES", "MORE_PHOTO" ),
                            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

                            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                            'OFFER_TREE_PROPS' => array("COLOR_REF","SIZE_GLK",""),

                            "RATE_FIRS"=>"N",
                            "LAZY_LOAD"=>'Y'
                        ),
                        false
                    );?>
                    <?/*?>
<div class="product-item-preview vertical" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                        <div class="image-container">
                            <img data-original="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" class="lazy" />
                            <div class="icons-container">
                                <?if($arElement["DATE_ACTIVE_FROM"]):?>
                                    <?//товары не более 2ух недель - новинки?>
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
                                        <?if($arPrice = $arElement["PRICES"][$code]):?>
                                            <?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                                                <span class="price old"><?=$arPrice["PRINT_VALUE"]?></span>
                                                <span class="price yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
                                            <?else:?>
                                                <span class="price yellow-text"><?=$arPrice["PRINT_VALUE"]?></span>
                                            <?endif;?>
                                        <?else:?>
                                            &nbsp;
                                        <?endif;?>
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
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
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
                    <?*/?>
                <?endforeach?>
                </div>
            </div>
            <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                <p><?=$arResult["NAV_STRING"]?></p>
            <?endif?>

        </div>
    </div>
</div>