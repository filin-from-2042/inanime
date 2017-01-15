<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
?>
<?
// парсинг полей для сортировки и фильтрации
$sortData = json_decode($_REQUEST["sort_data"], true);
$filterData = json_decode($_REQUEST["filter_data"], true);
//$arrFilter = array();
global $arrFilter;
$arrFilter["PROPERTY_IS_EIGHTEEN"] = false;
foreach($filterData as $field)
{
    switch($field["name"])
    {
        case 'arrFilter_P1_MIN':{
         $minPrice = $field["value"];

        }; break;
        case 'arrFilter_P1_MAX': {
            $maxPrice = $field["value"];
            $arrFilter["><CATALOG_PRICE_1"]=array($minPrice,$maxPrice);
        };break;
        case 'BRAND_REF1':{
            $arrFilter["PROPERTY_BRAND_REF1"]=$field["value"];
        };break;
        case 'MATERIAL1':{
            $arrFilter["PROPERTY_MATERIAL1"] = $field["value"];
        };break;
        case 'IS_EIGHTEEN':{
            unset($arrFilter["PROPERTY_IS_EIGHTEEN"]);
        };break;
        case 'discount': $discount = ($field["value"]=='false')?false:true;break;
        case 'week-goods': $weekGoods = ($field["value"]=='false')?false:true;break;
        case 'topsale': $topsale = ($field["value"]=='false')?false:true;break;
    }
}
if($discount||$weekGoods||$topsale)
{
    $IDs = array();
    $weekGoodsIDs = array(19,20,21,22,23,24,25,26,27,28,29,30);
    if (CModule::IncludeModule("catalog"))
    {
        if($weekGoods)
        {
            $dbProductDiscounts = CCatalogDiscount::GetList(
                array("SORT" => "ASC"),
                array(
                    "ID" => $weekGoodsIDs,
                    "ACTIVE" => "Y",
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    "COUPON" => ""
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
        }
        if($discount)
        {
            $dbProductDiscounts = CCatalogDiscount::GetList(
                array("SORT" => "ASC"),
                array(
                    "!ID" => $weekGoodsIDs,
                    "ACTIVE" => "Y",
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                    "COUPON" => ""
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
        }
        if($topsale)
        {
            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), array('!PROPERTY_IS_BESTSELLER'=>false), Array("ID"));
            while($ar_fields = $res->GetNext())
            {
                if($ar_fields["ID"]) $IDs[]=$ar_fields["ID"];
            }
            //$arrFilter['!PROPERTY_IS_BESTSELLER'] = false;
        }
    }
    $arrFilter["ID"] = $IDs;
}
// кол-во страниц товаров с текущем фильтром
$newArr = $arrFilter;
$newArr['IBLOCK_ID']='19';
$newArr['SECTION_ID']=$_REQUEST["section_id"];
$newArr['ACTIVE']='Y';
$newArr['INCLUDE_SUBSECTIONS']='Y';

$elem_per_page = $_REQUEST["page_element_count"];
CModule::IncludeModule("iblock");
$max_elements = CIBlockElement::GetList( array(),
    $newArr,
    array(),
    false,
    array('ID',
        'NAME')
);
$max_pages = ceil($max_elements/$elem_per_page);

echo '<div>';
echo '<span class="hidden" id="maxPages">'.$max_pages.'</span>';
?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"ajax-catalog", 
	array(
		"SEF_MODE" => "N",
        "SHOW_ALL_WO_SECTION" => "Y",
		"SEF_RULE" => "",
		"AJAX_MODE" => "Y",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "19",
		"SECTION_ID" => $_REQUEST["section_id"],
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => $sortData["sortField"],
		"ELEMENT_SORT_ORDER" => $sortData["sortType"],
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
		"PAGE_ELEMENT_COUNT" => $_REQUEST["page_element_count"],
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
		"PRICE_CODE" => json_decode($_REQUEST["price_code"]),
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
		"CACHE_TYPE" => "A",
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
<?
echo '</div>';
?>