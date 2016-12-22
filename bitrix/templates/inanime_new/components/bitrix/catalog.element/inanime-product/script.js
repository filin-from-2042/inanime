(function(window){
    if (!!window.InAnimeCatalogElement)
    {
        return;
    }

    window.InAnimeCatalogElement = function(params)
    {
        this.productID = params.productID;
        this.sizesData = params.sizesData;
        this.currentColorConfig = params.startColorData;
        this.ajaxURL = params.ajaxURL;
        this.popupGalleryID=null;
    };

    window.InAnimeCatalogElement.prototype.sizeClick = function(event)
    {
        var sizeButton = event.delegateTarget;
        //console.log(this.sizesData);
        //this.currentSizeConfig
        // установка параметров для выбора цвета
        this.currentColorConfig = {};
        var size = $(sizeButton).find('.value.hidden').text();
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
        $('.properties-container .color-container .ia-radio-button').each(
            function()
            {
                var radioButton = $(this);
                var colorValue = radioButton.find('.value.hidden').text();
                if(that.currentColorConfig[colorValue]!=undefined)
                {
                    if(!firstActiveButton) firstActiveButton = radioButton;
                    radioButton.css('display','inline-block');
                }
                else radioButton.css('display','none');
            }
        );
        firstActiveButton.trigger('click');
    };

    window.InAnimeCatalogElement.prototype.colorClick = function(event)
    {
        var colorButton = event.delegateTarget;
        var color = $(colorButton).find('.value.hidden').text();
        var colorData = this.currentColorConfig[color];
        var priceContainer = $('.price-container');
        var priceOld = priceContainer.find('.price.old');
        var priceNew = priceContainer.find('.price.yellow-text');
        var discountContainer = priceContainer.find('.discount');

        if(colorData.price.length>1){
            priceOld.text(colorData.price[0]+' ₽').show();
            priceNew.text(colorData.price[1]+' ₽');
            discountContainer.find('.discount-amount').text('Экономия '+colorData.price[2]+'% -'+colorData.price[3]+' ₽');
            discountContainer.show();
        }else{
            priceOld.hide();
            priceNew.text(colorData.price[0]+' ₽');
            discountContainer.hide();
        }

        $('.general-container.photo-container').css('display','none');
        $('.general-container.photo-container#photo_gallery_'+colorData.id+',' +
            '.general-container.photo-container#photo_gallery_xs_'+colorData.id).css('display','block');
        $('button.in-cart span.value, button.in-favorite span.value').each(function()
        {
            $(this).text(colorData.id);
        });
        $('.product-info .buttons-container .button-wrap.subscribe button').attr('data-item',colorData.id);
        if(colorData.can_buy)
        {
            $('.product-info .avalable').removeClass('hidden');
            $('.product-info .notavalable').addClass('hidden');
            $('.product-info .buttons-container .button-wrap.in-cart').show();
            $('.product-info .buttons-container .button-wrap.subscribe').hide();
        }
        else
        {
            $('.product-info .avalable').addClass('hidden');
            $('.product-info .notavalable').removeClass('hidden');
            $('.product-info .buttons-container .button-wrap.in-cart').hide();
            $('.product-info .buttons-container .button-wrap.subscribe').show();
        }

    };

    window.InAnimeCatalogElement.prototype.addQuestion = function(event)
    {
        var formContainer = $(event.delegateTarget).closest('.modal-body');
        $.ajax({
            type: "POST",
            url: this.ajaxURL,
            data: {
                action:'addQuestion',
                productID: this.productID,
                question : formContainer.find('textarea[name="mail-text"]').val(),
                username : formContainer.find('input[name="username"]').val(),
                email: formContainer.find('input[name="email"]').val(),
                sessid:BX.bitrix_sessid()
            },
            //dataType: 'json',
            success: function(data){
                formContainer.find('.status-container').show();
            },
            error: function( xhr, textStatus ) {
                alert( [ xhr.status, textStatus ] );
            }
        });
    };

    window.InAnimeCatalogElement.prototype.sendCheaper = function(event)
    {
        var formContainer = $(event.delegateTarget).closest('.modal-body');
        $.ajax({
            type: "POST",
            url: this.ajaxURL,
            data: {
                action:'sendCheaper',
                username : formContainer.find('input[name="username"]').val(),
                phone : formContainer.find('input[name="phone"]').val(),
                email: formContainer.find('input[name="email"]').val(),
                productLink: formContainer.find('input[name="product-link"]').val(),
                sessid: BX.bitrix_sessid()
            },
//            dataType: 'json',
            success: function(data){
                formContainer.find('.status-container').show();
            },
            error: function( xhr, textStatus ) {
                alert( [ xhr.status, textStatus ] );
            }
        });

    }

})(window);