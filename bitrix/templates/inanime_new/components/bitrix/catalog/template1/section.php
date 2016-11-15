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

