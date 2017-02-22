<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);


$carouselID = 'carousel-custom-vertical-'.$arResult["ID"];
$itemCounter = 0;
?>
<div id="<?=$carouselID?>" class="carousel-products vertical">
    <div class="title-container grey-container clearfix">
        <span class="title"><a href="<?=$arResult['SECTION_PAGE_URL']?>"><?=$arResult["NAME"]?></a></span>
        <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
        <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
    </div>

    <ul>
        <?foreach($arResult["ITEMS"] as $arElement):?>
            <?if($itemCounter==0):?><li><?endif?>

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
                        "RATE_FIRS"=>"N",
                        "LAZY_LOAD"=>'N',
                        "HIDE_BUTTONS"=>"Y",
                        "HORIZONTAL"=>"Y"
                    ),
                $component
                );?>

            <?/*?>
                <div class="product-item-preview horizontal">
                    <div class="image-container">
                        <img src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" />
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
                            <span class="price yellow-text"><?=CPrice::GetBasePrice($arElement["ID"])["PRICE"]?> &#8381;</span>
                        </div>
                        <div class="title-container">
                            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="link">
                                <div class="text"><?=$arElement["NAME"]?></div>
                                    <div class="article"><?($arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"])?'арт.'.$arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"]:'';?></div>
                            </a>
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
            <?if($itemCounter == 1){
                $itemCounter=0;
                echo '</li>';
            }else{
                $itemCounter=1;
            }?>
        <?endforeach?>
    </ul>
</div>
<script>
    $(document).ready(function () {
        inanime_new.init_custom_horizontal_carousel('<?=$carouselID?>', 1);
    });
    </script>