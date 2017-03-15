<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
?>
<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');
CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");

// парсинг полей для сортировки и фильтрации
$sortData = json_decode($_REQUEST["sort_data"], true);
$filterData = json_decode($_REQUEST["filter_data"], true);

global $arrFilter;

// массив с данными для фильтрации ТП по размерам и цветам
$arrColorsSizesSubFields = array();

// ИДшники товаров, отфильтрованных по кнопкам "Товар недели", "Со скидкой", "Хит продаж"
$arrButtonsIDs = array();
$weekGoodsIDs = array(19,20,21,22,23,24,25,26,27,28);

// Данные для фильтрации по наличию на складе
$arrStockFilter = array();

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
            $hlblock = HL\HighloadBlockTable::getById(7)->fetch();

            $entity = HL\HighloadBlockTable::compileEntity($hlblock = HL\HighloadBlockTable::getById(7)->fetch());
            $entity_data_class = $entity->getDataClass();
            $entity_table_name = $hlblock['TABLE_NAME'];

            $arFilter = array('UF_NAME'=>$field["value"]); //задаете фильтр по вашим полям

            $sTableID = 'tbl_'.$entity_table_name;
            $rsData = $entity_data_class::getList(array(
                "select" => array('*'), //выбираем все поля
                "filter" => $arFilter,
                "order" => array("UF_SORT"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
            ));
            $rsData = new CDBResult($rsData, $sTableID);
            while($arRes = $rsData->Fetch()){
                $arrFilter["PROPERTY_BRAND_REF1"] =$arRes['UF_XML_ID'];
            }

        };break;

        case 'MATERIAL1':{
            $arrFilter["PROPERTY_MATERIAL1"] = $field["value"];
        };break;

        case 'in-stock-checkbox-filter-code':{
            $arrStockFilter = array(
                'LOGIC' => 'OR',
                array(
                    'ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array("=CATALOG_AVAILABLE" => "Y")),
                ),
                array(
                    "LOGIC" => "AND",
                    array('OFFERS' => NULL),
                    array('=CATALOG_AVAILABLE' => "Y"),
                ),
            );

        };break;

        case 'discount':{
            if($field["value"]=='true')
            {
                // 2 выборки, 1ая для активных скидок с указанным интервалом, вторая для активных без указанного интервала
                $discountIntervalIDs = $weekGoodsIDs;
                $dbProductIntervalDiscounts = CCatalogDiscount::GetList(
                    array("SORT" => "ASC"),
                    array(
                        "!ID" => $weekGoodsIDs,
                        "ACTIVE" => "Y",
                        "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                        ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                        "COUPON" => "",
                        "SITE_ID"=>SITE_ID
                    ),
                    false,
                    false,
                    array(
                        "ID", "PRODUCT_ID", "SECTION_ID"
                    )
                );
                while ($arProductIntervalDiscounts = $dbProductIntervalDiscounts->Fetch())
                {
                    $discountIntervalIDs[] = $arProductIntervalDiscounts['ID'];
                    if($arProductIntervalDiscounts["PRODUCT_ID"]) $arrButtonsIDs[]=$arProductIntervalDiscounts["PRODUCT_ID"];
                }

                $dbProductDiscounts = CCatalogDiscount::GetList(
                    array("SORT" => "ASC"),
                    array(
                        "ACTIVE" => "Y",
                        "ACTIVE_FROM" => false,
                        "ACTIVE_TO" => false,
                        "COUPON" => "",
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
                    if($arProductDiscounts["PRODUCT_ID"]) $arrButtonsIDs[]=$arProductDiscounts["PRODUCT_ID"];
                }
            }
        };break;

        case 'week-goods':
        {
            if($field["value"]=='true')
            {
                $dbProductDiscounts = CCatalogDiscount::GetList(
                    array("SORT" => "ASC"),
                    array(
                        "ID" => $weekGoodsIDs,
                        "ACTIVE" => "Y",
                        "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                        ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL")),
                        "COUPON" => "",
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
                    if($arProductDiscounts["PRODUCT_ID"]) $arrButtonsIDs[]=$arProductDiscounts["PRODUCT_ID"];
                }
            }
        };break;

        case 'topsale':
        {
            if($field["value"]=='true')
            {
                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), array('!PROPERTY_IS_BESTSELLER'=>false), Array("ID"));
                while($ar_fields = $res->GetNext())
                {
                    if($ar_fields["ID"]) $arrButtonsIDs[]=$ar_fields["ID"];
                }
            }
        };break;

        default:
        {
            // фильтрация по свойствам типа чекбокс с картинкой или текстом
            $filterNameExploded = explode('_',$field["name"]);
            if(count($filterNameExploded)>2 && !is_nan($filterNameExploded[1]))
            {
                // цвет
                if(intval($filterNameExploded[1])==167 || intval($filterNameExploded[1])==188)
                {
                    $hlblock = HL\HighloadBlockTable::getById(1)->fetch();

                    $entity = HL\HighloadBlockTable::compileEntity($hlblock = HL\HighloadBlockTable::getById(1)->fetch());
                    $entity_data_class = $entity->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];

                    $arFilter = array('UF_NAME'=>$field["value"]); //задаете фильтр по вашим полям

                    $sTableID = 'tbl_'.$entity_table_name;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array('*'), //выбираем все поля
                        "filter" => $arFilter,
                        "order" => array("UF_SORT"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
                    ));
                    $rsData = new CDBResult($rsData, $sTableID);

                    $arrColors = array();
                    while($arRes = $rsData->Fetch()){
                        $arrColors[] =$arRes['UF_XML_ID'];
                    }
                    if($arrColors) $arrColorsSizesSubFields['PROPERTY_COLOR_REF']=$arrColors;
                }
                // размер
                if(intval($filterNameExploded[1])==208 || intval($filterNameExploded[1])==219)
                {
                    $hlblock = HL\HighloadBlockTable::getById(5)->fetch();

                    $entity = HL\HighloadBlockTable::compileEntity($hlblock = HL\HighloadBlockTable::getById(5)->fetch());
                    $entity_data_class = $entity->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];

                    $arFilter = array('UF_NAME'=>$field["value"]); //задаете фильтр по вашим полям

                    $sTableID = 'tbl_'.$entity_table_name;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array('*'), //выбираем все поля
                        "filter" => $arFilter,
                        "order" => array("UF_SORT"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
                    ));
                    $rsData = new CDBResult($rsData, $sTableID);

                    $arrSize = '';
                    while($arRes = $rsData->Fetch()){
                        $arrSize=$arRes['UF_XML_ID'];
                    }
                    if($arrSize) $arrColorsSizesSubFields['PROPERTY_SIZE_GLK'][]=$arrSize;
                }
            }
        }
    }
}

