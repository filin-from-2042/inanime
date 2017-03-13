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

    window.InAnimePreviewCatalogElement.prototype.inCartClick = function(element, callbackFunc)
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
            inanime_new.addToCart(parseInt($button.find('.hidden.value').text()), 1,price,false, false, callbackFunc);
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

    window.InAnimePreviewCatalogElement.prototype.QOcolorClick = function(event)
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
            priceOld.text(colorData.price[0]+' ₽').show();
            priceNew.text(colorData.price[1]+' ₽');
            discountContainer.find('.discount-amount').text('Экономия '+colorData.price[2]+'% -'+colorData.price[3]+' ₽');
            discountContainer.show();
        }else{
            priceOld.hide();
            priceNew.text(colorData.price[0]+' ₽');
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

    // удаление подписки на товар для страницы подписки
    window.InAnimePreviewCatalogElement.prototype.deleteSubscribe = function(bShowMessage, nextLocation)
    {
        bShowMessage = typeof bShowMessage !== 'undefined' ? bShowMessage : true;
        nextLocation = typeof nextLocation !== 'undefined' ? nextLocation : '/personal/cart?tab=not-available';
        if(!this.productID || !this.params.LIST_SUBSCRIPTIONS.hasOwnProperty(this.productID))
            return;

        BX.ajax({
            method: 'POST',
            dataType: 'json',
            url: '/bitrix/components/bitrix/catalog.product.subscribe.list/ajax.php',
            data: {
                sessid: BX.bitrix_sessid(),
                deleteSubscribe: 'Y',
                itemId: this.productID,
                listSubscribeId: this.params.LIST_SUBSCRIPTIONS[this.productID]
            },
            onsuccess: BX.delegate(function (result) {
                if(result.success)
                {
                    if(bShowMessage) this.showWindowWithAnswer({status: 'success'});
                    location.href=nextLocation;
                }
                else
                {
                    if(bShowMessage) this.showWindowWithAnswer({status: 'error', message: result.message});
                }
            }, this)
        });
    };


    window.InAnimePreviewCatalogElement.prototype.showWindowWithAnswer = function(answer)
    {
        answer = answer || {};
        if (!answer.message) {
            if (answer.status == 'success') {
                answer.message = BX.message('CPSL_STATUS_SUCCESS');
            } else {
                answer.message = BX.message('CPSL_STATUS_ERROR');
            }
        }
        var messageBox = BX.create('div', {
            props: {
                className: 'bx-catalog-subscribe-alert'
            },
            children: [
                BX.create('span', {
                    props: {
                        className: 'bx-catalog-subscribe-aligner'
                    }
                }),
                BX.create('span', {
                    props: {
                        className: 'bx-catalog-subscribe-alert-text'
                    },
                    text: answer.message
                }),
                BX.create('div', {
                    props: {
                        className: 'bx-catalog-subscribe-alert-footer'
                    }
                })
            ]
        });
        var currentPopup = BX.PopupWindowManager.getCurrentPopup();
        if(currentPopup) {
            currentPopup.destroy();
        }
        var idTimeout = setTimeout(function () {
            var w = BX.PopupWindowManager.getCurrentPopup();
            if (!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
                return;
            }
            w.close();
            w.destroy();
        }, 3500);
        var popupConfirm = BX.PopupWindowManager.create('bx-catalog-subscribe-status-action', null, {
            content: messageBox,
            onPopupClose: function () {
                this.destroy();
                clearTimeout(idTimeout);
            },
            autoHide: true,
            zIndex: 2000,
            className: 'bx-catalog-subscribe-alert-popup'
        });
        popupConfirm.show();
        BX('bx-catalog-subscribe-status-action').onmouseover = function (e) {
            clearTimeout(idTimeout);
        };
        BX('bx-catalog-subscribe-status-action').onmouseout = function (e) {
            idTimeout = setTimeout(function () {
                var w = BX.PopupWindowManager.getCurrentPopup();
                if (!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
                    return;
                }
                w.close();
                w.destroy();
            }, 3500);
        };
    };

    window.InAnimePreviewCatalogElement.prototype.QOSubmit = function(event)
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