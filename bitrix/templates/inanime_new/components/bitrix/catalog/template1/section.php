<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?$APPLICATION->AddHeadScript($this->GetFolder()."/jquery.lazyload.min.js");?>
<div class="container section-catalog">

    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "catalog-chain",
        Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => SITE_ID
        )
    );?>
    <div class="row">
        <div class="col-md-24 col-lg-24">
            <?
            $res = CIBlockSection::GetList(array("SORT"=>"ASC"), array('IBLOCK_ID' => $arParams['IBLOCK_ID'],'=CODE' => $arResult["VARIABLES"]["SECTION_CODE"]),false, array("UF_*"));
            $uf_value =null;
            $sectionName = null;

            //ID инфоблока каталога:
            $catalog_iblock_id;
            //раздел каталога:
            $catalog_section_id;
            if($uf_value = $res->GetNext())
            {
                $sectionName = $uf_value["NAME"];
                $catalog_iblock_id = $uf_value["IBLOCK_ID"];
                $catalog_section_id = $uf_value["ID"];
            }
            // sort data
            $_SESSION["inanime_new_catalogdata"]["arAvailableSort"] = array(
                "price" => Array("catalog_PRICE_1", "asc"),
                "rating" => Array("PROPERTY_rating", "asc"),
                "date_active" => Array("active_from", "asc"),
                "quantity" => Array("catalog_QUANTITY", "asc")
            );

            ?>
            <h1><?=$sectionName?></h1>
        </div>
    </div>
    <?if($arResult["VARIABLES"]["SECTION_CODE"]=="kategorii" || $arResult["VARIABLES"]["SECTION_CODE"]=="po-filmam-igram"){
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_main.php");
    }else{
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_sub.php");
    }?>
</div>
<?// ЛИДЕРЫ ПРОДАЖ
if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
{

     $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');
     $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');
    $productIds = array();
    $productIterator = CSaleProduct::GetBestSellerList(
        'AMOUNT',
        array(),
        array(array("=STATUS_ID" => 'E'),'SITE_ID'=>SITE_ID),
        15
    );
    while($product = $productIterator->fetch())
    {
        /*
         * $db_old_groups = CIBlockElement::GetElementGroups($product['PRODUCT_ID'], true);
            while($ar_group = $db_old_groups->Fetch())
                var_dump($ar_group["ID"]);
         * */
        if(CIBlockElement::GetIBlockByID((int)$product['PRODUCT_ID'])==20)
        {
            $mxResult = CCatalogSku::GetProductInfo((int)$product['PRODUCT_ID']);
            if (is_array($mxResult))
            {
                $productIds[] = $mxResult['ID'];
            }
        } else $productIds[] = $product['PRODUCT_ID'];
    }

    $carouselID = 'short-list-carousel-'.$ElementID;
    if(count($productIds)>0)
    {?>
    <div class="section-bestseller">
        <div class="short-list-carousel-row">
            <div class="container">
                <div class="row">
                    <div id="<?=$carouselID?>" class="short-products-carousel">
                        <div class="title-container clearfix">
                            <div class="title-text">Лидеры продаж</div>
                            <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                            <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                        </div>
                        <ul>
                            <?
                            foreach ($productIds as $product)
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
                                            "ELEMENT_ID" => $product,
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
    <div class="row section-description">
        <div class="text">
            <?
            $res = CIBlockSection::GetByID($catalog_section_id);
            $ar_res=$res->GetNext();
            echo $ar_res['DESCRIPTION'];
            ?>
        </div>
        <div class="show-below-btn">
            <i class="fa fa-angle-double-down" aria-hidden="true"></i>
        </div>
    </div>
    <div class="row section-main-banner">
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
    <script>
        /*AJAX прогрузка*/
        $(document).ready(function(){
            $("img.lazy").lazyload({effect : "fadeIn"});
            /* С какой страницы надо делать выборку из базы при ajax-запросе */
            window.scrollLoadStartFrom = 2;

            /* максимальное количество страниц */
            <?
            $elem_per_page = $arParams["PAGE_ELEMENT_COUNT"];
            CModule::IncludeModule("iblock");
            $max_elements = CIBlockElement::GetList( array(),
                                     array('IBLOCK_ID'=>$catalog_iblock_id,
                                       'ACTIVE'=>'Y',
                                       'SECTION_ID'=>$catalog_section_id,
                                       'INCLUDE_SUBSECTIONS'=>'Y'
                                     ),
                                     array(),
                                     false,
                                 array('ID',
                                       'NAME')
                                    );
            $max_pages = ceil($max_elements/$elem_per_page);
            ?>

            window.scrollLoadMaxPages = <?=$max_pages?>;
            window.currCatalogSectionID = <?=$catalog_section_id?>;
            window.currCatalogPageElementCount = <?=$elem_per_page?>;

            inanime_new.inProgress = false;
            $(window).scroll(function() {

                /*если количество элементов на странице < количества элементов в разделе*/
                if (window.scrollLoadMaxPages < window.scrollLoadStartFrom) inanime_new.inProgress = true;
                /* Если высота окна + высота прокрутки больше или равна высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос. 600 - это высота подвала в пикселях */
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 600 && !inanime_new.inProgress)
                {
                    inanime_new.changeViewHandler(true);
                }
            });
        });
    </script>
</div>
