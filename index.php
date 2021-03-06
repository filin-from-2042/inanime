<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аниме");
$APPLICATION->SetPageProperty("keywords", "аниме одежда, аниме берелоки, кулоны");
$APPLICATION->SetTitle("Интернет-магазин \"АНИМЕ аксессуаров\"");
?>
<style>
    hr.mobile-top-nav-line{
        display: none;
    }
</style>
<div class="container">
    <div class="row main-carousel">
        <div class="col-xs-24 col-sm-18 col-md-18 col-lg-18 general-banner-column">
            <?if (IsModuleInstalled("advertising")):?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:advertising.banner",
                    "bootstrap",
                    array(
                        "COMPONENT_TEMPLATE" => "bootstrap",
                        "TYPE" => "MAIN",
                        "NOINDEX" => "Y",
                        "QUANTITY" => "3",
                        "BS_EFFECT" => "fade",
                        "BS_CYCLING" => "N",
                        "BS_WRAP" => "Y",
                        "BS_PAUSE" => "Y",
                        "BS_KEYBOARD" => "Y",
                        "BS_ARROW_NAV" => "Y",
                        "BS_BULLET_NAV" => "Y",
                        "BS_HIDE_FOR_TABLETS" => "N",
                        "BS_HIDE_FOR_PHONES" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "DEFAULT_TEMPLATE" => "-",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO"
                    ),
                    false
                );?>
            <?endif?>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 custom-carousel-column">
            <script>
                $(document).ready(function(){
                    inanime_new.init_custom_vertical_carousel('carousel-custom-vertical',2);
                });
            </script>
            <? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');?>
            <? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');?>
            <?
            CModule::IncludeModule("iblock");
            CModule::IncludeModule("catalog");
            $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_SHOW_MAIN_SLIDER", "PROPERTY_MAIN_SLIDER_PHOTO", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT", "PROPERTY_IS_NEWPRODUCT", "DETAIL_PAGE_URL", "PROPERTY_DISCOUNT");
            $arFilter = Array("IBLOCK_ID" => array(18, 19, 23, 24), "ACTIVE" => "Y", "!PROPERTY_SHOW_MAIN_SLIDER" => false);
            $res = CIBlockElement::GetList(Array("ACTIVE_FROM" => "DESC", "SORT" => "ASC"), $arFilter, false, Array("nPageSize" => 15), $arSelect);
            $arSlides = array();
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();

                $arFields["file"] = CFile::ResizeImageGet(($arFields["PROPERTY_MAIN_SLIDER_PHOTO_VALUE"])?$arFields["PROPERTY_MAIN_SLIDER_PHOTO_VALUE"] : (($arFields["PREVIEW_PICTURE"]) ? $arFields["PREVIEW_PICTURE"] :$arFields["DETAIL_PICTURE"] ), array('width' => '1800', 'height' => '1000'), BX_RESIZE_IMAGE_PROPORTIONAL, true);

                $arSlides[] = $arFields;
            }

            if (count($arSlides) > 0) {
                ?>
                <div id="carousel-custom-vertical" class="carousel-custom">
                    <div class="prev button"><i class="fa fa-chevron-circle-up" aria-hidden="true"></i></div>
                    <ul>
                    <?
                    $counter = 0;
                    foreach ($arSlides as $slide) {
                        $counter++;
                        $previewText = (strlen($slide["PREVIEW_TEXT"]) > 83) ? substr($slide["PREVIEW_TEXT"], 0, 40) . '...' : $slide["PREVIEW_TEXT"];
                        $nameText = (strlen($slide["NAME"]) > 43) ? substr($slide["NAME"], 0, 40) . '...' : $slide["NAME"];
                        ?>
                        <li>
                            <div class="img-wrap">
                                <? if ($slide["file"]["src"]) { ?>
                                    <img src="<?= $slide["file"]["src"]; ?>">
                                <? } ?>
                                <a href="<?= $slide["DETAIL_PAGE_URL"]; ?>" class="text"><?=($previewText)?htmlspecialchars($previewText):htmlspecialchars($nameText)?></a>
                            </div>
                        </li>
                    <? } ?>
                    </ul>
                    <div class="next button"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></div>
                </div>
            <? } ?>
        </div>
        <div class="carousel-icon-hearts"></div>
    </div>
</div>
<div class="container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH."/include_areas/qualities.php",
            "AREA_FILE_RECURSIVE" => "N",
            "EDIT_MODE" => "html",
        ),
        false,
        Array('HIDE_ICONS' => 'Y')
    );?>
    <hr class="hidden-sm hidden-xs">
</div>
<div class="container">
<div class="row sections-carousel">
    <div class="fox-icon top"></div>
    <div class="fox-icon bottom"></div>
    <h2>Новиники</h2>
    <div class="col-xs-24 col-sm-24 col-md-18 col-md-offset-1 col-lg-offset-0 col-lg-18 column first-section">
        <div class="carousel-container col-sm-24">
            <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list1", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list1",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "769",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "active_from",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
        </div>
        <div class="carousel-container col-sm-24">
            <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list1", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list1",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "759",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "active_from",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
        </div>
    </div>
    <div class="col-xs-24 col-sm-24 col-md-18 col-md-offset-3 col-lg-offset-0 col-lg-6 column vertical-column">
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-24 first-vertical-carousel-column">
            <div class="carousel-container ">
                <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list2", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list2",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "743",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "active_from",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-24 second-vertical-carousel-column">
            <div class="carousel-container">
                <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list2", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list2",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "790",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "active_from",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
                </div>
            </div>
        </div>
    </div>
    <div id="fox-icon-bottom" class="fox-icon bottom"></div>
