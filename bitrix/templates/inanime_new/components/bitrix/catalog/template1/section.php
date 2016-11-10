<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?$APPLICATION->AddHeadScript($this->GetFolder()."/jquery.lazyload.min.js");?>
<div class="container section-catalog">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "catalog-chain",
        Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => ""
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
            ?>
            <h1><?=$sectionName?></h1>
        </div>
    </div>
    <?if($arResult["VARIABLES"]["SECTION_CODE"]=="kategorii" || $arResult["VARIABLES"]["SECTION_CODE"]=="po-filmam-igram"){?>
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "catalog-left",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $catalog_section_id,
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                        "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                        "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );
                ?>
            </div>
            <div class="col-md-18 col-lg-18">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "filterOff",
                    Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $catalog_section_id,
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "SHOW_ALL_WO_SECTION" => "Y",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                        "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                        "SET_TITLE" => $arParams["SET_TITLE"],
                        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                        "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],

                        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                    ),
                    $component
                );
                ?>
            </div>
        </div>
    <?}else{?>
        <div class="row">
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                "",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $catalog_section_id,
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                    "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                    "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>
        </div>
        <div class="row">
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "withFilter",
                Array(
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $catalog_section_id,
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                    "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],

                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                ),
                $component
            );
            ?>
        </div>
    <?}?>
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

            inanime_new.inProgress = false;
            $(window).scroll(function() {

                /*если количество элементов на странице < количества элементов в разделе*/
                if (window.scrollLoadMaxPages < window.scrollLoadStartFrom) inanime_new.inProgress = true;
                /* Если высота окна + высота прокрутки больше или равна высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос. 600 - это высота подвала в пикселях */
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 600 && !inanime_new.inProgress)
                {
                    var splittedVal = $('.section-catalog #section-sort-order').val();
                    inanime_new.getSectionPage(splittedVal, <?=$catalog_section_id?>, '<?=$arParams["PAGE_ELEMENT_COUNT"]?>', window.scrollLoadStartFrom);
                    window.scrollLoadStartFrom ++;
                }
            });
        });
    </script>
</div>

