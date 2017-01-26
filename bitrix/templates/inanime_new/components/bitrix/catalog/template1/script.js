$(window).ready(function()
{
    $('.section-description .show-below-btn').click(function(){
        var textElement = $(this).closest('.section-description').find('.text');
		var textContent = textElement.find('.text-content');
       var descContainer = $(this).closest('.section-description');
	   
        if(descContainer.hasClass('opened'))
        {
            $(this).closest('.section-description').find('.text').animate({height:70},'fast');
			$(this).find('i').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
            descContainer.removeClass('opened');
        }
        else
        {
            textElement.animate({'height':textContent.height()},'fast');
			$(this).find('i').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
            descContainer.addClass('opened');
        }
    });
});