(function(window){
    if(!!window.profilePersonalData)
    {
        return;
    }

    window.profilePersonalData = function()
    {
        $('.section-personal .address-column .add-button.address').click(
            function()
            {
                var $this = $(this);
                $this.hide();
                $this.closest('.address-column').find('.address-container.sample').toggle();
            }
        );
    };
})(window);
