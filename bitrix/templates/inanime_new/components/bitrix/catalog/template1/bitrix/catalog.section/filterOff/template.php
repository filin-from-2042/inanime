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
$this->setFrameMode(true);

$arAvailableSort = array(
    "price" => Array("catalog_PRICE_1", "asc"),
    "rating" => Array("PROPERTY_rating", "asc"),
    "date_active" => Array("active_from", "asc"),
    "quantity" => Array("catalog_QUANTITY", "asc")
);
?>
<div class="items-section">
    <div class="sort-container clearfix">
        <div class="select-container order">
            <div class="select-title"><?= GetMessage('SECT_SORT_LABEL'); ?>:</div>
            <select name="sort_order" onchange="inanime_new.refreshCatalogBySort(this.value, <?=$arParams["SECTION_ID"]?>,<?=$arParams["PAGE_ELEMENT_COUNT"]?>)">
                <?
                foreach ($arAvailableSort as $key => $val):?>
                    <option value="<?= $val[0]; ?>;desc" <?=($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='desc')?'selected':''?> >По <?= GetMessage('SECT_SORT_' . $key); ?> (по убыванию)</option>
                    <option value="<?= $val[0]; ?>;asc" <?=($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='asc')?'selected':''?> >По <?= GetMessage('SECT_SORT_' . $key); ?> (по возрастанию)</option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="type-buttons">
            <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_TOPSALE');?></button>
            <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_NEW');?></button>
            <button type="button" class="btn btn-default type-btn"><?= GetMessage('CATALOG_BTN_RECOMMENDED');?></button>
        </div>
    </div>
    <hr>
    <div class="items-container">

        <?foreach($arResult["ITEMS"] as $arElement):?>

            <div class="product-item-preview vertical">
                <div class="image-container">
                    <img src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" />
                    <div class="icons-container">
                        <?if($arElement["DATE_ACTIVE_FROM"]):?>
                            <?//товары не более 2ух недель - новинки?>
                            <?if(((strtotime("now")-strtotime($arElement["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                                <div class="additional-icon new"></div>
                            <?endif?>
                        <?endif?>
                        <?if($arElement["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                            <div class="additional-icon bestseller"></div>
                        <?endif?>
                        <?if($arElement["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                            <div class="additional-icon recommended"></div>
                        <?endif?>
                    </div>
                </div>
                <div class="data-container">
                    <div class="price-container">
                        <?foreach($arResult["PRICES"] as $code=>$arPrice):?>
                            <td>
                                <?if($arPrice = $arElement["PRICES"][$code]):?>
                                    <?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                                        <span class="price old"><?=$arPrice["PRINT_VALUE"]?> &#8381;</span>
                                        <span class="price yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?> &#8381;</span>
                                    <?else:?>
                                        <span class="price yellow-text"><?=$arPrice["PRINT_VALUE"]?> &#8381;</span>
                                    <?endif;?>
                                <?else:?>
                                    &nbsp;
                                <?endif;?>
                            </td>
                        <?endforeach;?>
                    </div>
                    <div class="title-container">
                        <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="link">
                            <div class="text"><?=$arElement["NAME"]?></div>
                            <div class="article">
                                <?
                                if($arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"])
                                    echo 'арт.'.$arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"];
                                else echo '';
                                ?>
                            </div>
                        </a>
                    </div>
                    <div class="rate-container">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:iblock.vote",
                            "stars",
                            Array(
                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                "ELEMENT_ID" => $arElement["ID"],
                                "MAX_VOTE" => $arParams["MAX_VOTE"],
                                "VOTE_NAMES" => $arParams["VOTE_NAMES"],
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                            ),
                            $component
                        );?>
                    </div>
                </div>
            </div>
        <?endforeach?>
    </div>
</div>