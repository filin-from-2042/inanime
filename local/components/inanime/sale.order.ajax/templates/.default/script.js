/*BX.addCustomEvent('onAjaxSuccess', function() {
	sp.init_form();
});
*/
(function(window)
{
   window.InAnimeOrderAjax = function(params)
   {
        this.saleProfiles = params.saleProfiles;
       this.locationPropID = params.locationPropID;

   };

    window.InAnimeOrderAjax.prototype.changeSaleProfile = function(event)
    {
        event.stopPropagation();
        // установка радиокнопок
        if ($(event.currentTarget).hasClass('ia-radio-button')) var radioButton = $(event.currentTarget);
        else var radioButton = $(event.currentTarget).closest('.radio-button-container').find('.ia-radio-button');
        radioButton.closest('.radio-container').find('input.ia-radio-value').val(radioButton.find('span.value.hidden').text());
        radioButton.closest('.radio-container').find('.ia-radio-button').removeClass('active');
        radioButton.addClass('active');

        var $container = $(event.currentTarget);
        var profileID = $container.closest('.radio-button-container').attr('id');
        $container.closest('.radio-container').find('#addreesProfile').val(profileID);
        var currProfileData = this.saleProfiles[profileID];
        console.log(profileID);
        $('.address-container #'+this.locationPropID).val(currProfileData.locationID);
        $('.address-container #'+this.locationPropID).attr('value',currProfileData.locationID);
        $('.address-container #'+this.locationPropID+'_val').val(currProfileData.locationFullName);

        $('.address-container .form-control.zip-input').val(currProfileData.zipCode);

        $('.address-container .form-control.street-input').val(currProfileData.street.replace('ул.',''));
        $('.address-container .form-control.house-number-input').val(currProfileData.house.replace('д.',''));
        $('.address-container .form-control.apartment-input').val(currProfileData.apartment.replace('кв.',''));
        $('.address-container .form-control.phone-input').val(currProfileData.phone);

        submitForm();
    };

    window.InAnimeOrderAjax.prototype.changeAddressData = function()
    {
        //var $cityInputVal = $('.order-drawing-up .address-container .form-control.city-input').val();
        var $streetInputVal = $('.order-drawing-up .address-container .form-control.street-input').val();
        var $houseNumberInputVal = $('.order-drawing-up .address-container .form-control.house-number-input').val();
        var $apartmentInputVal = $('.order-drawing-up .address-container .form-control.apartment-input').val();

        //if(!$cityInputVal) return;
        if(!$streetInputVal) return;
        if(!$houseNumberInputVal) return;
        if(!$apartmentInputVal) return;

        var fullAddr = 'ул. '+$streetInputVal.replace(',',' ').trim()+', д. '+$houseNumberInputVal.replace(',',' ').trim()+', кв. '+$apartmentInputVal.replace(',',' ').trim();
        $('.order-drawing-up .full-address').val(fullAddr);
    };

    window.InAnimeOrderAjax.prototype.changeUserType = function(event)
    {
        event.stopPropagation();
        if ($(event.currentTarget).hasClass('ia-radio-button')) var radioButton = $(event.currentTarget);
        else var radioButton = $(event.currentTarget).closest('.radio-button-container').find('.ia-radio-button');
        radioButton.closest('.radio-container').find('input.ia-radio-value').val(radioButton.find('span.value.hidden').text());
        radioButton.closest('.radio-container').find('.ia-radio-button').removeClass('active');
        radioButton.addClass('active');

        var contentID = radioButton.attr('data-contentid');
        radioButton.closest('.radio-tab-control-wrap').find('.radio-tabs-container .active').removeClass('active');
        radioButton.closest('.radio-tab-control-wrap').find('.radio-tabs-container #'+contentID).addClass('active');
    }
})(window);