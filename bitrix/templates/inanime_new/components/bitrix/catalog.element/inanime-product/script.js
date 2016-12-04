(function(window){
    if (!!window.InAnimeCatalogElement)
    {
        return;
    }

    window.InAnimeCatalogElement = function(sizeDataArray, startColorData)
    {
        this.sizesData = sizeDataArray;
        this.currentColorConfig = startColorData;
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
                 id:offerID
             }
         }
        }

        var that = this;

        $('.properties-container .color-container .ia-radio-button').each(
            function()
            {
                var radioButton = $(this);
                var colorValue = radioButton.find('.value.hidden').text();
                if(that.currentColorConfig[colorValue]!=undefined) radioButton.css('display','inline-block');
                else radioButton.css('display','none');
            }
        );
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
//        $('.general-container.photo-container. '+colorData.id).css('display','block');
        $('.general-container.photo-container#photo_gallery_'+colorData.id+',' +
            '.general-container.photo-container#photo_gallery_xs_'+colorData.id).css('display','block');

    }

})(window);