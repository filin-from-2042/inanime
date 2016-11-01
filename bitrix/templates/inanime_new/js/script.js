/**
 * Created by Set on 31.10.16.
 */
function inanime_new(){
    this.init_custom_carousel = function(element_id)
    {
        /* конфигурация */
        //this.cc_height = 180; // ширина изображения
        this.cc_count = 2; // количество изображений
        this.cc_position = 0; // текущий сдвиг влево
        this.carouselEl = document.getElementById(element_id);
        this.cc_height=this.carouselEl.querySelector('img').height;
        this.paddingBottom = parseInt($(this.carouselEl.querySelector('li')).css('paddingBottom'));

        this.carouselEl.querySelector('.prev').onclick = function() {
            // сдвиг влево
            // последнее передвижение влево может быть не на 3, а на 2 или 1 элемент
            inanime_new.cc_position = Math.min(inanime_new.cc_position + (inanime_new.cc_height+ inanime_new.paddingBottom) * inanime_new.cc_count, 0)
            $('#carousel-custom-vertical ul').animate(
                {
                    marginTop:inanime_new.cc_position + 'px'
                }
            );
        };

        this.carouselEl.querySelector('.next').onclick = function() {
            var listElems = inanime_new.carouselEl.querySelectorAll('li');
            // сдвиг вправо
            // последнее передвижение вправо может быть не на 3, а на 2 или 1 элемент
            inanime_new.cc_position = Math.max(inanime_new.cc_position  - (inanime_new.cc_height+ inanime_new.paddingBottom) * inanime_new.cc_count, -(inanime_new.cc_height+inanime_new.paddingBottom) * (listElems.length - inanime_new.cc_count));
            $('#carousel-custom-vertical ul').animate(
                {
                    marginTop:inanime_new.cc_position + 'px'
                }
            );
        };

    }
}
$(document).ready(function(){
    window.inanime_new = new inanime_new();
});