if($arrColorsSizesSubFields || $arrButtonsIDs || $arrStockFilter)
{
    $additionalFilterData = array('LOGIC'=>'AND');
    // по цветам и размерам ТП
    if($arrColorsSizesSubFields) $additionalFilterData[] = array('ID'=>CIBlockElement::SubQuery('PROPERTY_CML2_LINK', $arrColorsSizesSubFields));
    // по кнопкам "Товар недели", "Со скидкой", "Рекомендуем"
    if($arrButtonsIDs) $additionalFilterData[] = array('ID'=>$arrButtonsIDs);
    // по наличию
    if($arrStockFilter) $additionalFilterData[] = $arrStockFilter;
    $arrFilter[]=$additionalFilterData;
}

// кол-во страниц товаров с текущем фильтром
if($arrFilter && array_key_exists('ID', $arrFilter) && !$arrFilter["ID"]) return;
$newArr = $arrFilter;
$newArr['IBLOCK_ID']='19';
$newArr['SECTION_ID']=$_REQUEST["section_id"];
$newArr['ACTIVE']='Y';
$newArr['INCLUDE_SUBSECTIONS']='Y';

$elem_per_page = $_REQUEST["page_element_count"];
$max_elements = CIBlockElement::GetList( array(),
    $newArr,
    array(),
    false,
    array('ID',
        'NAME')
);
$max_pages = ceil($max_elements/$elem_per_page);
//var_dump($arrFilter);
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


        "OFFERS_SORT_FIELD" => $sortData["sortField"],
        "OFFERS_SORT_ORDER" => $sortData["sortType"],
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER2" => "desc",

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
		"OFFERS_LIMIT" => "5",
		"PRICE_CODE" => json_decode($_REQUEST["price_code"]),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
        "SHOW_OLD_PRICE" => "Y",
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
		"AJAX_OPTION_ADDITIONAL" => "undefined",
        "LAZY_LOAD"=>"N"
	),
	false
);?>
<?
echo '</div>';
?>