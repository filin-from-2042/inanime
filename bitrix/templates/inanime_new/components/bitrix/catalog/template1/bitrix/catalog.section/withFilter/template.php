<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="row">
    <div class="col-md-24 col-lg-24">
        <div class="sort-container clearfix">
            <div class="select-title"><?= GetMessage('SECT_SORT_LABEL'); ?>:</div>
            <div class="dropdown select-container order">
                <?
                $strList = '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">'."\n\r";
                $currSort;
                foreach ($_SESSION["inanime_new_catalogdata"]["arAvailableSort"] as $key => $val)
                {
                    if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='desc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-top"></span><span class="sort-value hidden">'.$val[0].';desc</span>';
                    if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='asc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-bottom"></span><span class="sort-value hidden">'.$val[0].';asc</span>';
                    $strList .= '<li>
                                    <span onclick="inanime_new.ddSetSelectedText(this);">
                                        '.GetMessage('SECT_SORT_' . $key).'
                                        <span class="glyphicon glyphicon-triangle-bottom"></span>
                                        <span class="sort-value hidden">'.$val[0].';desc</span>
                                    </span>
                                </li>'."\n\r";
                    $strList .= '<li>
                                    <span onclick="inanime_new.ddSetSelectedText(this);">
                                        '.GetMessage('SECT_SORT_' . $key).'
                                        <span class="glyphicon glyphicon-triangle-top"></span>
                                        <span class="sort-value hidden">'.$val[0].';asc</span>
                                    </span>
                                </li>'."\n\r";
                }
                $strList .= '</ul>'."\n\r";
                ?>
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-chevron-down"></span>
                    <span class="text"><?=$currSort?></span>
                </button>
                <?=$strList?>
            </div>
            <div class="type-buttons">
                <button type="button" class="btn btn-primary type-btn discount" data-toggle="button"><?= GetMessage('CATALOG_BTN_DISCOUNT');?></button>
                <button type="button" class="btn btn-primary type-btn week-goods" data-toggle="button"><?= GetMessage('CATALOG_BTN_WEEK_GOODS');?></button>
                <button type="button" class="btn btn-primary type-btn topsale" data-toggle="button"><?= GetMessage('CATALOG_BTN_TOPSALE');?></button>
            </div>
        </div>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter", "visual_vertical1", Array(
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
    </div>
    <div class="col-xs-18 col-sm-18 col-md-18 col-lg-18">
        <?if($arParams["DISPLAY_TOP_PAGER"]):?>
            <p><?=$arResult["NAV_STRING"]?></p>
        <?endif?>

        <div class="items-section">
            <div class="items-container">

                <?foreach($arResult["ITEMS"] as $arElement):?>

                    <div class="product-item-preview vertical">
                        <div class="image-container">
                            <img data-original="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" class="lazy" />
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
            <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                <p><?=$arResult["NAV_STRING"]?></p>
            <?endif?>

        </div>
    </div>
</div>