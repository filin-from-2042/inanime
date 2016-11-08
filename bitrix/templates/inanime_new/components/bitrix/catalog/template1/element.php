<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="page page_catalog-item">


    <div class="group">
        <div class="main-side">
<?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],

		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
		'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
		'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
		'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
		'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
		'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
		'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
		'BRAND_USE' => $arParams['DETAIL_BRAND_USE'],
		'BRAND_PROP_CODE' => $arParams['DETAIL_BRAND_PROP_CODE']
	),
	$component
);?>
            </div>
        </div>
<div class="right-side">
                        <?
                        global $arrRecFilter;

                        $APPLICATION->IncludeComponent(
                             "bitrix:catalog.section", "detail_recommend", Array(
                            "AJAX_MODE" => "N",
                            "IBLOCK_TYPE" => "catalog",
                            "IBLOCK_ID" => "2",
                            "SECTION_ID" => "",
                            "SECTION_CODE" => "",
                            "SECTION_USER_FIELDS" => array(0 => "PREVIEW_PICTURE",),
                            "ELEMENT_SORT_FIELD" => "ACTIVE_FROM",
                            "ELEMENT_SORT_ORDER" => "DESC",
                            "ELEMENT_SORT_FIELD2" => "SORT",
                            "ELEMENT_SORT_ORDER2" => "ASC",
                            "FILTER_NAME" => "arrRecFilter",
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "SHOW_ALL_WO_SECTION" => "Y",
                            "SECTION_URL" => "",
                            "DETAIL_URL" => "",
                            "BASKET_URL" => "/personal/order/make/",
                            "ACTION_VARIABLE" => "action",
                            "PRODUCT_ID_VARIABLE" => "id",
                            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                            "PRODUCT_PROPS_VARIABLE" => "prop",
                            "SECTION_ID_VARIABLE" => "SECTION_ID",
                            "META_KEYWORDS" => "-",
                            "META_DESCRIPTION" => "-",
                            "BROWSER_TITLE" => "-",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "DISPLAY_COMPARE" => "N",
                            "SET_TITLE" => "Y",
                            "SET_STATUS_404" => "N",
                            "PAGE_ELEMENT_COUNT" => 40,
                            "LINE_ELEMENT_COUNT" => "1",
                            "PROPERTY_CODE" => array(
                                0 => "IS_NEWPRODUCT",
                                1 => "MINIMUM_PRICE_COMBINE"
                            ),
                            "OFFERS_FIELD_CODE" => array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE"),
                            "OFFERS_PROPERTY_CODE" => array("SIZES_SHOES", "SIZES_CLOTHES", "COLOR_REF", "MORE_PHOTO", "ARTNUMBER"),
                            "OFFERS_SORT_FIELD" => "sort",
                            "OFFERS_SORT_ORDER" => "asc",
                            "OFFERS_SORT_FIELD2" => "id",
                            "OFFERS_SORT_ORDER2" => "desc",
                            "PRODUCT_DISPLAY_MODE" => "Y",
                            "OFFER_TREE_PROPS" => array("SIZES_SHOES", "SIZES_CLOTHES", "COLOR_REF"),
                            "OFFERS_LIMIT" => "5",
                            "PRICE_CODE" => array(0 => "BASE"),
                            "USE_PRICE_COUNT" => "N",
                            "SHOW_PRICE_COUNT" => "1",
                            "PRICE_VAT_INCLUDE" => "Y",
                            "PRODUCT_PROPERTIES" => array(),
                            "USE_PRODUCT_QUANTITY" => "Y",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "DISPLAY_TOP_PAGER" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "PAGER_TITLE" => "Товары",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_TEMPLATE" => "arrows",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "N",
                            "HIDE_NOT_AVAILABLE" => "N",
                            "CONVERT_CURRENCY" => "N",
                            "OFFERS_CART_PROPERTIES" => array("SIZES_SHOES", "SIZES_CLOTHES", "COLOR_REF"),
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N"
                                ), false
                        );
                        ?>
            <? unset($GLOBALS["arrFilter"]); ?>
            <div class="banner banner_col">

            <?
            $APPLICATION->IncludeComponent("bitrix:advertising.banner", "", Array(
                "TYPE" => "good_page",
                "CACHE_TYPE" => "A",
                "NOINDEX" => "Y",
                "CACHE_TIME" => "3600"
                    )
            );
            ?>
            </div>

        </div>
    </div>
</div>

<?
if (0 < $ElementID)
{
	if($arParams["USE_ALSO_BUY"] == "Y" && IsModuleInstalled("sale"))
	{?>
<?$APPLICATION->IncludeComponent("bitrix:sale.recommended.products", ".default", array(
	"ID" => $ElementID,
	"MIN_BUYES" => $arParams["ALSO_BUY_MIN_BUYES"],
	"ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
	"LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
	"DETAIL_URL" => $arParams["DETAIL_URL"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"PRICE_CODE" => $arParams["PRICE_CODE"],
	"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
	'CURRENCY_ID' => $arParams['CURRENCY_ID'],
	'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
	),
	$component
);
?>
<?
	}
	if($arParams["USE_STORE"] == "Y" && IsModuleInstalled("catalog"))
	{?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
		"PER_PAGE" => "10",
		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
		"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
		"ELEMENT_ID" => $ElementID,
		"STORE_PATH"  =>  $arParams["STORE_PATH"],
		"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
	),
	$component
);?>
<?
	}
}?>