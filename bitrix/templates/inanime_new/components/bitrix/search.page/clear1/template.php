<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
?>
<div class="container ia-search-page">
    <div class="ia-top-breadcrumb-title-container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "catalog-chain",
            Array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => SITE_ID
            )
        );
        ?>
        <?
        $APPLICATION->AddChainItem('Результаты поиска');
        ?>
        <h1 class="ia-page-title">Результаты поиска</h1>
    </div>
    <form action="" method="get">
        <input type="hidden" name="tags" value="<?echo $arResult["REQUEST"]["TAGS"]?>" />
        <input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />

        <div class="row search-panel-row">
            <div class="search-control-wrap">
                <div class="align-wrap">

                        <?$APPLICATION->IncludeComponent(
                        "bitrix:search.suggest.input",
                        "search-page",
                        array(
                            "NAME" => "q",
                            "VALUE" => $arResult["REQUEST"]["~QUERY"],
                            "INPUT_SIZE" => -1,
                            "DROPDOWN_SIZE" => 10,
                            "FILTER_MD5" => $arResult["FILTER_MD5"],
                        ),
                        $component, array("HIDE_ICONS" => "Y")
                    );?>

                    <input class="search-button btn btn-default ia-btn text-btn yellow-btn" type="submit" value="<?echo GetMessage("CT_BSP_GO")?>" />

                </div>
            </div>
            <div class="search-info-wrap">
                <div class="align-wrap">По Вашему запросу найдено : <?echo $arResult["NAV_RESULT"]->SelectedRowsCount()?></div>
            </div>
        </div>
    </form>

    <?if(count($arResult["SEARCH"])>0):?>
        <?
        $productsList = array();
        $articlesList = array();
        foreach($arResult["SEARCH"] as $arItem)
        {
            if($arItem['PARAM1']=='catalog'){ $productsList[] = $arItem;continue;}
            if($arItem['PARAM1']=='news'){ $articlesList[] = $arItem;continue;}
        }?>
        <?if(count($productsList)>0){?>
        <?if($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
        <div class="product-list-container list-container">
            <h2>Товары</h2>
            <hr>
            <div class="product-list-wrap">
            <?foreach($productsList as $arItem)
            {
                $currIBLOCK_ID=0;
                $currSECTION_ID=0;
                $currELEMENT_ID = intval($arItem["ITEM_ID"]);
                if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
                {
                    $res = CIBlockElement::GetByID($currELEMENT_ID);
                    if($ar_res = $res->GetNext())
                    {
                        $currIBLOCK_ID = $ar_res['IBLOCK_ID'];
                        $currSECTION_ID = $ar_res['IBLOCK_SECTION_ID'];
                    }
                }
                if($currIBLOCK_ID==0 || $currSECTION_ID==0) continue;
                ?>
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
                    "IBLOCK_ID" => $currIBLOCK_ID,
                    "ELEMENT_ID" => $currELEMENT_ID,
                    "ELEMENT_CODE" => "",
                    "SECTION_ID" => $currSECTION_ID,
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
                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                ),
                false
            );?>
            <?
            }?>
            </div>
        <?}
        ?>
        </div>
        <?if(count($articlesList)>0){?>
        <div class="articles-list-container list-container">
            <h2>Статьи</h2>
            <hr>
            <?
            foreach($articlesList as $arItem)
            {?>
                <h4><a href="<?echo $arItem["URL"]?>" class="light-blue-text-underline"><?echo $arItem["TITLE_FORMATED"]?></a></h4>
                <div class="search-preview gray-text"><?echo $arItem["BODY_FORMATED"]?></div>
            <?}?>
        </div>
        <?}?>


        <?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
    <?endif;?>
</div>