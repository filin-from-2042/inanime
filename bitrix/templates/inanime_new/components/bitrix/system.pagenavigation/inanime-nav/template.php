<?php

if (!defined('B_PROLOG_INCLUDED') || (B_PROLOG_INCLUDED !== true)) {
    die();
}

if (!$arResult["NavShowAlways"]) {
    if (
        (0 == $arResult["NavRecordCount"])
        ||
        ((1 == $arResult["NavPageCount"]) && (false == $arResult["NavShowAll"]))
    ) {
        return;
    }
}

$navQueryString      = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$navQueryStringFull  = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

?>
<div class="pagenavigation row">

    <?
    $arAvailableSort = array(
        "price" => Array("catalog_PRICE_1 ", "asc"),
        "rating" => Array("property_rating ", "asc"),
        "date_active" => Array("date_active", "asc"),
        "quantity" => Array("catalog_QUANTITY ", "asc")
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

    <div class="col-md-21 col-md-21">
        <div class="sort-container clearfix">
            <div class="select-container order">
                <div class="select-title"><?= GetMessage('SECT_SORT_LABEL'); ?>:</div>
                <select name="sort_order" onchange="location.href=location.pathname+'?sort='+this.value">
                    <?
                    foreach ($arAvailableSort as $key => $val):
                        $className = ($sort == $val[0]) ? ' current' : '';
                        if ($className)
                            $className .= ($sort_order == 'asc') ? ' asc' : ' desc';
                        ?>
                        <option value="<?= $key; ?>_desc" <? if ($sort == $val[0] && $sort_order == 'desc') echo 'selected="selected"'; ?> data-sort="<?= $val[0]; ?>" data-sort-order="desc"><a href="/">По <?= GetMessage('SECT_SORT_' . $key); ?> (по убыванию)</a></option>
                        <option value="<?= $key; ?>_asc" <? if ($sort == $val[0] && $sort_order == 'asc') echo 'selected="selected"'; ?> data-sort="<?= $val[0]; ?>" data-sort-order="asc">По <?= GetMessage('SECT_SORT_' . $key); ?> (по возрастанию)</option>
                    <? endforeach; ?>
                </select>
            </div>

            <div class="select-container quantity">
                <div class="select-title"><?= GetMessage('SECT_QUANTITY_LABEL');?>:</div>
                <select name="show_quantity">
                    <? foreach (unserialize(PAGE_ARRAY) as $pageCount): ?>
                        <option value="<?= $pageCount; ?>"<? if ($pageCount == $per_page) echo ' selected="selected"'; ?>><?= $pageCount; ?> на странице</option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="type-buttons">
                <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_TOPSALE');?></button>
                <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_NEW');?></button>
                <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_RECOMMENDED');?></button>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="numbers">
            <?php while ($arResult["nStartPage"] <= $arResult["nEndPage"]) { ?>
                <?php if ($arResult["nStartPage"] == $arResult["NavPageNomer"]) { ?>
                    <span class="active"><?php echo $arResult["nStartPage"] ?></span>
                <?php } elseif ((1 == $arResult["nStartPage"]) && (false == $arResult["bSavePage"])) { ?>
                    <a href="<?php echo $arResult["sUrlPath"] ?><?php echo $navQueryStringFull ?>"><?php echo $arResult["nStartPage"] ?></a>
                <?php } else { ?>
                    <a href="<?php echo $arResult["sUrlPath"] ?>?<?php echo $navQueryString ?>PAGEN_<?php echo $arResult["NavNum"] ?>=<?php echo $arResult["nStartPage"] ?>"><?php echo $arResult["nStartPage"] ?></a>
                <?php } ?>
                <?php $arResult["nStartPage"]++ ?>
            <?php } ?>
        </div>
    </div>
</div>
<hr>