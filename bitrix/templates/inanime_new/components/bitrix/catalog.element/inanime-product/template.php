<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//var_dump($arResult);
$strMainID = $this->GetEditAreaId($arResult['ID']);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);

$photoGalleryData = array();
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
            'photo'=>$offerPhotos
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
                $photoGalleryData[$strMainID][] = CFile::GetFileArray($photoID)['SRC'];
            }
        }

        if(empty($photoGalleryData))
        {
            foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
            {
                $photoGalleryData[$strMainID][] = $arOnePhoto['SRC'];
            }
        }
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
    if($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"])
    {
        foreach($arResult["PROPERTIES"]["MORE_PHOTO2"]["VALUE"] as $photoID)
        {
            $photoGalleryData[$strMainID][] = CFile::GetFileArray($photoID)['SRC'];
        }
    }

    if(empty($photoGalleryData))
    {
        foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
        {
            $photoGalleryData[$strMainID][] = $arOnePhoto['SRC'];
        }
    }
}
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
                                if($strMainID==$galleryID) $showGallery = true;
                            }
                            ?>
                            <div class="general-container photo-container" id="photo_gallery_<?=$galleryID?>" <?=($showGallery)?'':'style="display:none;"'?>>
                                <div class="photo-big-container">
                                    <img src="<?=$galleryPhoto[0];?>" class="photo-big">
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
                                        if($strMainID==$galleryID) $showGallery = true;
                                    }
                                    ?>
                                    <div class="general-container photo-container" id="photo_gallery_xs_<?=$galleryID?>"  <?=($showGallery)?'':'style="display:none;"'?>>
                                        <div class="photo-big-container">
                                            <img src="<?=$galleryPhoto[0];?>" class="photo-big">
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
                                                $JSStartColorData[$currOfferColor] = array('price'=>$offerData['price'], 'id'=>$offerID);
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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#characteristics" aria-controls="characteristics" role="tab" data-toggle="tab">Характеристики</a></li>
                <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Отзывы</a></li>
                <li role="presentation"><a href="#shipping" aria-controls="shipping" role="tab" data-toggle="tab">Доставка и гарантии</a></li>
                <li role="presentation"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Вопрос - ответ</a></li>
            </ul>

            <!-- Tab panes -->
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
                    <div class="comment-container">
                        <div class="comment-main grey-container ">
                            <div class="nickname-rate-container">
                                <div class="nicname">Иван Иванов</div>
                                <div class="comment-rate"></div>
                            </div>
                            <div class="part-title">Плюсы:</div>
                            <div class="part-text gray-text">Большой ассортимент, качественная продукция</div>
                            <div class="part-title">Минусы:</div>
                            <div class="part-text gray-text">Достаточно высокие цены, из-за того, что большинство продукции - импортная<BR>
                                Мне давно знаком этот небольшой уютный магазинчик, располагающий в пяти минутах ходьбы от метро Технологический институт.
                                Я с удовольствием наблюдал как расширяется его ассортимент - от обычной продажи дисков с аниме (японской анимацией).
                            </div>
                            <div class="part-title">Комментарий:</div>
                            <div class="part-text gray-text">
                                Достаточно высокие цены, из-за того, что большинство продукции - импортная<BR>
                                Мне давно знаком этот небольшой уютный магазинчик, располагающий в пяти минутах ходьбы от метро Технологический институт.
                                Я с удовольствием наблюдал как расширяется его ассортимент - от обычной продажи дисков с аниме (японской анимацией).
                            </div>
                            <div class="date-answer-container">
                                <div class="text gray-text">29 февраля 2016</div>
                                <span class="divider">|</span>
                                <div class="answer-button">Ответить</div>
                            </div>
                        </div>
                        <div class="comment-container sub-comment">
                            <div class="comment-main grey-container ">
                                <div class="nickname-rate-container">
                                    <div class="answer-icon"></div>
                                    <div class="nicname">Алексей Разин</div>
                                    <div class="comment-parent-nicname">Ивану Иванову</div>
                                </div>
                                <div class="part-text gray-text">
                                    Достаточно высокие цены, из-за того, что большинство продукции - импортная<br>
                                    Мне давно знаком этот небольшой уютный магазинчик, располагающий в пяти минутах ходьбы от метро Технологический институт.
                                    Я с удовольствием наблюдал как расширяется его ассортимент - от обычной продажи дисков с аниме (японской анимацией).
                                </div>
                                <div class="date-answer-container">
                                    <div class="text gray-text">29 февраля 2016</div>
                                    <span class="divider">|</span>
                                    <div class="answer-button">Ответить</div>
                                </div>
                            </div>
                        </div>
                        <div class="button-container">
                            <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="OK" value="Оставить отзыв">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                <span>Оставить отзыв</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="shipping">
                    <div class="text-container">
                        <div class="text-title">Доставка товаров по Москве</div>
                        <ul>
                            <li>1. Доставка заказов осуществляется в течении 1-3 рабочих дня (если товар нужно изготавливать, то срок сборки может быть дольше). </li>
                            <li>2. Доставка работает с 11 до 19 часов. </li>
                            <li>3. Дату и время доставки сотрудник службы доставки согласует с Вами по телефону. </li>
                            <li>4. Доставка курьером по Москве стоит 350 рублей. </li>
                            <li>5. Вы можете забрать заказ самостоятельно в нашем магазине около метро Менделеевская. В этом случае за доставку платить не нужно. </li>
                        </ul>
                        <div class="text-title">Доставка товаров по России</div>
                        <ul>
                            <li>1. Доставка товаров за пределы Москвы осуществляется транспортными компаниями или почтой. </li>
                            <li>2. Стоимость доставки любого заказа в любую точку России - 350 рублей (смотрите ниже некоторые исключения).</li>
                            <li>3. Срок доставки зависит от типа доставки и города. </li>
                            <li>4. Стоимость доставки первым классом - 600 рублей. Небольшие посылки весом до 2 кг. Требуется полная предоплата заказа.</li>
                            <li>5. Стоимость доставки EMS - 1200 рублей. Требуется полная предоплата заказа. Доставка на дом в течении нескольких дней.</li>
                            <li>6. Международная пересылка простой почтой без страховки - 900 рублей. Требуется полная предоплата заказа. </li>
                            <li>7. Международная пересылка EMS почтой  - 1900 рублей. Требуется полная предоплата заказа. </li>
                        </ul>


                        <div class="text-title">Расчет времени доставки в ваш город:  Волгоград</div>

                        <p>
                            Задача организации, в особенности же сложившаяся структура организации способствует подготовки и реализации системы обучения кадров,
                            соответствует насущным потребностям. Повседневная практика показывает, что начало повседневной работы по формированию позиции способствует
                            подготовки и реализации соответствующий условий активизации. Значимость этих проблем настолько очевидна, что реализация намеченных плановых
                            заданий требуют определения и уточнения дальнейших направлений развития.
                        </p>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="questions">
                    <div class="question-answer-section-container">
                        <div class="question-container">
                            <div class="question-title">
                                <span class="question-text">Что будет если не весь товар из заказанного окажется в наличии?</span>
                            </div>
                            <div class="question-answer gray-text">
                                Выберите понравившиеся продукты в нужном количестве и добавьте их в «корзину».
                                Перейдите в «корзину» и проверьте её содержимое. На этом этапе Вы можете изменить количество товара, выбрать подарки,
                                пробники и ввести код бонуса, если он у Вас есть. Нажмите кнопку «Оформить заказ». Авторизуйтесь, если у Вас уже есть Личный Кабинет,
                                или пройдите регистрацию. Выберете желаемый способ доставки и оплаты.  Сверьте еще раз содержимое Вашей «корзины». Нажмите «Завершить заказ».
                            </div>
                        </div>
                    </div>
                    <div class="question-answer-section-container">
                        <div class="question-container">
                            <div class="question-title">
                                <span class="question-text">Можете ли вы изготовить товар с моей картинкой?</span>
                            </div>
                            <div class="question-answer gray-text">
                                Выберите понравившиеся продукты в нужном количестве и добавьте их в «корзину».
                                Перейдите в «корзину» и проверьте её содержимое. На этом этапе Вы можете изменить количество товара, выбрать подарки,
                                пробники и ввести код бонуса, если он у Вас есть. Нажмите кнопку «Оформить заказ». Авторизуйтесь, если у Вас уже есть Личный Кабинет,
                                или пройдите регистрацию. Выберете желаемый способ доставки и оплаты.  Сверьте еще раз содержимое Вашей «корзины». Нажмите «Завершить заказ».
                            </div>
                        </div>
                    </div>
                    <div class="question-answer-section-container">
                        <div class="question-container">
                            <div class="question-title">
                                <span class="question-text">Как подтвердить заказ? </span>
                            </div>
                            <div class="question-answer gray-text">
                                Выберите понравившиеся продукты в нужном количестве и добавьте их в «корзину».
                                Перейдите в «корзину» и проверьте её содержимое. На этом этапе Вы можете изменить количество товара, выбрать подарки,
                                пробники и ввести код бонуса, если он у Вас есть. Нажмите кнопку «Оформить заказ». Авторизуйтесь, если у Вас уже есть Личный Кабинет,
                                или пройдите регистрацию. Выберете желаемый способ доставки и оплаты.  Сверьте еще раз содержимое Вашей «корзины». Нажмите «Завершить заказ».
                            </div>
                        </div>
                    </div>
                    <div class="question-answer-section-container">
                        <div class="question-container">
                            <div class="question-title">
                                <span class="question-text">Заказ пришел, но я не хочу его выкупать. Как вернуть предоплату и сколько времени вы можете хранить мой заказ?</span>
                            </div>
                            <div class="question-answer gray-text">
                                Выберите понравившиеся продукты в нужном количестве и добавьте их в «корзину».
                                Перейдите в «корзину» и проверьте её содержимое. На этом этапе Вы можете изменить количество товара, выбрать подарки,
                                пробники и ввести код бонуса, если он у Вас есть. Нажмите кнопку «Оформить заказ». Авторизуйтесь, если у Вас уже есть Личный Кабинет,
                                или пройдите регистрацию. Выберете желаемый способ доставки и оплаты.  Сверьте еще раз содержимое Вашей «корзины». Нажмите «Завершить заказ».
                            </div>
                        </div>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-default ia-btn text-btn blue-btn question-btn" type="submit" name="OK" value="Задать вопрос">
                            <span class="icon-question"></span>
                            <span>Задать вопрос</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal PHOTO LIGHTBOX -->
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

    </div>
</div>
<script>
    var InAnimeCatalogElement<?=$strMainID;?> = new window.InAnimeCatalogElement(<? echo CUtil::PhpToJSObject($sizesData, false, true); ?>,<? echo CUtil::PhpToJSObject($JSStartColorData, false, true); ?>);
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
    });
</script>