</div>

        <? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');?>
        <? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');?>
        <?
        if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
        {
            $dbProductDiscounts = CCatalogDiscount::GetList(
                array("SORT" => "ASC"),
                array(
                    "ACTIVE" => "Y",
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),
                            "YYYY-MM-DD HH:MI:SS",
                            CSite::GetDateFormat("FULL")),
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),
                            "YYYY-MM-DD HH:MI:SS",
                            CSite::GetDateFormat("FULL")),
                    ">=ID"=>19,
                    "<=ID"=>28,
                    "SITE_ID"=>SITE_ID
                ),
                false,
                false,
                array(
                )
            );
            //var_dump($dbProductDiscounts->SelectedRowsCount());
            $arProducts = array();
            while ($arProductDiscounts = $dbProductDiscounts->Fetch())
            {
               // var_dump($arProductDiscounts);
                $arProducts[]['ID'] = $arProductDiscounts['PRODUCT_ID'];
            }

            $carouselID = 'short-list-carousel-'.$ElementID;
            if(count($arProducts)>0){
                ?>
                <div class="short-list-carousel-row">
                    <div class="container">
                        <div class="row">
                            <div id="<?=$carouselID?>" class="short-products-carousel">
                                <div class="title-container clearfix">
                                    <div class="title-text">Товары недели</div>
                                    <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                    <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                    <div class="show-all button"><a href="/catalog?filterOn=week-goods">Посмотреть все</a></div>
                                </div>
                                <ul>
                                    <?
                                    foreach ($arProducts as $product)
                                    {?>
                                        <li>
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
                                                    "IBLOCK_ID" => 19,
                                                    "ELEMENT_ID" => $product['ID'],
                                                    "ELEMENT_CODE" => "",
                                                    "SECTION_ID" => '',
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
                                                    "RATE_FIRS"=>"Y"
                                                ),
                                                false
                                            );?>
                                        </li>
                                    <?}
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        if(window.innerWidth <= 760) inanime_new.init_custom_horizontal_carousel('<?=$carouselID?>', 2);
                        else if(window.innerWidth > 760 && window.innerWidth <= 1230) inanime_new.init_custom_horizontal_carousel('<?=$carouselID?>', 3);
                        else inanime_new.init_custom_horizontal_carousel('<?=$carouselID?>', 4);
                    });
                </script>
            <?}
        }
        ?>


<div class="container">
    <div class="row section-reviews-news">
        <div class="col-xs-24 col-sm-24 col-md-18 col-lg-18 reviews-list main">
            <div class="hidden-xs reveiew-icon-fox"></div>
            <div class="title-container clearfix">
                <div class="btn btn-default ia-btn text-btn blue-dark-btn view-all-btn"><a href="/reviews">Все обзоры</a></div>
                <h2>Обзоры</h2>
            </div>
            <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"reviews-main", 
	array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "24",
		"NEWS_COUNT" => "4",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COMPONENT_TEMPLATE" => "reviews-main",
		"AJAX_OPTION_ADDITIONAL" => "",
		"TEMPLATE_THEME" => "blue",
		"MEDIA_PROPERTY" => "",
		"SLIDER_PROPERTY" => "",
		"SEARCH_PAGE" => "/search/",
		"USE_RATING" => "N",
		"USE_SHARE" => "N"
	),
	false
);?>
        </div>
        <div class="col-xs-24 col-sm-24 col-md-6 col-lg-6 news">
            <div class="news-list">
                <div class="title-container clearfix">
                    <div class="btn btn-default ia-btn text-btn blue-dark-btn view-all-btn"><a href="/news">Все новости</a></div>
                    <h2>Новости</h2>
                </div>
            <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"news-main", 
	array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "18",
		"NEWS_COUNT" => "2",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COMPONENT_TEMPLATE" => "news-main",
		"AJAX_OPTION_ADDITIONAL" => "",
		"TEMPLATE_THEME" => "blue",
		"MEDIA_PROPERTY" => "",
		"SLIDER_PROPERTY" => "",
		"SEARCH_PAGE" => "/search/",
		"USE_RATING" => "N",
		"USE_SHARE" => "N"
	),
	false
);?>
                <div class="reveiew-icon-hearts"></div>
            </div>
        </div>
    </div>
</div>
<div class="section-about">
    <img src="<?=SITE_TEMPLATE_PATH."/images/sections-about-background.png"?>">
    <div class="container">
        <div class="row">
            <?php
            $res = CIBlockElement::GetList(Array(), array("ID"=>56071,"IBLOCK_ID"=>23, "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), array());
            while($ob = $res->GetNextElement())
            {
                $arFields = $ob->GetFields();
                ?><h2 class="yellow-text"><?=$arFields["NAME"]?></h2>
                <div class="content-text"><?=$arFields["PREVIEW_TEXT"]?></div>
                <a class="btn btn-default ia-btn yellow-btn more-btn" href="<?=$arFields["DETAIL_PAGE_URL"]?>" role="button"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></a>
            <?}
            ?>
        </div>
    </div>
</div>
<div class="section-subscribe">
    <div class="container">
        <?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.form", 
	"template1", 
	array(
		"USE_PERSONALIZATION" => "Y",
		"PAGE" => "#SITE_DIR#personal/subscribe/subscr_edit.php",
		"SHOW_HIDDEN" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => "template1",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>