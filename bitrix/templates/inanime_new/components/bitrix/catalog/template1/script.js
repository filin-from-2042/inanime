$(window).ready(function()
{
    $('.section-description .show-below-btn').click(function(){
        var textElement = $(this).closest('.section-description').find('.text');
        var currHeight = textElement.css('height');
        if(parseInt(currHeight)>70)
        {
            $(this).closest('.section-description').find('.text').animate({height:70},'fast');
			$(this).find('i').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
        }
        else
        {
            var heightAuto = textElement.css('height','auto').height();
            textElement.css('height', currHeight);
            textElement.animate({height:heightAuto},'fast');
			$(this).find('i').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
        }
    });
});