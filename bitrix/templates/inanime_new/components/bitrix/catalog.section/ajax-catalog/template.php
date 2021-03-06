<?foreach($arResult["ITEMS"] as $arElement):?><?$APPLICATION->IncludeComponent(
        "inanime:catalog.element",
        "inanime-preview-list-product",
        Array(
            "TEMPLATE_THEME" => "blue",
            "DISPLAY_NAME" => "Y",
            "DETAIL_PICTURE_MODE" => "IMG",
            "ADD_DETAIL_TO_SLIDER" => "N",
            "DISPLAY_PREVIEW_TEXT_MODE" => "E",
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
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
            "OFFERS_PROPERTY_CODE" => array( "COLOR_REF","SIZE_GLK","ARTNUMBER", "SIZES_SHOES", "SIZES_CLOTHES", "MORE_PHOTO" ),
            'OFFER_TREE_PROPS' => array("COLOR_REF","SIZE_GLK",""),
            'OFFERS_FIELD_CODE' => array('NAME'),

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
            "RATE_FIRS"=>"Y",
            "LAZY_LOAD"=>$arParams["LAZY_LOAD"]
        ),
        $component
    );?><?endforeach?>