<?
//var_dump($arResult['EDIT_LINK']);
$orientation = "vertical";
if(array_key_exists('HORIZONTAL',$arParams) && $arParams['HORIZONTAL']=='Y') $orientation='horizontal';
?>
<div class="product-item-preview <?=$orientation?> product-item-preview-<?=$arResult['ID']?>" id="<?=$this->GetEditAreaId($arResult['ID']);?>">
    <div class="image-container">
        <?if(array_key_exists('LAZY_LOAD', $arParams) && $arParams['LAZY_LOAD']=='Y'){?>
            <img data-original="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>"  class="lazy" />
        <?}else{?>
            <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" />
        <?}?>
        <div class="icons-container">
            <?if($arResult["DATE_ACTIVE_FROM"]):?>
                <?if(((strtotime("now")-strtotime($arResult["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                    <div class="additional-icon new"></div>
                <?endif?>
            <?endif?>
            <?if($arResult["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                <div class="additional-icon bestseller"></div>
            <?endif?>
            <?if($arResult["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                <div class="additional-icon recommended"></div>
            <?endif?>
        </div>
        <?
        $arDiscounts = CCatalogDiscount::GetDiscountByProduct(intVal($arResult['ID']));
        $discountData = array();
        if(count($arDiscounts)>0)
        {
            foreach($arDiscounts as $arDiscount)
            {
                if(intVal($arDiscount['ID'])>=19 && intVal($arDiscount['ID'])<=28)
                {
                    if(!empty($discountData) && floatVal($discountData['VALUE'])>=floatVal($arDiscount['VALUE'])) continue;
                    else $discountData = $arDiscount;
                }
            }
        }
        if($discountData)
        {
            $difference = strtotime($discountData["ACTIVE_TO"]) - strtotime("now");
            $daysLeft = intVal($difference/86400);
            $hoursLeft = intVal(($difference%86400)/3600);
            ?>
            <div class="week-good-icon-container">
                <div class="week-good-icon">
                    <span class="icon-title">Товар недели</span>
                    <span class="time-left"><?=$daysLeft?> д. <?=$hoursLeft?> ч.</span>
                </div>
            </div>
        <?}?>

        <?
            //БЫСТРЫЙ ЗАКАЗ
            $qoModalID = 'quick-order-modal'.$arResult['ID'];
            $photoGalleryData = array();
            $arJSParams = array();
            if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
            {
                // массив соответсвия 'цвет'=>'путь к изображению'
                $availableColors = array('not-set'=>'');
                // массив цветов по размеру ('размер'=>array('цвет1','цвет2'))
                $availableSizes = array();
                foreach ($arResult['SKU_PROPS'] as &$arProp)
                {
                    if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
                        continue;
                    if ('PICT' == $arProp['SHOW_MODE'])
                    {
                        if($arProp["CODE"]=="COLOR_REF")
                        {
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                $arOneValue['XML_ID'] = htmlspecialcharsbx($arOneValue['XML_ID']);
                                if(empty($arOneValue['XML_ID']) || $arOneValue['XML_ID']=='') continue;
                                $availableColors[$arOneValue['XML_ID']] = $arOneValue['PICT']['SRC'];
                            }
                        }
                        elseif($arProp["CODE"]=="SIZE_GLK")
                        {
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                $availableSizes[strtolower($arOneValue['NAME'])] = array();
                            }
                        }
                    }
                }
                unset($arProp);

                // идентификатор активного предложения при первом заходе на страницу
                $activeOfferID = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'];
                // данные по всем предложениям
                $offersData = array();
                foreach($arResult['OFFERS'] as $offer)
                {
                    $offerPhotos = array();
                    if($offer["PROPERTIES"]["GALLERY_PHOTO"]["VALUE"])
                    {
                        foreach($offer["PROPERTIES"]["GALLERY_PHOTO"]["VALUE"] as $photoID)
                        {
                            $offerPhotos[]= CFile::GetFileArray($photoID)["SRC"];
                        }
                    }
                    // цена в итоге преобразования массива в строку должна получиться в формате "текущаяЦена-стараяЦена-процентСкидки-суммаСкидки"
                    // если скидки нет, то будет выглядеть "текущаяЦена", т.е. массив с одним элементом
                    $prices=array();
                    if($offer["MIN_PRICE"]["VALUE"]!=$offer["MIN_PRICE"]["DISCOUNT_VALUE"])
                    {
                        $prices[]=$offer["MIN_PRICE"]["VALUE"];
                        $prices[]=$offer["MIN_PRICE"]["DISCOUNT_VALUE"];
                        $prices[]=$offer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"];
                        $prices[]=$offer["MIN_PRICE"]["DISCOUNT_DIFF"];
                    }
                    else
                    {
                        $prices[]=$offer["MIN_PRICE"]["VALUE"];
                    }
                    $offersData[$offer["ID"]] = array(
                        'color'=>(empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"])||$offer["PROPERTIES"]["COLOR_REF"]["VALUE"]=='') ? 'not-set' : $offer["PROPERTIES"]["COLOR_REF"]["VALUE"],
                        'price'=>$prices,
                        'size'=>$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"],
                        'photo'=>$offerPhotos,
                        'can_buy'=>$offer["CAN_BUY"]
                    );
                    $availableSizes[$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"]][] = (empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"])||$offer["PROPERTIES"]["COLOR_REF"]["VALUE"]=='') ? 'not-set' : $offer["PROPERTIES"]["COLOR_REF"]["VALUE"];

                    foreach($offerPhotos as $offerPhoto)
                    {
                        $photoGalleryData[$offer["ID"]][] = $offerPhoto;
                    }
                }

                if(empty($photoGalleryData))
                {
                    if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
                    {
                        foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
                        {
                            $photoGalleryData[$arResult['ID']][] = CFile::GetFileArray($photoID)['SRC'];
                        }
                    }

                    if(empty($photoGalleryData))
                    {
                        foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
                        {
                            $photoGalleryData[$arResult['ID']][] = $arOnePhoto['SRC'];
                        }
                    }
                }
                $canBuy = $offersData[$activeOfferID]['can_buy'];
                $arJSParams['hasOffers'] = true;
            }
            else
            {
                $arJSParams['hasOffers'] = false;
                $canBuy = $arResult['CAN_BUY'];
                if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
                {
                    foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
                    {
                        $photoGalleryData[$arResult['ID']][] = CFile::GetFileArray($photoID)['SRC'];
                    }
                }

                if(empty($photoGalleryData))
                {
                    foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
                    {
                        $photoGalleryData[$arResult['ID']][] = $arOnePhoto['SRC'];
                    }
                }
            }
        ?>
        <?if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
        {?>
            <div id="hide-overlay<?=$arResult['ID']?>" class="hide-char-overlay"></div>
            <div class="quick-order-overlay">
                <div class="offers-data-container">
                    <div class="properties-container">

                        <div class="size-container radio-container">
                            <input type="hidden" name="size-radio" class="ia-radio-value" />
                            <?
                            // заполнение данных по размерам из данных по предложениям
                            /* массив с данными по предложениям, распределнных по существующим цветам
                            'размер'=>array(
                                        'id предложения'=> array(
                                                                'color'=>'purple',
                                                                'price'=>$prices,
                                                                'size'=>'xl',
                                                                'photo'=>array(),
                                                                'can_buy'=>true
                                                                )
                                        )
                            )
                            */
                            $sizesData = array();
                            $JSStartColorData = array();
                            foreach ($availableSizes as $sizeName=>$sizeColors)
                            {
                                foreach($offersData as $offerID=>$offerData )
                                {
                                    if($offerData['size']!=$sizeName) continue;

                                    $currOfferColor = $offerData['color'];
                                    // из данных размера вытаскиваем и сохраняем данные для первоначально выбраного цвета
                                    if($offersData[$activeOfferID]['size']==$sizeName)
                                        $JSStartColorData[$currOfferColor] = array('price'=>$offerData['price'], 'id'=>$offerID, 'can_buy'=>$offerData['can_buy']);
                                    $sizesData[$sizeName][$offerID] = $offerData;
                                }?>
                                <?if(array_key_exists($sizeName,$sizesData)){?>
                                <div class="size-radio ia-radio-button <?= $offersData[$activeOfferID]['size']==$sizeName ? 'active' : ''?>">
                                    <span class="value hidden"><?= $sizeName?></span>
                                    <span><?= $sizeName;?></span>
                                </div>
                                <?}?>
                            <?}?>
                        </div>

                        <div class="color-container radio-container" >
                            <input type="hidden" name="color-radio" class="ia-radio-value" />
                            <?
                            foreach($availableColors as $colorName => $colorSRC)
                            {
                                $showColor = true;
                                $currSize = $offersData[$activeOfferID]["size"];
                                $currColor = $offersData[$activeOfferID]["color"];
                                $showColor = in_array($colorName,$availableSizes[$currSize]) && $colorName!='not-set';
                                ?>
                                <div class="image-radio ia-radio-button <?= $currColor==$colorName ? 'active' : ''?>" <?=!$showColor?'style="display:none;"':''?>>
                                    <span class="value hidden"><?= $colorName;?></span>
                                    <img src="<?=$colorSRC;?>" />
                                </div>
                            <?}?>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('.product-item-preview-<?=$arResult['ID']?> .ia-radio-button, .product-item-preview-<?=$arResult['ID']?> .radio-button-container .button-title')
                                    .click(inanime_new.radioClick);

                                $('.product-item-preview-<?=$arResult['ID']?> .color-container .ia-radio-button,.product-item-preview-<?=$arResult['ID']?> .color-container.radio-button-container .button-title')
                                    .click(
                                    function(event){InAnimePreviewCatalogElement<?=$arResult['ID'];?>.colorClick(event)}
                                );

                                $('.product-item-preview-<?=$arResult['ID']?> .size-container .ia-radio-button,.size-container.radio-button-container .button-title').click(
                                    function(event){InAnimePreviewCatalogElement<?=$arResult['ID'];?>.sizeClick(event)}
                                );
                            });
                        </script>
                    </div>
                </div>
            </div>
        <?}?>
    </div>
    <div class="data-container">
        <div class="price-container">
            <?
            if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
            {?>
                <div class="price">
                    <?
                    $currOfferPrice = $offersData[$activeOfferID]["price"];
                    $oldPrice;
                    $currentPrice;
                    if(isset($currOfferPrice[1]) && (floatVal($currOfferPrice[1]) > floatVal($currOfferPrice[0])))
                    {
                        $oldPrice=$currOfferPrice[0];
                        $currentPrice=$currOfferPrice[1];
                    } else $currentPrice = $currOfferPrice[0];

                    if ($arParams['SHOW_OLD_PRICE'] == 'Y')
                    {
                        if($oldPrice){?>
                            <span class="price old"><?=$oldPrice?> ₽</span>
                        <?}?>
                    <?}?>
                    <span class="price current yellow-text"><?=$currentPrice;?> ₽</span>
                </div>
            <?}else{
                foreach($arResult["PRICES"] as $code=>$arPrice):?>
                        <?if($arPrice = $arResult["PRICES"][$code]):?>
                            <?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                                <span class="price old"><?=$arPrice["PRINT_VALUE"]?></span>
                                <span class="price current yellow-text"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
                            <?else:?>
                                <span class="price current yellow-text"><?=$arPrice["PRINT_VALUE"]?></span>
                            <?endif;?>
                        <?else:?>
                            &nbsp;
                        <?endif;?>
                <?endforeach;?>
            <?
            }
            ?>
        </div>
        <div class="title-container">
            <a href="<?=$arResult["DETAIL_PAGE_URL"]?>" class="link">
                <div class="text"><?=$arResult["NAME"]?></div>
                <div class="article">
                    <?
                    if($arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"])
                        echo 'арт.'.$arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"];
                    else echo '';
                    ?>
                </div>
            </a>
        </div>
        <?
            // сперва рейтинг, потом кнопки, иначе наоборот
        ?>
        <?if(array_key_exists('RATE_FIRS',$arParams) && $arParams['RATE_FIRS']=='Y'){?>

            <div class="rate-container first-block">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "stars",
                    array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "MAX_VOTE" => "5",
                        "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                        "SET_STATUS_404" => "N",
                        "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME']
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>
            <?// показывать кнопки или нет?>
            <?if(!array_key_exists('HIDE_BUTTONS', $arParams) || $arParams['HIDE_BUTTONS']!='Y'){?>
                <div class="buttons-container last-block">
                    <?// для предложений отобразить две кнопки, иначе по одной?>
                <?if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                {?>
                    <div class="button-wrap in-cart" <?=(!$canBuy)?'style="display:none"':''?>>
                        <div class="btn-group ia-btn-group" role="group">
                            <button type="button" class="btn btn-default ia-btn yellow-btn quick-order hidden-xs" onclick="$('#<?=$qoModalID?>').modal()">
                                <img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>"/>
                            </button>
                            <button type="button" class="btn btn-default ia-btn yellow-btn in-cart"
                                    onclick="InAnimePreviewCatalogElement<?=$arResult['ID'];?>.inCartClick(this)">
                                <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                В корзину
                            </button>
                        </div>
                    </div>
                    <div class="button-wrap subscribe" <?=($canBuy)?'style="display:none"':''?>>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.product.subscribe",
                            "inanime-subscribe",
                            Array(
                                "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "PRODUCT_ID" => (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) ? $activeOfferID : $arResult['ID']
                            )
                        );?>
                    </div>
                <?}else{?>
                    <?if($canBuy){?>
                        <div class="btn-group ia-btn-group" role="group">
                            <button type="button" class="btn btn-default ia-btn yellow-btn quick-order hidden-xs" onclick="$('#<?=$qoModalID?>').modal()">
                                <img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>"/>
                            </button>
                            <button type="button" class="btn btn-default ia-btn yellow-btn in-cart"
                                    onclick="InAnimePreviewCatalogElement<?=$arResult['ID'];?>.inCartClick(this)">
                                <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                В корзину
                            </button>
                        </div>
                    <?}else{?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.product.subscribe",
                            "inanime-subscribe",
                            Array(
                                "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "PRODUCT_ID" => (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) ? $activeOfferID : $arResult['ID']
                            )
                        );?>
                    <?
                    }
                }?>
                    <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                        ,1
                        ,'<?=$arResult["NAME"]?>'
                        ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                        ,true)">
                        <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                    </button>
                </div>
            <?}?>
        <?}else{?>
            <?// показывать кнопки или нет?>
            <?if(!array_key_exists('HIDE_BUTTONS', $arParams) || $arParams['HIDE_BUTTONS']!='Y'){?>
                <div class="buttons-container">

                <?// для предложений отобразить две кнопки, иначе по одной?>
                <?if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                {?>
                    <div class="button-wrap in-cart" <?=(!$canBuy)?'style="display:none"':''?>>
                        <div class="btn-group ia-btn-group" role="group">
                            <button type="button" class="btn btn-default ia-btn yellow-btn quick-order hidden-xs" onclick="$('#<?=$qoModalID?>').modal()">
                                <img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>"/>
                            </button>
                            <button type="button" class="btn btn-default ia-btn yellow-btn in-cart"
                                    onclick="InAnimePreviewCatalogElement<?=$arResult['ID'];?>.inCartClick(this)">
                                <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                В корзину
                            </button>
                        </div>
                    </div>
                    <div class="button-wrap subscribe" <?=($canBuy)?'style="display:none"':''?>>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.product.subscribe",
                            "inanime-subscribe",
                            Array(
                                "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "PRODUCT_ID" => (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) ? $activeOfferID : $arResult['ID']
                            )
                        );?>
                    </div>
                <?}else{?>

                    <?if($arResult["CAN_BUY"]){?>
                        <div class="btn-group ia-btn-group" role="group">
                            <button type="button" class="btn btn-default ia-btn yellow-btn quick-order hidden-xs" onclick="$('#<?=$qoModalID?>').modal()">
                                <img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>"/>
                            </button>
                            <button type="button" class="btn btn-default ia-btn yellow-btn in-cart"
                                    onclick="InAnimePreviewCatalogElement<?=$arResult['ID'];?>.inCartClick(this)">
                                <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                В корзину
                            </button>
                        </div>
                    <?
                    }else{
                        ?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.product.subscribe",
                            "inanime-subscribe",
                            Array(
                                "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "PRODUCT_ID" => (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) ? $activeOfferID : $arResult['ID']
                            )
                        );?>
                    <?
                    }
                }?>
                    <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite" onclick="inanime_new.addToCart(<?=$arResult['ID']?>
                        ,1
                        ,'<?=$arResult["NAME"]?>'
                        ,<?=($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])?$arPrice["DISCOUNT_VALUE"]:$arPrice["VALUE"]?>
                        ,true)">
                        <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                    </button>
                </div>
            <?}?>
            <div class="rate-container">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "stars",
                    array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "MAX_VOTE" => "5",
                        "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                        "SET_STATUS_404" => "N",
                        "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME']
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>

        <?}?>
    </div>
    <?
    $arJSParams['productID'] = $arResult['ID'];
    $arJSParams['sizesData'] = $sizesData;
    $arJSParams['startColorData'] = $JSStartColorData;
    ?>
    <script>
        var InAnimePreviewCatalogElement<?=$arResult['ID'];?> = new window.InAnimePreviewCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true);?>);
    </script>
</div>