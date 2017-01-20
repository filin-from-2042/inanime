(function(window){
    if (!!window.InAnimePreviewCatalogElement)
    {
        return;
    }

    window.InAnimePreviewCatalogElement = function(params)
    {
        this.productID = params.productID;
        this.sizesData = params.sizesData;
        this.currentColorConfig = params.startColorData;
        this.ajaxURL = params.ajaxURL;
        this.popupGalleryID=null;
        this.params = params;

        if(this.params.hasOffers){
            $('#hide-overlay'+this.productID).click(function(event){
                event.stopPropagation();
                $(this).closest('.product-item-preview').find('.quick-order-overlay').hide();
                $(this).hide();
            });
        }
    };

    window.InAnimePreviewCatalogElement.prototype.inCartClick = function(element)
    {
        var $button = $(element);
        var price = parseFloat($button.closest('.product-item-preview').find('.price.current').text().replace(' ',''));
        var $productContainer = $button.closest('.product-item-preview');
        if(this.params.hasOffers)
        {
            $button.css('z-index','112');
            var charactOverlay = $productContainer.find('.quick-order-overlay');
            if(charactOverlay.is(":visible"))
            {
                inanime_new.addToCart(parseInt($button.find('.hidden.value').text()), 1,price,false);
                $productContainer.find('.hide-char-overlay').hide();
                $productContainer.find('.quick-order-overlay').hide();
            }
            else {
                $button.closest('.product-item-preview').find('.quick-order-overlay').show();
                $button.closest('.product-item-preview').find('.hide-char-overlay').show();
            }
        }
        else
        {
            inanime_new.addToCart(parseInt($button.find('.hidden.value').text()), 1,price,false);
        }
    };



    window.InAnimePreviewCatalogElement.prototype.sizeClick = function(event)
    {
        var sizeButton = event.delegateTarget;
        //console.log(this.sizesData);
        //this.currentSizeConfig
        // установка параметров для выбора цвета
        this.currentColorConfig = {};
        var $sizeButton = $(sizeButton);
        var size = $sizeButton.find('.value.hidden').text();
        if(Object.keys(this.sizesData[size]).length > 0)
        {
            for(var offerID in this.sizesData[size])
            {
                this.currentColorConfig[this.sizesData[size][offerID].color] = {
                    price:this.sizesData[size][offerID].price,
                    id:offerID,
                    can_buy:this.sizesData[size][offerID].can_buy
                }
            }
        }

        var that = this;
        var firstActiveButton = null;
        $sizeButton.closest('.properties-container').find('.color-container .ia-radio-button').each(
            function()
            {
                var radioButton = $(this);
                var colorValue = radioButton.find('.value.hidden').text();
                if(that.currentColorConfig[colorValue]!=undefined )
                {
                    if(!firstActiveButton) firstActiveButton = radioButton;
                    if(colorValue!='not-set') radioButton.css('display','inline-block');
                    else radioButton.css('display','none');
                }
                else radioButton.css('display','none');
            }
        );
        if(firstActiveButton) firstActiveButton.trigger('click');
    };

    window.InAnimePreviewCatalogElement.prototype.colorClick = function(event)
    {
        var colorButton = event.delegateTarget;
        var $colorButton = $(colorButton);
        var color = $colorButton.find('.value.hidden').text();
        var $previewProductContainer =  $colorButton.closest('.product-item-preview');
        var colorData = this.currentColorConfig[color];
        var priceContainer = $previewProductContainer.find('.price-container');
        var priceOld = priceContainer.find('.price.old');
        var priceNew = priceContainer.find('.price.current');

        if(colorData.price.length>1){
            priceOld.text(colorData.price[0]+' ₽').show();
            priceNew.text(colorData.price[1]+' ₽');
        }else{
            priceOld.hide();
            priceNew.text(colorData.price[0]+' ₽');
        }

        $previewProductContainer.find('button.in-cart span.value, button.in-favorite span.value').each(function()
        {
            $(this).text(colorData.id);
        });
        $previewProductContainer.find('.buttons-container .button-wrap.subscribe button').attr('data-item',colorData.id);
        if(colorData.can_buy)
        {
            $previewProductContainer.find('.buttons-container .button-wrap.in-cart').show();
            $previewProductContainer.find('.buttons-container .button-wrap.subscribe').hide();
        }
        else
        {
            $previewProductContainer.find('.buttons-container .button-wrap.in-cart').hide();
            $previewProductContainer.find('.buttons-container .button-wrap.subscribe').show();
        }

    };


})(window);