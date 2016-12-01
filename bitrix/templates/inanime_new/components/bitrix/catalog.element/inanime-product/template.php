<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//var_dump($arResult);
$strMainID = $this->GetEditAreaId($arResult['ID']);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);

if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
    //var_dump($arResult['OFFERS']);
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
    // метка отражает наличие своей галереи у товарного предложения
    $offerPhotoMark = false;
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
                $offerPhotoMark = true;
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
            'photo'=>$offerPhotos
            );
        //if(!empty($offer["PROPERTIES"]["COLOR_REF"]["VALUE"]))
            $availableSizes[$offer["PROPERTIES"]["SIZE_GLK"]["VALUE"]][] = $offer["PROPERTIES"]["COLOR_REF"]["VALUE"];

    }

//    var_dump($activeOfferID);
//    var_dump($offersData);
//    echo '<br>';
//    var_dump($availableColors);
//    echo '<br>';
//    var_dump($availableSizes);
//    echo '<br>';
}
else
{
    $canBuy = $arResult['CAN_BUY'];
}
?>
<div>
    <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"catalog-chain", 
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
                if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && $offerPhotoMark)
                {
                    foreach($offersData as $offerID=>$offerData)
                    {
                        if(is_array($offerData['photo']) && count($offerData['photo'])>0)
                        {
                            $carouselID = 'preview-photo-carousel_'.$offerID;
                            ?>
                            <div class="general-container photo-container <?=$offerID?>" style="display:<?=($offerID==$activeOfferID)?'block':'none'?>">
                                <div class="photo-big-container">
                                    <img src="<?=$offerData['photo'][0];?>" class="photo-big">
                                </div>
                                <div class="photo-carousel-container">
                                    <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                        <ul>
                                            <?
                                            foreach($offerData['photo'] as $singleOfferPhoto)
                                            {
                                                ?>
                                                <li>
                                                    <div class="photo-container">
                                                        <img src="<?=$singleOfferPhoto;?>">
                                                    </div>
                                                </li>
                                            <?
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="nav-container">
                                        <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                        <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                    });
                                </script>
                            </div>
                            <?
                        }
                    }
                }else{

                        $carouselID = 'preview-photo-carousel';
                        reset($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"]);
                        $arFirstPhoto = current($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"]);
                        ?>
                        <div class="general-container photo-container">
                            <div class="photo-big-container">
                                <img src="<?=CFile::GetFileArray($arFirstPhoto)["SRC"];?>" class="photo-big">
                            </div>
                            <div class="photo-carousel-container">
                                <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                    <ul>
                                        <?
                                        if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
                                        {
                                            foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
                                            {?>
                                                <li>
                                                    <div class="photo-container">
                                                        <img src="<?=CFile::GetFileArray($photoID)["SRC"];?>">
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
                            </div>
                            <script>
                                $(document).ready(function () {
                                    inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                });
                            </script>
                        </div>
                        <?
                    unset($arOnePhoto);
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

                            <?if($canBuy){?>
                            <span class="avalable">В наличие</span>
                            <?}else{?>
                            <span class="notavalable">Нет в наличии</span>
                            <?}?>

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
                            if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                            {
                                foreach($offersData as $offerID=>$offerData)
                                {
                                    if(is_array($offerData['photo']) && count($offerData['photo'])>0)
                                    {
                                        $carouselID = 'preview-photo-carousel_'.$offerID.'_xs';
                                        ?>
                                        <div class="general-container photo-container <?=$offerID?>" style="display:<?=($offerID==$activeOfferID)?'block':'none'?>">
                                            <div class="photo-big-container">
                                                <img src="<?=$offerData['photo'][0];?>" class="photo-big">
                                            </div>
                                            <div class="photo-carousel-container">
                                                <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                                    <ul>
                                                        <?
                                                        foreach($offerData['photo'] as $singleOfferPhoto)
                                                        {
                                                            ?>
                                                            <li>
                                                                <div class="photo-container">
                                                                    <img src="<?=$singleOfferPhoto['SRC'];?>">
                                                                </div>
                                                            </li>
                                                        <?
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="nav-container">
                                                    <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                                    <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                                </div>
                                            </div>
                                            <script>
                                                $(document).ready(function () {
                                                    inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                                });
                                            </script>
                                        </div>
                                    <?
                                    }
                                }
                            }else{

                                $carouselID = 'preview-photo-carousel_xs';
                                reset($arResult['MORE_PHOTO']);
                                $arFirstPhoto = current($arResult['MORE_PHOTO']);
                                ?>
                                <div class="general-container photo-container">
                                    <div class="photo-big-container">
                                        <img src="<?=$arFirstPhoto?>" class="photo-big">
                                    </div>
                                    <div class="photo-carousel-container">
                                        <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                            <ul>
                                                <?
                                                if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
                                                {
                                                    foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
                                                    {?>
                                                        <li>
                                                            <div class="photo-container">
                                                                <img src="<?=CFile::GetFileArray($photoID)["SRC"];?>">
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
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 4);
                                        });
                                    </script>
                                </div>
                                <?
                                unset($arOnePhoto);
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

                            <div class="size-container radio-container">
                                <input type="hidden" name="size-radio" class="ia-radio-value" />
                                <?
                                $sizesData = array();
                                $JSColorData = array();
                                foreach ($availableSizes as $sizeName=>$sizeColors)
                                {?>
                                    <?
                                    foreach($offersData as $offerID=>$offerData )
                                    {
                                        if($offerData['size']!=$sizeName) continue;


                                        $currOfferPrices = implode('-',$offerData['price']);
                                        $currOfferColor = $offerData['color'];

                                        $JSColorData[$currOfferColor] = array('price'=>$offerData['price'], 'id'=>$offerID);
                                        /*
                                        ?>
                                        <span class="offer-data hidden"><?=$currOfferColor?>;<?=$currOfferPrices?>;<?=$offerID?></span>
                                        <?*/
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
                                <?if($canBuy){?>
                                    <button type="button" class="btn btn-default ia-btn yellow-btn splitted-btn in-cart" onclick="inanime_new.addToCart(parseInt($(this).find('.hidden.value').text())
                                        ,parseInt($('.ia-counter-container input.counter-value').val())
                                        ,'<?=$arResult["NAME"]?>'
                                        ,<?=$minPrice['DISCOUNT_VALUE'];?>
                                        ,false)">
                                        <span class="hidden value"><?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?></span>
                                        <span class="icon-btn"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" width="17"/></span>
                                        <span class="text-btn">В корзину</span>
                                    </button>
                                <?
                                }else{
                                    ?>
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:catalog.product.subscribe",
                                        "inanime-subscribe",
                                        Array(
                                            "BUTTON_CLASS" => "btn btn-default ia-btn yellow-btn splitted-btn in-cart",
                                            "BUTTON_ID" => $arResult['ID']."-in-cart-btn",
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                            "PRODUCT_ID" => $arResult['ID']
                                        )
                                    );?>
                                <?
                                }
                                ?>

                                <button type="button" class="btn btn-default ia-btn blue-btn image-btn in-favorite hidden-sm hidden-xs">
                                    <img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>"/>
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
    </div>
</div>
<script>
    var InAnimeCatalogElement<?=$strMainID;?> = new window.InAnimeCatalogElement(<? echo CUtil::PhpToJSObject($sizesData, false, true); ?>,<? echo CUtil::PhpToJSObject($JSColorData, false, true); ?>);
    $(document).ready(function(){
        $('.general-container.photo-container .photo-container img').click(function(){
            var newSRC = $(this).attr('src');
            $(this).closest('.general-container.photo-container').find('.photo-big-container img').attr('src',newSRC);
        });
    });
</script>