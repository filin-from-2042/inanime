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
    // удаление подписки на товар для страницы подписки
    window.InAnimePreviewCatalogElement.prototype.deleteSubscribe = function()
    {

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
                    this.showWindowWithAnswer({status: 'success'});
                    location.reload();
                }
                else
                {
                    this.showWindowWithAnswer({status: 'error', message: result.message});
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



})(window);