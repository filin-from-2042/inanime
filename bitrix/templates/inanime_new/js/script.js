function inanime_new() {
    this.init_custom_vertical_carousel = function (element_id, elements_count) {
        var carouselData =
        {
            cc_count: elements_count, // ���������� �����������
            cc_position: 0, // ������� ����� �����
            carouselEl: document.getElementById(element_id),
            cc_height: document.getElementById(element_id).querySelector('img').height,
            paddingBottom: parseInt($(document.getElementById(element_id).querySelector('li')).css('paddingBottom'))
        }
        inanime_new[element_id] = carouselData;
        carouselData.carouselEl.querySelector('.prev').onclick = function () {
            var prevPosition = inanime_new[element_id].cc_position;
            var height = inanime_new[element_id].cc_height;
            var paddingBottom = inanime_new[element_id].paddingBottom;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.min(prevPosition + (height + paddingBottom) * count, 0);
            $('#' + element_id + ' ul').animate(
                {
                    marginTop: inanime_new[element_id].cc_position + 'px'
                }
            );
        };

        carouselData.carouselEl.querySelector('.next').onclick = function () {
            var listElems = inanime_new[element_id].carouselEl.querySelectorAll('li');
            var prevPosition = inanime_new[element_id].cc_position;
            var height = inanime_new[element_id].cc_height;
            var paddingBottom = inanime_new[element_id].paddingBottom;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.max(prevPosition - (height + paddingBottom) * count, -(height + paddingBottom) * (listElems.length - count));
            $('#' + element_id + ' ul').animate(
                {
                    marginTop: inanime_new[element_id].cc_position + 'px'
                }
            );
        };
    };
    this.init_custom_horizontal_carousel = function (element_id, elements_count) {
        var carouselData =
        {
            cc_count: elements_count,
            cc_position: 0,
            carouselEl: document.getElementById(element_id),
            cc_width: $(document.getElementById(element_id).querySelector('li')).width(),
            paddingRight: parseInt($(document.getElementById(element_id).querySelector('li')).css('paddingRight'))
        };

        inanime_new[element_id] = carouselData;
        $('#'+element_id).css('width',(carouselData.cc_width+carouselData.paddingRight)*elements_count );
        carouselData.carouselEl.querySelector('.prev').onclick = function () {
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.min(prevPosition + (width + paddingRight) * count, 0);
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        };

        carouselData.carouselEl.querySelector('.next').onclick = function () {
            var listElems = inanime_new[element_id].carouselEl.querySelectorAll('li');
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.max(prevPosition - (width + paddingRight) * count, -(width + paddingRight) * (listElems.length - count));
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        };
    };
    /* Переменная-флаг для отслеживания того, происходит ли в данный момент ajax-запрос. В самом начале даем ей значение false, т.е. запрос не в процессе выполнения */
    this.inProgress = false;
    this.getSectionPage = function(sortValue, sectionId, elementsCount, pageNumber, withReplace = false)
    {
        var splittedVal = sortValue.split(';');
        var sortField = splittedVal[0];
        var sortType = splittedVal[1];
        $.ajax({
            url: '/ajax/catalog_pager.php',
            method: 'POST',
            data: {
                "PAGEN_1" : pageNumber,
                "section_id": sectionId,
                "sort_field": String(sortField),
                "sort_order": String(sortType),
                "page_element_count": String(elementsCount),
                "price_code" : '["BASE"]'
            },
            beforeSend: function() {
                window.inanime_new.inProgress = true;
                $(".items-section").append('<div id="overlay-load"></div>');
            }
        }).done(function(data){

            $('.items-section #overlay-load').remove();
            var parsedData = $(data);
            // манипуляции с классом new для добавления lazyload новым элементам
            parsedData.find('.product-item-preview img.lazy').addClass('new');
            if(withReplace) $(".items-section .items-container .product-item-preview").remove();
            $(".items-section .items-container").append(parsedData.find('.product-item-preview'));

            $(".items-section .items-container .product-item-preview img.lazy.new").lazyload({effect : "fadeIn"});
            $(".items-section .items-container .product-item-preview img.lazy.new").removeClass('new');
            window.inanime_new.inProgress = false;
        });
    };
    this.ddSetSelectedText = function (element)
    {
        $(element).closest(".dropdown").find(".btn.dropdown-toggle").text('').append('<span class="glyphicon glyphicon-chevron-down"></span><span class="text">'+element.innerHTML+'</span>')/*.text(element.innerHTML)*/;
    }
}
window.inanime_new = new inanime_new();
