<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"inanime-product",
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
);
global $elementIAName;
$APPLICATION->AddChainItem($elementIAName);
?>


<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');?>
<?
if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
{
    $sectionID = 0;
    $arrPriceData = CPrice::GetBasePrice($ElementID);
    $percentPrice = ($arrPriceData['PRICE']/100)*30;

    $res = CIBlockElement::GetByID($ElementID);
    if($ar_res = $res->GetNext())
        $sectionID = intval($ar_res['IBLOCK_SECTION_ID']);

    $arFilter = Array("IBLOCK_ID" => 19,"SECTION_ID"=>$sectionID,'<=CATALOG_PRICE_1'=>floatval($arrPriceData['PRICE'])+$percentPrice,'>=CATALOG_PRICE_1'=>floatval($arrPriceData['PRICE'])-$percentPrice, "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>20), array());
    $arProducts = array();
    while ($ob = $res->GetNextElement())
        $arProducts[] = $ob->GetFields();

    $carouselID = 'short-list-carousel-'.$ElementID;
    if(count($arProducts)>0){
        ?>
        <div class="short-list-carousel-row">
            <div class="container">
                <div class="row">
                 <div id="<?=$carouselID?>" class="short-products-carousel">
                    <div class="title-container clearfix">
                        <div class="title-text">Похожие товары</div>
                        <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                        <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                    </div>
                    <ul>
                <?
                foreach ($arProducts as $product)
                {?>
                    <li>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.element",
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