
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
            <form>
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
                                <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="send-button" value="Отменить">
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
                                    if($activeOfferID==$galleryID) $showGallery = true;
                                }
                                else
                                {
                                    if($arResult['ID']==$galleryID) $showGallery = true;
                                }?>
                                <div class="photo-container" id="photo_gallery_<?=$galleryID?>" <?=($showGallery)?'':'style="display:none;"'?>>
                                    <div class="photo-big-container">
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
                                    <div class="photo-carousel-container">
                                        <script>
                                            $(document).ready(function () {
                                                inanime_new.init_product_horizontal_carousel(<?=$carouselID;?>, 3);
                                            });
                                        </script>
                                        <div id="<?=$carouselID;?>" class="preview-photo-carousel">
                                            <ul>
                                                <?if($galleryPhoto)
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
                                                }?>
                                            </ul>
                                        </div>
                                        <div class="nav-container">
                                            <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                            <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </div>
                            <?}
                        }?>
                    </div>
                    <div class="col-sm-12 col-md-8 col-lg-8 product-info">
                        <div class="art-container">
                            <span class="art">арт.23434643</span>
                            <span class="avalable">В наличие</span>
                            <span class="notavalable">Нет в наличии</span>

                        </div>
                        <div class="title-container">
                            Фигурка B-STYLE - Infinite Stratos: Houki Shinono Bunny Ver. 1/4 Complete Figure
                        </div>
                        <div class="rate-container">

                        </div>
                        <div class="description-container gray-text">
                            Таким образом постоянное информационно-пропагандистское обеспечение нашей деятельности способствует подготовки и реализации дальнейших!
                        </div>
                        <div class="price-container">
                            <div class="price">
                                <span class="price old">580 <span class="rub"></span> ₽</span>
                                <span class="price yellow-text">220 <span class="rub"></span> ₽</span>
                            </div>
                            <div class="color-container radio-container">
                                <input type="hidden" name="color-radio" class="ia-radio-value" />
                                <div class="image-radio ia-radio-button">
                                    <span class="value hidden">facebook</span>
                                    <img src="images/facebook-link.png" />
                                </div>
                                <div class="image-radio ia-radio-button">
                                    <span class="value hidden">twitter</span>
                                    <img src="images/twitter-link.png" />
                                </div>
                                <div class="image-radio ia-radio-button">
                                    <span class="value hidden">vimeo</span>
                                    <img src="images/vimeo-link.png" />
                                </div>
                            </div>
                            <div class="discount">
                                <span class="discount-amount">Экономия 60% -360р</span>
                            </div>
                        </div>
                        <div class="counter-button-container">
                            <div class="ia-counter-container">
                                <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                <input type="text" class="counter-value" value="1"/>
                            </div>
                            <span class="gray-text count-text">шт.</span>
                            <span class="total-text">Итого:</span>
                            <span class="total-value">660 ₽</span>
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
                                <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="send-button" value="Отменить">
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
            </form>
        </div>
    </div>
</div>
</div>