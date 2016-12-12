<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$strMainID = $this->GetEditAreaId($arResult['ID']);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);

$photoGalleryData = array();

$isRecommended = (bool)$arResult["PROPERTIES"]["IS_RECOMMEND"]["VALUE"];
if($arElement["DATE_ACTIVE_FROM"]){
    if(((strtotime("now")-strtotime($arElement["DATE_ACTIVE_FROM"]))/86400) <= 14){
        $isNew = true;
    }
}
$isBestseller = (bool)$arResult["PROPERTIES"]["IS_BESTSELLER"]["VALUE"];

if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
    $canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];


    $availableColors = array();
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
    $activeOfferID = 0;
    $minPriceBuff=$arResult['OFFERS'][0]["MIN_PRICE"]["DISCOUNT_VALUE"];
    // данные по всем предложениям
    $offersData = array();
    foreach($arResult['OFFERS'] as $offer)
    {
        if($minPriceBuff>0 && $offer["MIN_PRICE"]["DISCOUNT_VALUE"]<$minPriceBuff)
        {
            $minPriceBuff=$offer["MIN_PRICE"]["DISCOUNT_VALUE"];
            $activeOfferID = $offer["ID"];
        }
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
            'color'=>$offer["PROPERTIES"]["COLOR_REF"]["VALUE"],
            'price'=>$prices,
            'size'=>$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"],
            'photo'=>$offerPhotos,
            'can_buy'=>$offer["CAN_BUY"]
            );
        //if(!empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"]))
            $availableSizes[$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"]][] = $offer["PROPERTIES"]["COLOR_REF"]["VALUE"];

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
}
else
{
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
$arJSParams = array('ajaxURL'=>$templateFolder.'/ajax.php');
?>
<div class="container">
    <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"product-chain",
	array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s2",
		"COMPONENT_TEMPLATE" => "catalog-chain",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);

    ?>
