BX.namespace('BX.Sale.PersonalOrderComponent');

(function() {
	BX.Sale.PersonalOrderComponent.PersonalOrderList = {
		init : function(params)
		{
			params.url = params.url || "";

            $('.controls-buttons-column .option-btn').click(function()
            {
                var $optionBtn = $(this);
                var $tableRow = $optionBtn.closest('.table-row');
                var orderID = $optionBtn.closest('.table-row').attr('id');
                if(!$tableRow.hasClass('opened'))
                {
                    $.ajax({
                        type: "POST",
                        url: params.url,
                        data: {
                            orderID:orderID,
                            sessid: BX.bitrix_sessid()
                        },
                        //dataType: 'json',
                        success: function(data){
                            var $tableRow = $optionBtn.closest('.table-row');
                            $tableRow.addClass('opened').find('.more-data-container').replaceWith($(data));
                            $tableRow.find('.close-button-container .yellow-text-underline').click(
                                function()
                                {
                                    $tableRow.removeClass('opened');
                                }
                            );
                        },
                        error: function( xhr, textStatus ) {
                            alert( [ xhr.status, textStatus ] );
                        }
                    });
                }
                else $tableRow.removeClass('opened');

            });
		}
	};
})();
