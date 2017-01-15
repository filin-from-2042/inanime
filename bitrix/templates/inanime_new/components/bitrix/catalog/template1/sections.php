<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

//var_dump($_REQUEST['filterOn']);
$filterEnabled = $_REQUEST['filterOn'];
if(array_key_exists('filterOn',$_REQUEST) && trim($_REQUEST['filterOn']))
{
    $filterType = $_REQUEST['filterOn'];
    global $arrFilter;
    $arrFilter["PROPERTY_IS_EIGHTEEN"] = false;
    $IDs = array();
    $filterTitle;
    switch($filterType)
    {
        case 'week-goods': {
            $dbProductDiscounts = CCatalogDiscount::GetList(
                array("SORT" => "ASC"),
                array(
                    ">=ID"=>19,
                    "<=ID"=>28,
                    "ACTIVE" => "Y",
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    "SITE_ID"=>SITE_ID
                ),
                false,
                false,
                array(
                    "ID", "PRODUCT_ID", "SECTION_ID"
                )
            );
            while ($arProductDiscounts = $dbProductDiscounts->Fetch())
            {
                if($arProductDiscounts["PRODUCT_ID"]) $IDs[]=$arProductDiscounts["PRODUCT_ID"];
            }
            $filterTitle= 'Товары недели';
        };break;
    }
    $arrFilter['ID'] = $IDs;
    ?>
<div class="container sections-catalog">
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
        $APPLICATION->AddChainItem($filterTitle);
        ?>
        <h1 class="ia-page-title"><?=$filterTitle?></h1>
    </div>

    <?$APPLICATION->AddHeadScript($this->GetFolder()."/jquery.lazyload.min.js");?>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "ajax-catalog",
        array("USE_FILTER" => "Y",
            "SEF_MODE" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SEF_RULE" => "",
            "AJAX_MODE" => "Y",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => '',
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "ELEMENT_SORT_FIELD" => 'sort',
            "ELEMENT_SORT_ORDER" => 'asc',
            "FILTER_NAME" => "arrFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "SET_TITLE" => "Y",
            "SET_BROWSER_TITLE" => "Y",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "Y",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "Y",
            "META_DESCRIPTION" => "-",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
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
                0 => "",
                1 => "",
            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "id",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "5",
            "PRICE_CODE" => array('BASE'),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "BASKET_URL" => "/personal/basket.php",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "USE_PRODUCT_QUANTITY" => "N",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(
            ),
            "BACKGROUND_IMAGE" => "-",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "Y",
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
            "HIDE_NOT_AVAILABLE" => "N",
            "CONVERT_CURRENCY" => "N",
            "OFFERS_CART_PROPERTIES" => array(
            ),
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "COMPONENT_TEMPLATE" => "ajax-catalog",
            "AJAX_OPTION_ADDITIONAL" => "undefined"
        ),
        false
    );?>
    <script>
        $(document).ready(function(){
            $("img.lazy").lazyload({effect : "fadeIn"});
        });
    </script>
</div>
<?
}
else
{/*
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"catalog-top",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"]
	),
	$component
);
?>
<?*/}?>

