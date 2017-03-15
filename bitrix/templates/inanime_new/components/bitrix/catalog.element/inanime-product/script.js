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
        $(sizeButton).closest('.properties-container').find('.color-container .ia-radio-button').each(
            function()
            {
                var radioButton = $(this);
                var colorValue = radioButton.find('.value.hidden').text();
                if(that.currentColorConfig[colorValue]!=undefined)
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
            priceOld.text(colorData.price[0]).append('<span class="rub"></span>').show();
            priceNew.text(colorData.price[1]).append('<span class="rub"></span>');
            discountContainer.find('.discount-amount').text('Экономия '+colorData.price[2]+'% -'+colorData.price[3]).append('<span class="rub"></span>');
            discountContainer.show();
        }else{
            priceOld.hide();
            priceNew.text(colorData.price[0]).append('<span class="rub"></span>');
            discountContainer.hide();
        }
        
        // показываем галерею предложения, если отсутсвует то товара
        if($('.product-card .general-container.photo-container#photo_gallery_'+colorData.id).length > 0)
        {
            $('.product-card .general-container.photo-container').css('display','none');
            $('.product-card .general-container.photo-container#photo_gallery_'+colorData.id+',' +
                '.product-card .general-container.photo-container#photo_gallery_xs_'+colorData.id).css('display','block');
        }
        else
        {
            $('.product-card .general-container.photo-container#photo_gallery_'+this.productID).css('display','block');
        }

        $('.product-card .title-container').css('display','none');
        $('.product-card .title-container#title-container-'+colorData.id).css('display','block');

        $('.product-card button.in-cart span.value, .product-card button.in-favorite span.value').each(function()
        {
            $(this).text(colorData.id);
        });
        $('.product-card .buttons-container .button-wrap.subscribe button').attr('data-item',colorData.id);
        if(colorData.can_buy)
        {
            $('.product-card .avalable').removeClass('hidden');
            $('.product-card .notavalable').addClass('hidden');
            $('.product-card .buttons-container .button-wrap.in-cart').show();
            $('.product-card .buttons-container .button-wrap.subscribe').hide();
        }
        else
        {
            $('.product-card .avalable').addClass('hidden');
            $('.product-card .notavalable').removeClass('hidden');
            $('.product-card .buttons-container .button-wrap.in-cart').hide();
            $('.product-card .buttons-container .button-wrap.subscribe').show();
        }
    };

    window.InAnimeCatalogElement.prototype.QOcolorClick = function(event)
    {
        var colorButton = event.delegateTarget;
        var $colorButton = $(colorButton);
        var color = $colorButton.find('.value.hidden').text();
        var $QOModalContainer =  $colorButton.closest('.quick-order-modal');
        var colorData = this.currentColorConfig[color];
        var priceContainer = $QOModalContainer.find('.price-container');
        var priceOld = priceContainer.find('.price.old');
        var priceNew = priceContainer.find('.price.current');
        var discountContainer = priceContainer.find('.discount');

        if(colorData.price.length>1){
            priceOld.text(colorData.price[0]).append('<span class="rub"></span>').show();
            priceNew.text(colorData.price[1]).append('<span class="rub"></span>');
            discountContainer.find('.discount-amount').text('Экономия '+colorData.price[2]+'% -'+colorData.price[3]).append('<span class="rub"></span>');
            discountContainer.show();
        }else{
            priceOld.hide();
            priceNew.text(colorData.price[0]).append('<span class="rub"></span>');
            discountContainer.hide();
        }

        $QOModalContainer.find('.title-container').css('display','none');
        $QOModalContainer.find('.title-container#title-container-'+colorData.id).css('display','block');
        // обновляем цену в итоге
        var itemPrice = parseInt($QOModalContainer.find('.price.yellow-text').text().replace(' ',''));
        var counterValue = parseInt($QOModalContainer.find('.ia-counter-container .counter-value').val());
        $QOModalContainer.find('.total-value').empty().append((itemPrice*counterValue).toString()+'<span class="rub"></span>');

        $QOModalContainer.find('.product_id_value').val(colorData.id);
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


    window.InAnimeCatalogElement.prototype.QOSubmit = function(event)
    {
        event.preventDefault();
        var $form = $(event.delegateTarget);
        var statusContainer = $form.find('.status-container');
        var productID = parseInt($form.find('.product_id_value').val());
        var quantity = parseInt($form.find('.counter-value').val());
        var itemsCount = parseInt($form.find('.ia-counter-container .counter-value').val());
        var currPrice = parseInt($form.find('.price-container .price.yellow-text').text().replace(' ',''));
        var nameField = $form.find('.username-input');
        var phoneField = $form.find('.phone-input');
        var emailField = $form.find('.email-input');
        var nameFieldVal = $form.find('.username-input').val();
        var phoneFieldVal = $form.find('.phone-input').val();
        var emailFieldVal = $form.find('.email-input').val();

        if(!nameFieldVal || !phoneFieldVal || !emailFieldVal){
            var errorText = '';
            if(!nameFieldVal) errorText += 'Не заполнено поле '+nameField.attr('placeholder')+'!<br>';
            if(!phoneFieldVal) errorText += 'Не заполнено поле '+phoneField.attr('placeholder')+'!<br>';
            if(!emailFieldVal) errorText += 'Не заполнено поле '+emailField.attr('placeholder')+'!<br>';
            statusContainer.find('.success').hide();
            statusContainer.find('.error').empty().append(errorText).show();
            statusContainer.show();
            return;
        }

        $.ajax({
            type: "POST",
            url: this.ajaxURL,
            data: {
                action:'quickOrder',
                productID: productID,
                quantity: quantity,
                nameField : nameFieldVal,
                phoneField: phoneFieldVal,
                emailFiled: emailFieldVal,
                sessid:BX.bitrix_sessid()
            },
            success: function(data){
                statusContainer.find('.error').hide();
                statusContainer.find('.success').empty().append('Заказ успешно оформлен!').show();
                statusContainer.show();
            },
            error: function( xhr, textStatus ) {
                statusContainer.find('.success').hide();
                statusContainer.find('.error').empty().append(textStatus).show();
                statusContainer.show();
            }
        });
    }

})(window);