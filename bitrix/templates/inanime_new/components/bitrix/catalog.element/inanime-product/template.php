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
        if($offer["DETAIL_PICTURE"] && $offer["DETAIL_PICTURE"]["SRC"])
        {
            $offerPhotos[]= $offer["DETAIL_PICTURE"]["SRC"];
        }
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
            'title'=>$offer['NAME'],
            'color'=>(empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"])||$offer["PROPERTIES"]["COLOR_REF"]["VALUE"]=='') ? 'not-set' : $offer["PROPERTIES"]["COLOR_REF"]["VALUE"],
            'price'=>$prices,
            'size'=>$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"],
            'photo'=>$offerPhotos,
            'can_buy'=>$offer["CAN_BUY"]
            );
            $availableSizes[$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"]][] = (empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"])||$offer["PROPERTIES"]["COLOR_REF"]["VALUE"]=='') ? 'not-set' : $offer["PROPERTIES"]["COLOR_REF"]["VALUE"];

        $photoGalleryData[$offer["ID"]] = $offerPhotos;
        /*foreach($offerPhotos as $offerPhoto)
        {
            $photoGalleryData[$offer["ID"]][] = $offerPhoto;
        }*/
    }

    if(empty($photoGalleryData))
    {
        if($arResult['MORE_PHOTO'])
        {
            foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
            {
                $photoGalleryData[$arResult['ID']][] = $arOnePhoto['SRC'];
            }
        }

        if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
        {
            foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
            {
                $photoGalleryData[$arResult['ID']][] = CFile::GetFileArray($photoID)['SRC'];
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

    if($arResult['MORE_PHOTO'])
    {
        foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
        {
            $photoGalleryData[$arResult['ID']][] = $arOnePhoto['SRC'];
        }
    }

    if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
    {
        foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
        {
            $photoGalleryData[$arResult['ID']][] = CFile::GetFileArray($photoID)['SRC'];
        }
    }
}
$arJSParams = array('ajaxURL'=>$templateFolder.'/ajax.php');
// данные для админских кнопок битрикса
$arDataButtons = CIBlock::GetPanelButtons(
    $arResult["IBLOCK_ID"],
    $arResult["ID"],
    0,
    array("SECTION_BUTTONS"=>false, "SESSID"=>false)
);
?>
<?/*?>
<div class="container product-breadcrumbs">
    <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"product-chain",
	array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s2",
		"COMPONENT_TEMPLATE" => "catalog-chain",
		"COMPOSITE_FRAME_MODE" => "Y",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
    $component
);
    ?>
</div>
<?*/?>
<div class="product-card">
    <div class="container">
        <div class="row product-info" id="<?=$strMainID;?>">
        <?$this->AddEditAction($arResult['ID'], $arDataButtons["edit"]["edit_element"]["ACTION_URL"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));?>
        <?$this->AddDeleteAction($arResult['ID'], $arDataButtons["edit"]["delete_element"]["ACTION_URL"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));?>

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
                                        <div class="icons-container">
                                            <?if($isNew):?>
                                                <div class="additional-icon new"></div>
                                            <?endif?>
                                            <?if($isBestseller):?>
                                                <div class="additional-icon bestseller"></div>
                                            <?endif?>
                                            <?if($isRecommended):?>
                                                <div class="additional-icon recommended"></div>
                                            <?endif?>
                                            <?
                                            $discountPercentData = 0 ;
                                            if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])){
                                                $currGalleryPrices = $offersData[$galleryID]['price'];
                                                if(isset($currGalleryPrices[2])) $discountPercentData = $currGalleryPrices[2];
                                            }
                                            else
                                                $discountPercentData = $arResult["PRICES"]['BASE']['DISCOUNT_DIFF_PERCENT'];
                                            if($discountPercentData)
                                            {?>
                                                <div class="discount-container">
                                                    <div class="additional-icon  discount"></div>
                                                    <div class="discount-text">Скидка <?=$discountPercentData;?> %</div>
                                                </div>
                                            <?}
                                            ?>

                                        </div>
                                        <img src="<?=$galleryPhoto[0];?>" class="photo-big">
                                    </div>
                                </div>
                                <div class="photo-carousel-container">
                                    <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                        <ul>
                                            <?
                                            if($galleryPhoto)
                                            {
                                                foreach($galleryPhoto as $key=>$photoSRC)
                                                {?>
                                                    <li<?=($key==0)?' class="active"':''?>>
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
                    <div class="col-xs-24 col-sm-24 col-md-18 col-lg-18 data-column">
                        <div class="art-container">
                            <?if($arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]){?>
                                <span class="art">арт.<?=$arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]?></span>
                            <?}?>

                            <span class="avalable <?=(!$canBuy)?'hidden':''?>">В наличие</span>
                            <span class="notavalable <?=($canBuy)?'hidden':''?>">Нет в наличии</span>

                            <div class="hidden-xs hidden-md  hidden-lg socials-buttons-sm-container">
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath("include_areas/socials-buttons.php"),
                                    Array(),
                                    Array("MODE"=>"html")
                                );?>
                            </div>


                        </div>
                            <?
                            if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                            {
                                foreach($offersData as $offerID => $offer)
                                {?>
                                    <div class="title-container" id="title-container-<?=$offerID?>" <?=($activeOfferID==$offerID)?'':'style="display:none;"'?>><?=$offer['title']?></div>
                                <?}
                            }else
                            {?>
                                <div class="title-container">
                                <?
                                echo (
                                isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
                                    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                                    : $arResult["NAME"]
                                );
                                ?>
                                </div>
                            <?
                            }?>
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
                            <?if(!empty($photoGalleryData))
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
                                    }?>
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
                                                    <?if($galleryPhoto)
                                                    {
                                                        foreach($galleryPhoto as $key=>$photoSRC)
                                                        {?>
                                                            <li<?=($key==0)?' class="active"':''?>>
                                                                <div class="photo-container">
                                                                    <img src="<?=$photoSRC?>">
                                                                </div>
                                                            </li>
                                                        <?
                                                        }
                                                    }?>
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
                            <?if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                            {?>
                                <div class="price">
                                    <?
                                    $currOfferPrice = $offersData[$activeOfferID]["price"];
                                    $oldPrice;
                                    $currentPrice;
                                    if(isset($currOfferPrice[1]) && (floatVal($currOfferPrice[1]) < floatVal($currOfferPrice[0])))
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
                                    <span class="price yellow-text"><? echo $currentPrice; ?> ₽</span>
                                </div>
                                <?if('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){?>
                                    <div class="discount">
                                        <?
                                        if (0 < $currOfferPrice[3])
                                        {
                                            ?>
                                            <span class="discount-amount">Экономия <? echo $currOfferPrice[2]; ?>% -<?=$currOfferPrice[3]?> <span class="rub"></span></span>
                                            <span class="found-cheaper">Нашли дешевле?</span>
                                        <?
                                        }
                                        ?>
                                    </div>
                            <?}
                            }else{?>
                                    <div class="price">
                                        <?
                                        $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
                                        if ($arParams['SHOW_OLD_PRICE'] == 'Y')
                                        {
                                            if(0 < $minPrice['DISCOUNT_DIFF']){?>
                                                <span class="price old"><?=$minPrice['VALUE']?> ₽</span>
                                            <?}?>
                                        <?}?>
                                        <span class="price yellow-text"><? echo $minPrice['DISCOUNT_VALUE']; ?> <span class="rub"></span></span>
                                    </div>
                                    <div class="discount">
                                        <?
                                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
                                        {
                                            if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'])
                                            {
                                                ?>
                                                <span class="discount-amount">Экономия <? echo $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>% -<?=$minPrice['PRINT_DISCOUNT_DIFF']?></span>
                                                <span class="found-cheaper">Нашли дешевле?</span>
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
                        <?}?>

                        <div class="counter-button-container">
                            <div class="ia-counter-container">
                                <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                <input type="text" class="counter-value" value="1"/>
                            </div>
                            <span class="gray-text count-text">шт.</span>
                            <div class="buttons-container">

                                    <div class="button-wrap in-cart" <?=(!$canBuy)?'style="display:none"':''?>>
                                        <div class="btn-group ia-btn-group" role="group">
                                            <button type="button" class="btn btn-default ia-btn yellow-btn quick-order" >
                                                <img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>"/>
                                            </button>
                                            <button type="button" class="btn btn-default ia-btn yellow-btn in-cart"
                                                    onclick="inanime_new.addToCart(
                                                        parseInt($(this).find('.hidden.value').text())
                                                        ,parseInt($('.ia-counter-container input.counter-value').val())
                                                        ,'<?=$arResult["NAME"]?>'
                                                        ,parseInt($('.price-container .price.yellow-text').text())
                                                        ,false)">
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
                                $('.ia-counter-container .button').click(function(){inanime_new.counterButtonClick(this)});
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
                        if (!empty($arResult['DISPLAY_PROPERTIES']))
                        {?>
                            <ul class="characteristic-list gray-text">
                                <?
                                foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
                                {
                                    if(!$arOneProp['DISPLAY_VALUE']) continue;
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
                    }?>
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
                            <?
                            if (!$USER->IsAuthorized())
                            {
                            ?>
                            <div class="status-container">
                                <div class="success">Ответ на Ваш вопрос можно будет выслан Вам на почту</div>
                            </div>
                            <?}else{?>
                            <div class="status-container">
                                <div class="success">Ответ на Ваш вопрос можно будет прочитать в личном кабинете</div>
                            </div>
                            <?}?>
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
$arJSParams['productID'] = $arResult['ID'];
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
            var $this = $(this);
            var newSRC = $this.attr('src');
            $this.closest('.general-container.photo-container').find('li.active').removeClass('active');
            $this.closest('li').addClass('active');
            $this.closest('.general-container.photo-container').find('.photo-big-container img').attr('src',newSRC);
        });


        $('#question-popup button[name="send-button"]').click(function(event){
            event.preventDefault();
            InAnimeCatalogElement<?=$strMainID;?>.addQuestion(event);
        });
        $('#question-popup').on('hidden.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.status-container').hide();
            modal.find('input,textarea').val('');
        });

        $('.product-info .discount .found-cheaper').click(function(){
            $('#found-cheaper-popup').modal();
        });
        $('#found-cheaper-popup button[name="send-button"]').click(function(event){
            event.preventDefault();
            InAnimeCatalogElement<?=$strMainID;?>.sendCheaper(event);
        });
    });
</script>
