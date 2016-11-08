<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $mainCatalogSection;
//$APPLICATION->addChainItem("Каталог товаров",$mainCatalogSection["SECTION_PAGE_URL"]);
if ('Y' == $arParams['USE_FILTER']) {
    if (CModule::IncludeModule("iblock")) {
        $arFilter = array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ACTIVE" => "Y",
            "GLOBAL_ACTIVE" => "Y",
        );
        if (0 < intval($arResult["VARIABLES"]["SECTION_ID"])) {
            $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
        } elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"]) {
            $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
        }

        $obCache = new CPHPCache();
        if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
            $arCurSection = $obCache->GetVars();
        } else {
            $arCurSection = array();
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->GetNext()) {
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);
                }
                $CACHE_MANAGER->EndTagCache();
            } else {
                if (!$arCurSection = $dbRes->GetNext())
                    $arCurSection = array();
            }

            $obCache->EndDataCache($arCurSection);
        }
    }
}
?>
<div class="layout__col group">
    <div class="page page_catalog">
        <div class="form form_show_options">
            <?
            $arAvailableSort = array(
                "name" => Array("name", "asc"),
                "price" => Array("PROPERTY_MINIMUM_PRICE", "asc"),
                "date_active" => Array("date_active", "asc")
            );
            if (strlen($_SESSION["USER_VALUES"]["catalog"]["sort"]) > 0)
                $sort = $_SESSION["USER_VALUES"]["catalog"]["sort"];
            else
                $sort = "name";
            if (strlen($_SESSION["USER_VALUES"]["catalog"]["sort_order"]) > 0)
                $sort_order = $_SESSION["USER_VALUES"]["catalog"]["sort_order"];
            else
                $sort_order = "asc";


            if ($_SESSION["USER_VALUES"]["catalog"]["sort_order"] == "desc" && $_SESSION["USER_VALUES"]["catalog"]["sort"] == 'price') {
                $arAvailableSort['price'] = Array("PROPERTY_MAXIMUM_PRICE", "desc");
            }
            ?>
            <? $per_page = ((int) $_SESSION["USER_VALUES"]["catalog"]["per_page"] > 0) ? (int) $_SESSION["USER_VALUES"]["catalog"]["per_page"] : 12; ?>

            <? if (!isset($_REQUEST["bxajaxid"])): ?>
                <div class="selectWrap">
                    <select name="sort_order" id="catalog_sort_order" class="form__select">
                        <?
                        foreach ($arAvailableSort as $key => $val):
                            $className = ($sort == $val[0]) ? ' current' : '';
                            if ($className)
                                $className .= ($sort_order == 'asc') ? ' asc' : ' desc';
                            ?>
                            <option value="<?= $key; ?>_desc" <? if ($sort == $val[0] && $sort_order == 'desc') echo 'selected="selected"'; ?> data-sort="<?= $val[0]; ?>" data-sort-order="desc">По <?= GetMessage('SECT_SORT_' . $key); ?> (по убыванию)</option>
                            <option value="<?= $key; ?>_asc" <? if ($sort == $val[0] && $sort_order == 'asc') echo 'selected="selected"'; ?> data-sort="<?= $val[0]; ?>" data-sort-order="asc">По <?= GetMessage('SECT_SORT_' . $key); ?> (по возрастанию)</option>
    <? endforeach; ?>
                    </select>
                </div>

                <div class="selectWrap">
                    <select name="show_quantity" id="catalog_per_page" class="form__select">
                        <? foreach (unserialize(PAGE_ARRAY) as $pageCount): ?>
                            <option value="<?= $pageCount; ?>"<? if ($pageCount == $per_page) echo ' selected="selected"'; ?>><?= $pageCount; ?> на странице</option>
    <? endforeach; ?>
                    </select>
                </div>


            <? endif; ?>

            <? foreach (unserialize(PAGE_ARRAY) as $pageCount): ?>
                <a href="<?= $APPLICATION->GetCurPageParam('per_page=' . $pageCount, array('per_page')) ?>" id="per_page<?= $pageCount; ?>" class="ajax-catalog-link displaynone"></a>
            <? endforeach; ?>
            <? /* foreach ($arAvailableSort as $key => $val):
              $className = ($sort == $val[0]) ? ' current' : '';
              if ($className)
              $className .= ($sort_order == 'asc') ? ' asc' : ' desc';
              ?>
              <a href="<?= $APPLICATION->GetCurPageParam('sort=' . $key . '&order=desc', array('sort', 'order')) ?>" id="<?= $key; ?>_desc" class="ajax-catalog-link displaynone <? if ($sort_order == 'desc') echo $className ?>"></a>
              <a href="<?= $APPLICATION->GetCurPageParam('sort=' . $key . '&order=asc', array('sort', 'order')) ?>" id="<?= $key; ?>_asc" class="ajax-catalog-link displaynone <? if ($sort_order == 'asc') echo $className ?>"></a>

              <? endforeach; */ ?>





        </div>

        <div class="group">
            <div class="bx_sidebar">
                <div class="left-side">
                    <?
                    $APPLICATION->IncludeComponent(
                            "magwai:catalog.smart.filter", "magwai", Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $arCurSection['ID'],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_NOTES" => "",
                        "CACHE_GROUPS" => "Y",
                        "SAVE_IN_SESSION" => "N",
                        "XML_EXPORT" => "Y",
                        "SECTION_TITLE" => "NAME",
                        "SECTION_DESCRIPTION" => "DESCRIPTION"
                            ), $component, array('HIDE_ICONS' => 'Y')
                    );
                    ?>
                    <div class="banner banner_col">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:advertising.banner", "", Array(
                            "TYPE" => "catalog_page",
                            "CACHE_TYPE" => "A",
                            "NOINDEX" => "Y",
                            "CACHE_TIME" => "3600"
                                )
                        );
                        ?>
                    </div>
                </div>
            </div>


            <div class="main-side">

                <?
                if ($arParams["USE_COMPARE"] == "Y") {
                    ?>
                    <?
                    $APPLICATION->IncludeComponent(
                            "bitrix:catalog.compare.list", "", array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "NAME" => $arParams["COMPARE_NAME"],
                        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                        "COMPARE_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["compare"],
                            ), $component
                    );
                    ?><?
                }

                $intSectionID = 0;
                ?>

<?
if (isset($_REQUEST['arrFilter_P1_MIN']) || isset($_REQUEST['arrFilter_P1_MAX'])) {
    global ${$arParams["FILTER_NAME"]};
    $dop = array();
    if (isset($_REQUEST['arrFilter_P1_MIN'])) {
        $dop['>=PROPERTY_MINIMUM_PRICE'] = intVal($_REQUEST['arrFilter_P1_MIN']);
    }
    if (isset($_REQUEST['arrFilter_P1_MAX'])) {
        $dop['<=PROPERTY_MAXIMUM_PRICE'] = intVal($_REQUEST['arrFilter_P1_MAX']);
    }
    ${$arParams["FILTER_NAME"]} = array_merge(${$arParams["FILTER_NAME"]}, $dop);
}
?>

                <?
                $intSectionID = $APPLICATION->IncludeComponent(
                        "magwai:catalog.section", "", array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_SORT_FIELD" => $sort,
                    "ELEMENT_SORT_ORDER" => $sort_order,
                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                    "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                    "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                    "PAGE_ELEMENT_COUNT" => $per_page,
                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
                        ), $component
                );
                ?>
            </div>
            <div style="clear: both;"></div>

        </div>
    </div>
</div>
