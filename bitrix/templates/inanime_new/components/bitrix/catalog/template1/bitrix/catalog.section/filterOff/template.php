<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <p><?=$arResult["NAV_STRING"]?></p>
<?endif?>

<div class="items-section">
    <div class="sort-container clearfix">
        <div class="select-title"><?= GetMessage('SECT_SORT_LABEL'); ?>:</div>
        <div class="dropdown select-container order">
            <?
            $strList = '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">'."\n\r";
            $currSort;
            foreach ($_SESSION["inanime_new_catalogdata"]["arAvailableSort"] as $key => $val)
            {
                if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='desc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-bottom"></span><span class="sort-value hidden">'.$val[0].';desc</span>';
                if($arParams["ELEMENT_SORT_FIELD"]==$val[0] && $arParams["ELEMENT_SORT_ORDER"]=='asc') $currSort=GetMessage('SECT_SORT_' . $key).'<span class="glyphicon glyphicon-triangle-top"></span><span class="sort-value hidden">'.$val[0].';asc</span>';
                $strList .= '<li>
                                <span onclick="inanime_new.ddSetSelectedCatalogFilter(this);">
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
            <button type="button" class="btn btn-primary type-btn topsale" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_TOPSALE');?></button>
            <button type="button" class="btn btn-primary type-btn discount" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_DISCOUNT');?></button>
            <button type="button" class="btn btn-primary type-btn week-goods" data-toggle="button" onclick="inanime_new.changeViewHandler()"><?= GetMessage('CATALOG_BTN_WEEK_GOODS');?></button>
        </div>
    </div>
    <hr>
    <div class="items-container">

        <?foreach($arResult["ITEMS"] as $arElement):?>
            <?
            $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="product-item-preview vertical" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
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
                                        <span class="price old"><?=$arPrice["PRINT_VALUE"]?></span>
                                        <span class="price yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
                                    <?else:?>
                                        <span class="price yellow-text"><?=$arPrice["PRINT_VALUE"]?></span>
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
                    <div class="buttons-container">
                        <?if($arElement["CAN_BUY"]){?>
                            <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                                ,1
                                ,'<?=$arElement["NAME"]?>'
                                ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                                ,false)">
                                <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" /></span>
                                <span class="text-btn">В корзину</span>
                            </button>
                        <?
                        }else{
                            ?>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:catalog.product.subscribe",
                                "inanime-subscribe",
//                                "",
                                Array(
                                    "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                    "BUTTON_ID" => $arElement['ID']."-in-cart-btn",
                                    "CACHE_TIME" => "3600",
                                    "CACHE_TYPE" => "A",
                                    "PRODUCT_ID" => $arElement['ID']
                                )
                            );
                            ?>
                        <?
                        }
                        ?>
                        <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arElement['ID']?>
                            ,1
                            ,'<?=$arElement["NAME"]?>'
                            ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                            ,true)">
                            <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                        </button>
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