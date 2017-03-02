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
?>
<div class="container ia-articles-list-container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "catalog-chain",
        Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => SITE_ID
        )
    );?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "inanime-list",
//        "",
        Array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "NEWS_COUNT" => $arParams["NEWS_COUNT"],
            "SORT_BY1" => $arParams["SORT_BY1"],
            "SORT_ORDER1" => $arParams["SORT_ORDER1"],
            "SORT_BY2" => $arParams["SORT_BY2"],
            "SORT_ORDER2" => $arParams["SORT_ORDER2"],
            "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "MESSAGE_404" => $arParams["MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
            "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
            "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
            "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
            "ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
            "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
            "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
            "CHECK_DATES" => $arParams["CHECK_DATES"],
        ),
        $component
    );?>

    <div class="row main-carousel catalog hidden-sm hidden-xs">
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
    </div>

</div>
