
<!-- Modal QUESTION UNREGISTERED-->
<div class="modal fade ia-modal quick-order-modal" id="<?=$qoModalID?>" tabindex="-1" role="dialog" aria-labelledby="modalQuickOrder">
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
                <form class="quick-order-modal-form">
                    <div class="row">
                        <div class="hidden-xs hidden-sm col-md-9 col-lg-9 order-input-fields">
                            <h4 class="modal-title">Оформить заказ</h4>
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
                            <div class="row button-container">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 cancel-btn-container">
                                    <button class="btn btn-default ia-btn text-btn blue-btn" type="button" name="cancel-button" value="Отменить">
                                        <span>Отменить</span>
                                    </button>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 execute-btn-container">
                                    <button class="btn btn-default ia-btn text-btn yellow-btn" type="submit" name="send-button" value="Оформить">
                                        <span>Оформить</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="hidden-md hidden-lg">
                            <h4 class="modal-title">Оформить заказ</h4>
                        </div>

                        <input type="hidden" name="product_id" value="<?=((isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))?$activeOfferID:$arResult['ID'])?>" class="product_id_value">

                        <div class="col-sm-12 col-md-7 col-lg-7 photo-column">
                            <?
                            if(!empty($photoGalleryData))
                            {
                                foreach($photoGalleryData as $galleryID=>$galleryPhoto)
                                {
                                    $carouselID = 'preview-photo-carousel_'.$galleryID;
                                    $showGallery = false;
                                    if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                                    {
                                        // либо показывать галерею активного предложения, либо галерею товара
                                        if($activeOfferID==$galleryID || $arResult['ID']==$galleryID) $showGallery = true;
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
                                                    <?if($arResult["DATE_ACTIVE_FROM"]):?>
                                                        <?if(((strtotime("now")-strtotime($arResult["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                                                            <div class="additional-icon new tablet"></div>
                                                        <?endif?>
                                                    <?endif?>
                                                    <?if($arResult["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                                                        <div class="additional-icon bestseller tablet"></div>
                                                    <?endif?>
                                                    <?if($arResult["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                                                        <div class="additional-icon recommended tablet"></div>
                                                    <?endif?>
                                                    <?
                                                    if($arResult["PRICES"]['BASE']['DISCOUNT_DIFF_PERCENT'])
                                                    {?>
                                                        <div class="discount-container">
                                                            <div class="additional-icon  discount tablet"></div>
                                                            <div class="discount-text tablet">Скидка <?=$arResult["PRICES"]['BASE']['DISCOUNT_DIFF_PERCENT'];?>%</div>
                                                        </div>
                                                    <?}
                                                    ?>

                                                </div>
                                                <img src="<?=$galleryPhoto[0];?>" class="photo-big">
                                            </div>
                                        </div>
                                        <div class="photo-carousel-container">
                                            <div id="<?=$carouselID;?>" class="preview-photo-carousel clearfix">
                                                <ul>
                                                    <?
                                                    if($galleryPhoto)
                                                    {
                                                        foreach($galleryPhoto as $key=>$photoSRC)
                                                        {?>
                                                            <li<?=($key==0)?' class="active"':''?>><div class="photo-container">
                                                                    <img src="<?=$photoSRC?>">
                                                                </div></li>
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
                                                    inanime_new.init_product_horizontal_carousel('<?=$carouselID?>', 3);

                                                    $('#<?=$carouselID?> .general-container.photo-container .photo-container img').click(function(){
                                                        var $this = $(this);
                                                        var newSRC = $this.attr('src');
                                                        $this.closest('.general-container.photo-container').find('li.active').removeClass('active');
                                                        $this.closest('li').addClass('active');
                                                        $this.closest('.general-container.photo-container').find('.photo-big-container img').attr('src',newSRC);
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                <?
                                }
                            }
                            ?>
                        </div>

                        <div class="col-sm-12 col-md-8 col-lg-8 product-info">

                            <div class="art-container">
                                <?if($arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]){?>
                                    <span class="art">арт.<?=$arResult["PROPERTIES"]["ARTNUMBER1"]["VALUE"]?></span>
                                <?}?>
                                <?/*?>
                                <span class="avalable <?=(!$canBuy)?'hidden':''?>">В наличие</span>
                                <span class="notavalable <?=($canBuy)?'hidden':''?>">Нет в наличии</span>
                                <?*/?>
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
                            <div class="price-container">

                                <?
                                // цена для подсчета итога
                                $currItemPrice = 0;
                                ?>

                                <?if(isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
                                {?>
                                    <div class="row">
                                        <div class="col-lg-14 price-column">
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
                                                        <span class="price old"><?=$oldPrice?> <span class="rub"></span></span>
                                                    <?}?>
                                                <?}?>
                                                <span class="price yellow-text"><? echo $currentPrice; ?> <span class="rub"></span></span>
                                            </div>
                                            <?if('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){?>
                                                <div class="discount">
                                                    <?
                                                    if (0 < $currOfferPrice[3])
                                                    {
                                                        ?>
                                                        <span class="discount-amount">Экономия <? echo $currOfferPrice[2]; ?>% -<?=$currOfferPrice[3]?> <span class="rub"></span></span>
                                                    <?
                                                    }
                                                    ?>
                                                </div>
                                            <?}?>
                                        </div>
                                        <div class="col-lg-10 properties-column">
                                        <?if(!empty($arResult['OFFERS_PROP'])){?>
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
                                                        //$('.ia-radio-button,.radio-button-container .button-title').click(inanime_new.radioClick);
                                                        /*$('.color-container .ia-radio-button,.color-container.radio-button-container .button-title').click(
                                                         //function(event){InAnimeCatalogElement<?=$strMainID;?>.colorClick(event)}
                                                         );
                                                         $('.size-container .ia-radio-button,.size-container.radio-button-container .button-title').click(
                                                         // function(event){InAnimeCatalogElement<?=$strMainID;?>.sizeClick(event)}
                                                         );*/
                                                    });
                                                </script>
                                            </div>
                                        <?}?>
                                        </div>
                                        <?$currItemPrice = $currentPrice;?>
                                    </div>
                                <?}else{?>
                                    <div class="price">
                                        <?
                                        $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
                                        if ($arParams['SHOW_OLD_PRICE'] == 'Y')
                                        {
                                            if(0 < $minPrice['DISCOUNT_DIFF']){?>
                                                <span class="price old"><?=$minPrice['PRINT_VALUE']?></span>
                                            <?}?>
                                        <?}?>
                                        <span class="price yellow-text"><? echo $minPrice['DISCOUNT_VALUE']; ?></span>
                                    </div>
                                    <div class="discount">
                                        <?
                                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
                                        {
                                            if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'])
                                            {
                                                ?>
                                                <span class="discount-amount">Экономия <? echo $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>% -<?=$minPrice['PRINT_DISCOUNT_DIFF']?></span>
                                            <?
                                            }
                                        }
                                        ?>
                                    </div>
                                <?
                                    $currItemPrice = $minPrice['DISCOUNT_VALUE'];
                                }?>

                            </div>
                            <div class="counter-button-container">
                                <div class="ia-counter-container">
                                    <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                    <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                    <input type="text" class="counter-value" value="1"/>
                                </div>
                                <span class="gray-text count-text">шт.</span>
                                <span class="total-text">Итого:</span>
                                <span class="total-value"><?=$currItemPrice?> <span class="rub"></span></span>
                            </div>
                        </div>
                        <!-- MOBILE ONLY -->
                        <div class="col-sm-24 hidden-md hidden-lg order-input-fields">
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
                            <div class="row button-container">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 cancel-btn-container">
                                    <button class="btn btn-default ia-btn text-btn blue-btn" type="button" name="cancel-button" value="Отменить">
                                        <span>Отменить</span>
                                    </button>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 execute-btn-container">
                                    <button class="btn btn-default ia-btn text-btn yellow-btn" type="submit" name="send-button" value="Оформить">
                                        <span>Оформить</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row status-container">
                        <div class="hidden-xs hidden-sm col-md-9 col-lg-9 order-input-fields">
                            <div class="success "></div>
                            <div class="error"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>