</div>
<div class="product-card">
    <div class="container">
        <div class="row product-info">
            <div class="hidden-xs col-sm-10 col-md-10 col-lg-10 photo-column">
                <?
                    if(!empty($photoGalleryData))
                    {
                        foreach($photoGalleryData as $galleryID=>$galleryPhoto)
                        {
                            $carouselID = 'preview-photo-carousel_'.$galleryID;
                            $showGallery = false;
                            if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                            {
                                   if($activeOfferID==$galleryID) $showGallery = true;
                            }
                            else
                            {
                                if($arResult['ID']==$galleryID) $showGallery = true;
                            }
                            ?>
                            <div class="general-container photo-container" id="photo_gallery_<?=$galleryID?>" <?=($showGallery)?'':'style="display:none;"'?>>
                                <div class="photo-big-container">
                                    <div class="img-wrap">
                                        <?if($isNew):?>
                                            <div class="additional-icon new"></div>
                                        <?endif?>
                                        <?if($isBestseller):?>
                                            <div class="additional-icon bestseller"></div>
                                        <?endif?>
                                        <?if($isRecommended):?>
                                            <div class="additional-icon recommended"></div>
                                        <?endif?>
                                        <img src="<?=$galleryPhoto[0];?>" class="photo-big">
                                    </div>
                                </div>
                                <div class="photo-carousel-container">
                                    <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                        <ul>
                                            <?
                                            if($galleryPhoto)
                                            {
                                                foreach($galleryPhoto as $photoSRC)
                                                {?>
                                                    <li>
                                                        <div class="photo-container">
                                                            <img src="<?=$photoSRC?>">
                                                        </div>
                                                    </li>
                                                <?
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="nav-container">
                                        <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                        <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                        });
                                    </script>
                                </div>
                            </div>
                            <?
                        }
                    }
                ?>
            </div>
            <div class="col-xs-24 col-sm-14 col-md-14 col-lg-14">
                <div class="row">
                    <div class="col-xs-24 col-sm-22 col-md-18 col-lg-18 data-column">
                        <div class="art-container">
                            <?if($arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]){?>
                                <span class="art">арт.<?=$arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]?></span>
                            <?}?>

                            <span class="avalable <?=(!$canBuy)?'hidden':''?>">В наличие</span>
                            <span class="notavalable <?=($canBuy)?'hidden':''?>">Нет в наличии</span>

                            <div class="hidden-xs hidden-md  hidden-lg">
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath("include_areas/socials-buttons.php"),
                                    Array(),
                                    Array("MODE"=>"html")
                                );?>
                            </div>


                        </div>
                        <div class="title-container">
                            <?echo (
                                isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
                                ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                                : $arResult["NAME"]
                            ); ?>
                        </div>
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

                        <div class="visible-xs">
                            <?
                            if(!empty($photoGalleryData))
                            {
                                foreach($photoGalleryData as $galleryID=>$galleryPhoto)
                                {
                                    $carouselID = 'preview-photo-carousel_'.$galleryID.'_xs';
                                    $showGallery = false;
                                    if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                                    {
                                        if($activeOfferID==$galleryID) $showGallery = true;
                                    }
                                    else
                                    {
                                        if($arResult['ID']==$galleryID) $showGallery = true;
                                    }
                                    ?>
                                    <div class="general-container photo-container" id="photo_gallery_xs_<?=$galleryID?>"  <?=($showGallery)?'':'style="display:none;"'?>>
                                        <div class="photo-big-container">
                                            <div class="img-wrap">
                                                <?if($isNew):?>
                                                    <div class="additional-icon new"></div>
                                                <?endif?>
                                                <?if($isBestseller):?>
                                                    <div class="additional-icon bestseller"></div>
                                                <?endif?>
                                                <?if($isRecommended):?>
                                                    <div class="additional-icon recommended"></div>
                                                <?endif?>
                                                <img src="<?=$galleryPhoto[0];?>" class="photo-big">
                                            </div>
                                        </div>
                                        <div class="photo-carousel-container">
                                            <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                                <ul>
                                                    <?
                                                    if($galleryPhoto)
                                                    {
                                                        foreach($galleryPhoto as $photoSRC)
                                                        {?>
                                                            <li>
                                                                <div class="photo-container">
                                                                    <img src="<?=$photoSRC?>">
                                                                </div>
                                                            </li>
                                                        <?
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="nav-container">
                                                <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                                <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                            </div>
                                            <script>
                                                $(document).ready(function () {
                                                    inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                                });
                                            </script>
                                        </div>
                                    </div>
                                <?
                                }
                            }
                            ?>
                        </div>


                        <div class="price-container">
                            <?
                            if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                            {
                            ?>
                                <div class="price">
                                    <?
                                    $currOfferPrice = $offersData[$activeOfferID]["price"];
                                    $boolDiscountShow = count($currOfferPrice)>1;
                                    if ($arParams['SHOW_OLD_PRICE'] == 'Y')
                                    {
                                        if($boolDiscountShow){?>
                                        <span class="price old"><?=$currOfferPrice[0]?> ₽</span>
                                        <?}?>
                                    <?
                                    }
                                    ?>
                                    <span class="price yellow-text"><? echo $currOfferPrice[1]; ?> ₽</span>
                                </div>
                                <?if($boolDiscountShow){?>
                                    <div class="discount">
                                        <?
                                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
                                        {
                                            if (0 < $currOfferPrice[3])
                                            {
                                                ?>
                                                <span class="discount-amount">Экономия <? echo $currOfferPrice[2]; ?>% -<?=$currOfferPrice[3]?>р</span>
                                                <span class="found-cheaper">Нашли дешевле</span>
                                            <?
                                            }
                                        }
                                        ?>
                                    </div>
                            <?
                                }
                            }else{?>
                                    <div class="price">
                                        <?
                                        $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
                                        $boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);
                                        if ($arParams['SHOW_OLD_PRICE'] == 'Y')
                                        {
                                            if($boolDiscountShow){?>
                                                <span class="price old"><?=$minPrice['VALUE']?> ₽</span>
                                            <?}?>
                                        <?
                                        }
                                        ?>
                                        <span class="price yellow-text"><? echo $minPrice['DISCOUNT_VALUE']; ?> <span class="rub"></span> ₽</span>
                                    </div>
                                    <div class="discount">
                                        <?
                                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
                                        {
                                            if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'])
                                            {
                                                ?>
                                                <span class="discount-amount">Экономия <? echo $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>% -<?=$minPrice['PRINT_DISCOUNT_DIFF']?>  ₽</span>
                                                <span class="found-cheaper">Нашли дешевле</span>
                                            <?
                                            }
                                        }
                                        ?>
                                    </div>
                            <?}?>
                        </div>
                        <?if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
                        {
                            ?>
                            <div class="properties-container">

                                <div class="size-container radio-container">
                                    <input type="hidden" name="size-radio" class="ia-radio-value" />
                                    <?
                                    $sizesData = array();
                                    $JSStartColorData = array();
                                    foreach ($availableSizes as $sizeName=>$sizeColors)
                                    {?>
                                        <?
                                        foreach($offersData as $offerID=>$offerData )
                                        {
                                            if($offerData['size']!=$sizeName) continue;

                                            $currOfferColor = $offerData['color'];
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
                                    <script>
                                        $(document).ready(function(){
                                            $('.ia-radio-button,.radio-button-container .button-title').click(inanime_new.radioClick);
                                            $('.color-container .ia-radio-button,.color-container.radio-button-container .button-title').click(
                                                function(event){InAnimeCatalogElement<?=$strMainID;?>.colorClick(event)}
                                            );
                                            $('.size-container .ia-radio-button,.size-container.radio-button-container .button-title').click(
                                                function(event){InAnimeCatalogElement<?=$strMainID;?>.sizeClick(event)}
                                            );
                                        });
                                    </script>
                                </div>

                                <div class="color-container radio-container" >
                                    <input type="hidden" name="color-radio" class="ia-radio-value" />
                                    <?
                                    foreach($availableColors as $colorName => $colorSRC)
                                    {
                                        $showColor = true;
                                        $currSize = $offersData[$activeOfferID]["size"];
                                        $currColor = $offersData[$activeOfferID]["color"];
                                        $showColor = in_array($colorName,$availableSizes[$currSize]);
                                        $prices = implode('-',$offersData[$activeOfferID]['price']);

                                        ?>
                                            <div class="image-radio ia-radio-button <?= $currColor==$colorName ? 'active' : ''?>" <?=!$showColor?'style="display:none;"':''?>>
                                                <span class="value hidden"><?= $colorName;?></span>
                                                <span class="offer-data hidden"><?=$prices?>;<?=$activeOfferID?></span>
                                                <img src="<?=$colorSRC;?>" />
                                            </div>
                                    <?}?>
                            </div>
                        </div>
                        <?
                        }
                        ?>

                        <div class="counter-button-container">
                            <div class="ia-counter-container">
                                <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                <input type="text" class="counter-value" value="1"/>
                            </div>
                            <span class="gray-text count-text">шт.</span>
                            <div class="buttons-container">
                                    <div class="button-wrap in-cart" <?=(!$canBuy)?'style="display:none"':''?>>
                                        <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart"
                                            onclick="
                                                    inanime_new.addToCart(
                                                        parseInt($(this).find('.hidden.value').text())
                                                        ,parseInt($('.ia-counter-container input.counter-value').val())
                                                        ,'<?=$arResult["NAME"]?>'
                                                        ,parseInt($('.price-container .price.yellow-text').text())
                                                        ,false)
                                                ">
                                            <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                            <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" width="17"/></span>
                                            <span class="text-btn">В корзину</span>
                                        </button>
                                    </div>
                                    <div class="button-wrap subscribe" <?=($canBuy)?'style="display:none"':''?>>
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:catalog.product.subscribe",
                                            "inanime-subscribe",
                                            Array(
                                                "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                                "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                "PRODUCT_ID" => (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) ? $activeOfferID : $arResult['ID']
                                            )
                                        );?>
                                    </div>
                                <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite hidden-sm hidden-xs"
                                    onclick="inanime_new.addToCart(parseInt($(this).find('.hidden.value').text())
                                        ,parseInt($('.ia-counter-container input.counter-value').val())
                                        ,'<?=$arResult["NAME"]?>'
                                        ,parseInt($('.price-container .price.yellow-text').text())
                                        ,true)">
                                    <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                    <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" />
                                </button>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function ()
                            {
                                $('.ia-counter-container .button').click(inanime_new.counterButtonClick);
                            });
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-24 col-lg-24 data-column hidden-sm">
                        <?if ('' != $arResult['DETAIL_TEXT'])
                        {
                        ?>
                            <div class="description-container gray-text hidden-xs ">
                            <?
                            if ('html' == $arResult['DETAIL_TEXT_TYPE'])
                            {
                                echo $arResult['DETAIL_TEXT'];
                            }
                            else
                            {
                                ?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
                            }
                            ?>
                            </div>
                        <?
                        }?>
                        <div class="socials-section">
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath("include_areas/socials-buttons.php"),
                                Array(),
                                Array("MODE"=>"html")
                            );?>
                            <div class="print-container hidden-xs ">
                                <i class="fa fa-print" aria-hidden="true" onclick="window.print()"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row product-description hidden-md hidden-lg">
            <div class="col-sm-24">
                <div class="description-container gray-text">
                    <?
                    if ('html' == $arResult['DETAIL_TEXT_TYPE'])
                    {
                        echo $arResult['DETAIL_TEXT'];
                    }
                    else
                    {
                        ?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row product-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#characteristics" aria-controls="characteristics" role="tab" data-toggle="tab">Характеристики</a></li>
                <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Отзывы</a></li>
                <li role="presentation"><a href="#shipping" aria-controls="shipping" role="tab" data-toggle="tab">Доставка и гарантии</a></li>
                <li role="presentation"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Вопрос - ответ</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="characteristics">
                    <?
                    if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
                    {
                    ?>
                        <?
                        if (!empty($arResult['DISPLAY_PROPERTIES']))
                        {
                            ?>
                            <ul class="characteristic-list gray-text">
                                <?
                                foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
                                {
                                    ?>
                                    <li>
                                        <span class="char-name"><? echo $arOneProp['NAME']; ?></span>
                                        <span class="char-value"><?
                                            echo (
                                            is_array($arOneProp['DISPLAY_VALUE'])
                                                ? implode(' / ', $arOneProp['DISPLAY_VALUE'])
                                                : $arOneProp['DISPLAY_VALUE']
                                            ); ?>
                                        </span>
                                    </li><?
                                }
                                unset($arOneProp);
                                ?>
                            </ul>
                        <?
                        }
                    }
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="comments">

                    <?
                    if ('Y' == $arParams['USE_COMMENTS'])
                    {
                        ?>
                        <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.comments",
                        "product-comments",
                        array(
                            "ELEMENT_ID" => $arResult['ID'],
                            "ELEMENT_CODE" => "",
                            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                            "SHOW_DEACTIVATED" => $arParams['SHOW_DEACTIVATED'],
                            "URL_TO_COMMENT" => "",
                            "WIDTH" => "",
                            "COMMENTS_COUNT" => "5",
                            "BLOG_USE" => $arParams['BLOG_USE'],
                            "FB_USE" => $arParams['FB_USE'],
                            "FB_APP_ID" => $arParams['FB_APP_ID'],
                            "VK_USE" => $arParams['VK_USE'],
                            "VK_API_ID" => $arParams['VK_API_ID'],
                            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                            "CACHE_TIME" => $arParams['CACHE_TIME'],
                            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                            "BLOG_TITLE" => "",
                            "BLOG_URL" => $arParams['BLOG_URL'],
                            "PATH_TO_SMILE" => "",
                            "EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
                            "AJAX_POST" => "Y",
                            "SHOW_SPAM" => "Y",
                            "SHOW_RATING" => "N",
                            "FB_TITLE" => "",
                            "FB_USER_ADMIN_ID" => "",
                            "FB_COLORSCHEME" => "light",
                            "FB_ORDER_BY" => "reverse_time",
                            "VK_TITLE" => "",
                            "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );?>
                    <?
                    }
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="shipping">
                    <div class="text-container">
                        <?
                        if(CModule::IncludeModule("iblock"))
                        {
                            $res = CIBlockElement::GetByID(56129);
                            if($ar_res = $res->GetNext())
                                echo $ar_res["~DETAIL_TEXT"];
                        }
                        ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="questions">
                    <?
                    if(CModule::IncludeModule("iblock"))
                    {
                        $res = CIBlockElement::GetByID(56128);
                        if($ar_res = $res->GetNext())
                           echo $ar_res["~DETAIL_TEXT"];
                    }
                    ?>
                    <div class="button-container">
                        <button class="btn btn-default ia-btn text-btn blue-btn question-btn" type="submit" name="OK" value="Задать вопрос">
                            <span class="icon-question"></span>
                            <span>Задать вопрос</span>
                        </button>
                    </div>
                    <script>
                        $('#questions .question-answer-section-container .question-title').click(inanime_new.questionClick);
                        $('#questions .button-container .question-btn').click(function(){$('#question-popup').modal()});
                    </script>
                </div>
            </div>
        </div>

        <div class="modal fade ia-modal" id="photo-lightbox-modal" tabindex="-1" role="dialog" aria-labelledby="modalPhotoLightbox">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="clearfix ">
                            <i class="fa fa-times" aria-hidden="true"></i>
                          </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="photo-container">
                            <img />
                        </div>
                        <div class="control-buttons-container">
                            <div class="prev ia-button">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </div>
                            <div class="next ia-button">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade ia-modal" id="question-popup" tabindex="-1" role="dialog" aria-labelledby="modalQuestionPopup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="clearfix ">
                            <i class="fa fa-times" aria-hidden="true"></i>
                          </span>
                        </button>
                        <h4 class="modal-title">Задать вопрос</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <?
                            global $USER;
                            if (!$USER->IsAuthorized())
                            {
                            ?>
                                <div class="icon-input-container">
                                    <div class="icon-input-wrap">
                                        <input type="text" name="username" value="" placeholder="Имя" class="form-control username-input">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="icon-input-container">
                                    <div class="icon-input-wrap">
                                        <input type="text" name="email" value="" placeholder="Электронная почта" class="form-control email-input">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            <?}?>
                            <div class="icon-input-container">
                                <div class="icon-input-wrap">
                                    <textarea name="mail-text" placeholder="Текс сообщения"  class="form-control email-textarea" value=""></textarea>
                                </div>
                            </div>
                            <div class="status-container">
                                <div class="success">Ответ на Ваш вопрос можно будет прочитать в личном кабинете</div>
                            </div>
                            <div class="button-container">
                                <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="send-button" value="Отправить">
                                    <span>Отправить</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade ia-modal" id="found-cheaper-popup" tabindex="-1" role="dialog" aria-labelledby="modalFoundCheeper">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="clearfix">
                            <i class="fa fa-times" aria-hidden="true"></i>
                          </span>
                        </button>
                        <h4 class="modal-title">Нашли дешевле?</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="icon-input-container">
                                <div class="icon-input-wrap">
                                    <input type="text" name="username" value="" placeholder="Имя" class="form-control username-input">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="icon-input-container">
                                <div class="icon-input-wrap">
                                    <input type="text" name="phone" value="" placeholder="Телефон" class="form-control phone-input">
                                    <i class="ai-icon-bs ia-phone-icon" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="icon-input-container">
                                <div class="icon-input-wrap">
                                    <input type="text" name="email" value="" placeholder="Электронная почта" class="form-control email-input">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="icon-input-container">
                                <div class="icon-input-wrap">
                                    <input type="text" name="product-link" value="" placeholder="Ссылка на товар" class="form-control product-link-input">
                                    <i class="ai-icon-bs ia-link-icon" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="status-container">
                                <div class="success">Ссылка на товар отправлена</div>
                            </div>
                            <div class="button-container">
                                <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="send-button" value="Отправить">
                                    <span>Отправить</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?
$arJSParams['sizesData'] = $sizesData;
$arJSParams['startColorData'] = $JSStartColorData;
?>
<script>
    var InAnimeCatalogElement<?=$strMainID;?> = new window.InAnimeCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true);?>);
    $(document).ready(function(){
        $('.general-container.photo-container .photo-big-container img').click(function(){
            var galleryID = $(this).closest('.general-container.photo-container').attr('id');
            InAnimeCatalogElement<?=$strMainID;?>.popupGalleryID = galleryID;
            $('#photo-lightbox-modal .photo-container img').attr('src',$(this).attr('src'));
            $('#photo-lightbox-modal').modal()
        });

        $('#photo-lightbox-modal .control-buttons-container .ia-button').click(function(){
            var button = $(this);
            var currSRC = button.closest('.modal-body').find('.photo-container img').attr('src');

            var newSRC;
            if(button.hasClass('prev'))
                newSRC = $('#'+InAnimeCatalogElement<?=$strMainID;?>.popupGalleryID+' .photo-carousel-container img[src="'+currSRC+'"]')
                .closest('li').prev().find('img').attr('src');
            else if(button.hasClass('next'))
                newSRC = $('#'+InAnimeCatalogElement<?=$strMainID;?>.popupGalleryID+' .photo-carousel-container img[src="'+currSRC+'"]')
                                .closest('li').next().find('img').attr('src');
            if(newSRC)
                button.closest('.modal-body').find('.photo-container img').attr('src',newSRC);
        });

        $('.general-container.photo-container .photo-container img').click(function(){
            var newSRC = $(this).attr('src');
            $(this).closest('.general-container.photo-container').find('.photo-big-container img').attr('src',newSRC);
        });


        $('#question-popup button[name="send-button"]').click(function(event){
            event.preventDefault();
            InAnimeCatalogElement<?=$strMainID;?>.addQuestion(event);
        });
        $('#question-popup').on('hidden.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.status-container').hide();
            modal.find('input,textarea').val('');
        })

        $('.product-info .discount .found-cheaper').click(function(){
            $('#found-cheaper-popup').modal();
        });
        $('#found-cheaper-popup button[name="send-button"]').click(function(event){
            event.preventDefault();
            InAnimeCatalogElement<?=$strMainID;?>.sendCheaper(event);
        });
    });
</script>